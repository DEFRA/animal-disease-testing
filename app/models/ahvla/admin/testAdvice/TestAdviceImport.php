<?php

namespace ahvla\admin\testAdvice;

use ahvla\admin\testAdvice\exception\InvalidSpeciesCodeException;
use ahvla\admin\testAdvice\exception\IssuesWithDataListException;
use ahvla\admin\testAdvice\exception\WrongColumnCountException;
use ahvla\admin\testAdvice\exception\MissingProductInLIMS;
use ahvla\admin\testAdvice\exception\InvalidFieldException;
use ahvla\admin\TestAdviceImportException;
use ahvla\entity\species\SpeciesRepository;
use ahvla\entity\TestRecommendation;
use Exception;
use DB;
use Symfony\Component\HttpFoundation\File\File;
use ahvla\limsapi\LimsApiFactory;

class TestAdviceImport
{
    const csvColumnsCount = 7;
    const csvIgnoreHeader = true;

    const csvSpeciesCol = 0;
    const csvDiseaseCol = 1;
    const csvAgeCategoryCol = 2;
    const csvConditionCauseCol = 3;
    const csvSampleTypesCol = 4;
    const csvTestsCol = 5;
    const csvFurtherInfoCol = 6;
    /**
     * @var SpeciesRepository
     */
    private $speciesRepo;
    /**
     * @var LimsApiFactory
     */
    private $limsApiFactory;

    function __construct(
        SpeciesRepository $speciesRepo,
        LimsApiFactory $limsApiFactory
    )
    {
        $this->speciesRepo = $speciesRepo;
        $this->limsApiFactory = $limsApiFactory;
    }

    /**
     * @param File $csvFile
     * @return int //Number of rows loaded
     * @throws Exception
     */
    public function import(File $csvFile)
    {
        ini_set('auto_detect_line_endings', TRUE);
        $handle = fopen($csvFile->getRealPath(), 'r');
        if (self::csvIgnoreHeader) {
            fgetcsv($handle, 1000, ','); //Ignore first line
        }

        /** @var TestRecommendation[] $bulkInsertTestRecommendations */
        $bulkInsertTestRecommendations = [];
        $issuesWithData = [];
        $rowCount = 1;

        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            try {
                // Add 1 to rowCount to account for header (only used to report errors)
                $testAdvice = $this->getTestAdviceFromRow(($rowCount+1), $row);
                $bulkInsertTestRecommendations[] = $testAdvice;
            } catch (Exception $e) {
                if (!$e instanceof WrongColumnCountException
                    && !$e instanceof InvalidSpeciesCodeException
                    && !$e instanceof InvalidFieldException
                ) {
                    throw $e;
                }

                $issuesWithData[] = $e;
            }
            $rowCount++;
        }
        fclose($handle);
        ini_set('auto_detect_line_endings', FALSE);

        if ($issuesWithData) {
            throw new IssuesWithDataListException($issuesWithData);
        }

        DB::table('test_recommendations')->truncate();
        foreach ($bulkInsertTestRecommendations as $testAdvice) {
            DB::table('test_recommendations')->insert($testAdvice->toArray());
        }

        // Check imported product ids vs LIMS
        $noMatchProductsIds = $this->getOrphanedProductIdsFromLIMS($bulkInsertTestRecommendations);

        // Only report back these erroneous product ids once the import has taken place
        if (!empty($noMatchProductsIds)) {
            throw new MissingProductInLIMS($noMatchProductsIds);
        }

        return count($bulkInsertTestRecommendations);
    }

    /**
     * @param $rowNum
     * @param $row
     * @return TestRecommendation
     * @throws InvalidFieldException
     * @throws InvalidSpeciesCodeException
     * @throws WrongColumnCountException
     */
    private function getTestAdviceFromRow($rowNum, $row)
    {
        $species = trim($row[self::csvSpeciesCol]);
        $disease = trim($row[self::csvDiseaseCol]);
        $ageCategory = trim($row[self::csvAgeCategoryCol]);
        $conditionCause = trim($row[self::csvConditionCauseCol]);
        $sampleTypes = trim($row[self::csvSampleTypesCol]);
        $tests = trim($row[self::csvTestsCol]);
        $furtherInfo = trim($row[self::csvFurtherInfoCol]);

        if (count($row) != self::csvColumnsCount) {
            throw new WrongColumnCountException($rowNum);
        }
        if (!$this->validateSpecies($species)) {
            throw new InvalidSpeciesCodeException($rowNum, $species);
        }
        if (empty($disease)) {
            throw new InvalidFieldException($rowNum, 'disease');
        }
        if (empty($conditionCause)) {
            throw new InvalidFieldException($rowNum, 'condition/cause');
        }
        if (empty($tests)) {
            throw new InvalidFieldException($rowNum, 'tests');
        }

        $testAdvice = new TestRecommendation();
        $testAdvice->setSpeciesGroup($this->parseSpeciesGroup($species));
        $testAdvice->setSpecies($this->parseSpecies($species));
        $testAdvice->setDisease($disease);
        $testAdvice->setAgeCategory($ageCategory);
        $testAdvice->setConditionCause($conditionCause);
        $testAdvice->setSampleTypes($sampleTypes);
        $testAdvice->setRecommendedTests(str_replace(' ', '', $tests));
        $testAdvice->setFurtherInfo($furtherInfo);
        return $testAdvice;
    }

    private function parseSpeciesGroup($csvSpeciesCol)
    {
        if (strtoupper($csvSpeciesCol) === 'AVIAN') {
            return 'AVIAN';
        }elseif(strtoupper($csvSpeciesCol) === 'SMALL-RUMINANT'){

            return 'SMALL-RUMINANT';
        }

        return '';
    }

    private function parseSpecies($csvSpeciesCol)
    {
        if (strtoupper($csvSpeciesCol) === 'AVIAN') {
            return 'AVIAN';

        }elseif(strtoupper($csvSpeciesCol) === 'SMALL-RUMINANT'){
            return 'SMALL-RUMINANT';
        }

        return strtoupper($csvSpeciesCol);
    }

    private function validateSpecies($csvSpeciesCol)
    {
        if (strtoupper($csvSpeciesCol) === 'AVIAN') {
            return true;
        }elseif(strtoupper($csvSpeciesCol) === 'SMALL-RUMINANT'){
            return true;
        }

        $species = $this->speciesRepo->getOneBy('lims_code', strtoupper($csvSpeciesCol));
        if (!$species) {
            return false;
        }

        return true;
    }

    private function getOrphanedProductIdsFromLIMS($bulkInsertTestRecommendations)
    {
        $productsIdsCsv = '';
        $productsIds = array_map(function($product){
            return $product->tests;
        }, $bulkInsertTestRecommendations);

        $productIdsList = [];
        foreach ($productsIds as $productIdsStr) {
            $arr = explode(',', $productIdsStr);
            $productIdsList = array_merge($productIdsList, $arr);
        }

        // Remove any empty elements
        $productIdsList = array_filter($productIdsList);

        // Get unique elements
        $productIdsList = array_unique($productIdsList);

        // Get list as csv to send to LIMS
        $productsIdsCsv = implode(',', $productIdsList);

        /** @var GetProductsLimsService $getProductsService */
        $params = [
            'filter' => '',
            'tests' => $productsIdsCsv
        ];
        $getProductsService = $this->limsApiFactory->newGetProductsService();
        $products = $getProductsService->execute($params);

        $returnedProductIdsList = array_map(function($product){
            return $product->id;
        }, $products);

        $noMatchProductsIds = array_diff($productIdsList, $returnedProductIdsList);

        return $noMatchProductsIds;
    }
}
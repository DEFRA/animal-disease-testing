<?php
/**
 * Created by IntelliJ IDEA.
 * User: daniel.fernandes
 * Date: 27/01/2015
 * Time: 15:02
 */

namespace ahvla\controllers\apimock;


use Controller;
use Input;

class MockGeneralGetProductsController extends Controller
{

    public function getProductsAction()
    {
        $mockedProducts = [
            ['productId' => 'TC123232', 'name' => 'Culture, stained smears and wet preparation (for routine culture)',
                'price' => 20, 'maximumTurnaround' => 0, 'averageTurnaround' => 0, 'species' => ['CATTLE' => 'Cattle', 'PIG' => 'Pig'],
                'sampleTypes' => ['Blood', 'Fetal brain'], 'productType' => 'Culture',
                'options' => [
                    ['id' => 'OP1', 'name' => 'Cooper (hep1)'],
                    ['id' => 'OP2', 'name' => 'Nefa'],
                    ['id' => 'OP3', 'name' => 'Vit A']
                ], 'minOptions' => 1, 'maxOptions' => 2, 'optionsType' => 'Analytes'],
            ['productId' => 'TC098993', 'name' => 'PCR on fresh brain',
                'price' => 35.5, 'maximumTurnaround' => 3, 'averageTurnaround' => 2, 'species' => ['PIG' => 'Pig'],
                'sampleTypes' => ['Blood'], 'productType' => 'Biochem'],
            ['productId' => 'TC020002', 'name' => 'Bovine abortion/stillbirth serology package A  (L.hardjo/ N.caninum)',
                'price' => 50, 'maximumTurnaround' => 7, 'species' => ['DUCK' => 'Duck', 'PIG' => 'Pig'],
                'sampleTypes' => ['Faeces', 'Serum', 'Blood'], 'productType' => 'Histopathology',
                'options' => [
                    ['id' => 'OP1', 'name' => 'Vit B'],
                    ['id' => 'OP2', 'name' => 'Vit A'],
                    ['id' => 'OP3', 'name' => 'Vit C'],
                    ['id' => 'OP4', 'name' => 't - Thyroxine']
                ], 'minOptions' => 2],
            ['productId' => 'PC011113', 'name' => 'Iodine assay',
                'price' => 60, 'maximumTurnaround' => 5, 'averageTurnaround' => 2, 'species' => ['AV_FARMED' => 'Avian Farmed'],
                'sampleTypes' => ['Fetal brain'], 'productType' => 'Package'],
            ['productId' => 'TC394763', 'name' => 'Enteric package for 6 – 21 day old calves',
                'price' => 20.5, 'maximumTurnaround' => 4, 'averageTurnaround' => 4, 'species' => ['SHEEP' => 'Sheep'],
                'sampleTypes' => ['Blood', 'Fetal spleen or thymus'], 'productType' => 'SNT'],
            ['productId' => 'TC988774', 'name' => 'Paired ELISA antibody test',
                'price' => 10, 'maximumTurnaround' => 6, 'averageTurnaround' => 5, 'species' => ['CATTLE' => 'Cattle', 'PIG' => 'Pig', 'SHEEP' => 'Sheep'],
                'sampleTypes' => ['Fetal spleen or thymus'], 'productType' => 'Microscopy']
        ];


        $filter = Input::get('filter', '');
        $species = Input::get('species', '');

        $speciesList = [];
        if ($species) {
            $speciesList[] = $species;
        }

        $matches = [];

        foreach ($mockedProducts as $mockedProduct) {
            $ignoreProduct = false;

            foreach (explode(' ', trim($filter)) as $filterWord) {
                $filterWord = trim($filterWord);
                if ($filterWord) {
                    $wordFoundSomeWhere = false;
                    if ((!$speciesList || count($mockedProduct['species']) === 0 || $this->any_in_array($speciesList, array_keys($mockedProduct['species'])))
                        &&
                        (stripos($mockedProduct['productId'], $filterWord) !== false
                            || stripos($mockedProduct['name'], $filterWord) !== false
                            || stripos($mockedProduct['productType'], $filterWord) !== false
                            || $this->findInNestedArray(array_keys($mockedProduct['species']), $filterWord) !== false
                            || $this->findInNestedArray($mockedProduct['sampleTypes'], $filterWord) !== false)
                    ) {
                        $wordFoundSomeWhere = true;
                    }

                    if (!$wordFoundSomeWhere) {
                        $ignoreProduct = true;
                        break;
                    }
                }
            }

            if (!$ignoreProduct) {
                $matches[] = $mockedProduct;
            }
        }

        die(json_encode($matches));
    }


    private function findInNestedArray($array, $filterWord)
    {
        foreach ($array as $value) {
            if (stripos(strtolower($value), strtolower($filterWord)) !== false) {
                return true;
            }
        }
        return false;
    }


    private function any_in_array($needles, $haystackArray)
    {

        foreach ($needles as $needle) {
            if (in_array($needle, $haystackArray)) {
                return true;
            }
        }

        return false;
    }

}
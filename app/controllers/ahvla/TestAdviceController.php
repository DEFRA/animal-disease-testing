<?php

namespace ahvla\controllers;

use ahvla\admin\testAdvice\exception\InvalidSpeciesCodeException;
use ahvla\admin\testAdvice\exception\IssuesWithDataListException;
use ahvla\admin\testAdvice\exception\WrongColumnCountException;
use ahvla\admin\testAdvice\exception\InvalidFieldException;
use ahvla\admin\testAdvice\exception\MissingProductInLIMS;
use ahvla\admin\testAdvice\TestAdviceImport;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Factory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TestAdviceController extends BaseController
{
    /**
     * @var Redirector
     */
    private $redirect;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Factory
     */
    private $validatorFactory;
    /**
     * @var \ahvla\admin\testAdvice\TestAdviceImport
     */
    private $testAdviceImport;

    public function __construct(
        Application $app,
        Redirector $redirect,
        Request $request,
        Factory $validatorFactory,
        TestAdviceImport $testAdviceImport)
    {
        parent::__construct($app);
        $this->redirect = $redirect;
        $this->request = $request;
        $this->validatorFactory = $validatorFactory;
        $this->testAdviceImport = $testAdviceImport;
    }

    public function importAction()
    {
        $viewData = [];
        return $this->makeView('admin.test-advice', $viewData);
    }

    public function postImportAction()
    {
        $maxSizeKb = 500;

        /** @var UploadedFile|null $uploadedFile */
        $uploadedFile = $this->request->file('new_test_advice_file', null);

        $validator = $this->validatorFactory->make(
            ['new_test_advice_file' => $uploadedFile],
            ['new_test_advice_file' => 'required|mimes:txt|between:0,' . $maxSizeKb],
            [
                'new_test_advice_file.required' => 'Please select a file to upload',
                'new_test_advice_file.mimes' => 'File needs to be of type CSV',
                'new_test_advice_file.between' => 'File too big (Maximum of ' . (round($maxSizeKb, 2)) . ' KB)',
            ]
        );

        if ($validator->fails()) {
            return
                $this->redirect
                    ->to('test-advice/import')
                    ->withErrors($validator->errors());
        }

        try {
            $rowsLoadedCount = $this->testAdviceImport->import($uploadedFile);
        } catch (IssuesWithDataListException $e) {
            $messageBag = new MessageBag();
            foreach ($e->getExceptionsList() as $exception) {
                if ($exception instanceof WrongColumnCountException) {
                    $messageBag->add(
                        'new_test_advice_file',
                        "Wrong number of columns (Row Num: $exception->rowNum)"
                    );
                } elseif ($exception instanceof InvalidSpeciesCodeException) {
                    $messageBag->add(
                        'new_test_advice_file',
                        "Invalid species (Row Num: $exception->rowNum, Species: $exception->speciesCode)"
                    );
                } elseif ($exception instanceof InvalidFieldException) {
                    $messageBag->add(
                        'new_test_advice_file',
                        "Empty value (Row Num: $exception->rowNum, Field: $exception->fieldName)"
                    );
                } else {
                    throw $exception;
                }
            }
            return
                $this->redirect
                    ->to('test-advice/import')
                    ->withErrors($messageBag);
        } catch (MissingProductInLIMS $e) {
            $messageBag = new MessageBag();

            $messageBag->add(
                'new_test_advice_file',
                'The file has been imported but the following products do not exist on LIMS. You may fix them and re-upload the file (' . $e->getMissingProductIdsList() . ')'
            );

            return
                $this->redirect
                    ->to('test-advice/import')
                    ->withErrors($messageBag);
        }

        return
            $this->redirect->to('test-advice/import')
                ->with('successMessage',
                    'File successfully uploaded (' . $uploadedFile->getClientOriginalName() . ',' .
                    ' Rows Imported: ' . $rowsLoadedCount . ',' .
                    ' Size: ' . round($uploadedFile->getSize() / 1024 / 1024, 2) . ' MB' . ')'
                );
    }

}
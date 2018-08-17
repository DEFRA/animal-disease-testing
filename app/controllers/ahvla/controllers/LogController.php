<?php
namespace ahvla\controllers;

use ahvla\form\ErrorCodeForm;
use ahvla\logs\APIRequestLogRepository;
use ahvla\logs\ErrorLogRepository;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;

class LogController extends BaseController
{
    /**
     * The API Request log repo
     * @var APIRequestLogRepository
     */
    private $apiRepo;

    /**
     * The error log repo
     * @var ErrorLogRepository
     */
    private $errorRepo;

    /**
     * The form object used to validate the post data
     * @var ErrorCodeForm
     */
    private $form;

    /**
     * The redirector object
     * @var Redirector
     */
    private $redirect;

    /**
     * The constructor
     *
     * @param Application $app
     * @param APIRequestLogRepository $apiRepo
     * @param ErrorLogRepository $errorRepo
     * @param ErrorCodeForm $form
     * @param Redirector $redirect
     */
    public function __construct(
        Application $app,
        APIRequestLogRepository $apiRepo,
        ErrorLogRepository $errorRepo,
        ErrorCodeForm $form,
        Redirector $redirect)
    {
        parent::__construct($app);
        $this->apiRepo = $apiRepo;
        $this->errorRepo = $errorRepo;
        $this->form = $form;
        $this->redirect = $redirect;
        $this->pageTitle = 'System logs';
    }

    /**
     * Shows the logs for the system. Only accessible by a super admin
     *
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function viewAction()
    {
        // confirm valid user
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (!$currentUser->canManagePracticeAccounts()) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        // retrieve a list of logs
        $apiRequestLogs = $this->apiRepo->readFiles();
        $errorLogs = $this->errorRepo->readFiles();
        $errorDates = $this->errorRepo->getDates();

        return $this->makeView('admin.log-view', [
            'pageTitle' => $this->pageTitle,
            'apiRequestLogs' => $apiRequestLogs,
            'errorLogs' => $errorLogs,
            'errorDates' => $errorDates,
            'errorData' => \Request::old('error_data', '')
        ]);
    }

    /**
     * Downloads the requested file
     *
     * @param string $type Either error or api, for the type of file to retrieve
     * @param string $file The name of the file to download
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Exception
     */
    public function downloadAction($type, $file)
    {
        // confirm valid user
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (!$currentUser->canManagePracticeAccounts()) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        // confirm file exists
        if ($type == 'api') {
            $file = $this->apiRepo->getFile($file);
        } else {
            $file = $this->errorRepo->getFile($file);
        }
        if (!$file) {
            throw new \Exception('File cannot be found. Check type, filename, and permissions.');
        }

        return Response::download($file->getFullPath());
    }

    /**
     * Finds an error in the error logs
     *
     * @throws \Exception
     */
    public function findAction()
    {
        // confirm valid user
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (!$currentUser->canManagePracticeAccounts()) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        // validate form
        $isAjax = $this->form->isAjax();
        $validator = $this->form->getValidator();
        if ($validator->fails()) {
            return $this->redirect->back()
                ->withErrors($validator)
                ->withInput();
        }

        // confirm file exists
        $file = $this->errorRepo->getFileByDate($this->form->getDate());
        if (!$file) {
            throw new \Exception('File cannot be found. Check type, filename, and permissions.');
        }

        // find error
        $error = $file->findError($this->form->getErrorCode());
        if (!$error) {
            $error = 'No error found with that code.';
        }

        return $this->redirect->back()->withInput(array_merge(['error_data' => $error], \Request::input()));
    }
}

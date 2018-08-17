<?php

namespace ahvla\controllers\api;

use ahvla\entity\submission\SubmissionRepository;
use ahvla\controllers\StepBaseController;
use ahvla\limsapi\LimsApiFactory;
use Illuminate\Foundation\Application as App;
use Input;

class SubmissionController extends StepBaseController
{
    /**
     * @var SubmissionRepository
     */
    private $submissionRepository;
    /**
     * @var LimsApiFactory
     */
    private $limsApiFactory;

    public function __construct(
        App $app,
        SubmissionRepository $submissionRepository,
        LimsApiFactory $limsApiFactory
    )
    {
        parent::__construct($app,'');
        $this->submissionRepository = $submissionRepository;
        $this->limsApiFactory = $limsApiFactory;
    }

    public function getAction()
    {
        $input = Input::all();

        $user = $this->authenticationManager->getLoggedInUser();

        $input['filter'] = Input::get('filter', null);
        $input['pvsid'] = $user->getPracticeLimsCode();

        $result = $this->submissionRepository->getSubmissions($input);

        return $result;
    }

    public function getIdListAction()
    {
        $input = Input::all();

        $user = $this->authenticationManager->getLoggedInUser();

        $input['filter'] = Input::get('filter', null);
        $input['pvsid'] = $user->getPracticeLimsCode();

        $result = $this->submissionRepository->getSubmissionIdList($input);

        return $result;
    }
}
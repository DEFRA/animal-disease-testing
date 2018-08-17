<?php

namespace ahvla\controllers;

use ahvla\form\VictorSettingsForm;
use ahvla\controllers\BaseController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Application as App;
use ahvla\entity\victorSettings\VictorSettingsRepository;

/**
 * Handles modifying the system's information messages
 */
class VictorSettingsController extends BaseController {
	/** @var VictorSettingsRepository */
	protected $settingsRepo;

	/** @var VictorSettingsForm */
	protected $settingsForm;

	/**
	 * Constructor
	 * @param App $app
	 * @param VictorSettingsRepository $settingsRepo
	 * @param VictorSettingsForm $settingsForm
	 */
	function __construct(App $app, VictorSettingsRepository $settingsRepo, VictorSettingsForm $settingsForm) {
		parent::__construct($app);
		$this->settingsRepo = $settingsRepo;
		$this->settingsForm = $settingsForm;

        if (! $this->authenticationManager->getLoggedInUser()->canManageInformationMessages()) {
            throw new \Exception('You do not have permission to perform that action.');
        }		
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id = null)
	{
		$settings = $this->settingsRepo->all();

        return $this->makeView('admin.victor-settings.edit', compact('settings'));	
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        if ($this->settingsForm->getValidator()->fails()) {
            return Redirect::back()->withErrors($this->settingsForm->getValidator())->withInput();
        }

		$settings = $this->settingsRepo->getById($id);
		$settings->update([
			'displayLoginPageMessage' => $this->settingsForm->getDisplayLoginPageMessage(),
			'disableLogin' => $this->settingsForm->getDisableLogin(),
			'numPreviouslyDisallowedPasswords' => $this->settingsForm->getNumPreviouslyDisallowedPasswords(),
			'numDaysTilPasswordExpires' => $this->settingsForm->getNumDaysTilPasswordExpires(),
			'numWrongPasswordsBeforeSuspension' => $this->settingsForm->getNumWrongPasswordsBeforeSuspension(),
			// 'numDaysOfSuspension' => $this->settingsForm->getNumDaysOfSuspension(),
			'forgotPasswordMaxRequests' => $this->settingsForm->getForgotPasswordMaxRequests(),
			'forgotPasswordMinutesSuspended' => $this->settingsForm->getForgotPasswordMinutesSuspended(),
		]);

        return $this->makeView('admin.information-messages.edit-success', compact('settings'));	
	}
}

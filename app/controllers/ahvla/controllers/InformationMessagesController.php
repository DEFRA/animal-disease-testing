<?php

namespace ahvla\controllers;

use ahvla\controllers\BaseController;
use ahvla\form\InformationMessageForm;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Application as App;
use ahvla\entity\informationMessages\InformationMessagesRepository;

/**
 * Handles modifying the system's information messages
 */
class InformationMessagesController extends BaseController {
	/** @var InformationMessagesRepository */
	protected $infoMsgRepo;

	/** @var InformationMessageForm */
	protected $infoMsgForm;

	/**
	 * Constructor
	 * @param App $app
	 * @param InformationMessagesRepository $infoMsgRepo
	 * @param InformationMessageForm $infoMsgForm
	 */
	function __construct(App $app, InformationMessagesRepository $infoMsgRepo, InformationMessageForm $infoMsgForm) {
		parent::__construct($app);
		$this->infoMsgRepo = $infoMsgRepo;
		$this->infoMsgForm = $infoMsgForm;

		;
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
		$message = is_null($id)
			? $this->infoMsgRepo->byName('custom')
			: $this->infoMsgRepo->getById($id);

        return $this->makeView('admin.information-messages.edit', compact('message'));	
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        if ($this->infoMsgForm->getValidator()->fails()) {
            return Redirect::back()->withErrors($this->infoMsgForm->getValidator())->withInput();
        }

		$message = $this->infoMsgRepo->getById($id);
		$message->update([
			'title' => $this->infoMsgForm->getTitle(),
			'content' => $this->infoMsgForm->getContent(),
			'type' => $this->infoMsgForm->getType(),
		]);

        return $this->makeView('admin.information-messages.edit-success', compact('message'));	
	}
}

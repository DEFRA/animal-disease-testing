<?php

namespace ahvla\controllers;

use Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use ahvla\authentication\AuthenticationManager;

/**
 * Handles a VICTOR admin impersonating another system user
 *
 * @author David Gittins - WTG
 */
class ImpersonateController extends BaseController
{
	/** @var AuthenticationManager  */
	protected $authenticationManager;

	/**
	 * Constructor
	 * 
	 * @param AuthenticationManager $authenticationManager
	 */
	function __construct(AuthenticationManager $authenticationManager) {
		$this->authenticationManager = $authenticationManager;
	}

	/**
	 * Handles request to impersonate
	 * 
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postImpersonateAction()
	{
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (! $currentUser->canImpersonateUsers()) {
            throw new Exception('You do not have permission to impersonate a user.');
        }		

		$impersonatedUserId = Input::get('userId');
		$this->authenticationManager->impersonateUser($impersonatedUserId);

		return Redirect::home();
	}

	/**
	 * Handles request to reverse impersonation
	 * 		
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function revertImpersonation()
	{
		$this->authenticationManager->revertImpersonation();
		
		return Redirect::home();
	}
}

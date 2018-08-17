<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//TODO: Look at using Laravel built in authentication method
	Route::filter('pvs_auth', 'ahvla\laravel\filter\PvsAuthenticationFilter');

    Route::filter('sanitiseInput', function($route)
    {
        $formAttributeName = $route->getParameter('formAttributeName');
        if ($formAttributeName === null) {
            Input::merge(sanitiseInput(Input::all())); // all routes
        } else {
            Input::merge(sanitiseInput(Input::all(),$formAttributeName));  // ajax field updates
        }
    });

    Route::filter('load_cache', 'ahvla\laravel\filter\RouteLoadCache');
    Route::filter('save_cache', 'ahvla\laravel\filter\RouteSaveCache');
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

// See http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
Route::filter('sessionTimeout', 'ahvla\laravel\filter\SessionTimeoutFilter' );

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});


/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/
Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/*
|--------------------------------------------------------------------------
| Form Submission Completion Filter
|--------------------------------------------------------------------------
|
| Prevent the user clicking browser back button to manipulate
| a completed form and resubmit
|
|
*/
Route::filter('submission_form_complete', function($var1, $var2, $submissionComplete=0)
{
    $input = Input::all();

	// the form is complete, so don't allow user to come back here
	if ($submissionComplete) {
		return Redirect::to('step8?draftSubmissionId='.$input['draftSubmissionId']);
	}
});

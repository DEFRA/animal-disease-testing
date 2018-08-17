<?php
Route::when('*', 'csrf', array('post', 'put', 'delete'));

Route::group(['namespace' => 'ahvla\controllers', 'before'=>'sanitiseInput'], function()
{
    // original
    Route::match(array('GET'), '/', ['as' => 'home', 'uses' => 'IndexController@indexAction']);

    // start page (pre GDS)
    //Route::match(array('GET'), '/', ['as' => 'home', 'uses' => 'IndexController@serviceStartAction']);

    // moved login
    // n/a already a login route


    Route::match(array('GET'), '/dashboard', ['as' => 'dashboard', 'uses' => 'IndexController@dashboardAction']);
    Route::match(array('GET'), '/login', ['as' => 'login-form', 'uses' => 'LoginController@indexAction']);
    Route::match(array('GET'), '/login/admin', ['as' => 'login-form', 'uses' => 'LoginController@adminOverrideAction']);
    Route::match(array('POST'), '/login', ['as' => 'login', 'uses' => 'LoginController@loginAction']);
    Route::match(array('GET'), '/logout', ['as' => 'logout', 'uses' => 'LoginController@logoutAction']);

    Route::match(array('GET'), '/register', ['as' => 'registration', 'uses' => 'RegistrationController@indexAction']);
    Route::match(array('POST'), '/register', ['as' => 'registration', 'uses' => 'RegistrationController@registrationAction']);

    Route::match(array('GET'), '/my-profile', ['as' => 'my-profile-form', 'uses' => 'UsersController@myProfileAction']);
    Route::match(array('POST'), '/my-profile', ['as' => 'my-profile', 'uses' => 'UsersController@postMyProfileAction']);

    Route::match(array('GET'), '/feedback', ['as' => 'feedback-form', 'uses' => 'FeedbackController@getAction']);
    Route::match(array('POST'), '/feedback', ['as' => 'feedback', 'uses' => 'FeedbackController@postAction']);

    Route::match(array('GET'), '/help', ['as' => 'help', 'uses' => 'PagesController@getHelp']);

    Route::match(array('GET'), '/request-reset-password', ['as' => 'request-reset-password-form', 'uses' => 'ForgottenPasswordController@viewRequestAction']);
    Route::match(array('POST'), '/request-reset-password', ['as' => 'request-reset-password', 'uses' => 'ForgottenPasswordController@postRequestAction']);
    Route::match(array('GET'), '/reset-password/{id}/{resetPasswordCode}', ['as' => 'reset-password-form', 'uses' => 'ResetPasswordController@viewAction']);
    Route::match(array('POST'), '/reset-password', ['as' => 'reset-password', 'uses' => 'ResetPasswordController@postAction']);

    Route::match(array('GET'), '/activate-user/{id}/{activationCode}', ['as' => 'user-activate-form', 'uses' => 'UserActivateController@activateAction']);
    Route::match(array('POST'), '/activate-user/{id}/{activationCode}', ['as' => 'user-activate', 'uses' => 'UserActivateController@postActivateAction']);
    Route::match(array('GET'), '/activation-message', ['as' => 'user-activation-message', 'uses' => 'UserActivateController@viewActivationMessage']);

    Route::match(array('GET'), '/pvs-online', ['as' => 'pvs-online', 'uses' => 'LoginController@pvsOnline']);

    // reports
    Route::match(array('GET'), '/reports/report', ['before'=>'sessionTimeout|pvs_auth','uses'=>'ReportController@indexAction']);
    Route::match(array('GET'), '/reports/pdf', ['before'=>'sessionTimeout|pvs_auth','uses'=>'ReportController@pdfAction']);

    Route::group(['before' => 'sessionTimeout|pvs_auth'], function()
    {
        // Manage practices
        Route::group(['prefix' => '/practices'], function()
        {
            Route::match(array('GET'), '/', ['as' => 'practices.view', 'uses' => 'PracticesController@indexAction']);

            Route::match(array('GET'), '/register', ['as' => 'practice.register-form', 'uses' => 'PracticesController@registerAction']);
            Route::match(array('POST'), '/register', ['as' => 'practice.register', 'uses' => 'PracticesController@postRegisterAction']);

            Route::match(array('GET'), '/edit/{id}', ['as' => 'practice.edit-form', 'uses' => 'PracticeEditController@editAction']);
            Route::match(array('POST'), '/edit', ['as' => 'practice.edit', 'uses' => 'PracticeEditController@postEditAction']);
            Route::match(array('GET'), '/edit/{id}/delete', ['as' => 'practice.delete-confirm', 'uses' => 'PracticeEditController@deleteAction']);
            Route::match(array('POST'), '/edit/{id}/delete', ['as' => 'practice.delete', 'uses' => 'PracticeEditController@postDeleteAction']);

            Route::match(array('POST'), '/resend-email', ['as' => 'practice.resend-email', 'uses' => 'PracticeResendEmailController@postAction']);

            // Manager Users
            Route::group(['prefix' => '{practiceId?}/users'], function()
            {
                Route::match(array('GET'), '/', ['as' => 'users.view', 'uses' => 'UsersController@indexAction']);
                Route::match(array('GET'), '/register', ['as' => 'user.register-form', 'uses' => 'UsersController@registerAction']);
                Route::match(array('POST'), '/register', ['as' => 'user.register', 'uses' => 'UsersController@postRegisterAction']);
                Route::match(array('GET'), '/edit/{id}', ['as' => 'user.edit-form', 'uses' => 'UsersController@editAction']);
                Route::match(array('GET'), '/edit/{id}/delete', ['as' => 'user.delete-confirm', 'uses' => 'UsersController@deleteAction']);
                Route::match(array('POST'), '/edit/{id}/delete', ['as' => 'user.delete', 'uses' => 'UsersController@postDeleteAction']);
                Route::match(array('POST'), '/edit', ['as' => 'user.edit', 'uses' => 'UsersController@postEditAction']);
                Route::match(array('POST'), '/unsuspend', ['as' => 'user.unsuspend', 'uses' => 'UsersController@postUnsuspendAction']);
            });
        });

        // VICTOR admins can impersonate a user.
        Route::match(array('POST'), '/impersonate', ['as' => 'impersonate', 'uses' => 'ImpersonateController@postImpersonateAction']);
        Route::match(array('GET'), '/unimpersonate', ['as' => 'unimpersonate', 'uses' => 'ImpersonateController@revertImpersonation']);

        // Information Messages
        Route::match(array('GET'), '/information-messages/{id?}', ['as' => 'information-messages.edit', 'uses' => 'InformationMessagesController@edit']);
        Route::match(array('POST'), '/information-messages/{id}', ['as' => 'information-messages.update', 'uses' => 'InformationMessagesController@update']);

        // Information VICTOR settings
        Route::match(array('GET'), '/victor-settings', ['as' => 'settings.edit', 'uses' => 'VictorSettingsController@edit']);
        Route::match(array('POST'), '/victor-settings/{id}', ['as' => 'settings.update', 'uses' => 'VictorSettingsController@update']);

        // Manage victor admins
        Route::group(['prefix' => '/admins'], function()
        {
            Route::match(array('GET'), '/', ['as' => 'admin.view', 'uses' => 'VictorAdminController@indexAction']);

//            Route::match(array('GET'), '/register', ['as' => 'practice.register-form', 'uses' => 'PracticesController@registerAction']);
//            Route::match(array('POST'), '/register', ['as' => 'practice.register', 'uses' => 'PracticesController@postRegisterAction']);
//
//            Route::match(array('GET'), '/edit/{id}', ['as' => 'practice.edit-form', 'uses' => 'PracticeEditController@editAction']);
//            Route::match(array('POST'), '/edit', ['as' => 'practice.edit', 'uses' => 'PracticeEditController@postEditAction']);
//            Route::match(array('POST'), '/resend-email', ['as' => 'practice.resend-email', 'uses' => 'PracticeResendEmailController@postAction']);
        });

        // Manage Crud
        Route::group(['prefix' => '/crud'], function()
        {
            Route::match(array('GET'), '/', ['as' => 'crud.crud', 'uses' => 'CrudController@indexAction']);

            Route::match(array('GET'), '/{tableId}/create', ['as' => 'crud.create-crud', 'uses' => 'CrudController@createAction']);
            Route::match(array('POST'), '/{tableId}/create', ['as' => 'crud.create', 'uses' => 'CrudController@postCreateAction']);

            Route::match(array('GET'), '/{tableId}/edit', ['as' => 'crud.edit-crud', 'uses' => 'CrudController@editAction']);
            Route::match(array('POST'), '/{tableId}/edit', ['as' => 'crud.edit', 'uses' => 'CrudController@postEditAction']);
            Route::match(array('GET'), '/{tableId}/delete/{fieldId}', ['as' => 'crud.delete-confirm', 'uses' => 'CrudController@deleteAction']);
            Route::match(array('DELETE'), '/{tableId}/delete/{fieldId}', ['as' => 'crud.delete', 'uses' => 'CrudController@postDeleteAction']);

        });

        Route::group(['prefix'=>'/test-advice'], function(){
           Route::match(array('GET'), '/import',['as' => 'lookups.test-advice', 'uses'=>'TestAdviceController@importAction']);
           Route::match(array('POST'), '/import',['as' => 'lookups.test-advice-post', 'uses'=>'TestAdviceController@postImportAction']);
        });

        // Manage logs
        Route::group(['prefix' => '/logs'], function() {
            Route::match(array('GET'), '/', ['as' => 'logs.view', 'uses' => 'LogController@viewAction']);
            Route::match(array('GET'), '/download/{type}/{file}', ['as' => 'logs.download', 'uses' => 'LogController@downloadAction'])->where('type', 'api|error');
            Route::match(array('POST'), '/find', ['as' => 'logs.find', 'uses' => 'LogController@findAction']);
        });

        Route::match(array('GET', 'POST'), '/landing', ['as' => 'landing', 'uses' => 'LandingController@indexAction']);
        Route::match(array('GET'), '/start/{existingSubmissionId?}', ['uses' => 'LandingController@start']);
        Route::match(array('GET'), '/start-paired-submission/{linkedFirstOfPairSubmissionId}', ['uses' => 'LandingController@startPairedSubmission']);
        Route::match(array('GET'), '/step1', ['as' => 'step1','uses' => 'StepClientDetailsSubmissionController@indexAction']);
        Route::match(array('GET'), '/step2', ['as' => 'step2','uses' => 'StepAnimalDetailsSubmissionController@indexAction']);
        Route::match(array('GET'), '/step3', ['as' => 'step3','uses' => 'StepClinicalHistorySubmissionController@indexAction']);
        Route::match(array('GET'), '/step4', ['as' => 'step4','uses' => 'StepTestsSubmissionController@indexAction']);
        Route::match(array('GET'), '/step5', ['as' => 'step5','uses' => 'StepYourBasketSubmissionController@indexAction']);
        Route::match(array('GET'), '/step6', ['as' => 'step6','uses' => 'StepDeliverySubmissionController@indexAction']);
        Route::match(array('GET'), '/step7', ['as' => 'step7','uses' => 'StepReviewConfirmSubmissionController@indexAction']);
        Route::match(array('GET'), '/step8', ['as' => 'step8','uses' => 'StepPrintSubmissionController@indexAction']);
        Route::match(array('POST'), '/step-client-details-post', ['uses' => 'StepClientDetailsSubmissionController@postAction']);
        Route::match(array('POST'), '/step-animal-details-post', ['uses' => 'StepAnimalDetailsSubmissionController@postAction']);
        Route::match(array('POST'), '/step-clinical-history-post', ['uses' => 'StepClinicalHistorySubmissionController@postAction']);
        Route::match(array('POST'), '/step-tests-submission-post', ['uses' => 'StepTestsSubmissionController@postAction']);
        Route::match(array('POST'), '/step-review-confirm-post', ['uses' => 'StepReviewConfirmSubmissionController@postAction']);
        Route::match(array('POST'), '/step-delivery-post', ['uses' => 'StepDeliverySubmissionController@postAction']);
        Route::match(array('POST'), '/step-basket-post', ['uses' => 'StepYourBasketSubmissionController@postAction']);
        Route::match(array('POST'), '/step-print-documents-post', ['uses' => 'StepPrintSubmissionController@postAction']);
        Route::match(array('GET', 'POST'), '/print-address-label', ['uses' => 'SubmissionController@printAddressLabelAction']);
        Route::match(array('GET', 'POST'), '/print-dispatch-note', ['uses' => 'SubmissionController@printDispatchNoteAction']);

        // partials
        Route::match(array('GET'), '/small-basket', ['uses' => 'StepTestsSubmissionController@smallBasketAction']);
        Route::match(array('GET'), '/submissions/filter', ['uses' => 'SubmissionController@filterSubmissionsAction']);

        Route::group(['prefix' => '/api/v1'], function()
        {
            Route::match(array('GET'), '/pvs-client', ['uses' => 'api\PvsClientController@getAction']);
            Route::match(array('GET'), '/pvs-animals-address', ['uses' => 'api\PvsClientController@getAnimalsAddressAction']);
            Route::match(array('POST'), '/pvs-client/set', ['uses' => 'api\PvsClientController@setClientAction']);
            Route::match(array('POST'), '/pvs-animals-address/set', ['uses' => 'api\PvsClientController@setAnimalsAddressAction']);
            Route::match(array('POST'), '/pvs-client/new', ['uses' => 'api\PvsClientController@setNewClientAction']);
            Route::match(array('POST'), '/pvs-animals-address/new', ['uses' => 'api\PvsClientController@setNewAnimalsAddressAction']);
            Route::match(array('POST'), '/pvs-client/unset', ['uses' => 'api\PvsClientController@unsetClientAction']);
            Route::match(array('POST'), '/pvs-animals-address/unset', ['uses' => 'api\PvsClientController@unsetAnimalsAddressAction']);
            Route::match(array('GET'), '/animal-breed', ['uses' => 'api\AnimalBreedController@getAction']);
            Route::match(array('GET'), '/species', ['uses' => 'api\SpeciesController@getAction']);
            Route::match(array('GET'), '/species/diseases/list/{species}', ['uses' => 'api\SpeciesDiseaseController@listAction']);
            Route::match(array('GET'), '/species/sample-types/list/{species}/{disease?}', ['uses' => 'api\SampleTypesController@listAction']);

            Route::group(['before' => 'load_cache', 'after' => 'save_cache'], function()
            {
                Route::match(array('GET'), '/species-purpose', ['uses' => 'api\SpeciesPurposeController@getAction']);
                Route::match(array('GET'), '/species-age', ['uses' => 'api\SpeciesAgeController@getAction']);
                Route::match(array('GET'), '/sex-group', ['uses' => 'api\SexGroupsController@getAction']);
                Route::match(array('GET'), '/housing', ['uses' => 'api\HousingController@getAction']);
            });

            Route::match(array('GET'), '/product', ['uses' => 'api\ProductController@getAction']);
            Route::match(array('GET'), '/test-recommendations', ['uses' => 'api\TestRecommendationController@getAction']);
            Route::match(array('GET'), '/submission', ['uses' => 'api\SubmissionController@getAction']);

            Route::match(array('GET'), '/basket-product/{productId}', ['uses' => 'api\BasketProductController@getBasketProduct']);
            Route::match(array('POST'), '/basket-product/{productId}', ['uses' => 'api\BasketProductController@postAction']);
            Route::match(array('POST'), '/basket-product/delete/{productId}', ['uses' => 'api\BasketProductController@deleteAction']);
            Route::match(array('GET'),  '/basket-product/delete/{productId}/{step}/{draftSubmissionId}', ['uses' => 'api\BasketProductController@deleteActionNoJS', 'as' => 'removeProduct']);
            Route::match(array('POST'), '/basket-product/animal/delete/{productId}/{animalId}', ['uses' => 'api\BasketProductController@deleteAnimalAction']);
            Route::match(array('GET'), '/basket-product/animal/delete/{productId}/{animalId}/{draftSubmissionId}', ['uses' => 'api\BasketProductController@deleteAnimalActionNoJS', 'as' => 'removeAnimal']);
            Route::match(array('POST'), '/basket-product/product/{productId}/sample/{sampleId}', ['uses' => 'api\BasketProductController@sampleAction']);
            Route::match(array('POST'), '/basket-product/package/{packageId}/product/{productId}/sample/{sampleId}', ['uses' => 'api\BasketProductController@packageSampleAction']);

            Route::match(array('POST'), '/form-input/{formClassName}/{formAttributeName}', ['before'=>'formAttr', 'uses' => 'api\FormInputController@postAction']);
            Route::match(array('GET'), '/form/{formClassName}', ['uses' => 'api\FormController@getAction']);
            Route::match(array('GET'), '/form/validate/{formClassName}', ['uses' => 'api\FormController@validateAction']);
        });

        // submission
        Route::match(array('GET'), '/submission', ['before'=>'sessionTimeout|pvs_auth','uses'=>'SubmissionController@viewPastSubmissionAction']);

        // submission_id can be draft or completed
        Route::match(array('POST'), '/cancel-submission', ['before'=>'sessionTimeout|pvs_auth','uses'=>'SubmissionController@cancelSubmissionAction']);
        Route::match(array('GET'), '/cancel-submission-dialog', ['before'=>'sessionTimeout|pvs_auth','uses'=>'SubmissionController@cancelSubmissionDialogAction']);
        Route::match(array('GET'), '/cancel-submission-static', ['before'=>'sessionTimeout|pvs_auth','uses'=>'SubmissionController@cancelSubmissionStaticAction']);

        // misc
        Route::match(array('GET'), '/googleLatLong', ['uses' => 'AppController@googleLatLongAction']);
        Route::match(array('GET'), '/uisample', ['uses' => 'AppController@uiSampleAction']);
    });
});

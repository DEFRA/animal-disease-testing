<?php

/* submissions/getClients, mock same folder structure as LIMS API server URL endpoint */
Route::get('/LIMSRestAPI/general/getClients', 'ahvla\controllers\apimock\MockCallsController@getClientsAction');
Route::get('/LIMSRestAPI/submissions/getSamples', 'ahvla\controllers\apimock\MockCallsController@getSamplesAction');

Route::get('/LIMSRestAPI/general/getProducts', 'ahvla\controllers\apimock\MockGeneralGetProductsController@getProductsAction');
Route::get('/LIMSRestAPI/submissions/getSubmissions', 'ahvla\\controllers\\apimock\\MockCallsController@getSubmissionsAction');

Route::get('/LIMSRestAPI/submissions/getDeliveryAddresses', 'ahvla\\controllers\\apimock\\MockCallsController@getDeliveryAddressesAction');

Route::post('/LIMSRestAPI/submissions/createUpdateDraftSubmission', 'ahvla\\controllers\\apimock\\MockCreateUpdateDraftSubmission@createSubmission');
Route::post('/LIMSRestAPI/submissions/confirmDraftSubmission', 'ahvla\\controllers\\apimock\\MockConfirmDraftSubmission@confirmSubmission');

Route::get('/LIMSRestAPI/submissions/getSubmission', 'ahvla\\controllers\\apimock\\MockGetDraftSubmission@getSubmission');

Route::get('/LIMSRestAPI/submissions/getLatestResults', 'ahvla\\controllers\\apimock\\MockCallsController@getLatestResults');
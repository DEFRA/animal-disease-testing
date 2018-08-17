<?php

namespace ahvla\limsapi\service;

use ahvla\limsapi\AbstractLimsApiService;
use Config;

class CancelSubmission extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        $response = $this->apiClient->postRawJson(
            Config::get('ahvla.lims-prefix').'submissions/cancelSubmission',
            [
                'pvsId' => $params['pvsId'],
                'submissionDraftId' => $params['submissionDraftId'],
                'clinician' => $params['clinician']
            ]
        );

        $errorMessages = $response['errorMessages'];
        $hasErrors = $response['hasErrors'];

        return $response;
    }

    /**
     * @inheritdoc
     */
    public function getMandatoryParameters()
    {
        return [
            'pvsId',
            'submissionDraftId',
            'clinician'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getOptionalParameters()
    {
        return [

        ];
    }
}

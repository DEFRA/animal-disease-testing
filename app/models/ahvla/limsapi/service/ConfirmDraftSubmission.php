<?php

namespace ahvla\limsapi\service;

use ahvla\limsapi\AbstractLimsApiService;
use Config;

class ConfirmDraftSubmission extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        $response = $this->apiClient
            ->postRawJson(
                Config::get('ahvla.lims-prefix').'submissions/confirmDraftSubmission',
                [
                    'draftSubmissionId' => $params['draftSubmissionId'],
                    'pvsId' => $params['pvsId']
                ]
            );

        return $response['submissionId'];
    }

    /**
     * @inheritdoc
     */
    public function getMandatoryParameters()
    {
        return [
            'draftSubmissionId',
            'pvsId'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getOptionalParameters()
    {
        return [];
    }
}
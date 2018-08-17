<?php

namespace ahvla\limsapi\service;

use ahvla\entity\submission\Submission;
use ahvla\limsapi\AbstractLimsApiService;
use Config;

class CreateUpdateDraftSubmission extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        /** @var \ahvla\entity\submission\Submission $submission */
        $submission = $params['submission'];

        $response = $this->apiClient
            ->postRawJson(
                Config::get('ahvla.lims-prefix').'submissions/createUpdateDraftSubmission',
                $submission
            );

        return $response['draftSubmissionId'];
    }

    /**
     * @inheritdoc
     */
    public function getMandatoryParameters()
    {
        return [
            Submission::CLASS_NAME => 'submission'
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
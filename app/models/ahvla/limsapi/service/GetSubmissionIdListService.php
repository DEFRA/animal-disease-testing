<?php

namespace ahvla\limsapi\service;

use ahvla\entity\submission\Submission;
use ahvla\limsapi\AbstractLimsApiService;
use Config;

class GetSubmissionIdListService extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        if (empty($params['pvsid'])) {
            return [];
        }

        $filters = array();

        if (isset($params['pvsid'])) { $filters['pvsid'] = $params['pvsid']; }
        if (isset($params['filter'])) { $filters['filter'] = $params['filter']; }

        $response = $this->apiClient->get(
            Config::get('ahvla.lims-prefix').'submissions/getSubmissionIdList',
            $filters
        );

        $submission = [];
        foreach ($response as $record) {

            $submissionRecord = new Submission();
            $submissionRecord->setSubmissionId(isset($record['submissionId']) ? trim($record['submissionId']) : '');

            if (isset($record['submissionId'])&&!empty($record['submissionId'])) {
                $masterSubmissionId = $record['submissionId'];
            }

            // the masterSubmissionId is the id field taken by the Submission json serialisation
            // object when the response is sent back to the browser
            $submissionRecord->setMasterSubmissionId($masterSubmissionId);

            $submission[] = $submissionRecord;
        }

        return $submission;
    }

    /**
     * @inheritdoc
     */
    public function getMandatoryParameters()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getOptionalParameters()
    {
        return [
            'filter',
            'pvsid'
        ];
    }
}
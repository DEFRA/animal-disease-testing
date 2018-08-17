<?php

namespace ahvla\entity\submission;

use ahvla\limsapi\LimsApiFactory;
use ahvla\limsapi\LimsPagination;
use ahvla\entity\submission\Submission;

/*
 * @author Kai Chan <kai.chan@wtg.co.uk>
 */

class SubmissionRepository
{

    /*
     * Number of records per page
     */
    const PER_PAGE = 5;

    /**
     * Pagination of records
     */
    public $limsPaginator;

    /**
     * @var LimsApiFactory
     */
    private $limsApiFactory;

    public function __construct(
        LimsApiFactory $limsApiFactory
    )
    {
        $this->limsApiFactory = $limsApiFactory;
    }

    public function getSubmissionIdList($filters = array())
    {
        $getSubmissionIdListService = $this->limsApiFactory->newGetSubmissionIdListService();
        $result = $getSubmissionIdListService->execute($filters);

        // paginate
        $this->limsPaginator = new LimsPagination($result, self::PER_PAGE, isset($filters['page']) ? $filters['page'] : 1);
        $this->limsPaginator->paginate();

        return $this->limsPaginator->currentItems;
    }

    /**
     * Get Submissions from LIMS
     *
     * @return Submission[]
     */
    public function getSubmissions($filters = array())
    {
        // filter date
        if (isset($filters['date'])) {

            switch ($filters['date']) {
                case 'LAST_DAY':
                    $filters['submittedDateBegin'] = date('Y-m-d',strtotime("-1 days"));
                    $filters['submittedDateEnd'] = date('Y-m-d');
                    break;
                case 'LAST_WEEK':
                    $filters['submittedDateBegin'] = date('Y-m-d',strtotime("-1 week"));
                    $filters['submittedDateEnd'] = date('Y-m-d');
                    break;
                case 'LAST_14DAYS':
                    $filters['submittedDateBegin'] = date('Y-m-d',strtotime("-2 week"));
                    $filters['submittedDateEnd'] = date('Y-m-d');
                    break;
                case 'LAST_MONTH':
                    $filters['submittedDateBegin'] = date('Y-m-d',strtotime("-1 month"));
                    $filters['submittedDateEnd'] = date('Y-m-d');
                    break;
                case 'LAST_6_MONTHS':
                    $filters['submittedDateBegin'] = date('Y-m-d',strtotime("-6 month"));
                    $filters['submittedDateEnd'] = date('Y-m-d');
                    break;
                case 'LAST_YEAR':
                    $filters['submittedDateBegin'] = date('Y-m-d',strtotime("-12 month"));
                    $filters['submittedDateEnd'] = date('Y-m-d');
                    break;
                case 'LAST_18_MONTHS':
                    $filters['submittedDateBegin'] = date('Y-m-d',strtotime("-18 month"));
                    $filters['submittedDateEnd'] = date('Y-m-d');
                    break;
            }
        }

        $getSubmissionsService = $this->limsApiFactory->newGetSubmissionsService();
        $result = $getSubmissionsService->execute($filters);

        // paginate
        $this->limsPaginator = new LimsPagination($result, self::PER_PAGE, isset($filters['page']) ? $filters['page'] : 1);
        $this->limsPaginator->paginate();

        return $this->limsPaginator->currentItems;
    }

    /**
     * Get a single submission from LIMS
     *
     * @return Submission[]
     */
    public function getSingleSubmission($filters = array())
    {
        $getSubmissionService = $this->limsApiFactory->newGetSubmissionLimsService();
        $submission = $getSubmissionService->execute(
            [
                'submissionId' => $filters['submissionId'],
                'pvsId' => $filters['pvsId']
            ]
        );

        return $submission;
    }

    /**
     * Get latest results of a submission
     */
    public function getLatestResults($filters = array())
    {
        $getSubmissionService = $this->limsApiFactory->newGetLatestResults();
        $results = $getSubmissionService->execute(
            [
                'submissionId' => $filters['submissionId'],
                'pvsId' => $filters['pvsId']
            ]
        );

        // calc the total charges cost
        if (isset($results['Charges'])) {
            $total = 0;
            foreach ($results['Charges'] as $charge) {
                $total += $charge['TotalCost'];
            }
            $results['ChargesTotal'] = $total;
        }

        return $results;
    }

    /**
     * Get PVS  details
     */
    public function getPVS($filters = array())
    {
        $getSubmissionService = $this->limsApiFactory->newGetPVS();
        $results = $getSubmissionService->execute(
            [
                'pvsId' => $filters['pvsId']
            ]
        );

        return $results;
    }

    /**
     * Get Pdf
     */
    public function getReleaseSummaryAndReport($filters = array())
    {
        $getPdfService = $this->limsApiFactory->newGetReleaseSummaryAndReport();
        $results = $getPdfService->execute(
            [
                'submissionId' => $filters['submissionId'],
                'pvsId' => $filters['pvsId']
            ]
        );

        return $results;
    }

}
<?php

namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use Session;

class FilterSubmissionsForm
{
    /**
     * @var Factory
     */
    private $validationFactory;
    /**
     * @var Request
     */
    private $input;

    public function __construct(Factory $validationFactory, Request $input)
    {
        $this->validationFactory = $validationFactory;
        $this->input = $input;
    }

    /**
     * @return Validator
     */
    public function getValidator()
    {
        return $this->validationFactory->make(
            $this->input->all(),
            [

            ]
        );
    }

    public function getClient()
    {
        return $this->input->get('client', null);
    }

    public function getClinician()
    {
        return $this->input->get('clinician', null);
    }

    public function getStatus()
    {
        return $this->input->get('status', null);
    }

    public function getDate()
    {
        return $this->input->get('date', '');
    }

    /*
     * Statuses for drop down
     */
    public function getStatuses()
    {
        $statuses = array(  ''=>'Show All',
                            'Draft'=>'Draft',
                            'Submitted'=>'Submitted',
                            'In Progress'=>'In progress',
                            'Cancelled'=>'Cancelled',
                            'Samples Overdue'=>'Samples overdue',
                            'All Tests Complete'=>'All tests complete',
                            'Results Available' => 'Results available',
                            );

        return $statuses;
    }

    /*
     * Dates for drop down
     */
    public function getFilterDates()
    {
        $statuses = array(  'LAST_DAY'=>'In the last day',
                            'LAST_WEEK'=>'In the last week',
                            'LAST_14DAYS'=>'In the last 14 days',
                            'LAST_MONTH'=>'In the last month',
                            'LAST_6_MONTHS'=>'In the last 6 months',
                            'LAST_YEAR'=>'In the last year',
                            'LAST_18_MONTHS'=>'In the last 18 months',
                            );

        return $statuses;
    }

    public function getDefaultFilterDate()
    {
        return 'LAST_MONTH';
    }

    public function saveFiltersInSession($input)
    {
        $searchData = array();

        if (isset($input['clientId'])) { $searchData['clientId'] = $input['clientId']; }
        if (isset($input['status'])) { $searchData['status'] = $input['status']; }
        if (isset($input['clinician'])) { $searchData['clinician'] = $input['clinician']; }
        if (isset($input['date'])) { $searchData['date'] = $input['date']; }

        if (!empty($searchData)) {
            Session::set('FilterSubmissionsForm', $searchData);
        }
    }

    public function getFiltersInSession()
    {
        return Session::get('FilterSubmissionsForm');
    }

}
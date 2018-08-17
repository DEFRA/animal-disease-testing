<?php

namespace ahvla\address;

use ahvla\limsapi\LimsApiFactory;

class DeliveryAddress
{
    /**
     * @var LimsApiFactory
     */
    private $limsApiFactory;

    /**
     * @param LimsApiFactory $limsApiFactory
     */
    public function __construct(LimsApiFactory $limsApiFactory)
    {
        $this->limsApiFactory = $limsApiFactory;
    }

    public function getTestAddresses($params)
    {
        $draftSubmissionId = isset($params['draftSubmissionId'])?$params['draftSubmissionId']:'';
        $pvsId = isset($params['pvsId'])?$params['pvsId']:'';

        $getDeliveryAddressesService = $this->limsApiFactory->newGetDeliveryAddressesLimsService();

        $addresses = $getDeliveryAddressesService
            ->execute([
                'pvsId'=>$pvsId,
                'draftSubmissionId'=>$draftSubmissionId
            ]);

        return $addresses;
    }

}
<?php

namespace ahvla\printpage;

use ahvla\limsapi\LimsApiFactory;

class DispatchNote implements PrintInterface
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

    public function getTestAddresses()
    {
        $getDeliveryAddressesService = $this->limsApiFactory->newGetDeliveryAddressesLimsService();

        $addresses = $getDeliveryAddressesService
            ->execute([]);

        return $addresses;
    }

}
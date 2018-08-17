<?php

namespace ahvla\entity\client;

use ahvla\client\ClientSearch;
use App;
use ahvla\entity\AbstractEloquentRepository;


class ClientRepository extends AbstractEloquentRepository
{
    /**
     * @var ClientSearch
     */
    private $clientSearch;

    /**
     * @param ClientSearch $clientSearch
     */
    public function __construct(ClientSearch $clientSearch)
    {
        $this->clientSearch = $clientSearch;
    }

    /**
     * @return Clients[]
     */
    public function getClients($filters=array())
    {

        $id = isset($filters['id'])?$filters['id']:'';
        $freeTextFilter = isset($filters['filter'])?$filters['filter']:'';
        $addressType = isset($filters['address_type'])?$filters['address_type']:'';

        $result = $this->clientSearch->searchClientsAndSaveResults($id, $freeTextFilter, $addressType);

        return $result;
    }
}
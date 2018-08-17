<?php

namespace ahvla\limsapi;

use ahvla\entity\submission\Submission;
use ahvla\limsapi\service\LimsApiCallLog;
use Exception;
use GuzzleHttp\Client;
use Config;


class LimsApiClient
{
    /**
     * @var Client
     */
    private $guzzleClient;
    /**
     * @var LimsApiCallLog
     */
    private $log;

    /**
     * @var string
     */
    private $timeout;

    public function __construct(Client $httpClient, LimsApiCallLog $log, $maxTimeout)
    {
        $this->guzzleClient = $httpClient;
        $this->log = $log;
        $this->timeout = $maxTimeout;
    }

    /**
     * @param string $method
     * @param string[] $queryParameters
     * @return mixed
     * @throws \ServiceUnavailableException
     */
    public function get($method, $queryParameters, $timeout = false)
    {

        try {
            $start = microtime(true);

            $this->guzzleClient->setDefaultOption('verify', false);
            $response = $this->guzzleClient->get(
                $method,
                [
                    'query' => $queryParameters,
                    'auth'    => [ Config::get('ahvla.ibm-curl-user'), Config::get('ahvla.ibm-curl-password') ],
                    'timeout' => $timeout !== false ? $timeout : $this->timeout,
                    'connect_timeout' => $timeout !== false ? $timeout : $this->timeout,
                ]
            );

            // if (class_exists('Debugbar')) {
            //     \Debugbar::getCollector('api_requests')->addRequest(array_merge(['method' => 'GET', 'url' => $method], $queryParameters));
            // }

            $json = $response->json();

            $this->log->logCall(
                $this->guzzleClient,
                LimsApiCallLog::GET_METHOD,
                $method,
                $queryParameters,
                $json,
                null,
                microtime(true) - $start,
                $this->timeout
            );
            return $json;
        } catch (Exception $e) {
            $this->log->logCall(
                $this->guzzleClient,
                LimsApiCallLog::GET_METHOD,
                $method,
                $queryParameters,
                isset($response) && $response ? $response->getBody() : '',
                $e,
                microtime(true) - $start,
                $this->timeout
            );
            $this->log->logError(
                $this->guzzleClient,
                LimsApiCallLog::GET_METHOD,
                $method,
                $queryParameters,
                isset($response) && $response ? $response->getBody() : '',
                $e,
                microtime(true) - $start,
                $this->timeout
            );
            throw new \ServiceUnavailableException('API Service Unavailable (1). Please try again later.');
        }
    }

    /**
     * @param string $method
     * @param string[] $parameters
     * @return mixed
     * @throws \ServiceUnavailableException
     */
    public function post($method, array $parameters)
    {
        try {
            $start = microtime(true);

            $this->guzzleClient->setDefaultOption('verify', false);

            $response = $this->guzzleClient->post(
                $method,
                [
                    'body' => $parameters,
                    'auth'    => [ Config::get('ahvla.ibm-curl-user'), Config::get('ahvla.ibm-curl-password') ],
                    'timeout' => $this->timeout,
                    'connect_timeout' => $this->timeout,
                ]
            );

            if (class_exists('Debugbar')) {
                \Debugbar::getCollector('api_requests')->addRequest(array_merge(['method' => 'POST', 'url' => $method], $parameters));
            }

            $json = $response->json();

            $this->log->logCall(
                $this->guzzleClient,
                LimsApiCallLog::POST_METHOD,
                $method,
                $parameters,
                $json,
                null,
                microtime(true) - $start,
                $this->timeout
            );
            return $json;
        } catch (Exception $e) {
            $this->log->logCall(
                $this->guzzleClient,
                LimsApiCallLog::POST_METHOD,
                $method,
                $parameters,
                isset($response) && $response ? $response->getBody() : '',
                $e,
                microtime(true) - $start,
                $this->timeout
            );
            $this->log->logError(
                $this->guzzleClient,
                LimsApiCallLog::POST_METHOD,
                $method,
                $parameters,
                isset($response) && $response ? $response->getBody() : '',
                $e,
                microtime(true) - $start,
                $this->timeout
            );
            throw new \ServiceUnavailableException('API Service Unavailable (2). Please try again later.');
        }
    }

    /**
     * @param string $method
     * @param $object
     * @return mixed
     * @throws \ServiceUnavailableException
     */
    public function postRawJson($method, $object)
    {
        try {
            $start = microtime(true);

            $this->guzzleClient->setDefaultOption('verify', false);

            $response = $this->guzzleClient->post(
                $method,
                [
                    'body' => json_encode($object),
                    'headers' => ['content-type' => 'application/json'],
                    'auth'    => [ Config::get('ahvla.ibm-curl-user'), Config::get('ahvla.ibm-curl-password') ],
                    'timeout' => $this->timeout,
                    'connect_timeout' => $this->timeout,
                ]
            );

            if (class_exists('Debugbar')) {
                \Debugbar::getCollector('api_requests')->addRequest(array_merge(['method' => 'POST - raw', 'url' => $method], ['json_object' => $object]));
            }

            $jsonResponse = $response->json();

            $this->log->logCall(
                $this->guzzleClient,
                LimsApiCallLog::RAW_JSON_POST_METHOD,
                $method,
                $object,
                $jsonResponse,
                null,
                microtime(true) - $start,
                $this->timeout
            );
            return $jsonResponse;
        } catch (Exception $e) {
            $this->log->logCall(
                $this->guzzleClient,
                LimsApiCallLog::RAW_JSON_POST_METHOD,
                $method,
                $object,
                isset($response) && $response ? $response->getBody() : '',
                $e,
                microtime(true) - $start,
                $this->timeout
            );
            $this->log->logError(
                $this->guzzleClient,
                LimsApiCallLog::RAW_JSON_POST_METHOD,
                $method,
                $object,
                isset($response) && $response ? $response->getBody() : '',
                $e,
                microtime(true) - $start,
                $this->timeout
            );
            throw new \ServiceUnavailableException('API Service Unavailable (3). Please try again later.');
        }
    }
}
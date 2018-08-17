<?php

namespace ahvla\limsapi\service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Config\Repository;

class LimsApiCallLog
{
    const CLASS_NAME = __CLASS__;

    const POST_METHOD = 'POST';
    const RAW_JSON_POST_METHOD = 'JSON_POST';
    const GET_METHOD = 'GET';

    /**
     * @var Repository
     */
    private $config;


    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * @param Client $guzzleClient
     * @param $type
     * @param $url
     * @param $parameters
     * @param $response
     * @param $exception
     * @param $timeElapsed
     * @internal param string $type
     * @internal param string $url
     * @internal param array|object $parameters
     * @internal param string $response
     * @internal param Exception|null $exception
     */
    public function logCall(Client $guzzleClient, $type, $url, $parameters, $response, $exception, $timeElapsed, $timeout)
    {
        $this->logData($guzzleClient, $type, $url, $parameters, $response, $exception, $timeElapsed, $timeout, 'api-request');
    }

    public function logError(Client $guzzleClient, $type, $url, $parameters, $response, $exception, $timeElapsed, $timeout)
    {
        $this->logData($guzzleClient, $type, $url, $parameters, $response, $exception, $timeElapsed, $timeout, 'api-error');
    }

    private function logData(Client $guzzleClient, $type, $url, $parameters, $response, $exception, $timeElapsed, $timeout, $prefix)
    {
        $logInfo = [
            'timestamp' => date("Y-m-d H:i:s"),
            'url' => $guzzleClient->getBaseUrl() . $url,
            'method' => $type,
        ];

        if (is_array($parameters)) {
            $logInfo['parameters'] = $parameters;
        } elseif (is_object($parameters)) {
            $logInfo['payload'] = $parameters;
        }

        if ($exception) {
            if ($exception instanceof ClientException) {
                /** @var ClientException $exception */
                $exception = $exception;
                $logInfo['response'] = (string)$exception->getResponse()->getBody();
             } elseif ($exception instanceof ServerException) {
                $logInfo['response'] = $exception->getResponse()->getBody()->__toString();
            } else {
                $logInfo['response'] = '';
            }

            $logInfo['exception_message'] = $exception->getMessage();
            $logInfo['exception_stacktrace'] = $exception->getTraceAsString();
        } else {
            $logInfo['response'] = $response;
        }

        $logInfo['timeout'] = $timeout;
        $logInfo['secondsElapsed'] = $timeElapsed;

        if ($exception || $this->config->get('ahvla.log-all-lims-api-requests', false)) {
            $requestFileName = $prefix.'-'.date('Y-m-d');
            $fileName = storage_path() . '/logs/' . $requestFileName . '.log';

            // Grab old content and remove the wrapping brackets
            $oldContent = "";
            if(file_exists($fileName)){
                $oldContent = file_get_contents($fileName);
                $oldContent = trim($oldContent, ' []\n\t\r\0');
            }

            $seperator = ",\n";

            $content = $oldContent.$seperator.json_encode($logInfo);

            $content = trim($content, " ,\n[]\t\r\0"); // Ensure no commas at the start of the file

            $content = "[".$content."]";

            file_put_contents($fileName, $content);
        }
    }
}
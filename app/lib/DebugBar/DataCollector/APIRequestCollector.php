<?php
namespace DebugBar\DataCollector;

/**
 * Class APIRequestCollector
 *
 * Displays all widget data for the API requests
 * @package DebugBar\DataCollector
 */
class APIRequestCollector extends DataCollector implements Renderable {
    /**
     * A store for arrays
     * @var array
     */
    private $requests = [];

    /**
     * Returns all requests for rendering into the widget
     *
     * @return array
     */
    public function collect() {
        return array(
            'num_requests' => count($this->requests),
            'requests' => $this->requests,
        );
    }

    /**
     * Adds a request to the store
     *
     * @param $data
     */
    public function addRequest($data) {
        $this->requests[] = $data;
    }

    /**
     * Gets the name of the widget
     *
     * @return string
     */
    public function getName() {
        return 'api_requests';
    }

    /**
     * Gets the widget to display
     *
     * @return array
     */
    public function getWidgets() {
        return [
            'api_requests' => [
                'icon' => 'leaf',
                'widget' => 'PhpDebugBar.Widgets.ListWidget',
                'map' => 'api_requests.requests',
                'default' => '[]'
            ],
            'api_requests:badge' => [
                'map' => 'api_requests.num_requests',
                'default' => 0
            ]
        ];
    }
}
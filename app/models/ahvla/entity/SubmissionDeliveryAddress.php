<?php

namespace ahvla\entity;


class SubmissionDeliveryAddress {

    // delivery addresses
    public $deliveryAddresses;

    public function __construct($response)
    {
        $this->deliveryAddresses = $response;
    }

    /**
     *
     */
    public function getDeliveryAddresses()
    {
        return $this->deliveryAddresses;
    }

    /**
     * Checks if the separate addresses and the single address fields are
     * actually the same address
     */
    public function onlySingleAddress()
    {
        // drop out if no separate addresses
        if (!isset($this->deliveryAddresses['separateAddresses'])) {
            return true;
        }

        // drop out if there are multiple separate addresses
        $separates = $this->deliveryAddresses['separateAddresses'];
        if (sizeof($separates) > 1) {
            return false;
        }

        // check both types have addresses set
        $separate = reset($separates);
        $single = $this->deliveryAddresses['singleAddress'];
        if (!isset($separate['address']) || !isset($single['address'])) {
            return true;
        }

        // check the addresses are the same
        $address1 = $separate['address'];
        $address2 = $single['address'];
        if ($address1['address1'] == $address2['address1']
            && $address1['address2'] == $address2['address2']
            && $address1['address3'] == $address2['address3']) {
            return true;
        }

        return false;
    }

    public function addPackageInfo($addressConfig, $basketProducts)
    {
        foreach ($basketProducts as $product) {
            if ($product->testPackType === 'PACKAGE') {
                if ($addressConfig === 'separate') {
                    foreach ($this->deliveryAddresses['separateAddresses'] as $i => $separate) {
                        foreach ($separate['tests'] as $ii => $test) {
                            if ($test['packageCode'] === $product->id) {
                                $this->deliveryAddresses['separateAddresses'][$i]['tests'][$ii]['packageDescription'] = $product->name;
                            }
                        }
                        $this->deliveryAddresses['separateAddresses'][$i]['tests'] = self::sortByPackageAndTest($this->deliveryAddresses['separateAddresses'][$i]['tests']);
                    }
                } elseif ($addressConfig === 'single') {
                    foreach ($this->deliveryAddresses['singleAddress']['tests'] as $i => $test) {
                        if ($test['packageCode'] === $product->id) {
                            $this->deliveryAddresses['singleAddress']['tests'][$i]['packageDescription'] = $product->name;
                        }
                    }
                }
                $this->deliveryAddresses['singleAddress']['tests'] = self::sortByPackageAndTest($this->deliveryAddresses['singleAddress']['tests']);
            }
        }
    }

    public static function sortByPackageAndTest($tests)
    {
        foreach ($tests as $key => $row) {
            $firstOrder[$key] = $row['packageCode'];
            $secondOrder[$key] = $row['testId'];
        }

        array_multisort($firstOrder, SORT_STRING, SORT_DESC,
            $secondOrder, SORT_STRING, SORT_DESC,
            $tests);

        return $tests;
    }

    public function containsPooledTests($tests)
    {
        foreach ($tests as $i => $test) {
            if ($test['poolGroup'] !== null) {
                return true;
            }
        }
        return false;
    }

}
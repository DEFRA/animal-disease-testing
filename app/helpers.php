<?php
/**
 * Created by PhpStorm.
 * User: omar
 * Date: 21/05/15
 * Time: 13:35
 */

/**
 * Split the delivery address string that is returned from the LIMS Call
 * Example: 'West House, Station Road, Thirsk. YO7 1PZ'
 * @param $addressStr
 * @return array
 */
function splitAddressString($addressStr)
{
    $temp = explode(', ', $addressStr);
    $tempLast = array_pop($temp);
    $temp1 = explode('. ', $tempLast);
    $addressArr = array_merge($temp, $temp1);
    return $addressArr;
}

function versionRelease()
{
    $tagId = '';

    if (file_exists('tag_id')) {
        $tagId = '&nbsp;build: <a target="_blank"  href="/releasecommits">'. file_get_contents('tag_id').'</a>';
    }

    return $tagId;
}

function plural($amount) {
    if ( $amount === 1 ) {
        return '';
    }
    return 's';
}

function fopsopStatus($testPairedStatus) {

    $fopsop = '';

    if ($testPairedStatus === 'FOP') {
        $fopsop = ' - FOP';
    }
    if ($testPairedStatus === 'SOP') {
        $fopsop = ' - SOP';
    }

    return $fopsop;
}

// For dispatch note
function displayProducts($tests, $containsPooledTests = null)
{
    $package_code = '';
    $package_code_changed = false;

    for($idx=0,$idxMax=count($tests);$idx<$idxMax;$idx++) {

        if (isset($tests[$idx]['packageCode']) && !is_null($tests[$idx]['packageCode'])) {
            $package = true;
            if ($package_code !== $tests[$idx]['packageCode']) {
                $package_code = $tests[$idx]['packageCode'];
                $package_code_changed = true;
            }
        } else {
            $package = false;
        }

        if ($package_code_changed) {
            echo '<tr>';
            echo '<td style="border: 1px solid #000;"></td>';
            echo '<td style="border: 1px solid #000;">'.($package ? htmlentities($tests[$idx]['packageCode']):'').'</td>';
            echo '<td style="border: 1px solid #000;">'.($package ? htmlentities($tests[$idx]['packageDescription']): '').'</td>';
            echo '<td style="border: 1px solid #000;"></td>';
            echo '<td style="border: 1px solid #000;"></td>';
            if ($containsPooledTests) {
                echo '<td style="border: 1px solid #000;"></td>';
            }
            echo '</tr>';
            $package_code_changed = false; // reset
        }

        echo '<tr>';

        echo '<td style="border: 1px solid #000;">'.htmlentities($tests[$idx]['sampleType']).fopsopStatus($tests[$idx]['testPairedStatus']). '</td>';
        echo '<td style="border: 1px solid #000;">'.($package ? htmlentities($tests[$idx]['packageCode']).' / ' : '').htmlentities($tests[$idx]['testId']). '</td>';
        echo '<td style="border: 1px solid #000;">'.htmlentities($tests[$idx]['testDescription']). '</td>';
        echo '<td style="border: 1px solid #000;">'.htmlentities($tests[$idx]['sampleId']).'</td>';
        echo '<td style="border: 1px solid #000;">'.htmlentities($tests[$idx]['animalId']).'</td>';
        if ($containsPooledTests) {
            echo '<td style="border: 1px solid #000;">' . htmlentities($tests[$idx]['poolGroup']) . '</td>';
        }

        echo '</tr>';
    }
}

function sanitiseInput($input = array(), $formAttributeName = null)
{
    $sanitisedInput = [];

    $use_strip_tags = ['written_clinical_history','feedback-msg']; // user input likely to contain "<" and ">"

    foreach ($input as $name => $value) {

        if (!empty($value)) {
            if (is_array($value)) { // HTML array element e.g. Admin lookup tables
                foreach ($value as $array_key => $array_values) {
                    foreach ($array_values as $array_name => $array_value) {
                        $sanitisedInput[$name][$array_key][$array_name] = filter_var($array_value, FILTER_SANITIZE_STRING);
                    }
                }
            } else { // field must use strip_tags OR field is from ajax update and must use strip_tags (ajax updates come through route filters in different structure)
                if (in_array($name, $use_strip_tags) || (in_array($formAttributeName, $use_strip_tags) && $name === 'value')) {
                    $sanitisedInput[$name] = strip_tags($value);
                } else {
                    $sanitisedInput[$name] = filter_var($value, FILTER_SANITIZE_STRING);
                }
            }
        }
    }
    return $sanitisedInput;
}
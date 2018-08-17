<?php
/**
 * Created by IntelliJ IDEA.
 * User: daniel.fernandes
 * Date: 08/01/2015
 * Time: 11:42
 */

namespace ahvla\config;

use Config;

class AhvlaConfig {

    public function getLimsApiUrl(){
        return Config::get('ahvla.lims-api-url');
    }

    public function getLimsPrefix(){
        return Config::get('ahvla.lims-prefix');
    }

    public function getGACode(){
        return Config::get('ahvla.gacode');
    }

    public function getAPITimeout(){
        return Config::get('ahvla.api-timeout');
    }
}
<?php

namespace ahvla\entity\victorSettings;

use ahvla\entity\AbstractEloquentRepository;

class VictorSettingsRepository extends AbstractEloquentRepository {
    /*
     * @var VictorSettings
     */
    protected $model;

    /**
     * Constructor
     * @param VictorSettings $model
     */
    public function __construct(VictorSetting $model)
    {
        $this->model = $model;
    }

    /**
     * Get all settings
     * 
     * @return mixed             
     */
    public function all()
    {
        return $this->model->first();
    }

    /**
     * Get specific setting
     * @param  strong  $setting      setting name
     * @return mixed             
     */
    public function get($setting)
    {
       return $this->model->first()->{$setting};
    }

    /**
     * Set specific setting
     * @param $setting
     * @param $value
     */
    public function set($setting, $value)
    {
        $this->model->update([$setting => $value]);
    }

    /**
     * Get the current status Id of the login form
     * @return mixed
     */
    public function getLoginFormStatus() {
        return $this->get('disableLogin');
    }

    /**
     * Enable the login form
     */
    public function enableLogin() {

        $this->set('disableLogin', 0);
        $this->set('displayLoginPageMessage', 0);

    }

    /**
     * Disable the login form
     */
    public function disableLogin() {

        $this->set('disableLogin', 1);
        $this->set('displayLoginPageMessage', 1);

    }

}
<?php
namespace ConfigManager;

class Config
{
    protected $store = [];
    protected $watchdogs = [];

    protected $configDirPath = './config';
    protected $connectorsDirPath = './connectors';
    protected $defaultConfigPrefix = 'default';
    protected $connectors = [];

    function __construct($params = [])
    {
        $this->store = new Store;

        if (isset($params['configDirPath'])) {
            $this->store->setConfigDirPath($params['configDirPath']);
        }
        if (isset($params['connectorsDirPath'])) {
            $this->store->setConnectorsDirPath($params['connectorsDirPath']);
        }
        if (isset($params['defaultConfigPrefix'])) {
            $this->store->setDefaultConfigPrefix($params['defaultConfigPrefix']);
        }
        if (isset($params['connectors'])) {
            $this->store->setConnectors($params['connectors']);
        }
    }

    /**
     * Initialize config and load config elements from sources
     */
    public function init() {
        $this->store->init();
    }

    /**
     * Clear and reload config object
     */
    public function reload() {
        $this->store->reload();
    }

    /**
     * Clear config object
     */
    public function clearasync () {
        $this->store->clear();
    }

    /**
     * Get a config element by key
     * @param string key 
     * @param mixed defaultValue 
     */
    public function get_config($key, $defaultValue) {
        $this->store->getConfigByKey($key, $defaultValue);
    }

    /**
     * List all config elements
     */
    public function list() {
        $this->store->listConfig();
    }

    /**
     * Set a config element
     * @param string key 
     * @param mixed value 
     */
    public function set($key, $value) {
        $this->store->setConfigValue($key, $value);
    }

    // /**
    //  * Stop all running watchdog processes
    //  * 
    //  * @return void
    //  */
    // public function stop_watchdogs() {
    //     $this->store->stopWatchdogs();
    // }

    /**
     * Get current config app env
     * 
     * @return string
     */
    public function get_current_env() {
        $this->store->getCurrentEnv();
    }
}

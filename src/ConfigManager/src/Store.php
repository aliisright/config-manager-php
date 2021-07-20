<?php
namespace ConfigManager;

use Illuminate\Support\Arr;

class Store
{
    protected $store = [];
    protected $watchdogs = [];

    protected $configDirPath = 'config';
    protected $connectorsDirPath = './connectors';
    protected $defaultConfigPrefix = 'default';
    protected $connectors = [];

    function __construct()
    {
        $this->store = [];
    }

    public function setConfigDirPath($path) {
        $this->configDirPath = $path;
    }

    public function setConnectorsDirPath($path) {
        $this->connectorsDirPath = $path;
    }

    public function setDefaultConfigPrefix($prefix) {
        $this->defaultConfigPrefix = $prefix;
    }

    public function setConnectors($connectors) {
        $this->connectors = $connectors;
    }

    public function loadConfigFiles($env = null) {
        $env = $env ?? getenv('CONFIG__APP_ENV');
        $appEnv = $env ?? 'default';
        $envPath = __DIR__ . '/../' . $this->configDirPath . '/' . $appEnv;

        foreach (scandir($envPath) as $file) {
            // read the file
            if (!in_array($file, ['.', '..'])) {
                $arr = explode('.', $file);
                $arr = array_slice($arr, 0, -1);
                $filename = implode('.', $arr);

                $file = file_get_contents($envPath . '/' . $file, "r");
                $fileContent = json_decode($file, true);
                $newFileContent = [];

                if (isset($this->store[$filename])) {
                    $newFileContent = deepMerge($this->store[$filename], $fileContent);
                } 
                $this->store[$filename] = $newFileContent;
            }
        }
        if (!$this->store['APP_ENV']) {
            $this->store['APP_ENV'] = $appEnv;
        }
    }

    public function loadEnvVariable($key, $value) {
        $keys = explode('__', $key);
        if (isset($keys[0]) && $keys[0] !== "CONFIG") {
            return false;
        }
        array_shift($keys);

        $dotSeparated = implode('.', $keys);

        arraySet($this->store, $dotSeparated, $value);
        return $value;
    }

    public function loadEnvironment() {
        $env = getenv();
        foreach ($env as $key => $value) {
            $envKey = explode('__', $key);
            if (count($envKey) <= 1 || (isset($envKey[0]) && $envKey[0] !== 'CONFIG')) {
                continue;
            }
            $this->loadEnvVariable($key, $value);
        }
    }

        /**
     * Initialize config and load config elements from sources
     */
    public function init() {
        $this->loadConfigFiles('default');
        $this->loadConfigFiles();
        $this->loadEnvironment();
    }

    /**
     * Clear and reload config object
     */
    public function reload() {
        $this->store = [];
        $this->init();
    }

    /**
     * Clear config object
     */
    public function clear() {
        $this->store = [];
    }

    /**
     * Get a config element by key
     * 
     * @param string $key
     * @param mixed $defaultValue
     */
    public function getConfigByKey(string $key, $defaultValue = null) {
        return Arr::get($this->store, $key, $defaultValue);
    }

    /**
     * List all config elements
     */
    public function listConfig() {
        return $this->store;
    }

    /**
     * Set a config element
     * @param string key 
     * @param mixed value 
     */
    public function setConfigValue(string $key, $value) {
        $newStore = $this->store;
        Arr::set($newStore, $key, $value);
        $this->store = $newStore;
    }

    /**
     * Get current app config env
     * 
     * @return string
     */
    public function getCurrentEnv() {
        return Arr::get($this->store, ['APP_ENV'], null);
    }
}

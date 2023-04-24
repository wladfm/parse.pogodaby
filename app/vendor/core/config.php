<?php

namespace Core;

/**
 * @property null|string cities
 * @property null|string replace_city
 * @property null|string path_img
 * @property null|string url
 * @property null|string pre_all_city
 * @property null|string pre_api
 * @property null|string pre_img
 * @property null|string curl_timeout_s
 */
class Config extends Singleton
{
    private $isLoad = false;
    private $config = [];

    /**
     * @param $key
     * @return mixed|null
     */
    public function __get($key)
    {
        $this->load();
        return $this->config[$key] ?? null;
    }

    /**
     * @return void
     */
    private function load()
    {
        if ($this->isLoad) {
            return;
        }
        $dirConfig = __DIR__ . '/../.config';
        if (!is_dir($dirConfig)) {
            mkdir($dirConfig);
        }
        $fileConfig = $dirConfig . DIRECTORY_SEPARATOR . 'application.ini';
        // Default file
        if (!file_exists($fileConfig)) {
            if (file_exists($fileConfig . '.sample')) {
                copy($fileConfig . '.sample', $fileConfig);
            }
        }

        $this->config = parse_ini_file($fileConfig);
        $this->isLoad = true;
    }

    /**
     * @return array
     */
    public function getCities()
    {
        $cities = explode(',', $this->cities);
        $result = [];
        foreach ($cities as $city) {
            $data = explode(':', trim($city));
            if (count($data) < 2) {
                continue;
            }
            $result[] = ['country' => $data[0], 'city' => $data[1]];
        }
        return $result;
    }

    /**
     * @param $id
     * @return string|null
     */
    public function getReplaceCity($id)
    {
        $replaceCities = explode(',', $this->replace_city);
        $result = [];
        foreach ($replaceCities as $replace) {
            $data = explode(':', trim($replace));
            if (count($data) < 2) {
                continue;
            }
            $result[$data[0]] = $data[1];
        }
        return array_key_exists($id, $result) ? $result[$id] : null;
    }

    /**
     * Таймаут запроса
     * @return int
     */
    public function getCurlTimeOut()
    {
        return intval($this->curl_timeout_s);
    }
}
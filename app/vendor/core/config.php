<?php

namespace Core;

/**
 * @property null|string cities
 * @property null|string path_img
 * @property null|string url
 * @property null|string pre_all_city
 * @property null|string pre_api
 * @property null|string pre_img
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
}
<?php

namespace Core;

class Radar extends CurlAbstract
{
    private $country;
    private $city;

    public function __construct(string $country, string $city)
    {
        $this->country = $country;
        $this->city = $city;
    }

    /**
     * @return array|null
     */
    public function getParamsCity()
    {
        $allCities = $this->getCurl(Config::getInstance()->url . Config::getInstance()->pre_all_city);
        if ($allCities) {
            $allCities = json_decode($allCities, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                $allCities = $allCities[$this->country];
                $foundKey = array_search($this->city, array_column($allCities, 'name'));
                return $foundKey === false ? null : $allCities[$foundKey];
            }
        }
        return null;
    }

    /**
     * @param int $id
     * @return string|null
     */
    public function getLastRadar(int $id)
    {
        $data = $this->getCurl(Config::getInstance()->url . Config::getInstance()->pre_api . $id);
        if ($data) {
            $data = json_decode($data, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                return $data ? end($data)['url'] : null;
            }
        }
        return null;
    }

    /**
     * @param int $id
     * @param string $filename
     * @return bool|string
     */
    public function getImg(int $id, string $filename)
    {
        return $this->getCurl(Config::getInstance()->url . Config::getInstance()->pre_img . $id . DIRECTORY_SEPARATOR . $filename);
    }
}
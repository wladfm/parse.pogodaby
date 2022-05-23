<?php

namespace Core;

class Radar extends CurlAbstract
{
    private string $country;
    private string $city;

    const PRE_ALL_CITY = '/api/v2/weather-fact/all-cities';
    const PRE_API = '/api/v2/radar/';
    const PRE_IMG = '/files/radars/static/';

    public function __construct(string $country, string $city)
    {
        $this->country = $country;
        $this->city = $city;
    }

    /**
     * @return array|null
     */
    public function getParamsCity(): ?array
    {
        $allCities = $this->getCurl(Config::getInstance()->url . self::PRE_ALL_CITY);
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
    public function getLastRadar(int $id): ?string
    {
        $data = $this->getCurl(Config::getInstance()->url . self::PRE_API . $id);
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
    public function getImg(int $id, string $filename): bool|string
    {
        return $this->getCurl(Config::getInstance()->url . self::PRE_IMG . $id . DIRECTORY_SEPARATOR . $filename);
    }
}
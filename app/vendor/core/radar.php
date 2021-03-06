<?php

namespace Core;

class Radar extends CurlAbstract
{
    private $country;
    private $city;
    private $allCities = null;

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
        if (!$this->allCities) {
            $this->allCities = $this->getCurl(Config::getInstance()->url . Config::getInstance()->pre_all_city);
        }
        if ($this->allCities) {
            $this->allCities = json_decode($this->allCities, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                $this->allCities = $this->allCities[$this->country];
                $foundKey = array_search($this->city, array_column($this->allCities, 'name'));
                return $foundKey === false ? null : $this->allCities[$foundKey];
            }
        }
        return null;
    }

    /**
     * @param int $id
     * @return string|null
     */
    public function getLastRadar(int &$id)
    {
        $data = $this->getCurl(Config::getInstance()->url . Config::getInstance()->pre_api . $id);
        if ($data) {
            $data = json_decode($data, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                if ($data && !array_key_exists('message', $data)) {
                    return end($data)['url'];
                }
            }
        }
        $replaceId = Config::getInstance()->getReplaceCity($id);
        if ($replaceId) {
            $id = $replaceId;
            return $this->getLastRadar($replaceId);
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
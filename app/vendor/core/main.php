<?php

namespace Core;

use Exception;

class Main extends Singleton
{
    public function run()
    {
        try {
            $cities = Config::getInstance()->getCities();
            foreach ($cities as $city) {
                $radar = new Radar($city['country'], $city['city']);
                $params = $radar->getParamsCity();
                if ($params) {
                    $filename = $radar->getLastRadar(intval($params['id']));
                    if ($filename) {
                        $img = $radar->getImg(intval($params['id']), $filename);
                        $this->saveFile($params['icao'] . '_latest.png', $img);
                    }
                }
            }
        } catch (Exception $exception) {
            echo 'File ' . $exception->getFile() . '. Line ' . $exception->getLine() . '. Error: ' . $exception->getMessage() . PHP_EOL;
        }
    }

    private function saveFile(string $filename, string $img): void
    {
        $file = fopen(Config::getInstance()->path_img . $filename, 'w');
        if ($file !== false) {
            fwrite($file, $img);
            fclose($file);
        }
    }
}
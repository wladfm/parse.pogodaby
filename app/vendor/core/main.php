<?php

namespace Core;

use Exception;

class Main extends Singleton
{
    public function run()
    {
        try {
            $radar = new Radar(country: Config::getInstance()->country, city: Config::getInstance()->city);
            $params = $radar->getParamsCity();
            if ($params) {
                $filename = $radar->getLastRadar(intval($params['id']));
                if ($filename) {
                    $img = $radar->getImg(intval($params['id']), $filename);
                    $this->saveFile($filename, $img);
                }
            }
        } catch (Exception $exception) {
            echo 'File ' . $exception->getFile() . '. Line ' . $exception->getLine() . '. Error: ' . $exception->getMessage() . PHP_EOL;
        }
    }

    private function saveFile(string $filename, string $img): void
    {
        $file = fopen('./img/' . $filename, 'w');
        fwrite($file, $img);
        fclose($file);
    }
}
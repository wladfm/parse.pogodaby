<?php

namespace Core;

use Exception;

class Main extends Singleton
{
    public function run()
    {
        try {
            $radar = new Radar(Config::getInstance()->country, Config::getInstance()->city);
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
        $file = fopen(PATH_ROOT . '/img/' . $filename, 'w');
        if ($file !== false) {
            fwrite($file, $img);
            fclose($file);
        }
    }
}
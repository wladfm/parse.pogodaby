<?php

namespace Core;

/**
 * @property null|string country
 * @property null|string city
 * @property null|string url
 * @property null|string pre_all_city
 * @property null|string pre_api
 * @property null|string pre_img
 */
class Config extends Singleton
{
    private bool $isLoad = false;
    private array $config = [];

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
            } else {
                $fp = fopen($fileConfig, "w");
                $textConfig = 'path=db/migrations' . PHP_EOL;
                fwrite($fp, $textConfig);
                fclose($fp);
            }
        }

        $this->config = parse_ini_file($fileConfig);
        $this->isLoad = true;
    }
}
<?php
const URL_API = 'https://pogoda.by/api/v2/radar/26666';
const URL_IMG = 'https://pogoda.by/files/radars/static/26666/';

/**
 * @param string $url
 * @return string|null
 */
function getLastRadar(string $url): ?string
{
    $data = curl($url);
    if ($data) {
        $data = json_decode($data, true);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $data ? end($data)['url'] : null;
        }
    }
    return null;
}

/**
 * @param string $url
 * @return bool|string
 */
function getImg(string $url): bool|string
{
    return curl($url);
}

/**
 * @param string $url
 * @return bool|string
 */
function curl(string $url): bool|string
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

/**
 * @param string $filename
 * @param string $img
 * @return void
 */
function saveFile(string $filename, string $img): void
{
    $file = fopen('./img/' . $filename, 'w');
    fwrite($file, $img);
    fclose($file);
}

$filename = getLastRadar(URL_API);
if ($filename) {
    $img = getImg(URL_IMG . $filename);
    saveFile($filename, $img);
}
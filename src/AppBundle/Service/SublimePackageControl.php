<?php

namespace AppBundle\Service;


class SublimePackageControl
{
    /** @var string  */
    private static $baseUrl = 'https://packagecontrol.io/packages/';

    /** @var object */
    private $doc;

    /**
     * @param string $packageUrl
     */
    public function __construct($package)
    {
        $this->doc = $this->getDocFromPackage($package);
    }

    /**
     * Get total number of downloads.
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->shortenNumber($this->doc->installs->total);
    }

    /**
     * Get total number of downloads for given platform.
     *
     * @param string $platform
     * @return string
     */
    public function getPlatform($platform)
    {
        return $this->shortenNumber($this->doc->installs->$platform);
    }

    /**
     * @param string $package
     * @return bool
     */
    public static function packageIsValid($package)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('%s%s.json', self::$baseUrl, $package));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return true;
        }

        return false;
    }

    /**
     * Get JSON from package
     *
     * @param string $package
     * @return string
     */
    private function getDocFromPackage($package)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('%s%s.json', self::$baseUrl, $package));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return json_decode($response);
        }

        return null;
    }

    /**
     * Use to convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+, 1B+ etc
     *
     * @param string $n
     * @return string
     */
    private function shortenNumber($n) {
        if ($n < 1000) {
            // 1 - 999
            $newNumber = floor($n);
            $suffix = '';
        } else if ($n < 1000000) {
            // 1k-999k
            $newNumber = floor($n / 1000);
            $suffix = 'K';
        } else if ($n < 1000000000) {
            // 1m-999m
            $newNumber = floor($n / 1000000);
            $suffix = 'M';
        } else if ($n < 1000000000000) {
            // 1b-999b
            $newNumber = floor($n / 1000000000);
            $suffix = 'B';
        } else if ($n >= 1000000000000) {
            // 1t+
            $newNumber = floor($n / 1000000000000);
            $suffix = 'T';
        }

        if ($newNumber < 1000 || ($newNumber >= 1000 && $suffix)) {
            return $newNumber . $suffix;
        }

        return $n;
    }
}

<?php

namespace AppBundle\Service;


class SublimePackageControl
{
    /** @var string  */
    private static $baseUrl = 'https://packagecontrol.io/packages/';

    /** @var \DOMXpath */
    private $doc;

    /**
     * @param string $packageUrl
     */
    public function __construct($package)
    {
        $packageUrl = sprintf('%s%s', self::$baseUrl, $package);
        $this->doc = $this->getDoc($packageUrl);
    }

    /**
     * Get total number of downloads.
     *
     * @return string
     */
    public function getTotal()
    {
        $node = $this->doc->query('//ul[@class="totals"]//span[@class="installs"]');
        $num = trim($node[0]->textContent);

        return $num;
    }

    /**
     * Get total number of downloads for given platform.
     *
     * @param string $platform
     * @return string
     */
    public function getPlatform($platform)
    {
        $node = $this->doc->query('//span[@class="'.$platform.' installs"]');
        $num = trim($node[0]->textContent);

        return $num;
    }

    /**
     * @param string $package
     * @return bool
     */
    public static function packageIsValid($package)
    {
        $packageUrl = sprintf('%s%s', self::$baseUrl, $package);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $packageUrl);
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
     * Create document from package url in order to crawl stats.
     *
     * @param string $packageUrl
     * @return \DOMXpath
     */
    private function getDoc($packageUrl)
    {
        $pageContent = file_get_contents($packageUrl);

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHtml($pageContent);
        libxml_clear_errors();

        return new \DOMXpath($doc);
    }
}

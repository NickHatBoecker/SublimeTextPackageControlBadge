<?php

namespace AppBundle\Service;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Badge
{
    const BADGETYPE_TOTAL = 'total';
    const BADGETYPE_WINDOWS = 'windows';
    const BADGETYPE_MACOS = 'osx';
    const BADGETYPE_LINUX = 'linux';

    private $validBadgeTypes = [
        self::BADGETYPE_TOTAL,
        self::BADGETYPE_WINDOWS,
        self::BADGETYPE_MACOS,
        self::BADGETYPE_LINUX,
    ];

    /** @var UrlGeneratorInterface */
    private $router;

    /**
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param string $packageUrl
     * @param string $badgeType
     * @return string
     */
    public function getNum($package, $badgeType)
    {
        $sublime = new SublimePackageControl($package);

        $num = $sublime->getTotal();
        if ($badgeType !== self::BADGETYPE_TOTAL && $this->isValidBadgeType($badgeType)) {
            $num = $sublime->getPlatform($badgeType);
        }

        return $num;
    }

    /**
     * @param string $packageUrl
     * @param string $badgeType
     *
     * @return string
     */
    public function getMarkdownCode($package, $badgeType)
    {
        $url = $this->router->generate('get_badge', [
            'badgeType' => $badgeType,
            'package' => $package,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $homepageUrl = $this->router->generate('homepage', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $code = sprintf(
            "[![SublimeBadge](%s)](%s)",
            $url,
            $homepageUrl
        );

        return $code;
    }

    /**
     * @param string $badgeType
     * @return bool
     */
    private function isValidBadgeType($badgeType)
    {
        return in_array($badgeType, $this->validBadgeTypes);
    }
}

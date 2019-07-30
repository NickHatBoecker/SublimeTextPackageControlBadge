<?php

namespace AppBundle\Controller;


use AppBundle\Service\StringCompressor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Service\Badge;
use Symfony\Component\HttpFoundation\Response;

class BadgeController extends Controller
{
    /**
     * @Route("/badge/{badgeType}/{package}.svg/", name="get_badge"))
     *
     * @param string $badgeType
     * @param string $package
     */
    public function getBadgeAction($badgeType, $package)
    {
        try {
            $response = new Response($this->renderView('badge.html.twig', [
                'num' => $this->get(Badge::class)->getNum($package, $badgeType),
                'badgeType' => $badgeType,
                'colors' => [
                    Badge::BADGETYPE_TOTAL => '#aaa',
                    Badge::BADGETYPE_LINUX => '#ef9f9f',
                    Badge::BADGETYPE_WINDOWS => '#a8bacf',
                    Badge::BADGETYPE_MACOS => '#bd9cb7',
                ],
            ]));

            $response->headers->set('Content-type', 'image/svg+xml');
        } catch (\Exception $e) {
            $response = new Response($e->getMessage(), 400);
        }

        $response->headers->set('Access-Control-Allow-Origin','*');

        return $response;
    }
}

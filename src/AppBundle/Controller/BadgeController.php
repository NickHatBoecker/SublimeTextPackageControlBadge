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
     * @Route("/badge/{badgeType}/{package}.svg", name="get_badge"))
     *
     * @param string $badgeType
     * @param string $package
     */
    public function getBadgeAction($badgeType, $package)
    {
        try {
            $response = new Response($this->renderView('badge.html.twig', [
                'num' => $this->get(Badge::class)->getNum($package, $badgeType),
            ]));

            $response->headers->set('Content-type', 'image/svg+xml');
        } catch (\Exception $e) {
            $response = new Response("Could not retrieve numbers. Is the package name correct?", 400);
        }

        $response->headers->set('Access-Control-Allow-Origin','*');

        return $response;
    }
}

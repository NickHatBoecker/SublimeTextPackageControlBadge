<?php

namespace AppBundle\Controller;

use AppBundle\Form\BadgeType;
use AppBundle\Service\Badge;
use AppBundle\Service\SublimePackageControl;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $counter = $this->get('AppBundle\Service\Counter');
        $form = $this->createForm(BadgeType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get form data
            $package = $form->getData()['package'];
            $badgeType = $form->getData()['badgeType'];

            $markdownCode = '';
            if (SublimePackageControl::packageIsValid($package)) {
                $markdownCode = $this->get(Badge::class)->getMarkdownCode($package, $badgeType);
            }

            $counter->increase();

            return $this->render('homepage.html.twig', [
                'form' => $form->createView(),
                'markdownCode' => $markdownCode,
                'badgeType' => $badgeType,
                'package' => $package,
                'counter' => $this->get('AppBundle\Service\Counter')->get(),
            ]);
        }

        return $this->render('homepage.html.twig', [
            'form' => $form->createView(),
            'counter' => $this->get('AppBundle\Service\Counter')->get(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @param AdRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/ads", name="ads")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * @param $slug
     * @param AdRepository $repo
     * @return Response
     * @Route("/ads/{slug}", name="ads_show")
     */
    public function show($slug, AdRepository $repo)
    {
        $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }
}

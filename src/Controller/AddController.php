<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AddController extends AbstractController
{
    /**
     * @param AdRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('add/index.html.twig', [
            'ads' => $ads
        ]);
    }
}

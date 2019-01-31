<?php

namespace App\Controller;

use App\Service\Stats;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     * @param ObjectManager $manager
     * @param Stats $stats
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ObjectManager $manager, Stats $stats)
    {
        $statistiques = $stats->getStats();

        $bestAds = $stats->getAdsStats('DESC');

        $worstAds = $stats->getAdsStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $statistiques,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds
        ]);
    }
}

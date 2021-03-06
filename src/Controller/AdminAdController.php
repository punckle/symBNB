<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     * @param $page
     * @param Pagination $pagination
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($page, Pagination $pagination)
    {
        $pagination->setEntityClass(Ad::class)
            ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'ads' => $pagination->getData(),
            'pages' => $pagination->getPages(),
            'page' => $page
        ]);
    }

    /**
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce {$ad->getTitle()} a bien été enregistrée"
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     */
    public function delete(Ad $ad, ObjectManager $manager)
    {
        //On vérifie si l'annonce n'a pas de réservations en cours:
        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce {$ad->getTitle()} car elle a des réservations en cours"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce {$ad->getTitle()} a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_ads_index');
    }
}

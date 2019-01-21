<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Entity\Ad;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class AdController extends AbstractController
{
    /**
     * @param AdRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Ad $ad
     * @return Response
     * @Route("/ads/{slug}", name="ads_show")
     */
    public function show(Ad $ad)
    {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @param Ad $ad
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/ads/{slug}/edit", name="ad_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas,
         vous ne pouvez pas la modifier")
     */
    public function edit(Request $request, ObjectManager $manager, Ad $ad)
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été éditée"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
            ]);

    }

    /**
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/ads/{slug}/delete", name="ad_delete")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()")
     */
    public function delete(Ad $ad, ObjectManager $manager)
    {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash('success', "Votre annonce a bien été supprimée");

        return $this->redirectToRoute('ads_index');
    }

}

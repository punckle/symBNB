<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users_index")
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserRepository $repository)
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/admin/users/{id}/edit", name="admin_users_edit")
     * @param User $user
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(User $user, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le profil de {$user->getFullName()} a bien été modifié"
            );

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/users/{id}/delete", name="admin_users_delete")
     * @param User $user
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(User $user, ObjectManager $manager)
    {
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            'L\'utilisateur a bien été supprimé'
        );

        return $this->redirectToRoute('admin_users_index');
    }
}

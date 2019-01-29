<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comments_index")
     * @param CommentRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(CommentRepository $repo)
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     * @param Comment $comment
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire a bien été modifié !"
            );
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     * @param ObjectManager $manager
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(ObjectManager $manager, Comment $comment)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_comments_index');
    }
}

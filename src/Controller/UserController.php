<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}", name="user_show")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(User $user)
    {
        return $this->render('user/login.html.twig', [
            'user' => $user
        ]);
    }
}

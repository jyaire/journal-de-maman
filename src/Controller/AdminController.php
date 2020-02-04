<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     * @param UserRepository $users
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(UserRepository $users, EntityManagerInterface $em) :Response
    {
        // list user waiting approbation
        $waiters = $users->findAll();
        return $this->render(
            'admin/index.html.twig',
            [
                'waiters' => $waiters,
            ]
        );
    }
}

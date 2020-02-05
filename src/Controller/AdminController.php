<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(UserRepository $userRepository, EntityManagerInterface $em) :Response
    {
        if (isset($_GET['getValidated'])) {
            $user = $userRepository->findOneBy([
            'id' => $_GET['id'],
            ]);
            $user->setIsValidated($_GET['getValidated']);
            if ($_GET['getValidated'] == 1) {
                $user->setRoles(["ROLE_LECTOR"]);
            }
            if ($_GET['getValidated'] == 0) {
                $user->setRoles(["ROLE_USER"]);
            }
            $em = $this->getDoctrine()->getManager();
            $em-> persist($user);
            $em->flush();
        }

        // list user waiting approbation
        $waiters = $userRepository->findAll();
        return $this->render(
            'admin/index.html.twig',
            [
                'waiters' => $waiters,
            ]
        );
    }
}

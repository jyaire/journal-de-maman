<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * @Route("admin")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     * @IsGranted("ROLE_ADMIN")
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function index(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ) :Response {
        if (isset($_GET['getValidated'])) {
            $user = $userRepository->findOneBy([
            'id' => $_GET['id'],
            ]);
            $user->setIsValidated($_GET['getValidated']);
            if ($_GET['getValidated'] == 1) {
                $user->setRoles(["ROLE_LECTOR"]);
                // Envoie d'un mail à l'utilisateur pour confirmer l'inscription
                $email = (new TemplatedEmail())
                    ->from(new Address('jyaire@gmail.com', 'Le Journal de Maman'))
                    ->to(new Address($user->getEmail(), $user->getFirstname()))
                    ->subject('Le Journal de Maman vous est accessible !')
                    // path of the Twig template to render
                    ->htmlTemplate('emails/validation.html.twig')
                    // variables for the template
                    ->context([
                        'user' => $user,
                    ])
                ;

                $mailer->send($email);

                $this->addFlash(
                    'success',
                    'Validation effective, un message a été envoyé à cet utilisateur'
                );
            }
            if ($_GET['getValidated'] == 0) {
                $user->setRoles(["ROLE_USER"]);
                $this->addFlash(
                    'success',
                    'Accès supprimé'
                );
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

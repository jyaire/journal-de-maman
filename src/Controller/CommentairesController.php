<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commentaires")
 */
class CommentairesController extends AbstractController
{
    /**
     * @Route("/", name="commentaires_index", methods={"GET"})
     * @IsGranted("ROLE_LECTOR")
     */
    public function index(CommentairesRepository $comRepository): Response
    {
        return $this->render('commentaires/index.html.twig', [
            'commentaires' => $comRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="commentaires_new", methods={"GET","POST"})
     * @IsGranted("ROLE_LECTOR")
     */
    public function new(Request $request): Response
    {
        $commentaire = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('commentaires_index');
        }

        return $this->render('commentaires/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commentaires_show", methods={"GET"})
     * @IsGranted("ROLE_LECTOR")
     */
    public function show(Commentaires $commentaire): Response
    {
        return $this->render('commentaires/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commentaires_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_LECTOR")
     */
    public function edit(Request $request, Commentaires $commentaire): Response
    {
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commentaires_index');
        }

        return $this->render('commentaires/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commentaires_delete", methods={"DELETE"})
     * @IsGranted("ROLE_LECTOR")
     */
    public function delete(Request $request, Commentaires $commentaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commentaires_index');
    }
}

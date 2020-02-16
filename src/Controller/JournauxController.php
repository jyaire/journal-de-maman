<?php

namespace App\Controller;

use App\Entity\Journaux;
use App\Form\JournauxType;
use App\Repository\JournauxRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/journaux")
 */
class JournauxController extends AbstractController
{
    /**
     * @Route("/", name="journaux_index", methods={"GET"})
     */
    public function index(JournauxRepository $journauxRepository): Response
    {
        return $this->render('journaux/index.html.twig', [
            'journauxes' => $journauxRepository->findBy([], ['datedebut' => 'ASC']),
        ]);
    }

    /**
     * @Route("/new", name="journaux_new", methods={"GET","POST"})
     * @IsGranted("ROLE_LECTOR")
     */
    public function new(Request $request): Response
    {
        $journaux = new Journaux();
        $form = $this->createForm(JournauxType::class, $journaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($journaux);
            $entityManager->flush();

            return $this->redirectToRoute('journaux_index');
        }

        return $this->render('journaux/new.html.twig', [
            'journaux' => $journaux,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="journaux_show", methods={"GET"})
     * @param Journaux $journaux
     * @return Response
     */
    public function show(Journaux $journaux): Response
    {
        return $this->render('journaux/show.html.twig', [
            'journaux' => $journaux,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="journaux_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_LECTOR")
     */
    public function edit(Request $request, Journaux $journaux): Response
    {
        $form = $this->createForm(JournauxType::class, $journaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('journaux_index');
        }

        return $this->render('journaux/edit.html.twig', [
            'journaux' => $journaux,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="journaux_delete", methods={"DELETE"})
     * @IsGranted("ROLE_LECTOR")
     * @param Request $request
     * @param Journaux $journaux
     * @return Response
     */
    public function delete(Request $request, Journaux $journaux): Response
    {
        if ($this->isCsrfTokenValid('delete'.$journaux->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($journaux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('journaux_index');
    }
}

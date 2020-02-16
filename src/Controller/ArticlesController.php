<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use App\Repository\JournauxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/articles")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="articles_index", methods={"GET"})
     * @param ArticlesRepository $articlesRepository
     * @return Response
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepository->findBy([], ['jour' => 'ASC']),
        ]);
    }

    /**
     * @Route("/new", name="articles_new", methods={"GET","POST"})
     * @IsGranted("ROLE_LECTOR")
     * @param Request $request
     * @param JournauxRepository $journaux
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function new(Request $request, JournauxRepository $journaux): Response
    {
        $article = new Articles();

        if (isset($_GET['journal'])) {
            // cherche le journal de provenance si on ajoute à partir d'une page journal
            $idjournal = $_GET['journal'];
            $journal = $journaux->FindOneBy(['id' => $idjournal]);
            $article->setJournal($journal);
        } else {
            $message = "Merci de vérifier que le bon journal est sélectionné !";
            $this->addFlash(
                'danger',
                $message
            );
        }

        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $journal = $article->getJournal();
            $date = $article->getJour()->format('d/m/Y');
            $entityManager->persist($article);
            $entityManager->flush();

            $message = "La page du $date a été ajoutée, merci !";
            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('journaux_show', [
                'id' => $journal->getId(),
            ]);
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="articles_show", methods={"GET"})
     * @IsGranted("ROLE_LECTOR")
     * @param Articles $article
     * @return Response
     */
    public function show(Articles $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

//    /**
//     * @Route("/random", name="random_article")
//     * @param Articles $article
 //    * @return RedirectResponse
//     */
//    public function getRandomArticle(Articles $article)
//    {
//        $id = $article->getId();
//       return $this->redirectToRoute('articles_show', [
//           'id' => $id,
//       ]);
//    }

    /**
     * @Route("/{id}/edit", name="articles_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_LECTOR")
     * @param Request $request
     * @param Articles $article
     * @return Response
     */
    public function edit(Request $request, Articles $article): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $message = "L'article a été modifié !";
            $this->addFlash('success', $message);

            return $this->redirectToRoute('articles_show', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="articles_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param Articles $article
     * @return Response
     */
    public function delete(Request $request, Articles $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $date = $article->getJour()->format('d/m/Y');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        $message = "La page du $date a été effacée !";
        $this->addFlash(
            'success',
            $message
        );

        return $this->redirectToRoute('journaux_show', [
            'id' => $article->getJournal()->getId(),
        ]);
    }
}

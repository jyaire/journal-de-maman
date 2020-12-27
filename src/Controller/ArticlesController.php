<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Commentaires;
use App\Entity\Likes;
use App\Entity\User;
use App\Form\ArticlesType;
use App\Form\CommentairesType;
use App\Repository\ArticlesRepository;
use App\Repository\JournauxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use \DateTime;

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
     * @return Response
     * @throws \Exception
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
            $article->setAjouteur($this->getUser())->setAjoutdate(new DateTime('now'));
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
     * @Route("/{id}", name="articles_show", methods={"GET", "POST"})
     * @IsGranted("ROLE_LECTOR")
     * @param Articles $article
     * @param ArticlesRepository $articles
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function show(
        Articles $article,
        ArticlesRepository $articles,
        Request $request
    ): Response {
        if (isset($_GET['fav'])) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($_GET['fav'] == true) {
                // ajout du like
                $like = new Likes();
                $like->setArticle($article)->setAuteurlike($this->getUser())->setDatelike(new DateTime('now'));
                $entityManager->persist($like);
                $entityManager->flush();

                $message = "Cette date a été ajoutée à vos favoris !";
                $this->addFlash(
                    'success',
                    $message
                );
            } else {
                // suppression du like
                $em = $this->getDoctrine()->getManager();
                $like = $em
                    ->getRepository(Likes::class)
                    ->findOneBy(['auteurlike' => $this->getUser(), 'article' => $article]);
                $em->remove($like);
                $em->flush();
                $message = "Date supprimée de vos favoris";
                $this->addFlash(
                    'success',
                    $message
                );
            }
        }

        // gestion de la couleur du coeur
        $coeur = false;
        $likes = $article->getLikes();
        if (isset($likes)) {
            // condition si un fav correspond à cet article et à l'utilisateur connecté pour colorier le coeur en rose
            foreach ($likes as $like) {
                if ($like->getArticle() === $article and $like->getAuteurlike() == ($this->getUser())) {
                    $coeur = true;
                }
            }
        }

        // cherche l'article précédent et suivant
        $previous = $articles->previous($article);
        $next = $articles->next($article);

        // ajout du formulaire des commentaires

        $commentaire = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setDatecom(new DateTime('now'))->setAuteurcom($this->getUser())->setArticle($article);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $message = "Votre commentaire a été posté !";
            $this->addFlash('success', $message);

            return $this->redirectToRoute('articles_show', [
            'id' => $article->getId(),
            ]);
        }
        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'coeur' => $coeur,
            'previous' => $previous,
            'next' => $next,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="articles_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_LECTOR")
     * @param Request $request
     * @param Articles $article
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, Articles $article): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setModifieur($this->getUser())->setModifdate(new DateTime('now'));
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

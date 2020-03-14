<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Journaux;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index() :Response
    {
        // statistiques des journaux et des articles
        $em = $this->getDoctrine()->getManager();
        $repoJournaux = $em->getRepository(Journaux::class);
        $totalJournaux = $repoJournaux->createQueryBuilder('j')
            ->select('count(j.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $repoArticles = $em->getRepository(Articles::class);
        $totalArticles = $repoArticles->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $totalContribs = $repoArticles->createQueryBuilder('a')
            ->select('count(distinct a.ajouteur)')
            ->getQuery()
            ->getSingleScalarResult();

        // contributeurs

        return $this->render('index.html.twig', [
            'totalJournaux' => $totalJournaux,
            'totalArticles' => $totalArticles,
            'totalContribs' => $totalContribs,
        ]);
    }
}

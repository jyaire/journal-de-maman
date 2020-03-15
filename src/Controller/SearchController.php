<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     * @IsGranted("ROLE_LECTOR")
     * @param Request $request
     * @param ArticlesRepository $articles
     * @return RedirectResponse|Response
     */
    public function search(Request $request, ArticlesRepository $articles)
    {
        $results = [];
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();
            $results = $articles->searchInContent($search);
            return $this->render('search/search.html.twig', [
                'articles' => $results,
                'search' => $search,
                'form' => $form->createView(),
            ]);
        }

        return $this->render('search/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

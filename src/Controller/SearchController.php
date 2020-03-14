<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
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
     * @param Request $request
     * @param ArticlesRepository $articles
     * @param $results
     * @return RedirectResponse|Response
     */
    public function search(Request $request, ArticlesRepository $articles)
    {
        $search = '';
        $results = [];
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();
            $results = $articles->searchInContent($search);
        }

        return $this->render('search/search.html.twig', [
            'articles' => $results,
            'search' => $search,
            'form' => $form->createView(),
        ]);
    }
}

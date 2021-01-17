<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Entity\Articles;
use App\Form\DocumentsType;
use App\Repository\DocumentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use \DateTime;

/**
 * @Route("/documents")
 * @IsGranted("ROLE_LECTOR")
 */
class DocumentsController extends AbstractController
{
    /**
     * @Route("/", name="documents_index", methods={"GET"})
     */
    public function index(DocumentsRepository $documentsRepository): Response
    {
        return $this->render('documents/index.html.twig', [
            'documents' => $documentsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{article}", name="documents_new", methods={"GET","POST"})
     * @param Request $request
     * @param Articles $articles
     * @param SluggerInterface $slugger
     */
    public function new(Request $request, Articles $article, SluggerInterface $slugger): Response
    {
        
        $form = $this->createForm(DocumentsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $files = $form->get('files')->getData();
            $quantity = 0;
            $directory = "documents/";

            foreach($files as $file) {
                $extension = $file->guessExtension();
                if ($file->getSize() > 10000000 ) {

                    $message = "Echec ! Chaque fichier doit peser moins de 10 Mo";
                    $this->addFlash('danger', $message);

                    return $this->redirectToRoute('documents/new.html.twig', [
                        'article' => $article,
                        'form' => $form->createView(),
                    ]);
                }
                if ($extension == 'jpg' or $extension == 'jpeg' or $extension == 'png' or $extension == 'pdf') {
                    $uniqId = uniqid();
                    $fileName = 'journal-' . $article->getId() . '-' . date('YmdHis') . '-' . $uniqId . '.' . $extension;
                    try {
                        $file->move($directory, $fileName);
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $document = new Documents();
                    $document
                        ->setArticle($article)
                        ->setPoster($this->getUser())
                        ->setFile($fileName)
                        ->setDate((new DateTime('now')))
                        ;
                    $entityManager->persist($document);
                }
                else {
                    $message = "Echec ! Tous les documents doivent être au format jpg, jpeg, png ou PDF";
                    $this->addFlash('danger', $message);
                    return $this->render('documents/new.html.twig', [
                        'article' => $article,
                        'form' => $form->createView(),
                    ]);
                }
                $quantity++;
            }
            
            $entityManager->flush();

            $message = $quantity . " document(s) mis en ligne";
            $this->addFlash('success', $message);
            return $this->redirectToRoute('articles_show', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('documents/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{pdf}", name="documents_show", methods={"GET"},  defaults={"pdf": null})
     * @param Documents $document
     * @param ?string $pdf
     */
    public function show(Documents $document, ?string $pdf): Response
    {
        if ($pdf == null) {
            return $this->render('documents/show.html.twig', [
                'document' => $document,
                'pdf' => $pdf,
            ]);
        } 
        else {
            return $this->file('documents/'. $document->getFile());
        }
        
    }

    /**
     * @Route("/{id}", name="documents_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Documents $document): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            
            // delete file in dir
            $fichier = "documents/" . $document->getFile();
            if( file_exists ($fichier))
                unlink( $fichier ) ;

            // delete document in DB
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();
        }

        $message = "Document supprimé";
        $this->addFlash('success', $message);

        return $this->redirectToRoute('articles_show', [
            'id' => $document->getArticle()->getId(),
        ]);
    }
}

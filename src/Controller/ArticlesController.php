<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/articles')]
class ArticlesController extends AbstractController
{
    #[Route('/', name: 'articles_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'articles_new', methods: ['GET', 'POST'])]
    /**
     * @Security("is_granted('ROLE_EDITOR')")
     */
    public function new(Request $request,ManagerRegistry $doctrine, EntityManagerInterface $entityManager, SluggerInterface $slugger, TranslatorInterface $translator): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setUserId($this->getUser());

			$image = $form->get('image')->getData();
			if($image){
				$fichier = md5(uniqid()).'.'.$image->guessExtension();
				$image->move(
					$this->getParameter('app.path.article_images'),
					$fichier
				);
				$article->setImage($fichier);
			}

            $entityManager = $doctrine->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $message = $translator->trans('Article published successfully');

            $this->addFlash('message', $message);
            return $this->redirectToRoute('articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'articles_show', methods: ['GET'])]
    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function show(Articles $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'articles_edit', methods: ['GET', 'POST'])]
    /**
     * @Security("is_granted('ROLE_EDITOR')")
     */
    public function edit(Request $request, Articles $article, EntityManagerInterface $entityManager, SluggerInterface $slugger, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $message = $translator->trans('Article modifié avec succés ');

            $this->addFlash('message', $message);

            return $this->redirectToRoute('articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}' ,name: 'articles_delete')]
    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id, ManagerRegistry $doctrine) {
        $article = $doctrine->getRepository(Articles::class)->find($id);
  
        $entityManager =$doctrine->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
  
        $response = new Response();
        $response->send();

        return $this->redirectToRoute('articles_index');
      }

    

}

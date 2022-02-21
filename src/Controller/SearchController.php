<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\CitySearch;
use App\Entity\PrixSearch;
use App\Form\CitySearchType;
use App\Form\PrixSearchType;
use App\Repository\ArticlesRepository;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\CategoriesSearch;
use App\Form\CategoriesSearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/searchcat/", name="article_par_cat")
     * Method({"GET", "POST"})
     */
    public function articlesParCategorie(Request $request) {
        $CategoriesSearch = new CategoriesSearch();
        $form = $this->createForm(CategoriesSearchType::class,$CategoriesSearch);
        $form->handleRequest($request);
  
        $articles= [];
  
        if($form->isSubmitted() && $form->isValid()) {
          $Categories = $CategoriesSearch->getCategories();
         
          if ($Categories!="") 
          {
            
            $articles= $Categories->getArticles();
           // ou bien 
           //$articles= $this->getDoctrine()->getRepository(Article::class)->findBy(['Categories' => $Categories] );
          }
          else   
            $articles= $this->getDoctrine()->getRepository(Articles::class)->findAll();
          }
        
        return $this->render('search/articleParCategorie.html.twig',['form' => $form->createView(),'articles' => $articles]);
    }

    /**
     * @Route("/searchcity/", name="article_par_city")
     * Method({"GET", "POST"})
     */
    public function articlesParCity(Request $request) {
        $CitysSearch = new CitySearch();
        $form = $this->createForm(CitySearchType::class,$CitysSearch);
        $form->handleRequest($request);
  
        $articles= [];
  
        if($form->isSubmitted() && $form->isValid()) {
          $City = $CitysSearch->getCity();
         
          if ($City!="") 
          {
            
            $articles= $City->getArticles();
           
          }
          else   
            $articles= $this->getDoctrine()->getRepository(Articles::class)->findAll();
          }
        
        return $this->render('search/articleParCity.html.twig',['form' => $form->createView(),'articles' => $articles]);
    }

    /**
     * @Route("/searchprix/", name="article_par_prix")
     * Method({"GET"})
     */
    public function articlesParPrix(Request $request,ArticlesRepository $articleRepo)
    {
     
      $prixSearch = new PrixSearch();
      $form = $this->createForm(PrixSearchType::class,$prixSearch);
      $form->handleRequest($request);

      $articles= [];

      if($form->isSubmitted() && $form->isValid()) {
        $minPrix = $prixSearch->getMinPrix(); 
        $maxPrix = $prixSearch->getMaxPrix();
          
       $articles= $articleRepo->findByPrixRange($minPrix,$maxPrix);
        //$articles= $this->getDoctrine()->getRepository(Articles::class)->findByPrixRange($minPrix,$maxPrix);
    }

    return  $this->render('search/articlesParPrix.html.twig',[ 'form' =>$form->createView(), 'articles' => $articles]);  
  }
}

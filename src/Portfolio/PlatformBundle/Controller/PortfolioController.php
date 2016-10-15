<?php

namespace Portfolio\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use PortfolioController\PlatformBundle\Entity\portfolio;

class PortfolioController extends Controller
{
    public function affichageAction()
    {
    	// Récupération des données de la table Accueil
        $repositoryCategorie = $this->getDoctrine()->getRepository('PortfolioPlatformBundle:categorie');
        $categories = $repositoryCategorie->findAll();

        $categorie1 = $categories[0];
        $repositoryPortfolio = $this->getDoctrine()->getRepository('PortfolioPlatformBundle:portfolio');
        // var_dump($categorie1->getId());
		$images = $repositoryPortfolio->findBy(array("categorie" => $categorie1->getId()));

        // Envoi des données à la vue
        return $this->render('PortfolioPlatformBundle:Portfolio:index.html.twig', 
        	array("categories" =>$categories,"images"=>$images));
    }
    public function modification(){

    }

}


<?php

namespace Portfolio\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Portfolio\PlatformBundle\Entity\portfolio;
use Portfolio\PlatformBundle\Entity\categorie;
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
    public function modificationAction()
    {
    	$repositoryCategorie = $this->getDoctrine()->getRepository('PortfolioPlatformBundle:categorie');
        $categories = $repositoryCategorie->findAll();
    	return $this->render('PortfolioPlatformBundle:Portfolio:modificationCategorie.html.twig', array("categories" => $categories));
    }
    public function modificationCategorieAction(Request $request)
    {
    	$nombreCategorie = $request->request->get('nombreCategorie');
 		$newCategories = $request->request->get('newCategorie');
 		$i;
 		$repository = $this->getDoctrine()->getRepository('PortfolioPlatformBundle:categorie');
 		for($i = 0; $i < $nombreCategorie ; $i++){
 			$categories[$i] = $request->request->get('categorie' . ($i+1));
 			$categoriesId[$i] = $request->request->get('idCategorie' . ($i+1));
 			$categoriesId[$i] = intval($categoriesId[$i]);
       		$categorie = $repository->findBy(array("id" => $categoriesId[$i]));
       		$categorie[0]->setValeur($categories[$i]);
	 		$em = $this->getDoctrine()->getManager();
        	$em->persist($categorie[0]);
 		}
 		if($newCategories != null){
	        $newcategorie = new categorie();
	        $newcategorie->setValeur($newCategories);
	 		$em = $this->getDoctrine()->getManager();
	        $em->persist($newcategorie);
        }
        try{
            $em->flush();
        }catch(Exception $e){
            return new Response($e);
        }
        return $this->affichageAction();
    }

}


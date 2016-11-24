<?php

namespace Portfolio\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

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
        	array("categories" =>$categories,"images"=>$images, "categorieSingle" => $categorie1->getId()));
    }

    public function affichageFrontAction(Request $request)
    {   
        // Récupération des données de la table Accueil
        $repositoryCategorie = $this->getDoctrine()->getRepository('PortfolioPlatformBundle:categorie');
        $categories = $repositoryCategorie->findAll();

        $idCategorie = $request->request->get('idCategoriePortFolio');
        if($idCategorie == null){
            $categorie1 = $categories[0];
        }else{
            $categorie1 = $this->affichageParId($idCategorie);
        }
        $repositoryPortfolio = $this->getDoctrine()->getRepository('PortfolioPlatformBundle:portfolio');
        // var_dump($categorie1->getId());
        $images = $repositoryPortfolio->findBy(array("categorie" => $categorie1->getId()));

        // Envoi des données à la vue
        return $this->render('PortfolioPlatformBundle:Portfolio:portfolioFront.html.twig', 
            array("categories" =>$categories,"images"=>$images, "categorieSingle" => $categorie1->getId()));
    }

    public function affichageParId($id){
        $repository = $this->getDoctrine()->getRepository('PortfolioPlatformBundle:categorie');
        $tarif = $repository->find($id);

        // Envoi des données à la vue
        return $tarif;
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
        $em = $this->getDoctrine()->getManager();
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
    public function affichageParCategorieAction(Request $request){

        $repositoryCategorie = $this->getDoctrine()->getRepository('PortfolioPlatformBundle:categorie');
        $categories = $repositoryCategorie->findAll();

        $repositoryCategorie = $this->getDoctrine()->getRepository('PortfolioPlatformBundle:portfolio');
       
        $categorie = $request->request->get("lienCategoriePortfolio");
        $categorie = $this->convertionLabelEnId($categorie);
        $qb = $repositoryCategorie->createQueryBuilder('p')
        ->join('p.categorie', 'cate')
        ->where('cate.valeur = :valeurCate' )
        ->andWhere('p.categorie = cate.id')
        ->setParameter('valeurCate', $categorie->getValeur())
        ->addSelect('cate')
        ;

        $resultatRequete = $qb
        ->getQuery()
        ->getResult()
        ;

        // // Envoi des données à la vue
        return $this->render('PortfolioPlatformBundle:Portfolio:index.html.twig', 
            array("categories" =>$categories,"images"=>$resultatRequete, "categorieSingle" => $categorie->getId()));
    }
    public function ajoutImageParCategorieAction(Request $request)
    {
        // récupération de la requête 
        $semiPath = "/FLP/Symfony/web/bundles/Portfolio/upload";

        $images = $request->files->all();
        $idCategorie = $images["idCategorie"]->getClientOriginalName();
        $idCategorieInt = intval($idCategorie);
        $repositoryCategorie = $this->getDoctrine()->getRepository('PortfolioPlatformBundle:categorie');
        $categorie = $repositoryCategorie->findOneBy(array("id" => $idCategorieInt));

        $path = $this->get('kernel')->getRootDir() . '/../web/bundles/Portfolio/upload';
        $i = 0;
        foreach($images as $image){
            $filename = $image->getClientOriginalName();
            if($filename != $idCategorie){
                // Ajout de la photo dans le serveur
                $filename = $image->getClientOriginalName();
                $image->move($path,$filename);
                $urlImage = $semiPath . "/" . $filename;

                // Ajout de l'url dans le tableau ( pour le retourner )

                $listeUrlImage["urlImage"][$i] = $urlImage;
                $i++;

                // Ajout du lien en BDD
                $portfolio = new portfolio();
                $portfolio->setUrlImage($urlImage);
                // var_dump($categorie);
                $portfolio->setCategorie($categorie);
                $em = $this->getDoctrine()->getManager();
                $em->persist($portfolio);
                
                try{
                    $em->flush();
                }catch(Exception $e){
                    return new Response($e);
                }
                $listeUrlImage["id"][$i] = $portfolio->getId();
            }
        }
       
        $response = new JsonResponse();
        $response->setData($listeUrlImage);
        return $response;
    }

    public function suppressionImageParCategorieAction(Request $request){
        $params = array();
        $content = $request->getContent();
        $params = json_decode($content ,true); 
        $em = $this->getDoctrine()->getManager();
        $portfolio = $em->getRepository('PortfolioPlatformBundle:portfolio')->find($params["idPhoto"]);
        // var_dump($portfolio);
        $em->remove($portfolio);
        $em->flush();
        return  new Response("ok");
    }
    public function suppressionCategorieAction(Request $request){
        $params = array();
        $content = $request->getContent();
        $params = json_decode($content ,true); 
        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository('PortfolioPlatformBundle:categorie')->find($params["idPortfolioCategorie"]);
        $articles = $em->getRepository('PortfolioPlatformBundle:portfolio')->findBy(array("categorie" => $evenement ));

        foreach($articles as $article){
            $em->remove($article);
            $em->flush();
        }

        $em->remove($evenement);
        $em->flush();
        return  new Response("ok");
    }

    public function convertionLabelEnId($label){
        $repositoryCategorie = $this->getDoctrine()->getRepository('PortfolioPlatformBundle:categorie');
        $categorie = $repositoryCategorie->findOneByValeur($label);  
        return $categorie;
    }

}


<?php

namespace Accueil\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\JsonResponse;


use Accueil\PlatformBundle\Entity\Accueil;

class AccueilController extends Controller
{
   /**
    * retourne la liste des liens de photos récupéré depuis la bdd
    * @return liste des liens de photo
    *
    */
    public function affichage_listePhotoAction()
    {
        // récupération des données de la table Accueil
        $repository = $this->getDoctrine()->getRepository('AccueilPlatformBundle:Accueil');
        $photos = $repository->findAll();
        // redirection vers la liste des photo
    	return  $this->render('AccueilPlatformBundle:Accueil:index.html.twig', array("photos" => $photos));

    }

    public function frontAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AccueilPlatformBundle:Accueil');
        $photos = $repository->findAll();
        return  $this->render('AccueilPlatformBundle:Accueil:frontAccueil.html.twig', array("photos" => $photos));
    }
    
   /**
    *
    * Ajout de plusieurs photos en même temps en BDD 
    * retourne les liens des photos ajoutées.
    * @param liste de photo en type file
    * @return Array listeUrlImage
    * 
    */
    public function ajout_photoAction(Request $request)
    {
        // récupération de la requête 
        // $semiPath = "/Symfony/web/bundles/Accueil/upload";

        $semiPath = "/FLP/Symfony/web/bundles/Accueil/upload";

    	$images = $request->files->all();
    	$path = $this->get('kernel')->getRootDir() . '/../web/bundles/Accueil/upload';
        $i = 0;
        foreach($images as $image){

            // Ajout de la photo dans le serveur
        	$filename = $image->getClientOriginalName();
        	$image->move($path,$filename);
            $urlImage = $semiPath . "/" . $filename;

            // Ajout de l'url dans le tableau ( pour le retourner )

            $listeUrlImage["urlImage"][$i] = $urlImage;
            $i++;

            // Ajout du lien en BDD
            $accueil = new Accueil();
            $accueil->setUrlImage($urlImage);
            $em = $this->getDoctrine()->getManager();
            $em->persist($accueil);
            
            try{
                $em->flush();
            }catch(Exception $e){
                return new Response($e);
            }
            $listeUrlImage["id"][$i] = $accueil->getId();
        }
       
        $response = new JsonResponse();
        $response->setData($listeUrlImage);
  		return $response;
    }

   /**
    *
    * Suppression d'une photo 
    * @param la photo à supprimer
    * @return l'id à supprimer
    * 
    */
    public function suppression_photoAction(Request $request)
    {

        $params = array();
        $content = $request->getContent();
        $params = json_decode($content ,true); 
        $em = $this->getDoctrine()->getManager();
        $accueil = $em->getRepository('AccueilPlatformBundle:Accueil')->find($params["idPhoto"]);
        var_dump("accueil : ", $accueil);
        $em->remove($accueil);
        $em->flush();
        return  new Response("ok");
    }
}

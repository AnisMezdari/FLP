<?php

namespace Presentation\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Presentation\PlatformBundle\Entity\presentation;

class PresentationController extends Controller
{
	/*
	 * Retourne le titre , le lien de la photo et le texte de la BDD
	 */
    public function affichageAction()
    {

        // Récupération des données de la table Accueil
        $repository = $this->getDoctrine()->getRepository('PresentationPlatformBundle:presentation');
        $presentation = $repository->find(1);

        // Envoi des données à la vue
        return $this->render('PresentationPlatformBundle:Presentation:index.html.twig',array("presentation" =>$presentation ));
    }
    
    public function frontAction(Request $request){
        $repository = $this->getDoctrine()->getRepository('PresentationPlatformBundle:presentation');
        $presentation = $repository->find(1);
        return $this->render('PresentationPlatformBundle:Presentation:frontPresentation.html.twig',array("presentation" =>$presentation ));
    }

    /*
     * Modifie la bdd en fonction des valeurs envoyées par le formulaire
     */
    public function modificationAction(Request $request)
    {
    	// Récupération des données du formulaire
    	$titre = $request->request->get('titre');
    	$texte = $request->request->get('texte');
    	$image = $request->request->get('image');
        $images = $request->files->all();

    	// Changement en base de données
    	$repository = $this->getDoctrine()->getRepository('PresentationPlatformBundle:presentation');
        $presentation = $repository->find(1);
        $presentation->setTitre($titre);
        $presentation->setTexte($texte);
        if($images["image"] != NULL ){
            $image = $this->uploadImage($image, $request);
            $presentation->setUrlImage($image);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($presentation);
        
        try{
            $em->flush();
        }catch(Exception $e){
            return new Response($e);
        }
        return $this->affichageAction();
    }

    public function uploadImage($image , $request)
    {
    	// initialisation du chemin pour l'image
    	// $semiPath = "/Symfony/web/bundles/Accueil/upload";
        $semiPath = "/FLP/Symfony/web/bundles/Accueil/upload";
    	// initialisation du chemin pour le serveur afin d'upload l'image dedans
    	$path = $this->get('kernel')->getRootDir() . '/../web/bundles/Accueil/upload';
    	// récupération de l'image 
    	$images = $request->files->all();
    	// var_dump($images)
    	$image = $images["image"];

    	 // Ajout de la photo dans le serveur
    	$filename = $image->getClientOriginalName();
    	$image->move($path,$filename);
        $urlImage = $semiPath . "/" . $filename;

        return $urlImage;
    }
}

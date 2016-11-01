<?php

namespace Contact\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use ContactController\PlatformBundle\Entity\contact;

class ContactController extends Controller
{
    public function affichageAction()
    {
         // Récupération des données de la table Accueil
        $repository = $this->getDoctrine()->getRepository('ContactPlatformBundle:contact');
        $contact = $repository->find(1);

        // Envoi des données à la vue
        return $this->render('ContactPlatformBundle:Contact:index.html.twig',array("contact" =>$contact));
    }

    public function modificationAction(Request $request)
    {

        // Récupération des données du formulaire
    	$titre = $request->request->get('titre');
    	$adresse = $request->request->get('adresse');
    	$email = $request->request->get('email');
    	$tel = $request->request->get('tel');
        $telFixe  = $request->request->get('telFixe');
    	$image = $request->request->get('image');
    	$images = $request->files->all();

    	// Changement en base de données
    	$repository = $this->getDoctrine()->getRepository('ContactPlatformBundle:contact');
        $contact = $repository->find(1);
        $contact->setTitre($titre);
        $contact->setAdresse($adresse);
        $contact->setEmail($email);
        $contact->setTelephone($tel);
        $contact->setTelephoneFixe($telFixe);

        if($images["image"] != NULL ){
			$image = $this->uploadImage($image, $request);
    		$contact->setUrlImage($image);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($contact);
        
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
    	$semiPath = "/FLP/Symfony/web/bundles/Accueil/upload";
    	// initialisation du chemin pour le serveur afin d'upload l'image dedans
    	$path = $this->get('kernel')->getRootDir() . '/../web/bundles/Accueil/upload';
    	// récupération de l'image 
    	$images = $request->files->all();
    	$image = $images["image"];

    	 // Ajout de la photo dans le serveur
    	$filename = $image->getClientOriginalName();
    	$image->move($path,$filename);
        $urlImage = $semiPath . "/" . $filename;

        return $urlImage;
    }
}

<?php

namespace Tarif\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Tarif\PlatformBundle\Entity\tarif;

class TarifController extends Controller
{
    public function affichageAction()
    {
    	$repository = $this->getDoctrine()->getRepository('TarifPlatformBundle:tarif');
        $tarifs = $repository->findAll();

        // Envoi des données à la vue
        return $this->render('TarifPlatformBundle:Tarif:tarif.html.twig',array("tarifs" =>$tarifs));
    }
        public function affichageFrontAction()
    {
        $repository = $this->getDoctrine()->getRepository('TarifPlatformBundle:tarif');
        $tarifs = $repository->findAll();

        // Envoi des données à la vue
        return $this->render('TarifPlatformBundle:Tarif:tarifFront.html.twig',array("tarifs" =>$tarifs));
    }

    public function modificationAction(Request $request)
    {
    	$idTarif = $request->request->get('idTarif');
    	$text = $request->request->get('textTarif');
    	$prix = $request->request->get('prixTarif');
    	$image = $request->request->get('inputFileTarif');
    	$images = $request->files->all();

    	$repository = $this->getDoctrine()->getRepository('TarifPlatformBundle:tarif');
        $tarif = $repository->findOneBy(array("id" => $idTarif));
        $tarif->setTexte($text);
        $tarif->setPrix($prix);

    	if($images["inputFileTarif"] != NULL ){
			$image = $this->uploadImage($image, $request);
    		$tarif->setUrlImage($image);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($tarif);
        
        try{
            $em->flush();
        }catch(Exception $e){
            return new Response($e);
        }
        return $this->affichageAction();

    }
    public function ajoutAction(){
    	$newTarif = new Tarif();
    	$newTarif->setUrlImage("/FLP/Symfony/web/bundles/Tarif/upload/logoFLS.gif");
    	$em = $this->getDoctrine()->getManager();
        $em->persist($newTarif);
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
    	$semiPath = "/FLP/Symfony/web/bundles/Tarif/upload";
    	// initialisation du chemin pour le serveur afin d'upload l'image dedans
    	$path = $this->get('kernel')->getRootDir() . '/../web/bundles/Tarif/upload';
    	// récupération de l'image 
    	$images = $request->files->all();
    	$image = $images["inputFileTarif"];

    	 // Ajout de la photo dans le serveur
    	$filename = $image->getClientOriginalName();
    	$image->move($path,$filename);
        $urlImage = $semiPath . "/" . $filename;

        return $urlImage;
    }
}

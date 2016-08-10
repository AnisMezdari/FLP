<?php

namespace Accueil\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AccueilController extends Controller
{
    public function affichage_listePhotoAction()
    {
        
        // ajout en bdd de toutes les photos

        // redirection vers la liste des photo
    	return  $this->render('AccueilPlatformBundle:Accueil:index.html.twig', array("image" =>"test"));

    }

    public function ajout_photoAction(Request $request)
    {
    	// var_dump($request->files->all());

    	$images = $request->files->all();
    	// $path = $request->getBasePath();
    	$path = $this->get('kernel')->getRootDir() . '/../web/bundles/Accueil/upload';
    	// var_dump($path);
        foreach($images as $image){
        	$filename = $image->getClientOriginalName();
        	$image->move($path,$filename);
        }
        // tableau des lien des images
        // envoi de tableau en parametre
  		return  new Response("ok");


    }
}

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

    /*
     * Modifie la bdd en fonction des valeurs envoyées par le formulaire
     */
    public function modificationAction(Request $request)
    {
    	// Récupération des données du formulaire
    	$titre = $request->request->get('titre');
    	$texte = $request->request->get('texte');
    	// $image = $request->...

    	$repository = $this->getDoctrine()->getRepository('PresentationPlatformBundle:presentation');
        $presentation = $repository->find(1);
        $presentation->setTitre($titre);
        $presentation->setTexte($texte);
		// $presentation->setUrlImage($titre);
        $em = $this->getDoctrine()->getManager();
        $em->persist($presentation);
        
        try{
            $em->flush();
        }catch(Exception $e){
            return new Response($e);
        }
        return $this->affichageAction();
    }
}

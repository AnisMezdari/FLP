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
      $session = $this->get('session');
      $motDePasse = $session->get('idUser');
      if($motDePasse == "d9cd8d70637282cd0685c962166387e2" && $session->get('email') == "imen"){
      	$repository = $this->getDoctrine()->getRepository('TarifPlatformBundle:tarif');
        $tarifs = $repository->findAll();

        // Envoi des données à la vue
        return $this->render('TarifPlatformBundle:Tarif:tarif.html.twig',array("tarifs" =>$tarifs));
      }else{
        $repository = $this->getDoctrine()->getRepository('AccueilPlatformBundle:Accueil');
        $photos = $repository->findAll();
        return  $this->render('AccueilPlatformBundle:Accueil:frontAccueil.html.twig', array("photos" => $photos));
      }
    }

    public function affichageFrontAction(Request $request)
    {

        $tarif = $this->getPremierTarif();
        $tarif = $tarif[0];
        $repository = $this->getDoctrine()->getRepository('TarifPlatformBundle:tarif');
        $tarifs = $repository->findAll();
        $estPremier = true;
        $estDernier = false;
        // Envoi des données à la vue
        return $this->render('TarifPlatformBundle:Tarif:tarifFront.html.twig',array("tarifs" =>$tarifs , "tarif" => $tarif, "estPremier" => $estPremier , "estDernier" => $estDernier));
    }

    public function affichageFrontSuivantAction(Request $request)
    {
        $idTarif = $request->request->get('tarifId');
        $suivant = $request->request->get('tarifSuivant');
        $tarifs = $this->getPremierTarif();
        $tarifRetourne = null;
        $estDernier = false;
        $estPremier = false;
        for($i = 0; $i < sizeof($tarifs) ; $i++){
            if($tarifs[$i]->getId() == $idTarif){
                if($suivant == 1){
                    $tarifRetourne = $tarifs[$i+1];
                    if($i == sizeof($tarifs)-2){
                        $estDernier = true;
                    }
                }else{
                    $tarifRetourne = $tarifs[$i-1];
                    if($i == 0){
                        $estPremier = true;
                    }
                }
            }
        }
        $repository = $this->getDoctrine()->getRepository('TarifPlatformBundle:tarif');
        $tarifs = $repository->findAll();

        // Envoi des données à la vue
        return $this->render('TarifPlatformBundle:Tarif:tarifFront.html.twig',array("tarifs" =>$tarifs , "tarif" => $tarifRetourne, "estPremier" => $estPremier , "estDernier" => $estDernier));
    }

    public function affichageParId($id){
        $repository = $this->getDoctrine()->getRepository('TarifPlatformBundle:tarif');
        $tarif = $repository->find($id);


        return $tarif;
    }
    public function getPremierTarif(){
        $repository = $this->getDoctrine()->getRepository('TarifPlatformBundle:tarif');
        $tarif = $repository->findBy(array(), array('id' => 'asc'));

        return $tarif;
    }

    public function modificationAction(Request $request)
    {

      $session = $this->get('session');
      $motDePasse = $session->get('idUser');
      if($motDePasse == "d9cd8d70637282cd0685c962166387e2" && $session->get('email') == "imen"){
      	$idTarif = $request->request->get('idTarif');
      	$text = $request->request->get('textTarif');
      	$prix = $request->request->get('prixTarif');
      	$image = $request->request->get('inputFileTarif');
        $fullTexte = $request->request->get('fullTexte');
      	$images = $request->files->all();


      	$repository = $this->getDoctrine()->getRepository('TarifPlatformBundle:tarif');
        $tarif = $repository->findOneBy(array("id" => $idTarif));
        $tarif->setTexte($text);
        $tarif->setPrix($prix);
        $tarif->setFullTexte($fullTexte);

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
      }else{
        $repository = $this->getDoctrine()->getRepository('AccueilPlatformBundle:Accueil');
        $photos = $repository->findAll();
        return  $this->render('AccueilPlatformBundle:Accueil:frontAccueil.html.twig', array("photos" => $photos));

      }

    }
    public function ajoutAction(){
      $session = $this->get('session');
      $motDePasse = $session->get('idUser');
      if($motDePasse == "d9cd8d70637282cd0685c962166387e2" && $session->get('email') == "imen"){
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
      }else{
        $repository = $this->getDoctrine()->getRepository('AccueilPlatformBundle:Accueil');
        $photos = $repository->findAll();
        return  $this->render('AccueilPlatformBundle:Accueil:frontAccueil.html.twig', array("photos" => $photos));

      }
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

    public function suppressionAction(Request $request){
      $session = $this->get('session');
      $motDePasse = $session->get('idUser');
      if($motDePasse == "d9cd8d70637282cd0685c962166387e2" && $session->get('email') == "imen"){
        $params = array();
        $content = $request->getContent();
        $params = json_decode($content ,true);
        $em = $this->getDoctrine()->getManager();
        $accueil = $em->getRepository('TarifPlatformBundle:tarif')->find($params["idTarif"]);
        $em->remove($accueil);
        $em->flush();
        return  new Response("ok");
      }else{
        $repository = $this->getDoctrine()->getRepository('AccueilPlatformBundle:Accueil');
        $photos = $repository->findAll();
        return  $this->render('AccueilPlatformBundle:Accueil:frontAccueil.html.twig', array("photos" => $photos));

      }

    }
}

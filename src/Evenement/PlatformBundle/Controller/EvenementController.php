<?php

namespace Evenement\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Evenement\PlatformBundle\Entity\evenement;
use Evenement\PlatformBundle\Entity\imageEvenement;



class EvenementController extends Controller
{
    public function affichageAction()
    {
    	 $repository = $this->getDoctrine()->getRepository('EvenementPlatformBundle:evenement');
		 $modele = $repository->findAll();
		// Envoi des données à la vue
		return $this->render('EvenementPlatformBundle:Evenement:evenement.html.twig',array("evenements" =>$modele ));
    }

    public function affichageFrontAction()
    {
        $repository = $this->getDoctrine()->getRepository('EvenementPlatformBundle:imageEvenement');
        $qb = $repository->createQueryBuilder('image')
        ->join('image.evenement' , 'event')
        ->addSelect('event')
        ;

        $evenemntImage = $qb
        ->getQuery()
        ->getResult()
        ;

        // var_dump($evenemntImage);
        // die();

        // Envoi des données à la vue
        return $this->render('EvenementPlatformBundle:Evenement:evenementFront.html.twig',array("evenements" =>$evenemntImage ));
    }

    public function affichageFrontEnSavoirPlusAction(Request $request)
    {
         $idEvenement = $request->request->get('idEvenement');

        $repository = $this->getDoctrine()->getRepository('EvenementPlatformBundle:evenement');
        $qb = $repository->createQueryBuilder('event')
        ->select('event,img')
        ->leftjoin('EvenementPlatformBundle:imageEvenement', 'img', 'WITH' , 'img.evenement = event.id')
        ->where('event.id = :idEvent' )
        // ->addSelect('EvenementPlatformBundle:imageEvenement')
        ->setParameter('idEvent', $idEvenement)
        ;

        $evenemntImage = $qb
        ->getQuery()
        ->getResult()
        ;

        return $this->render('EvenementPlatformBundle:Evenement:evenementEnSavoirPlus.html.twig',array("evenements" => $evenemntImage ));
    }

    public function ajoutAction(){
        $evenement = new Evenement();
        $evenement->setTitre(" Nouvelle événément");

        $em = $this->getDoctrine()->getManager();
        $em->persist($evenement);
        try{
            $em->flush();
        }catch(Exception $e){
            return new Response($e);
        }
        return $this->affichageAction();
    }

    public function suppressionAction(Request $request){
        $params = array();
        $content = $request->getContent();
        $params = json_decode($content ,true); 
        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository('EvenementPlatformBundle:evenement')->find($params["idEvenement"]);
        $articles = $em->getRepository('EvenementPlatformBundle:imageEvenement')->findBy(array("evenement" => $evenement ));

        foreach($articles as $article){
            $em->remove($article);
            $em->flush();
        }

        $em->remove($evenement);
        $em->flush();
        return  new Response("ok");
    }

    public function affichageArticleAction(Request $request)
    {
    	$idEvenement = $request->request->get('idEvenement');

	 //   	$repository = $this->getDoctrine()->getRepository('EvenementPlatformBundle:imageEvenement');
		// $evenemntImage = $repository->findBy(array("evenement" => $idEvenement));


        $repository = $this->getDoctrine()->getRepository('EvenementPlatformBundle:evenement');
        $qb = $repository->createQueryBuilder('event')
        ->select('event,img')
        ->leftjoin('EvenementPlatformBundle:imageEvenement', 'img', 'WITH' , 'img.evenement = event.id')
        ->where('event.id = :idEvent' )
        // ->addSelect('EvenementPlatformBundle:imageEvenement')
        ->setParameter('idEvent', $idEvenement)
        ;

        // $em = $this->getDoctrine()->getManager();
        // $query = $em->createQuery('SELECT image FROM EvenementPlatformBundle:imageEvenement image  RIGHT JOIN image.evenement_id WHERE event.id = image.evenement AND event.id = :id ');
        // // $query = $em->createQuery('SELECT event FROM EvenementPlatformBundle:evenement event ');
        // $query->setParameter('id', $idEvenement);
        // $evenemntImage = $query->getResult();

        $evenemntImage = $qb
        ->getQuery()
        ->getResult()
        ;

        // var_dump($evenemntImage);
        // die();

        return $this->render('EvenementPlatformBundle:Evenement:evenementArticle.html.twig', array("evenements" => $evenemntImage));
    }

    public function modificationArticleAction(Request $request){

        $idEvenement = $request->request->get('idEvenement');

        // Récupération des données du formulaire
        $titre = $request->request->get('nameTitreEvenement');
        $texte = $request->request->get('nameTexteEvenement');

        // Changement en base de données
        $repository = $this->getDoctrine()->getRepository('EvenementPlatformBundle:evenement');
        $evenement = $repository->find($idEvenement);
        $evenement->setTitre($titre);
        $evenement->setTexte($texte);

        $em = $this->getDoctrine()->getManager();
        $em->persist($evenement);
        
        try{
            $em->flush();
        }catch(Exception $e){
            return new Response($e);
        }
        return $this->affichageAction();
    }

    public function ajoutArticleAction(Request $request){

        // récupération de la requête 
        $semiPath = "/Symfony/web/bundles/Evenement/upload";

        $images = $request->files->all();
        $idEvenement = $images["idEvenement"]->getClientOriginalName();
        $idEvenementInt = intval($idEvenement);

        $repositoryEvenement = $this->getDoctrine()->getRepository('EvenementPlatformBundle:evenement');
        $evenement = $repositoryEvenement->findOneBy(array("id" => $idEvenementInt));

        $path = $this->get('kernel')->getRootDir() . '/../web/bundles/Evenement/upload';
        $i = 0;
        foreach($images as $image){
            $filename = $image->getClientOriginalName();
            if($filename != $idEvenement){
                // Ajout de la photo dans le serveur
                $filename = $image->getClientOriginalName();
                $image->move($path,$filename);
                $urlImage = $semiPath . "/" . $filename;

                // Ajout de l'url dans le tableau ( pour le retourner )
                $listeUrlImage["urlImage"][$i] = $urlImage;
                $i++;

                // Ajout du lien en BDD
                $imageEvenement = new imageEvenement();
                $imageEvenement->setUrlImage($urlImage);
                
                $imageEvenement->setEvenement($evenement);
                $em = $this->getDoctrine()->getManager();
                // var_dump($imageEvenement);
                $em->persist($imageEvenement);
                
                try{
                    $em->flush();
                }catch(Exception $e){
                    return new Response($e);
                }
                $listeUrlImage["id"][$i] = $imageEvenement->getId();
            }
        }
        $response = new JsonResponse();
        $response->setData($listeUrlImage);
        return $response;
    }

    public function suppressionArticleAction(Request $request){

        $params = array();
        $content = $request->getContent();
        $params = json_decode($content ,true); 
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('EvenementPlatformBundle:imageEvenement')->find($params["idPhoto"]);
        $em->remove($article);
        $em->flush();
        return  new Response("ok");
    }
}

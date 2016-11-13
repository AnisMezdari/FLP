<?php

namespace Evenement\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        return $this->render('EvenementPlatformBundle:Evenement:evenementArticle.html.twig', array("evenements" => $evenemntImage));
    }
}

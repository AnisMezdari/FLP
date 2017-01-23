<?php

namespace User\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use User\PlatformBundle\Entity\user;
use User\PlatformBundle\Entity\imageUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
  public function affichageAction()
  {
       // Récupération des données de la table Accueil
      $repository = $this->getDoctrine()->getRepository('UserPlatformBundle:User');
      $users = $repository->findAll();

      // Envoi des données à la vue
      return $this->render('UserPlatformBundle:User:user.html.twig',array("users" =>$users));
  }
  
  public function ajoutAction(Request $request){
    
        $name = $request->request->get('name');
        $firstname = $request->request->get('firstname');
        $email = $request->request->get('email');
    
        $user = new User();
        $user->setName($name);
        $user->setFirstname($firstname);
        $user->setEmail($email);
        $em = $this->getDoctrine()->getManager();
        $em->persist($evenement);
        try{
            $em->flush();
        }catch(Exception $e){
            return new Response($e);
        }
        return $this->affichageAction();
    }

  public function voirAction(Request $request)
  {
    	$idUser = $request->request->get('idUser');

      $repository = $this->getDoctrine()->getRepository('UserPlatformBundle:user');
      $qb = $repository->createQueryBuilder('utilisateur')
      ->select('utilisateur,img')
      ->leftjoin('UserPlatformBundle:imageUser', 'img', 'WITH' , 'img.user = utilisateur.id')
      ->where('utilisateur.id = :idUser' )
      // ->addSelect('EvenementPlatformBundle:imageEvenement')
      ->setParameter('idUser', $idUser)
      ;

      $user = $qb
      ->getQuery()
      ->getResult()
      ;
      var_dump($user);
        return $this->render('UserPlatformBundle:User:oneUser.html.twig', array("user" => $user));
  }

}

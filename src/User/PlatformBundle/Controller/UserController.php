<?php

namespace User\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

}

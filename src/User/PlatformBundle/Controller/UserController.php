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

}

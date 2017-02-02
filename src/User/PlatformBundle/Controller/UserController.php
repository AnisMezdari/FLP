<?php

namespace User\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use User\PlatformBundle\Entity\user;
use User\PlatformBundle\Entity\imageUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\src\Accueil\PlatformBundle\Controller\AccueilController;
use ZipArchive;

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

  public function affichageAjoutAction(){

      return $this->render('UserPlatformBundle:User:addUser.html.twig');
  }

  public function addUserAction(Request $request){

    $portable = $request->request->get('namePortable');
    $nom = $request->request->get('nameNom');
    $prenom = $request->request->get('namePrenom');
    $email = $request->request->get('nameEmail');

    $user = new User();
    $user->setPortable($portable);
    $user->setNom($nom);
    $user->setPrenom($prenom);
    $user->setEmail($email);
    $motdepasse = $user->generationMDP();
    // $motdepasse = "21071995anis";
    // var_dump($motdepasse);
    $this->envoiMail($user->getEmail(),$motdepasse);
    $user->setMotDePasse($user->cryptage($motdepasse));


    $em = $this->getDoctrine()->getManager();
    $em->persist($user);
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
        return $this->render('UserPlatformBundle:User:oneUser.html.twig', array("users" => $user));
  }

  public function modificationAction(Request $request){

      $idUser = $request->request->get('idUser');

      // Récupération des données du formulaire
      $portable = $request->request->get('namePortable');
      $nom = $request->request->get('nameNom');
      $prenom = $request->request->get('namePrenom');
      $email = $request->request->get('nameEmail');

      // Changement en base de données
      $repository = $this->getDoctrine()->getRepository('UserPlatformBundle:user');
      $user = $repository->find($idUser);
      $user->setPortable($portable);
      $user->setNom($nom);
      $user->setPrenom($prenom);
      $user->setEmail($email);

      $em = $this->getDoctrine()->getManager();
      $em->persist($user);

      try{
          $em->flush();
      }catch(Exception $e){
          return new Response($e);
      }
      return $this->affichageAction();
  }

  public function ajoutAction(Request $request){

      // récupération de la requête

      //affichageFrontEnSavoirPlusAction
      $semiPath = "/FLP/Symfony/web/bundles/User/upload";

      // internet
      // $semiPath = "/Symfony/web/bundles/User/upload";

      $images = $request->files->all();
      $idUser = $images["idOneUser"]->getClientOriginalName();
      $idUserInt = intval($idUser);

      $repositoryUser = $this->getDoctrine()->getRepository('UserPlatformBundle:user');
      $user = $repositoryUser->findOneBy(array("id" => $idUserInt));


      $path = $this->get('kernel')->getRootDir() . '/../web/bundles/User/upload';
      $semiPath = $semiPath . "/" . $idUser;

      if (!file_exists($path."/".$idUser)) {
        mkdir($path."/".$idUser, 0777);
      }
      $path = $path . "/" . $idUser;

      $i = 0;
      foreach($images as $image){
          $filename = $image->getClientOriginalName();
          if($filename != $idUser){
              // Ajout de la photo dans le serveur
              $filename = $image->getClientOriginalName();
              $image->move($path,$filename);
              $urlImage = $semiPath . "/" . $filename;

              // Ajout de l'url dans le tableau ( pour le retourner )
              $listeUrlImage["urlImage"][$i] = $urlImage;
              $i++;

              // Ajout du lien en BDD
              $imageUser = new imageUser();
              $imageUser->setUrlImage($urlImage);

              $imageUser->setUser($user);
              $em = $this->getDoctrine()->getManager();
              // var_dump($imageEvenement);
              $em->persist($imageUser);

              try{
                  $em->flush();
              }catch(Exception $e){
                  return new Response($e);
              }
              $listeUrlImage["id"][$i] = $imageUser->getId();
          }
      }
      $this->zipDossierPhoto($idUser);
      $response = new JsonResponse();
      $response->setData($listeUrlImage);
      return $response;
  }

  public function suppressionAction(Request $request){

      $params = array();
      $content = $request->getContent();
      $params = json_decode($content ,true);
      $em = $this->getDoctrine()->getManager();
      $imageUser = $em->getRepository('UserPlatformBundle:imageUser')->find($params["idImageUser"]);
      $em->remove($imageUser);
      $em->flush();
      return  new Response("ok");
  }


  public function suppressionUserAction(Request $request){
      $params = array();
      $content = $request->getContent();
      $params = json_decode($content ,true);
      $em = $this->getDoctrine()->getManager();
      $user = $em->getRepository('UserPlatformBundle:user')->find($params["idOneUser"]);
      $imagesUser = $em->getRepository('UserPlatformBundle:imageUser')->findBy(array("user" => $user ));

      foreach($imagesUser as $imageUser){
          $em->remove($imageUser);
          $em->flush();
      }

      $em->remove($user);
      $em->flush();
      return  new Response("ok");
  }

  public function envoiMail($email , $motdepasse){

      $contenuMail = "Fleur de lys photgraphy vous a créé un compte"
      . "afin que vous ayez accès à vos photos. Voici les informations de votre compte : <br>"
      . "Nom de compte : " . $email . "<br>"
      . "Mot de passe : " . $motdepasse;

      $message = \Swift_Message::newInstance()
      ->setSubject('Votre compte Fleur de lys')
      ->setFrom('contact@fleurdelysphotography.fr')
      ->setTo($email)
      ->setBody(
          $contenuMail,
          'text/html'
      );

      $this->get('mailer')->send($message);
  }

  public function afficheConnexionFrontAction(){
      return $this->render('UserPlatformBundle:User:connexion.html.twig');
  }

  public function connexionFrontAction(Request $request){

    $email = $request->request->get('email');
    $motDePasse = $request->request->get('motdepasse');

    $user = new User();
    $motDePasseCrypte = $user->cryptage($motDePasse);

    $em = $this->getDoctrine()->getManager();
    $user = $em->getRepository('UserPlatformBundle:user')->findOneBy(array("motdepasse" => $motDePasseCrypte, "email" => $email));

    if($user == NULL){
      return $this->render('UserPlatformBundle:User:connexion.html.twig', array("error" => "Votre email ou votre mot de passe est incorrect"));
    }else{
       $_SESSION['idUser'] = $motDePasseCrypte;
       $_SESSION['email'] = $email;
      return $this->affichephotoFrontAction();
    }
    //  var_dump("bonjour");
    // return $this->render('AccueilPlatformBundle:Accueil:frontAccueil.html.twig');

  }

  public function affichephotoFrontAction(){

    if($_SESSION['idUser'] != NULL){
     $motDePasse = $_SESSION['idUser'];

     $em = $this->getDoctrine()->getManager();
     $user = $em->getRepository('UserPlatformBundle:user')->findOneBy(array("motdepasse" => $motDePasse, "email" =>  $_SESSION['email']));

     $repository = $this->getDoctrine()->getRepository('UserPlatformBundle:user');
     $qb = $repository->createQueryBuilder('user')
     ->select('user,img')
     ->leftjoin('UserPlatformBundle:imageUser', 'img', 'WITH' , 'img.user = user.id')
     ->where('user.id = :idUser' )
     // ->addSelect('EvenementPlatformBundle:imageEvenement')
     ->setParameter('idUser', $user->getId())
     ;

     $userImage = $qb
     ->getQuery()
     ->getResult()
     ;

     return $this->render('UserPlatformBundle:User:photos.html.twig', array("images" => $userImage));

   }else{
     $AccueilController = new AccueilController();
     $AccueilController->affichage_listePhotoAction();
   }

  }

  public function zipDossierPhoto($idUser){
    $pathImage = $this->get('kernel')->getRootDir() . '/../web/bundles/User/upload/'. $idUser;
    $zipPath = $this->get('kernel')->getRootDir() . '/../web/bundles/User/upload/'. $idUser . '.zip';
    $zipArchive = new ZipArchive();

    if (!$zipArchive->open($zipPath, ZIPARCHIVE::CREATE))
      die("Failed to create archive\n");

    $zipArchive->addFile($pathImage . "/mariage-5.png", "mariage-top");
    $zipArchive->close();

  }

}

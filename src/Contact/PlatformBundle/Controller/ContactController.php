<?php

namespace Contact\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use ContactController\PlatformBundle\Entity\contact;

class ContactController extends Controller
{
    public function affichageAction()
    {
      $session = $this->get('session');
      $motDePasse = $session->get('idUser');
      if($motDePasse == "d9cd8d70637282cd0685c962166387e2" && $session->get('email') == "imen"){
         // Récupération des données de la table Accueil
        $repository = $this->getDoctrine()->getRepository('ContactPlatformBundle:contact');
        $contact = $repository->find(1);

        // Envoi des données à la vue
        return $this->render('ContactPlatformBundle:Contact:index.html.twig',array("contact" =>$contact));
      }else{
        $repository = $this->getDoctrine()->getRepository('AccueilPlatformBundle:Accueil');
        $photos = $repository->findAll();
        return  $this->render('AccueilPlatformBundle:Accueil:frontAccueil.html.twig', array("photos" => $photos));
      }

    }

    public function affichageFrontAction()
    {
         // Récupération des données de la table Accueil
        $repository = $this->getDoctrine()->getRepository('ContactPlatformBundle:contact');
        $contact = $repository->find(1);

        // Envoi des données à la vue
        return $this->render('ContactPlatformBundle:Contact:contactFront.html.twig',array("contact" =>$contact));
    }

    public function modificationAction(Request $request)
    {

      $session = $this->get('session');
      $motDePasse = $session->get('idUser');
      if($motDePasse == "d9cd8d70637282cd0685c962166387e2" && $session->get('email') == "imen"){
          // Récupération des données du formulaire
      	$titre = $request->request->get('titre');
      	$adresse = $request->request->get('adresse');
      	$email = $request->request->get('email');
      	$tel = $request->request->get('tel');
        $telFixe  = $request->request->get('telFixe');
      	$image = $request->request->get('image');
      	$images = $request->files->all();

      	// Changement en base de données
      	$repository = $this->getDoctrine()->getRepository('ContactPlatformBundle:contact');
          $contact = $repository->find(1);
          $contact->setTitre($titre);
          $contact->setAdresse($adresse);
          $contact->setEmail($email);
          $contact->setTelephone($tel);
          $contact->setTelephoneFixe($telFixe);

          if($images["image"] != NULL ){
  			$image = $this->uploadImage($image, $request);
      		$contact->setUrlImage($image);
          }
          $em = $this->getDoctrine()->getManager();
          $em->persist($contact);

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
    	$semiPath = "/FLP/Symfony/web/bundles/Accueil/upload";
    	// initialisation du chemin pour le serveur afin d'upload l'image dedans
    	$path = $this->get('kernel')->getRootDir() . '/../web/bundles/Accueil/upload';
    	// récupération de l'image
    	$images = $request->files->all();
    	$image = $images["image"];

    	 // Ajout de la photo dans le serveur
    	$filename = $image->getClientOriginalName();
    	$image->move($path,$filename);
        $urlImage = $semiPath . "/" . $filename;

        return $urlImage;
    }

    public function envoiMailAction(Request $request){

        $nom = $request->request->get('nom');
        $email = $request->request->get('email');
        $telephone = $request->request->get('telephone');
        $ville = $request->request->get('ville');
        $message= $request->request->get('message');

        $contenuMail = "".$nom."<br>";
        $contenuMail = $contenuMail . "".$email."<br>";
        $contenuMail = $contenuMail . "".$telephone."<br>";
        $contenuMail = $contenuMail . "".$ville."<br>";
        $contenuMail = $contenuMail . "<p>".$message."</p>";

        // var_dump($contenuMail);
        // die;
        $message = \Swift_Message::newInstance()
        ->setSubject('Contact Fleur de Lys Photography '.$nom)
        ->setFrom('contact@fleurdelysphotography.fr')
        ->setTo('mezdari77580@gmail.com')
        ->setBody(
            $contenuMail,
            'text/html'
        )
        /*
         * If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'Emails/registration.txt.twig',
                array('name' => $name)
            ),
            'text/plain'
        )
        */
    ;
        $this->get('mailer')->send($message);

        return $this->affichageFrontAction();
    }


}

<?php

namespace Evenement\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EvenemntController extends Controller
{
    public function affichageAction()
    {
        return $this->render('EvenementPlatformBundle:Evenement:evenement.html.twig');
    }
}

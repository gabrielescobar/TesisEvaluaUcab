<?php

namespace evaluaUcab\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class RubricaController extends Controller {
    
    function indexAction(){
        
        $em = $this->getDoctrine()->getManager();
        
        $profesor = $this->get('security.context')->getToken()->getUser();
        $paginator = $this->get('knp_paginator');
        $rubricas = $em->getRepository('ProfesorBundle:Rubrica')->findRubricasByProf($profesor->getId());
        $pagination = $paginator->paginate($rubricas, $this->getRequest()->query->get('page', 1), 5);

        
    return $this->render('ProfesorBundle:Default:rubricas.html.twig', compact('pagination'));
   
    }
    
    
}


?>
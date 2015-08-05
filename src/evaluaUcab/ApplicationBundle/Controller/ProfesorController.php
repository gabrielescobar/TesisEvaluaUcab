<?php

namespace evaluaUcab\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ProfesorController extends Controller {

    public function verAction()
    {
           
        $em = $this->getDoctrine()->getManager();
        $profesores = $em->getRepository('ProfesorBundle:Profesor')->findAll();
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($profesores,$this->getRequest()->query->get('page', 1),15);
        
        return $this->render('ApplicationBundle:Default:verProfesores.html.twig',compact('pagination'));
    }
    

}

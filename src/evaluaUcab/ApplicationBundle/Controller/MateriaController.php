<?php

namespace evaluaUcab\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use evaluaUcab\ProfesorBundle\Entity\Materia;

class MateriaController extends Controller
{
    public function verAction()
    {
           
        $em = $this->getDoctrine()->getManager();
        $materias = $em->getRepository('ProfesorBundle:Materia')->findAllOrderByName();
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($materias,$this->getRequest()->query->get('page', 1),5);
        
        return $this->render('ApplicationBundle:Default:verMaterias.html.twig',compact('pagination'));
    }
    
       public function crearAction()
    {
        $materia = new Materia();
        $formulario = $this->createFormBuilder($materia)
                ->add('nombre','text')
                ->add('descripcion','textarea')
                ->getForm();
        
        
        $request = $this->getRequest();

        $formulario->handleRequest($request);

        if ($formulario->isValid()) {
           
            $em = $this->getDoctrine()->getManager();
       
            // Guardamos el objeto en base de datos
            $em->persist($materia);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Materia creada !');

            return $this->redirect($this->generateUrl('materia'));
        }
       // var_dump($formulario->getErrors());
 
        return $this->render('ApplicationBundle:Default:crearMateria.html.twig', array('formulario' => $formulario->createView())
        );
    }
    
    public function renderMateriasAction(){
        $em = $this->getDoctrine()->getManager();
        $materias = $em->getRepository('ProfesorBundle:Materia')->findAllOrderByName();
        return $this->render('ProfesorBundle:Default:renderMaterias.html.twig', array('materias' => $materias));
    }
    

}


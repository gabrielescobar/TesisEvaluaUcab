<?php

namespace evaluaUcab\EstudianteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use evaluaUcab\EstudianteBundle\Entity\Solicitud;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SolicitudController extends Controller {

    public function verAction() {

        $em = $this->getDoctrine()->getManager();
        $estudiante = $this->get('security.context')->getToken()->getUser();
        $solicitudesPendientes = $em->getRepository('EstudianteBundle:Solicitud')->findBy(array('estudiante'=>$estudiante->getId(),
            'status'=>'Pendiente'));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($solicitudesPendientes,$this->getRequest()->query->get('page', 1),5);
        
        return $this->render('EstudianteBundle:Default:solicitud.html.twig',compact('pagination'));
    }

    public function solicitarAccesoAction() {

        return $this->render('EstudianteBundle:Default:crearSolicitud.html.twig');
    }

    public function registrarAccesoAction() {

        $idMateria = (int) $this->getRequest()->request->get('form_materia');
        $idSección = (int) $this->getRequest()->request->get('form_seccion');
        var_dump($idMateria);
        var_dump($idSección);
        $em = $this->getDoctrine()->getManager();
        $estudiante = $this->get('security.context')->getToken()->getUser();
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($idSección);
         $aux = $em->getRepository('EstudianteBundle:Solicitud')->findBy(array
                ('seccion' => $seccion, 'estudiante' =>$estudiante));
         
         if (empty($aux)){
             $solicitud = new Solicitud();
             $solicitud->setEstudiante($estudiante);
     
             $solicitud->setSeccion($seccion);
             $solicitud->setStatus("Pendiente");
             $em->persist($solicitud);
             $em->flush();

             $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Solicitud de acceso creada !');
             return $this->redirect($this->generateUrl('solicitud'));
         } else{
             if ($aux[0]->getStatus() == 'Pendiente'){
                 $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Ya se encuentra registrada esta solicitud de acceso !');
             } else{
                 $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Ya se encuentra registrado en la sección !');
             }
         }
   
        return $this->render('EstudianteBundle:Default:crearSolicitud.html.twig');
    }

}

<?php

namespace evaluaUcab\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use evaluaUcab\ProfesorBundle\Entity\Seccion;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SeccionController extends Controller {

    public function verAction($idProfesor) {

        $em = $this->getDoctrine()->getManager();
        $secciones = $em->getRepository('ProfesorBundle:Seccion')->findSeccionesByProfesor($idProfesor);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($secciones, $this->getRequest()->query->get('page', 1), 5);
        return $this->render('ProfesorBundle:Default:verSecciones.html.twig', compact('pagination'));
    }

    public function crearAction($id) {


        //return $this->render('ProfesorBundle:Default:index.html.twig');

        $seccion = new Seccion();
        $formulario = $this->createFormBuilder($seccion)
                ->add('materia', 'entity', array(
                    'class' => 'ProfesorBundle:Materia',
                    'property' => 'nombre',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                                ->orderBy('m.nombre', 'ASC');
                    }
                ))
                ->add('codigo', 'text')
                ->getForm();


        $request = $this->getRequest();

        $formulario->handleRequest($request);

        if ($formulario->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $profesor = $em->getRepository('ProfesorBundle:Profesor')->find($id);

            // Guardamos el objeto en base de datos
            $seccion->setProfesor($profesor);
            $em->persist($seccion);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Sección creada !');

            return $this->redirect($this->generateUrl('seccion', array('idProfesor' => $id)));
        }
        // var_dump($formulario->getErrors());

        return $this->render('ProfesorBundle:Default:crearSeccion.html.twig', array('formulario' => $formulario->createView())
        );
    }

    public function obtenerSeccionAction(Request $request) {
        //$var = 
        $idMateria = $request->request->get('materia_id');
        $em = $this->getDoctrine()->getManager();
        $secciones = $em->getRepository('ProfesorBundle:Seccion')->findSeccionesByMateria($idMateria);

        return new Response(json_encode($secciones));
    }

    public function renderSeccionByProfAction() {
        $profesor = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $secciones = $em->getRepository('ProfesorBundle:Seccion')->findSeccionesOrderByMateria($profesor->getId());
        return $this->render('ProfesorBundle:Default:renderSecciones.html.twig', array('secciones' => $secciones));
    }
    
    public function eliminarAction($id){
        $prof= $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($id);
        
        $em->remove($seccion);
        $em->flush();
        
        return $this->redirect($this->generateUrl('seccion',array('id'=>$prof->getId())));
        
    }
    
     public function alumnosEnSeccionAction($id){
        $em = $this->getDoctrine()->getManager();
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($id);
     
        //Se obtienen los estudiantes de la seccion
        $estudiantes = $em->getRepository('ProfesorBundle:Seccion')->findEstudiantesEnSeccion($seccion->getId());
          
      
        return $this->render('ProfesorBundle:Default:alumnosAceptados.html.twig',array('estudiantes'=>$estudiantes,
            'idSeccion' =>$seccion->getId(),'portafolio' => $seccion->getPortafolio()));
        
        
    }
    
    public function borrarEstudianteSeccionAction($idEstudianteSeccion){
         
        $em = $this->getDoctrine()->getManager();
        $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')->find($idEstudianteSeccion);
        
        $em->remove($estudianteSeccion);
        $em->flush();
        
        return new Response();
        
    }
    
     public function borrarSeccionAction($idSeccion){
         
        $em = $this->getDoctrine()->getManager();
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($idSeccion);
        
        $em->remove($seccion);
        $em->flush();
        
        return new Response();
        
    }

}


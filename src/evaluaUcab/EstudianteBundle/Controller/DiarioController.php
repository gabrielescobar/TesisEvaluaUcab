<?php

namespace evaluaUcab\EstudianteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use evaluaUcab\EstudianteBundle\Entity\Diario;
use evaluaUcab\EstudianteBundle\Entity\Estudiante;
use evaluaUcab\EstudianteBundle\Form\DiarioType;
use Symfony\Component\HttpFoundation\Response;

class DiarioController extends Controller {

   
   
    
    public function editDiarioAction($idPortafolio,$idDiario) {
        $em = $this->getDoctrine()->getManager();
        $diario = $em->getRepository('EstudianteBundle:Diario')->find($idDiario);
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        if (!$diario) {
            throw $this->createNotFoundException('No se ha encontrado el diario solicitado');
        }

        $editForm = $this->createForm(new DiarioType(), $diario);


        return $this->render('EstudianteBundle:Default:modalEditDiario.html.twig', array(
                    'diario' => $diario,
                    'edit_form' => $editForm->createView(),'seccion'=>$portafolio->getSeccion()
        ));
    }

    public function updateDiarioAction($idPortafolio,$idDiario) {
      
        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $diario = $em->getRepository('EstudianteBundle:Diario')->find($idDiario);
       

        if (!$diario) {
            throw $this->createNotFoundException('No se ha encontrado el diario solicitado');
        }

        $editForm = $this->createForm(new DiarioType(), $diario);

        $request = $this->getRequest();

        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $diario->setFechaModificacion(new \DateTime('now'));
            $em->persist($diario);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Nota Modificada!');

            return $this->redirect($this->generateUrl('editar_diario', 
                    array('idPortafolio'=> $idPortafolio,'idDiario'=>$idDiario)));
        }
          //var_dump($editForm->getErrors());
        return $this->render('EstudianteBundle:Default:modalEditDiario.html.twig', array(
                    'diario' => $diario,
                    'edit_form' => $editForm->createView(),'seccion'=>$portafolio->getSeccion()
        ));
    }
    
    public function eliminarDiarioAction($idDiario){
       
        $em = $this->getDoctrine()->getManager();
        $diario = $em->getRepository('EstudianteBundle:Diario')->find($idDiario);
        
        $em->remove($diario);
        $em->flush();
       
        return new Response();
    }
    
    
    public function crearDiarioAction($idPortafolio){
        $diario = new Diario();   
        $formulario = $this->createForm(new DiarioType(), $diario);     
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
           
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
       
        
        $formulario->handleRequest($request);
        
        $aux = $em->getRepository('EstudianteBundle:Diario')->
                findBy(array('titulo'=>$diario->getTitulo(),'portafolio'=>$portafolio ));
        
        if(empty($aux)){

        if ($formulario->isValid()) {
            $estudiante = $this->get('security.context')->getToken()->getUser();
            
            $diario->setEstudiante($estudiante);
            $diario->setPortafolio($portafolio);
            $diario->setFechaModificacion(new \DateTime('now'));
     
            // Guardamos el objeto en base de datos
            $em->persist($diario);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Nota guardada!');

            return $this->redirect($this->generateUrl('diarios',array('idPortafolio'=>$idPortafolio)));
        }
      } else{
           $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡Ya existe una nota con el mismo nombre!');
           
       
      }
       // var_dump($formulario->getErrors());
        return $this->render('EstudianteBundle:Default:crearDiario.html.twig',
                array('formulario' => $formulario->createView(),
                    'seccion'=>$portafolio->getSeccion())
        );
        
    }
    
    public function diariosAction($idPortafolio) {
      
         /*Se obtiene el estudiante en sesion y se retornan sus diarios*/
        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $estudiante = $this->get('security.context')->getToken()->getUser();
        $diarios = $em->getRepository('EstudianteBundle:Diario')->findBy(array('estudiante'=>$estudiante,
            'portafolio'=>$portafolio));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($diarios,$this->getRequest()->query->get('page', 1),10);
        
        return $this->render('EstudianteBundle:Default:diarios.html.twig',
          array('seccion'=>$portafolio->getSeccion(),'diarios'=>$pagination));
         
    }

    
    public function verDiarioAction($id){
        //dependiendo del id del diario se mostrará 
         return $this->render('EstudianteBundle:Default:new.html.twig', 
         array('id' => $id));
    }

}
?>

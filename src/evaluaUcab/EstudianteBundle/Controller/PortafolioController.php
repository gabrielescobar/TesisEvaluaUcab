<?php

namespace evaluaUcab\EstudianteBundle\Controller;
use evaluaUcab\ProfesorBundle\Entity\HistoricoExamen;

use evaluaUcab\ProfesorBundle\Entity\EstudianteSeccion;
use evaluaUcab\ProfesorBundle\Entity\Portafolio;
use evaluaUcab\ProfesorBundle\Entity\PortafolioRepository;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PortafolioController extends Controller {

    public function listaPortafoliosAction() {

        /* Se obtiene el estudiante en sesion */
        $estudiante = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')->findBy(array('estudiante' => $estudiante));

        return $this->render('EstudianteBundle:Default:listaPortafolios.html.twig', array('estudianteSeccion' => $estudianteSeccion));
    }

    public function verPortafolioAction($id) {

        $em = $this->getDoctrine()->getManager();
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($id);

        return $this->render('EstudianteBundle:Default:portafolio.html.twig', array('seccion' => $seccion));
    }

    public function obtenerExamenesAction($idSeccion) {
        $em = $this->getDoctrine()->getManager();
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($idSeccion);
        $examenes = $em->getRepository('ProfesorBundle:ExamenPortafolio')->findBy(array('portafolio' => $seccion->getPortafolio()));

        return $this->render('EstudianteBundle:Default:examenesPorPortafolio.html.twig', array('examenPortafolio' => $examenes, 'seccion' => $seccion));
    }

    public function presentoExamen($idExamenPortafolio) {
        $em = $this->getDoctrine()->getManager();
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $seccion = $examenPortafolio->getPortafolio()->getSeccion();
        $estudiante = $this->get('security.context')->getToken()->getUser();
        $historicoExamen = new HistoricoExamen();

         if ($examenPortafolio->getTipo() == 0){ //si es 0 es individual el examen, si es 1 es grupal el examen
                 //Se setea el estudiante Seccion
                 $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')
                         ->findBy(array('estudiante'=>$estudiante,'seccion'=>$seccion)) ;
                 $historicoExamen= $em->getRepository('ProfesorBundle:HistoricoExamen')
                         ->findBy(array('examenPortafolio'=>$examenPortafolio,'estudianteSeccion'=>$estudianteSeccion));
                 
              if(!empty($historicoExamen)){
                return 1; 
             }
            
           }else{
                 $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')
                         ->findBy(array('estudiante'=>$estudiante,'seccion'=>$seccion)) ;
                 $grupo = $estudianteGrupo[0]->getGrupo();
                 
                 $registros = $em->getRepository('ProfesorBundle:Portafolio')
                         ->findPresentoExamenByGrupo($grupo->getId(), $examenPortafolio->getId());
                 
                  if(!empty($registros)){
                    return 1; 
                  }
             
            }
             
           
          
        return 0; // 
    }

    //Funcion que valida la fecha de presentacion de una actividad respecto a la fecha de cierre de la misma 
    public function validarFechaPresentacion($idExamenPortafolio) {
        $em = $this->getDoctrine()->getManager();
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $fechaCierre = $examenPortafolio->getFechaCierre();
        $horaCierre = $examenPortafolio->getHoraCierre();
        
        $aux = new \DateTime($fechaCierre->format('Y-m-d').' '.$horaCierre->format('H:i'));
        $fechaPresentacion = new \DateTime; //fecha actual

        $interval = $fechaPresentacion->diff($aux);

        if ($interval->format('%R') == "+") {
            //var_dump("La evaluacion se puede realizar");
            return 1;
        } else {
            return 0;
        }


        return 0;
    }

    public function verExamenEstudianteAction($idExamenPortafolio) {
        //Enviar lo necesario para generar todos los campos de opciones 
        //Se debe validar q no hayan respuestasEstudiante para este examen, si hay no se debe cargar
        //Se debe validar la fecha de cierre del examen en el momento q se va a presentar 
        $validacionFecha = $this->validarFechaPresentacion($idExamenPortafolio);
         //var_dump($validacionFecha);
        $em = $this->getDoctrine()->getManager();
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $seccion = $examenPortafolio->getPortafolio()->getSeccion();
        
        if ($validacionFecha == 1) {
            
            $historicoExamen = new HistoricoExamen();
            $estudiante = $this->get('security.context')->getToken()->getUser();
             if ($examenPortafolio->getTipo() == 0){ //si es 0 es individual el examen, si es 1 es grupal el examen
                 //Se setea el estudiante Seccion
                
                 $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')
                         ->findOneBy(array('estudiante'=>$estudiante,'seccion'=>$seccion)) ;
                 
                 $historicoExamen->setEstudianteSeccion($estudianteSeccion[0]);
            
             }if ($examenPortafolio->getTipo() == 1){
                
                 $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')
                         ->findBy(array('estudiante'=>$estudiante,'seccion'=>$seccion)) ;
                 $historicoExamen->setEstudianteGrupo($estudianteGrupo[0]);
                 
             }
             
             $historicoExamen->setExamenPortafolio($examenPortafolio);
             
             
             $historicoExamen->setNota(0);
             //$em->persist($historicoExamen);
            //SUSTITUIR
            $presentoExamen = 0;//$this->presentoExamen($idExamenPortafolio);
            if ($presentoExamen == 0) {
            /************************************/
                $form = $this->createFormBuilder();

                foreach ($examenPortafolio->getExamen()->getPreguntas() as $pregunta) {
                  
                    if ($pregunta->getTipo() == 'escrito') {
                      
                        //si la pregunta es de tipo escrito se debe mostrar el editor de texto
                        $form->add($pregunta->getId(), 'ckeditor', array(
                            'config' => array(
                                'resize_dir' => 'disable',
                                'fontSize_defaultLabel' => 'Tamaño',
                                'extraPlugins' => 'ckeditor_wiris'),
                            'label' => $pregunta->getTitulo(),
                            'attr' => array('class' => 'espacio'))); //aqui iria la pregunta :)
                    } else if ($pregunta->getTipo() == 'simple') {
                        //add radio button
                       
                        $array= array();
                        if($pregunta->getRespuestas() != null)
                           
                        foreach ($pregunta->getRespuestas() as $respuesta) {
                             
                               if($respuesta != null)
                                
                              $array[''.$respuesta->getId().''] = $respuesta->getContenido();
                        }
                           
                     
                        $form->add($pregunta->getId(), 'choice', array(
                            'expanded' => true,
                            'multiple' => false,
                            'choices' => array($array),
                            'label' => $pregunta->getTitulo()));
                    } else if ($pregunta->getTipo() == 'multiple') {
                        //add checkbox
                       
                        $array2= array();
                        foreach ($pregunta->getRespuestas() as $respuesta) {
                           
                            $array2[''.$respuesta->getId().''] = $respuesta->getContenido();
                        }

                        $form->add($pregunta->getId(), 'choice', array(
                            'expanded' => true,
                            'multiple' => true,
                            'choices' => array($array2),
                            'label' => $pregunta->getTitulo()));
                    }
                }
                $em->persist($historicoExamen);
                $em->flush();
                
                $duracion= $examenPortafolio->getDuracion()->format('H:i');
                $time = $this->timeToClock($duracion) ;
                return $this->render('EstudianteBundle:Default:formPresentarExamen.html.twig', array(
                            'form' => $form->getForm()->createView(), 'seccion' => $examenPortafolio->getPortafolio()->getSeccion(),
                            'idExamenPortafolio' => $idExamenPortafolio,'duracion'=>$time));
           /*****************************************************/
                } else {
                //  obtener_examenes id seccion

                $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡El examen ya fue presentado!');
                return $this->redirect($this->generateUrl('obtener_examenes', array('idSeccion' => $examenPortafolio->getPortafolio()->getSeccion()->getId())));
            }
        }else{
                 $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡El examen ya finalizó!');
                return $this->redirect($this->generateUrl('obtener_examenes', array('idSeccion' => $examenPortafolio->getPortafolio()->getSeccion()->getId())));
           
        }
    }
    /*
     * Funcion para calcular los minutos de la duracion del examen. el plugin FlipClock la necesita en segundos
     */
    function timeToClock($duracion){
        $tiempo = explode (":",$duracion);
        return $total = $tiempo[0]*3600+$tiempo[1]*60;
        
        
    }
    
    function asignacionesPorPortafolioAction($idPortafolio){
        
        $estudianteSeccion='';
        $estudianteGrupo='';
        
        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $estudiante = $this->get('security.context')->getToken()->getUser();
      
        
             $egrupo = $em->getRepository('ProfesorBundle:Grupo')->findEstudianteGrupoByEstudianteYSeccion
                       ($portafolio->getSeccion()->getId(),$estudiante->getId());
             $estudianteGrupo = $egrupo[0]->getId();
            
         
         //Es individual
            $eSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')->findBy(array('estudiante'=>$estudiante,
            'seccion'=>$portafolio->getSeccion()));
            $estudianteSeccion = $eSeccion[0]->getId();
            
      
        
          
        $mapasMentales = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio'=>$portafolio,
            'tipo'=>'mapamental', 'estatus'=>1));
        $presentaciones = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio'=>$portafolio,
            'tipo'=>'presentacion','estatus'=>1));
        
        $autoevaluaciones= $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio'=>$portafolio,
            'tipo'=>'autoevaluacion','estatus'=>1));
        $coevaluaciones = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio'=>$portafolio,
            'tipo'=>'coevaluacion','estatus'=>1));
        
        return $this->render('EstudianteBundle:Default:viewAsignacionesEstudiante.html.twig', 
                array('seccion'=>$portafolio->getSeccion(),
                    'mapasMentales'=>$mapasMentales,'presentaciones'=>$presentaciones,
                    'autoevaluaciones'=>$autoevaluaciones,'coevaluaciones'=>$coevaluaciones,
                    'estudianteSeccion'=>$estudianteSeccion,'estudianteGrupo'=>$estudianteGrupo,
                    'grupo'=>$egrupo[0]->getGrupo()
                ));
        
    }
    
    function misCalificacionesAction($idPortafolio){
        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $estudiante = $this->get('security.context')->getToken()->getUser();
      
        $estudianteSeccion=$em->getRepository('ProfesorBundle:EstudianteSeccion')
                ->findBy(array('estudiante'=>$estudiante,'seccion'=>$portafolio->getSeccion()));
       
       $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')
                ->findBy(array('estudiante'=>$estudiante,'seccion'=>$portafolio->getSeccion()));
      
       //Mapas individuales
       $mapasIndividuales = $em->getRepository('ProfesorBundle:Portafolio')->
            findAsignacionesPresentadasByEstudiante($portafolio->getId(), $estudianteSeccion[0]->getId(),'mapamental') ;

       //
       //Presentaciones
       //Coevaluaciones
       //Autoevaluaciones
       
       
       
       //Examenes
       $examenesIndividuales =$em->getRepository('ProfesorBundle:Portafolio')->
       findNotasExamenesToEstudiante($idPortafolio,$portafolio->getSeccion()->getId(),$estudianteSeccion[0]->getId());
        
        
       $examenesGrupales =$em->getRepository('ProfesorBundle:Portafolio')->
       findNotasExamenesToGrupo($idPortafolio,$portafolio->getSeccion()->getId(),$estudianteGrupo[0]->getGrupo()->getId());
       
        return $this->render('EstudianteBundle:Default:misCalificaciones.html.twig', 
                array('seccion'=>$portafolio->getSeccion(),'examenesIndividuales'=>$examenesIndividuales,
                    'examenesGrupales'=>$examenesGrupales,
                    'mapasIndividuales' => $mapasIndividuales
                ));
        
    }

}

?>

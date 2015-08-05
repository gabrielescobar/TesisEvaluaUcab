<?php

namespace evaluaUcab\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use evaluaUcab\ProfesorBundle\Entity\Asignacion;
use evaluaUcab\ProfesorBundle\Entity\Evento;

class AsignacionController extends Controller {

    function viewAsignacionesAction($idPortafolio) {

        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);

       $mapasMentales = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio' => $portafolio,
            'tipo' => 'mapamental'));

        $presentaciones = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio' => $portafolio,
            'tipo' => 'presentacion'));

        $autoevaluaciones = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio' => $portafolio,
            'tipo' => 'autoevaluacion'));
        $coevaluaciones = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio' => $portafolio,
            'tipo' => 'coevaluacion'));   
        
        return $this->render('ProfesorBundle:Default:viewAsignaciones.html.twig', 
                array('portafolio' => $portafolio,
                    'mapasMentales' => $mapasMentales,
                    'presentaciones'=> $presentaciones,
                    'autoevaluaciones' => $autoevaluaciones,
                    'coevaluaciones' =>$coevaluaciones));
    }

    function registrarAsignacionAction($idPortafolio) {

        //Recibir datos 

        $titulo = $this->getRequest()->request->get('form_titulo');
        $tipo = $this->getRequest()->request->get('form_tipo');
        $descripcion = $this->getRequest()->request->get('form_descripcion');
        $fechaCierre = new \DateTime($this->getRequest()->request->get('form_fechaCierre'));
        $eleccion = $this->getRequest()->request->get('form_eleccion');
        $tipoHerramienta = $this->getRequest()->request->get('form_herramienta');
        $porcentaje = $this->getRequest()->request->get('form_porcentaje');
        $modoPresentacion = (int) $this->getRequest()->request->get('form_presentacion');
        $estatus = (int) $this->getRequest()->request->get('form_estatus');

        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('America/Caracas'));

        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);

        //se chequea q no exista una asignacion con el mismo nombre dentro del portafolio    
        $aux = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio' => $portafolio,
            'titulo' => $titulo, 'tipo' => $tipo));

        if (empty($aux)) {
            $asignacion = new Asignacion();
            $asignacion->setTitulo($titulo);
            $asignacion->setTipo($tipo);
            $asignacion->setFechaInicio($date);
            $asignacion->setDescripcion($descripcion);
            $asignacion->setFechaCierre($fechaCierre);
            $asignacion->setPortafolio($portafolio);
            $asignacion->setPorcentaje($porcentaje);
            $asignacion->setEstatus($estatus);
            //$asignacion->setModoPresentacion($presentacion);
            if ($modoPresentacion == 0) { //si la opcion es individual
                $asignacion->setModoPresentacion($modoPresentacion);
            } else { //si la opcion es grupal, se debe verificar q el portafolio es de tipo mixto
                if ($portafolio->getTipo() == 'mixto') {
                    $asignacion->setModoPresentacion($modoPresentacion);
                } else {
                    $this->getRequest()->getSession()->getFlashBag()->add('warning', 'El portafolio debe ser Mixto para las asignaciones grupales');
                    return $this->render('ProfesorBundle:Default:viewAsignaciones.html.twig', array('portafolio' => $portafolio));
                }
            }

            if ($tipoHerramienta == 'lista') {
                $lista = $em->getRepository('ProfesorBundle:Lista')->find($eleccion);
                $asignacion->setLista($lista);
            } else if ($tipoHerramienta == 'rubrica') {
                $rubrica = $em->getRepository('ProfesorBundle:Rubrica')->find($eleccion);
                $asignacion->setRubrica($rubrica);
            }

            $em->persist($asignacion);
            $em->flush();
            //se debe agregar de forma automatica un evento al calendario
            $evento = new Evento();
            $evento->setTitulo($asignacion->getTitulo());
            $evento->setDetalle($asignacion->getDescripcion());
            $evento->setTipo('event-important');
            $evento->setSeccion($portafolio->getSeccion());
            $evento->setAsignacion($asignacion);
            if($estatus == 1){
                $evento->setVisible(1);
            }else{
                $evento->setVisible(0);
            }

            $auxInicio = strtotime($date->format('Y-m-d H:i')) * 1000;
            $auxFin = strtotime($fechaCierre->format('Y-m-d H:i')) * 1000;
         
            $evento->setInicio($auxInicio);
            $evento->setFin($auxFin);

            $em->persist($evento);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Asignación creada');
            return $this->redirect($this->generateUrl('asignaciones', array('idPortafolio' => $portafolio->getId())));
              
            
            } else {
            $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Ya existe la asignación');
            return $this->redirect($this->generateUrl('asignaciones', array('idPortafolio' => $portafolio->getId())));
             }
    }

    public function borrarAsignacionAction($idAsignacion) {

        $em = $this->getDoctrine()->getManager();
        $asignacion = $em->getRepository('ProfesorBundle:Asignacion')->find($idAsignacion);

        $em->remove($asignacion);
        $em->flush();

        return new Response();
    }

    function formEditarAsignacionAction($idAsignacion) {

        $em = $this->getDoctrine()->getManager();
        $asignacion = $em->getRepository('ProfesorBundle:Asignacion')->find($idAsignacion);

        return $this->render('ProfesorBundle:Default:formEditarAsignacion.html.twig', array('portafolio' => $asignacion->getPortafolio(), 'asignacion' => $asignacion));
    }

    function editarAsignacionAction($idAsignacion) {

        $fechaCierre = new \DateTime($this->getRequest()->request->get('form_fechaCierre'));
        $estatus = $this->getRequest()->request->get('form_estatus');

        $herramienta = $this->getRequest()->request->get('form_herramienta');
        $eleccion = $this->getRequest()->request->get('form_eleccion');
        $error = 0;

        $em = $this->getDoctrine()->getManager();
        $asignacion = $em->getRepository('ProfesorBundle:Asignacion')->find($idAsignacion);

        $presentadas = $em->getRepository('ProfesorBundle:Calificacion')->findBy(array('asignacion' => $asignacion));

        if (empty($presentadas)){
            //si nadie presento es que puedo editar
            $evento = $asignacion->getEvento();
            $asignacion->setFechaCierre($fechaCierre);
           
            if($estatus != null){
                if(($asignacion->getEstatus() == 0) && ($estatus == 1)){
                  //Esta desactivada y voy a activarla, se setea la fecha de inicio del evento 
                  //asociado a la asignacion
                     $date = new \DateTime('today');
                     $var = $date->format('Y-m-d H:i');
                     $auxInicio = strtotime($var)*1000;
                     $evento->setInicio($auxInicio);
                                   
                }
              $asignacion->setEstatus($estatus);
            }
            //setea el evento asociado a la asignacion
            $auxFin = strtotime($this->getRequest()->request->get('form_fechaCierre')) * 1000;  
            $evento->setFin($auxFin);
            
            

            if (($herramienta != null) && ($eleccion != null)) {

                if ($herramienta == 'lista') {
                    $lista = $em->getRepository('ProfesorBundle:Lista')->find($eleccion);
                    $asignacion->setLista($lista);
                    $asignacion->setRubrica(null);
                } else if ($herramienta == 'rubrica') {
                    $rubrica = $em->getRepository('ProfesorBundle:Rubrica')->find($eleccion);
                    $asignacion->setRubrica($rubrica);
                    $asignacion->setLista(null);
                }
            }
        } else {
            $error = 1; //si ya alguien presento la asignacion
            $this->getRequest()->getSession()->getFlashBag()->add('warning', 'La asignación ya fue realizada por un estudiante');
            return $this->redirect($this->generateUrl('asignaciones', array('idPortafolio' => $asignacion->getPortafolio()->getId())));
        }
        
        $em->persist($asignacion);
        $em->persist($evento);
        $em->flush();
        $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Cambio realizado');
        return $this->redirect($this->generateUrl('asignaciones', array('idPortafolio' => $asignacion->getPortafolio()->getId())));
    }

    function verAsignacionAction($idAsignacion) {

        $em = $this->getDoctrine()->getManager();
        $asignacion = $em->getRepository('ProfesorBundle:Asignacion')->find($idAsignacion);

        return $this->render('ProfesorBundle:Default:verAsignacion.html.twig', array('portafolio' => $asignacion->getPortafolio(), 'asignacion' => $asignacion));
    }

    function verAsignacionesEstudianteAction($idPortafolio, $idEstudiante) {

        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $estudiante = $em->getRepository('EstudianteBundle:Estudiante')->find($idEstudiante);
        $eSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')->findBy(array('estudiante' => $estudiante,
            'seccion' => $portafolio->getSeccion()));

       $mapasMentales = $em->getRepository('ProfesorBundle:Asignacion')->findAsignacionMapasMentalesByEstudiante($estudiante->getId(), $eSeccion[0]->getId(), $portafolio->getId());
        
        $presentaciones = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio' => $portafolio,
            'tipo' => 'presentacion', 'modoPresentacion' => 0));

        $autoevaluaciones = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio' => $portafolio,
            'tipo' => 'autoevaluacion', 'modoPresentacion' => 0));
        $coevaluaciones = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio' => $portafolio,
            'tipo' => 'cooevaluacion', 'modoPresentacion' => 0));

        return $this->render('ProfesorBundle:Default:asignacionesPortafolioEstudiante.html.twig', array('portafolio' => $portafolio, 'estudiante' => $estudiante,
                    'mapasMentales' => $mapasMentales, 'presentaciones' => $presentaciones,
                    'autoevaluaciones' => $autoevaluaciones, 'coevaluaciones' => $coevaluaciones,
                    'estudianteSeccion' => $eSeccion[0]->getId()));
    }
    
       function verAsignacionesGrupoAction($idPortafolio, $idGrupo) {

        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $grupo = $em->getRepository('ProfesorBundle:Grupo')->find($idGrupo);
       
        $mapasMentales = $em->getRepository('ProfesorBundle:Asignacion')->findAsignacionMapasMentalesByGrupo($grupo->getId(), $portafolio->getId());

        $presentaciones = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio' => $portafolio,
            'tipo' => 'presentacion', 'modoPresentacion' => 1));

        $autoevaluaciones = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio' => $portafolio,
            'tipo' => 'autoevaluacion', 'modoPresentacion' => 1));
        $coevaluaciones = $em->getRepository('ProfesorBundle:Asignacion')->findBy(array('portafolio' => $portafolio,
            'tipo' => 'cooevaluacion', 'modoPresentacion' => 1));

        return $this->render('ProfesorBundle:Default:asignacionesPorPortafolioGrupo.html.twig', array('portafolio' => $portafolio, 'grupo' => $grupo,
                    'mapasMentales' => $mapasMentales, 'presentaciones' => $presentaciones,
                    'autoevaluaciones' => $autoevaluaciones, 'coevaluaciones' => $coevaluaciones,
                    ));
    }

}

?>

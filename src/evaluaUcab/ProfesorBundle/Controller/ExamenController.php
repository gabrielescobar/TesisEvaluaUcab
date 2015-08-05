<?php

namespace evaluaUcab\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use evaluaUcab\ProfesorBundle\Entity\Examen;
use evaluaUcab\ProfesorBundle\Entity\Pregunta;
use evaluaUcab\ProfesorBundle\Entity\Respuesta;
use evaluaUcab\EstudianteBundle\Entity\RespuestaEstudiante;
use Symfony\Component\HttpFoundation\Response;
use evaluaUcab\ProfesorBundle\Form\PreguntaType;
use evaluaUcab\ProfesorBundle\Form\RespuestaProfesorType;

class ExamenController extends Controller {

    public function indexAction() {

        $profesor = $this->get('security.context')->getToken()->getUser();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($profesor->getExamenes(), $this->getRequest()->query->get('page', 1), 5);

        return $this->render('ProfesorBundle:Default:examenes.html.twig', compact('pagination'));
    }

    public function crearExamenAction() {
        return $this->render('ProfesorBundle:Default:crearExamen.html.twig');
    }

    public function registrarExamenAction() {

        $titulo = $this->getRequest()->request->get('form_titulo');
        $descripcion = $this->getRequest()->request->get('form_descripcion');
       
        $profesor = $this->get('security.context')->getToken()->getUser();


        if (isset($titulo) && (isset($descripcion))) {
            $examen = new Examen();
            $em = $this->getDoctrine()->getManager();

            //Se verifica que no exista un examen con el mismo nombre
            $aux = $em->getRepository('ProfesorBundle:Examen')->findBy(array
                ('profesor' => $profesor,
                'titulo' => $titulo));

            if (empty($aux)) {

                $examen->setTitulo($titulo);
                $examen->setDescripcion($descripcion);
                $examen->setPuntaje(0);
                $examen->setProfesor($profesor);

                $em->persist($examen);
                $em->flush();
                //Se envia el parametro idExamen para crear las preguntas        
                return $this->redirect($this->generateUrl('preguntas', array('id' => $examen->getId())));
            } else {

                $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡Ya se encuentra registrado un examen con el mismo nombre !');
                return $this->render('ProfesorBundle:Default:crearExamen.html.twig');
            }
        }


        return $this->render('ProfesorBundle:Default:crearExamen.html.twig');
    }

    public function agregarPreguntaAction($id) { //idExamen
        
        $pregunta = new Pregunta();
        $editForm = $this->createForm(new PreguntaType(), $pregunta);
        
        return $this->render('ProfesorBundle:Default:preguntas.html.twig', array('id' => $id,
            'form' => $editForm->createView()));
    }

    public function registrarPreguntaAction($id) { //idExamen
        $contenido = $this->getRequest()->request->get('evaluaUcab_profesorbundle_preguntatype');
        $titulo = $contenido['titulo'];
        $tipo = $this->getRequest()->request->get('form_tipo');
        $puntaje = $this->getRequest()->request->get('form_puntaje');
        //aqui 

        $em = $this->getDoctrine()->getManager();
        $examen = $em->getRepository('ProfesorBundle:Examen')->find($id);

        if ((isset($tipo))) { //titulo no lo toma en cuenta xq el ckeditor lo trabaja por detrás 
            
            $pregunta = new Pregunta();

            //Se verifica que no exista una pregunta con el mismo nombre
            $aux = $em->getRepository('ProfesorBundle:Pregunta')->findBy(array
                ('examen' => $examen,
                'titulo' => $titulo));

            if (empty($aux)) {
    
                $pregunta->setTitulo($titulo);
                $pregunta->setTipo($tipo);
                $pregunta->setCalificacion($puntaje);

                $pregunta->setExamen($examen);

                $em->persist($pregunta);
                $em->flush();

                if (($tipo === 'simple') || ($tipo === 'multiple')) {
                    
                    //si es de tipo simple o multiple implica que tengo q agregar opciones de respuesta
                    return $this->redirect($this->generateUrl('respuestas', array('idExamen' => $id, 'idPregunta' => $pregunta->getId())));
                } else if (($tipo === 'escrito')) {
                    //Se crea una respuesta unica xq la respuesta será suministrada por el estudiante
                    $respuestaUnica = new Respuesta();
                    $respuestaUnica->setValidacion(2);
                    $respuestaUnica->setPregunta($pregunta);
                    $respuestaUnica->setPuntaje($pregunta->getCalificacion());
                    $respuestaUnica->setContenido('');
                    $em->persist($respuestaUnica);
                    $em->flush();
                }
                //Se envia el parametro idExamen  
                $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Pregunta registrada !');

                return $this->redirect($this->generateUrl('preguntas', array('id' => $id)));
            } else {
//
                $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Ya se encuentra registrada la pregunta !');
          
                return $this->redirect($this->generateUrl('preguntas', array('id' => $id))); }
        }
  
                return $this->redirect($this->generateUrl('preguntas', array('id' => $id)));  }

    public function agregarRespuestaAction($idExamen, $idPregunta) {
        $respuesta = new Respuesta();
        $editForm = $this->createForm(new RespuestaProfesorType, $respuesta);
        
        return $this->render('ProfesorBundle:Default:respuestas.html.twig', array('idExamen' => $idExamen,
                    'idPregunta' => $idPregunta,
                    'form'=>$editForm->createView()));
    }

    public function registrarRespuestaAction($idExamen, $idPregunta) {
        $textAreaForm = $this->getRequest()->request->get('evaluaUcab_profesorbundle_respuestaprofesortype');
        $contenido = $textAreaForm['contenido'];
        $validacion = $this->getRequest()->request->get('form_validacion');
        $puntaje = $this->getRequest()->request->get('form_puntaje');
        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);
        $respuestasGuardadas = $pregunta->getRespuestas();
        
        $textArea = new Respuesta();
        $editForm = $this->createForm(new RespuestaProfesorType(), $textArea);
        $request = $this->getRequest();
        $editForm->handleRequest($request);
        

        if (!empty($respuestasGuardadas) && ($pregunta->getTipo() === 'simple')) {
            if ($validacion === 'true') {
                $contador = 1;
            } else {
                $contador = 0;
            }
            foreach ($respuestasGuardadas as $rg) {
                if ($rg->getValidacion() == 1) {
                    $contador = $contador + 1;
                }
            }

            if ($contador > 1) {
                $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Ya existe una respuesta verdadera');
              
                return $this->redirect($this->generateUrl('respuestas', array('idExamen' => $idExamen, 'idPregunta' => $idPregunta)));
            }
        }


        if ((isset($validacion)) && ($validacion === 'false')) {
            $puntaje = 0;
        }

        if ((($contenido != '')) && (isset($validacion)) && (isset($puntaje))) {
            $respuesta = new Respuesta();

            //Se verifica que no exista una respuesta con el mismo nombre
            $aux = $em->getRepository('ProfesorBundle:Respuesta')->findBy(array
                ('pregunta' => $pregunta,
                'contenido' => $contenido));

            if (empty($aux)) {

                $respuesta->setContenido($contenido);
                $respuesta->setPuntaje($puntaje);
                $respuesta->setPregunta($pregunta);

                if ($validacion === 'true') {
                    $respuesta->setValidacion(1);
                } else {
                    $respuesta->setValidacion(0);
                }

                $em->persist($respuesta);
                $em->flush();
                $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Respuesta registrada !');

                return $this->redirect($this->generateUrl('respuestas', array('idExamen' => $idExamen, 'idPregunta' => $idPregunta)));
               
            } else {
                $this->getRequest()->getSession()->getFlashBag()->clear();
                $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Ya se encuentra registrada la respuesta !');
                
                return $this->redirect($this->generateUrl('respuestas', array('idExamen' => $idExamen, 'idPregunta' => $idPregunta)));
            }
        }
     
        $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Campos incompletos');

                return $this->redirect($this->generateUrl('respuestas', array('idExamen' => $idExamen, 'idPregunta' => $idPregunta)));
    }

    public function finExamenAction() {
        
    }

    public function finPreguntasAction($idExamen) {
        $em = $this->getDoctrine()->getManager();
        $examen = $em->getRepository('ProfesorBundle:Examen')->find($idExamen);
        $preguntas = $em->getRepository('ProfesorBundle:Pregunta')->findBy(array('examen' => $idExamen));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($preguntas, $this->getRequest()->query->get('page', 1), 5);


        if (empty($preguntas)) {
            $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡El exámen debe contener al menos una pregunta!');
            return $this->render('ProfesorBundle:Default:preguntas.html.twig', array('id' => $examen->getId()));
        } else {
            $calificacionExamen = 0;
            foreach ($preguntas as $pregunta) {
                $calificacionExamen = $calificacionExamen + $pregunta->getCalificacion();
            }

            $examen->setPuntaje($calificacionExamen);
            $em->persist($examen);
            $em->flush();

            return $this->render('ProfesorBundle:Default:prevPreguntas.html.twig', array('pagination' => $pagination,
                        'examen' => $examen));
        }
    }

    public function finRespuestasAction($id) { //idPregunta
        //preguntar si hay alguna respuesta para esa pregunta, si hay lo dejamos, si no hay pues vuelve a respuestas
        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($id);
        $respuestas = $em->getRepository('ProfesorBundle:Respuesta')->findBy(array('pregunta' => $pregunta));

        if (empty($respuestas)) {
            //Setear valor de la pregunta (sumando todas las respuestas de dicha pregunta)
            $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡Debe existir al menos una respuesta a la pregunta!');
      
            return $this->redirect($this->generateUrl('respuestas', array('idExamen' => $pregunta->getExamen()->getId(),
                'idPregunta' => $pregunta->getId())));
           
        } else { //Si ya hay al menos alguna respuesta si tiene sentido finalizar
            $calificacionPregunta = 0;
            $cont = 0;
            foreach ($respuestas as $respuesta) {
                if ($respuesta->getValidacion() == 1) { //para validar q exista al menos una verdadera
                    $cont = $cont + 1;
                }
                $calificacionPregunta = $calificacionPregunta + $respuesta->getPuntaje();
            }
            if ($cont > 0) {
                $pregunta->setCalificacion($calificacionPregunta);
                $em->persist($pregunta);
                $em->flush();
            } else {
                $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡Debe existir al menos una respuesta verdadera!');
                
            return $this->redirect($this->generateUrl('respuestas', array('idExamen' => $pregunta->getExamen()->getId(),
                'idPregunta' => $pregunta->getId())));
            }

            return $this->render('ProfesorBundle:Default:prevRespuestas.html.twig', array('respuestas' => $respuestas,
                        'pregunta' => $pregunta));
        }
    }

    public function eliminarRespuestaAction($idPregunta, $idRespuesta) { //idRespuesta
        $em = $this->getDoctrine()->getManager();
        $respuesta = $em->getRepository('ProfesorBundle:Respuesta')->find($idRespuesta);

        $em->remove($respuesta);
        $em->flush();

        //$this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Respuesta eliminada!');
        //return $this->redirect($this->generateUrl('fin_respuestas', array('id' => $idPregunta)));

        return new Response();
    }

    public function eliminarPreguntaAction($idExamen, $idPregunta) {

        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);
        //set valor del examen
        $em->remove($pregunta);
        $em->flush();

        // $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Pregunta eliminada!');
        //return $this->redirect($this->generateUrl('fin_preguntas', array('idExamen' => $idExamen)));

        return new Response();
    }

    public function verExamenAction($idExamen) {

        $em = $this->getDoctrine()->getManager();
        $examen = $em->getRepository('ProfesorBundle:Examen')->find($idExamen);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($examen->getPreguntas(), $this->getRequest()->query->get('page', 1), 5);

        return $this->render('ProfesorBundle:Default:verExamen.html.twig', array('examen' => $examen,
                    'pagination' => $pagination));
    }

    public function verPreguntaAction($idPregunta) {

        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        return $this->render('ProfesorBundle:Default:verPregunta.html.twig', array('pregunta' => $pregunta));
    }

    public function editarExamenAction($idExamen) {
        //validar si el examen esta en uso 
        $em = $this->getDoctrine()->getManager();
        $examen = $em->getRepository('ProfesorBundle:Examen')->find($idExamen);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->findBy(array('examen' => $examen));

        if (empty($examenPortafolio)) {
            return $this->render('ProfesorBundle:Default:editarExamen.html.twig', array('examen' => $examen));
        }


        $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡El examen está en uso!');
        return $this->redirect($this->generateUrl('examenes'));
    }

    public function updateExamenAction($idExamen) {
        $titulo = $this->getRequest()->request->get('form_titulo');
        $descripcion = $this->getRequest()->request->get('form_descripcion');
        $em = $this->getDoctrine()->getManager();

        $profesor = $this->get('security.context')->getToken()->getUser();
        $examenActual = $em->getRepository('ProfesorBundle:Examen')->find($idExamen);


        $aux = $em->getRepository('ProfesorBundle:Examen')->findBy(array
            ('profesor' => $profesor,
            'titulo' => $titulo));

        if (empty($aux) || ($titulo == $examenActual->getTitulo())) {

            $examenActual->setTitulo($titulo);
            $examenActual->setDescripcion($descripcion);
            $em->persist($examenActual);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Examen editado !');
            return $this->redirect($this->generateUrl('examenes'));
        }
        //else
        $this->getRequest()->getSession()->getFlashBag()->clear();
        $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡Ya se encuentra registrado un examen con el mismo nombre !');
        return $this->render('ProfesorBundle:Default:editarExamen.html.twig', array('examen' => $examenActual));
    }

    public function prevEditarPreguntasAction($idExamen) {
        $em = $this->getDoctrine()->getManager();
        $examen = $em->getRepository('ProfesorBundle:Examen')->find($idExamen);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($examen->getPreguntas(), $this->getRequest()->query->get('page', 1), 5);


        return $this->render('ProfesorBundle:Default:prevEditarPreguntas.html.twig', array('examen' => $examen,
                    'pagination' => $pagination));
    }

    public function editarPreguntaAction($idPregunta) {
        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);
        $editForm = $this->createForm(new PreguntaType(), $pregunta);
        return $this->render('ProfesorBundle:Default:editarPregunta.html.twig', array('pregunta' => $pregunta,
            'formulario'=>$editForm->createView()));
    }

    public function updatePreguntaAction($idPregunta) {
        $titulo = $this->getRequest()->request->get('form_pregunta');
        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);
        
        $editForm = $this->createForm(new PreguntaType(), $pregunta);

        $request = $this->getRequest();

        $editForm->handleRequest($request);


        $aux = $em->getRepository('ProfesorBundle:Pregunta')->findBy(array
            ('examen' => $pregunta->getExamen(),
            'titulo' => $titulo));

        if (empty($aux) || ($titulo == $pregunta->getTitulo())) {
            if ($pregunta->getTipo() === 'escrito') {
                $puntaje = $this->getRequest()->request->get('form_puntaje');
                $pregunta->setCalificacion($puntaje);
            }
        

            $em->persist($pregunta);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Pregunta editada !');
            return $this->redirect($this->generateUrl('prev_editar_preguntas', array('idExamen' => $pregunta->getExamen()->getId())));
        }
        //else
        $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡Ya se encuentra registrada una pregunta con el mismo título !');
        return $this->redirect($this->generateUrl('prev_editar_preguntas', array('idExamen' => $pregunta->getExamen()->getId())));
    }

    public function prevEditarRespuestasAction($idPregunta) {
        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        if ($pregunta->getTipo() === 'escrito') {
            $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡No existen respuestas!');
            return $this->redirect($this->generateUrl('editar_pregunta', array('idPregunta' => $pregunta->getId())));
        }

        return $this->render('ProfesorBundle:Default:prevEditarRespuestas.html.twig', array('pregunta' => $pregunta));
    }

    public function editarRespuestaAction($idRespuesta) {
        $em = $this->getDoctrine()->getManager();
        $respuesta = $em->getRepository('ProfesorBundle:Respuesta')->find($idRespuesta);
        $editForm = $this->createForm(new RespuestaProfesorType(), $respuesta);
        return $this->render('ProfesorBundle:Default:editarRespuesta.html.twig', array('respuesta' => $respuesta,
            'form'=>$editForm->createView()));
    }

    private function actualizarPuntajePregunta($idPregunta) {
        $em = $this->getDoctrine()->getManager();
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        $calificacion = 0;
        foreach ($pregunta->getRespuestas() as $respuesta) {
            $calificacion = $calificacion + $respuesta->getPuntaje();
        }

        $pregunta->setCalificacion($calificacion);
        $em->persist($pregunta);
        $em->flush();

        $this->actualizarPuntajeExamen($pregunta->getExamen()->getId());
    }

    private function actualizarPuntajeExamen($idExamen) {
        $em = $this->getDoctrine()->getManager();
        $examen = $em->getRepository('ProfesorBundle:Examen')->find($idExamen);

        $calificacion = 0;
        foreach ($examen->getPreguntas() as $pregunta) {
            $calificacion = $calificacion + $pregunta->getCalificacion();
        }

        $examen->setPuntaje($calificacion);
        $em->persist($examen);
        $em->flush();
    }

    public function updateRespuestaAction($idRespuesta) {

        //datos q entran
        $textAreaForm = $this->getRequest()->request->get('evaluaUcab_profesorbundle_respuestaprofesortype');
        $contenido = $textAreaForm['contenido'];
        $em = $this->getDoctrine()->getManager();
        $respuestaActual = $em->getRepository('ProfesorBundle:Respuesta')->find($idRespuesta);
        $pregunta = $respuestaActual->getPregunta();

        //se verifica si existe una respuesta igual
        $aux = $em->getRepository('ProfesorBundle:Respuesta')->findBy(array
            ('pregunta' => $pregunta,
            'contenido' => $contenido));

        if (empty($aux) || ($contenido != '')) {
            //si es verdadera tomo el puntaje
            if ($respuestaActual->getValidacion() === 1) {
                $puntaje = $this->getRequest()->request->get('form_puntaje');
                $respuestaActual->setPuntaje($puntaje);
            }

            $respuestaActual->setContenido($contenido);

            $em->persist($respuestaActual);
            $em->flush();

            $this->actualizarPuntajePregunta($pregunta->getId());

            $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Respuesta editada !');
            return $this->redirect($this->generateUrl('prev_editar_respuestas', array('idPregunta' => $pregunta->getId())));
        }


        //else
        $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡Ya se encuentra registrada una respuesta con el mismo contenido !');
        return $this->redirect($this->generateUrl('prev_editar_respuestas', array('idPregunta' => $pregunta->getId())));
    }

    public function borrarExamenAction($idExamen) {

        $em = $this->getDoctrine()->getManager();
        $examen = $em->getRepository('ProfesorBundle:Examen')->find($idExamen);
        $em->remove($examen);
        $em->flush();


        return new Response();
    }

    public function renderExamenesAction() {
        $em = $this->getDoctrine()->getManager();
        $profesor = $this->get('security.context')->getToken()->getUser();
        $examenes = $em->getRepository('ProfesorBundle:Examen')->findBy(array('profesor' => $profesor));

        return $this->render('ProfesorBundle:Default:renderExamenes.html.twig', array('examenes' => $examenes));
    }

    public function calcularNota($examenPortafolio) {
        $acumulado = 0;
        $examen = $examenPortafolio->getExamen(); //examen definido por el prof
        $em = $this->getDoctrine()->getManager();
        foreach ($examen->getPreguntas() as $pregunta) {
            if ($pregunta->getTipo() == 'simple') {
                $respuestaCorrecta = $em->getRepository('ProfesorBundle:Respuesta')
                        ->findBy(array('pregunta' => $pregunta, 'validacion' => 1));
                $respuestaEstudiante = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                        ->findBy(array('pregunta' => $pregunta));

                if ($respuestaCorrecta[0]->getId() === (int) $respuestaEstudiante[0]->getRespuesta()) {
                    
                    $acumulado = $acumulado + $respuestaCorrecta[0]->getPuntaje();
                    
                }
            } else if ($pregunta->getTipo() == 'multiple') {
                // obtener las respuestas correctas del profesor
                // obtener las respuestas del alumno
                $auxAcumulado = 0;
                $error = 0;
                $respuestaCorrectaArray = $em->getRepository('ProfesorBundle:Respuesta')
                        ->findBy(array('pregunta' => $pregunta, 'validacion' => 1));
                $countCorrectas = count($respuestaCorrectaArray);
                $respuestaEstudianteArray = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                        ->findBy(array('pregunta' => $pregunta));
                $countRespEstudiante = count($respuestaEstudianteArray);
                
                if ($countCorrectas == $countRespEstudiante){
                foreach ($respuestaCorrectaArray as $rc) {
                    foreach ($respuestaEstudianteArray as $re) {
                       
                        if ($rc->getId() === (int) $re->getRespuesta()) {
                            $error = 0;
                            $auxAcumulado = $auxAcumulado + $rc->getPuntaje();
                            break;
                        }else{
                            $error = 1;
                        }
                    }
                }  
            }
              if ($error === 0)
                   $acumulado = $acumulado + $auxAcumulado; 
            
            } //finMultiple
        }
      //  var_dump('Acumulado' . $acumulado);
        return $acumulado;
    }

    public function corregirExamenAction($idExamenPortafolio) {
        // primero se hace persist de todas las respuestas enviadas por el estudiante,
        // luego se llama a una funcion q devuelve la nota total o parcial del examen 
        //obtiene todos los elementos 
        // Si la pregunta es seleccion multiple deben estar marcadas las opciones buenas para hacer la suma
        $contenido = $this->getRequest()->request->get('form');
        $nota = 0;
        //var_dump($contenido);
        $em = $this->getDoctrine()->getManager();

        $estudiante = $this->get('security.context')->getToken()->getUser();
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $seccion = $examenPortafolio->getPortafolio()->getSeccion();
        $aux = 0; // 0 si es individual 1 si es grupal
        //chequear si el examen es en grupo o individual para hacer el set correcto

        if ($examenPortafolio->getTipo() == 0) { //si es 0 es individual el examen, si es 1 es grupal el examen
            //Se setea el estudiante Seccion
            $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')
                    ->findBy(array('estudiante' => $estudiante, 'seccion' => $seccion));
        } else {
            $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')
                    ->findBy(array('estudiante' => $estudiante, 'seccion' => $seccion));
            $aux = 1;
        }

        foreach ($examenPortafolio->getExamen()->getPreguntas() as $pregunta) {
            $re = new RespuestaEstudiante();

            if ($pregunta->getTipo() == 'escrito') {
                $re = new RespuestaEstudiante();
                // $re->setEstudianteSeccion($estudianteSeccion);
                if ($aux == 1) {
                    $re->setEstudianteGrupo($estudianteGrupo[0]);
                } else {
                    $re->setEstudianteSeccion($estudianteSeccion[0]);
                }
                $re->setRespuesta($contenido[$pregunta->getId()]); // lo q esta marcado         
                $re->setPregunta($pregunta);
                $re->setExamenPortafolio($examenPortafolio);
                $em->persist($re);
            } else if ($pregunta->getTipo() == 'simple') {

                $re = new RespuestaEstudiante();
//                $re->setEstudianteSeccion($estudianteSeccion);
                if ($aux == 1) {
                    $re->setEstudianteGrupo($estudianteGrupo[0]);
                } else {
                    $re->setEstudianteSeccion($estudianteSeccion[0]);
                }
                $re->setPregunta($pregunta);
                $re->setRespuesta($contenido[$pregunta->getId()]); // lo q esta marcado 
                $re->setExamenPortafolio($examenPortafolio);
                $em->persist($re);
            } else if ($pregunta->getTipo() == 'multiple') {

                if (!empty($contenido[$pregunta->getId()])) {
                    foreach ($contenido[$pregunta->getId()] as $resp) {
                        $re = new RespuestaEstudiante();
//                        $re->setEstudianteSeccion($estudianteSeccion);
                        if ($aux == 1) {
                            $re->setEstudianteGrupo($estudianteGrupo[0]);
                        } else {
                            $re->setEstudianteSeccion($estudianteSeccion[0]);
                        }
                        $re->setPregunta($pregunta);
                        $re->setRespuesta($resp); // lo q esta marcado    
                        $re->setExamenPortafolio($examenPortafolio);
                        $em->persist($re);
                    }
                } else {
//                    $re = new RespuestaEstudiante();
//                    $re->setEstudianteSeccion($estudianteSeccion);
                    $re->setPregunta($pregunta);
                    $re->setRespuesta("");
                    $em->persist($re);
                }
            }
        }

        $em->flush();
        $nota = $this->calcularNota($examenPortafolio);
        //Setear el historico con la nota 
        if ($aux == 1) {
             $historicoExamen = $em->getRepository('ProfesorBundle:HistoricoExamen')
                     ->findBy(array('examenPortafolio' => $examenPortafolio,'estudianteGrupo'=>$estudianteGrupo));
             $historicoExamen[0]->setNota($nota);
             $em->persist($historicoExamen[0]);
             $em->flush();
        } else {
             $historicoExamen = $em->getRepository('ProfesorBundle:HistoricoExamen')
                 ->findBy(array('examenPortafolio' => $examenPortafolio,'estudianteSeccion'=>$estudianteSeccion));
              
             $historicoExamen[0]->setNota($nota); 
             $em->persist($historicoExamen[0]);
             $em->flush();
        }
        
      $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Examen presentado! Sacaste '.$nota.'pts');
      return $this->redirect($this->generateUrl('obtener_examenes', array('idSeccion' => $seccion->getId())));
      
      
        }

}


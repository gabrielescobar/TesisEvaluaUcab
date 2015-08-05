<?php

namespace evaluaUcab\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use evaluaUcab\ProfesorBundle\Entity\Seccion;
use evaluaUcab\ProfesorBundle\Entity\Portafolio;
use evaluaUcab\ProfesorBundle\Entity\Evento;
use evaluaUcab\ProfesorBundle\Entity\ExamenPortafolio;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use evaluaUcab\EstudianteBundle\Form\RespuestaType;

class PortafolioController extends Controller {

    public function crearAction() {
        $em = $this->getDoctrine()->getManager();
        // $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($id);
        $profesor = $this->get('security.context')->getToken()->getUser();
        $secciones = $em->getRepository('ProfesorBundle:Seccion')->findBy(array('profesor' => $profesor));
        $portafolio = new Portafolio();
        /* validacion de fecha, min el dia de hoy */
        $date = new \DateTime('today');
        $var = $date->format('Y-m-d');

        $formulario = $this->createFormBuilder($portafolio)
                ->add('tipo', 'choice', array(
                    'choices' => array(
                        'individual' => 'Individual',
                        'mixto' => 'Mixto',
            )))
                ->add('fechaCierre', 'date', array(
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'attr' => array('min' => $var)))
                ->add('seccion', 'choice', array('choices' => $secciones))
                ->add('seccion', 'entity', array(
                    'class' => 'ProfesorBundle:Seccion',
                    'choices' => $secciones))
                ->getForm();


        $request = $this->getRequest();

        $formulario->handleRequest($request);

        if ($formulario->isValid()) {
            $validate = $em->getRepository('ProfesorBundle:Portafolio')->findBy(array('seccion' => $portafolio->getSeccion()));

            if (empty($validate)) {

                /* Se inserta con la fecha actual */
                $fechaCreacion = new \DateTime;
                $portafolio->setFechaCreacion($fechaCreacion);

                $fechaCierre = $portafolio->getFechaCierre();
                $interval = $fechaCreacion->diff($fechaCierre);

                if ($interval->format('%R') == "+") {
                    $em->persist($portafolio);
                    $em->flush();
                    $portafolio->onCreatePortafolio();//crea las carpetas
                    $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Portafolio Creado!');
                    return $this->redirect($this->generateUrl('portafolios'));
                } else {
                    $this->getRequest()->getSession()->getFlashBag()->add('warning', 'La fecha de cierre debe ser mayor a la fecha actual');
                }
            } else {
                $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Ya fue creado un portafolio para esta sección');
            }
        }
        // var_dump($formulario->getErrors());

        return $this->render('ProfesorBundle:Default:crearPortafolio.html.twig', array('formulario' => $formulario->createView())
        );
    }

    public function seleccionarMateriaAction() {
        //aqui
        //  $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Pass "_demo" route name without any parameters
        //$breadcrumbs->addItem("Portafolios", $this->get("router")->generate("portafolios"));
        // Pass "_demo_hello" route name with parameters
//    $breadcrumbs->addRouteItem("Hello Breadcrumbs", "_demo_hello", array(
//        'name' => 'Breadcrumbs',
//    ));
        $em = $this->getDoctrine()->getManager();
        $profesor = $this->get('security.context')->getToken()->getUser();


        $consulta = $em->createQuery('
            SELECT DISTINCT m.id,m.nombre
            FROM ProfesorBundle:Seccion s JOIN s.materia m JOIN s.profesor p
            WHERE p.id = :id 
            ORDER BY m.nombre
        ');
        $consulta->setParameter('id', $profesor->getId());



        return $this->render('ProfesorBundle:Default:portafolios.html.twig', array('materias' => $consulta->getResult())
        );
    }

    public function borrarPortafolioAction($idPortafolio) {
        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);

        $em->remove($portafolio);
        $em->flush();

        return new Response();
    }

    public function obtenerPortafoliosAction() {

        //aqui
        //$breadcrumbs = $this->get("white_october_breadcrumbs");



        $idMateria = (int) $this->getRequest()->request->get('form_materia');
        $em = $this->getDoctrine()->getManager();
        $profesor = $this->get('security.context')->getToken()->getUser();
        $materia = $em->getRepository('ProfesorBundle:Materia')->find($idMateria);

        $paginator = $this->get('knp_paginator');

        $secciones = $em->getRepository('ProfesorBundle:Seccion')->findBy(array
            ('profesor' => $profesor,
            'materia' => $materia));

        $pagination = $paginator->paginate($secciones, $this->getRequest()->query->get('page', 1), 5);

        //$breadcrumbs->addRouteItem("Portafolios", "portafolios");
        // $breadcrumbs->addRouteItem($materia->getNombre(),"portafolios");

        return $this->render('ProfesorBundle:Default:obtenerPortafolios.html.twig', compact('pagination')
        );
    }

    public function verPortafolioAction($idSeccion) {
        $em = $this->getDoctrine()->getManager();
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($idSeccion);

        return $this->render('ProfesorBundle:Default:verPortafolio.html.twig', array('portafolio' => $seccion->getPortafolio()));
    }

    public function viewExamenesAction($idPortafolio) {
        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        return $this->render('ProfesorBundle:Default:viewExamenes.html.twig', array('portafolio' => $portafolio));
    }

    public function validarExamen($examen) {

        $cont1 = 0;
        //$contPregunta = 0;

        if (!empty($examen->getPreguntas())) {
            foreach ($examen->getPreguntas() as $pregunta) {
                $cont1 = 0;
                if (($pregunta->getTipo() === 'simple') || ($pregunta->getTipo() === 'multiple')) {
                    // $contPregunta = $contPregunta + 1;
                    if (!empty($pregunta->getRespuestas())) {
                        foreach ($pregunta->getRespuestas() as $respuesta) {
                            if ($respuesta->getValidacion() == 1) {
                                $cont1 = $cont1 + 1;
                            }
                        } //fin for respuestas
                    } else { //implica q no tiene respuestas
                        return 0;
                    }
                    if ($cont1 < 1) { //implica que la pregunta no tiene al menos una respuesta correcta
                        return 0;
                    }
                }
            } //fin for preguntas
        } else { //implica q no tiene preguntas
            return 0;
        }
        return 1;
    }

    function importarExamenAction($idPortafolio) {

        //Se inserta la fecha actual como fecha de creacion 
        // $date = new \DateTime('NOW');
        //$date = new \DateTime('NOW', new \DateTimeZone('America/Caracas'));

        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('America/Caracas'));

        //se reciben los datos
        $idExamen = (int) $this->getRequest()->request->get('form_examen');
        $duracion = new \DateTime($this->getRequest()->request->get('form_duracion'));
        $fechaCierre = new \DateTime($this->getRequest()->request->get('form_fechaCierre'));
        $horaCierre = new \DateTime($this->getRequest()->request->get('form_horaCierre'));
        $tipo = (int) $this->getRequest()->request->get('form_tipo');
        $porcentaje = $this->getRequest()->request->get('form_porcentaje');
        $em = $this->getDoctrine()->getManager();

        $examen = $em->getRepository('ProfesorBundle:Examen')->find($idExamen);
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);

        if ($this->validarExamen($examen) == 1) {

            //Se chequea si no ha sido importado el examen al portafolio
            $aux = $em->getRepository('ProfesorBundle:ExamenPortafolio')->findBy(array('examen' => $examen,
                'portafolio' => $portafolio));

            if (empty($aux)) {

                $registro = new ExamenPortafolio();
                $registro->setFechaCierre($fechaCierre);
                $registro->setFechaInicio($date);
                $registro->setExamen($examen);
                $registro->setPortafolio($portafolio);
                $registro->setDuracion($duracion);
                $registro->setHoraCierre($horaCierre);
                $registro->setPorcentaje($porcentaje);
                if ($tipo == 0) { //si la opcion es individual
                    $registro->setTipo($tipo);
                } else { //si la opcion es grupal, se debe verificar q el portafolio es de tipo mixto
                    if ($portafolio->getTipo() == 'mixto') {
                        $registro->setTipo($tipo);
                    } else {
                        $this->getRequest()->getSession()->getFlashBag()->add('warning', 'El portafolio debe ser Mixto para los examenes grupales');
                        return $this->render('ProfesorBundle:Default:viewExamenes.html.twig', array('portafolio' => $portafolio));
                    }
                }
                $em->persist($registro);
                $em->flush();
                //se debe agregar de forma automatica un evento al calendario
                $evento = new Evento();
                $evento->setTitulo($examen->getTitulo());
                $evento->setDetalle($examen->getDescripcion());
                $evento->setTipo('event-important');
                $evento->setSeccion($portafolio->getSeccion());
                $evento->setExamenPortafolio($registro);
                $evento->setVisible(1);
                $auxInicio = strtotime($date->format('Y-m-d H:i')) * 1000;
                $auxFin = strtotime($this->getRequest()->request->get('form_fechaCierre') . ' ' . $this->getRequest()->request->get('form_horaCierre')) * 1000;
                // var_dump('FechaCierre ' . $this->getRequest()->request->get('form_fechaCierre') . ' ' . $this->getRequest()->request->get('form_horaCierre'));
                //var_dump('FechaInicio  ' . $date->format('Y-m-d H:i'));

                $evento->setInicio($auxInicio);
                $evento->setFin($auxFin);

                $em->persist($evento);
                $em->flush();
            } else {
                $this->getRequest()->getSession()->getFlashBag()->add('warning', 'El examen ya existe en el portafolio');
                return $this->render('ProfesorBundle:Default:viewExamenes.html.twig', array('portafolio' => $portafolio));
            }

            $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Examen importado');
            return $this->render('ProfesorBundle:Default:viewExamenes.html.twig', array('portafolio' => $portafolio));
        }
        $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Faltan respuestas y/o preguntas al examen. Diríjase a Editar Examen');
        return $this->render('ProfesorBundle:Default:viewExamenes.html.twig', array('portafolio' => $portafolio));
    }

    public function detalleExamenPortafolioAction($idExamenPortafolio) {

        $em = $this->getDoctrine()->getManager();
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);

        return $this->render('ProfesorBundle:Default:detalleExamenPortafolio.html.twig', array('portafolio' => $examenPortafolio->getPortafolio(), 'examenPortafolio' => $examenPortafolio));
    }

    public function borrarExamenPortafolioAction($idExamenPortafolio) {

        $em = $this->getDoctrine()->getManager();
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);

        //Al borrar este registro, automaticamente se borra el evento asociado
        $em->remove($examenPortafolio);
        $em->flush();

        return new Response();
    }

    public function formEditarExamenPortafolioAction($idExamenPortafolio) {
        $em = $this->getDoctrine()->getManager();
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);

        return $this->render('ProfesorBundle:Default:formEditarExamenPortafolio.html.twig', array('portafolio' => $examenPortafolio->getPortafolio(), 'examenPortafolio' => $examenPortafolio));
    }

    public function editarExamenPortafolioAction($idExamenPortafolio) {

        $duracion = new \DateTime($this->getRequest()->request->get('form_duracion'));
        $fechaCierre = new \DateTime($this->getRequest()->request->get('form_fechaCierre'));
        $horaCierre = new \DateTime($this->getRequest()->request->get('form_horaCierre'));
        $tipo = (int) $this->getRequest()->request->get('form_tipo');
        $em = $this->getDoctrine()->getManager();
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $res = $em->getRepository('EstudianteBundle:RespuestaEstudiante')->findBy(array('examenPortafolio' => $examenPortafolio));

        if (empty($res)) {

            if ($tipo == 0) { //si la opcion es individual
                $examenPortafolio->setFechaCierre($fechaCierre);
                $examenPortafolio->setDuracion($duracion);
                $examenPortafolio->setHoraCierre($horaCierre);
                $examenPortafolio->setTipo($tipo);
                $em->persist($examenPortafolio);
                $em->flush();
            } else { //si la opcion es grupal, se debe verificar q el portafolio es de tipo mixto
                if ($examenPortafolio->getPortafolio()->getTipo() == 'mixto') {
                    $examenPortafolio->setFechaCierre($fechaCierre);
                    $examenPortafolio->setDuracion($duracion);
                    $examenPortafolio->setHoraCierre($horaCierre);
                    $examenPortafolio->setTipo($tipo);
                    $em->persist($examenPortafolio);
                    $em->flush();
                } else {
                    $this->getRequest()->getSession()->getFlashBag()->add('warning', 'El portafolio debe ser Mixto para los examenes grupales');
                    return $this->render('ProfesorBundle:Default:viewExamenes.html.twig', array('portafolio' => $examenPortafolio->getPortafolio()));
                }
            }

            //Se debe actualizar el evento
            $auxFin = strtotime($this->getRequest()->request->get('form_fechaCierre') . ' ' . $this->getRequest()->request->get('form_horaCierre')) * 1000;
            $evento = $examenPortafolio->getEvento();
            $evento->setFin($auxFin);
            $evento->setVisible(1);
            $em->persist($evento);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Cambios realizados');
            return $this->render('ProfesorBundle:Default:viewExamenes.html.twig', array('portafolio' => $examenPortafolio->getPortafolio()));
        } else {
            $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Alumnos ya han presentado la evaluación');
            return $this->render('ProfesorBundle:Default:viewExamenes.html.twig', array('portafolio' => $examenPortafolio->getPortafolio()));
        }

        return new Response();
    }

    public function maxFechaAction($idPortafolio) {

        $em = $this->getDoctrine()->getManager();
        $maxExamenes = $em->getRepository('ProfesorBundle:Portafolio')->findMaxDateExamenes($idPortafolio);
        $maxAsignacion = $em->getRepository('ProfesorBundle:Portafolio')->findMaxDateAsignacion($idPortafolio);
        $aux = 0;

        if (($maxExamenes[0]["fechaMaxima"] != null) && ($maxAsignacion[0]["fechaMaxima"] != null)) { //si los dos tienen algo se crean las fechas
            $fechaMaxExamenes = new \DateTime($maxExamenes[0]["fechaMaxima"]);
            $fechaMaxAsignacion = new \DateTime($maxAsignacion[0]["fechaMaxima"]);

            $interval = $fechaMaxExamenes->diff($fechaMaxAsignacion);

            if ($interval->format('%R') == "+") {

                return new Response(json_encode($maxAsignacion));
            } else {
                return new Response(json_encode($maxExamenes));
            }
        } else if (($maxExamenes[0]["fechaMaxima"] == null) && ($maxAsignacion[0]["fechaMaxima"] != null)) { //si maxExamenes esta vacio, asignacion es la maxima
            //retornar el maxAsignacion
            var_dump("entrando maxAsignacion");
            $fechaMaxAsignacion = new \DateTime($maxAsignacion[0]["fechaMaxima"]);

            var_dump(json_encode($fechaMaxAsignacion));

            return new Response(json_encode($maxAsignacion));
        } else if (($maxExamenes[0]["fechaMaxima"] != null) && ($maxAsignacion[0]["fechaMaxima"] == null)) { //si maxAsignacion esta vacio, examenes es la maxima
            //retornar el maxExamenes
            $fechaMaxExamenes = new \DateTime($maxExamenes[0]["fechaMaxima"]);


            return new Response(json_encode($maxExamenes));
        }

        return new Response();
        ;
    }

    function updatePortafolioAction($idPortafolio, $value) {


        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $fecha = new \DateTime($value);
        $portafolio->setFechaCierre($fecha);
        $em->persist($portafolio);
        $em->flush();

        $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Cambio realizado');
        //return $this->redirect($this->generateUrl('obtener_portafolios'));
        return new Response();
    }

    function calendarioProfesorAction($idSeccion) {

        $em = $this->getDoctrine()->getManager();
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($idSeccion);

        return $this->render('ProfesorBundle:Default:calendarioProfesor.html.twig', array('seccion' => $seccion));
    }

    function verPortafolioEstudianteAction($idPortafolio, $idEstudiante) {
        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $estudiante = $em->getRepository('EstudianteBundle:Estudiante')->find($idEstudiante);

        return $this->render('ProfesorBundle:Default:verPortafolioEstudiante.html.twig', array('portafolio' => $portafolio, 'estudiante' => $estudiante));
    }

    function verPortafolioGrupoAction($idPortafolio, $idGrupo) {
        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $grupo = $em->getRepository('ProfesorBundle:Grupo')->find($idGrupo);

        return $this->render('ProfesorBundle:Default:verPortafolioGrupo.html.twig', array('portafolio' => $portafolio, 'grupo' => $grupo));
    }

    function verExamenesEstudianteAction($idPortafolio, $idEstudiante) {

        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $estudiante = $em->getRepository('EstudianteBundle:Estudiante')->find($idEstudiante);
        $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')->findBy(array('seccion' => $portafolio->getSeccion(), 'estudiante' => $estudiante));

        $historico = $em->getRepository('ProfesorBundle:Portafolio')->
                findExamenesPresentadosByEstudiante($portafolio->getId(), $estudianteSeccion[0]->getId());

        return $this->render('ProfesorBundle:Default:verExamenesEstudiante.html.twig', array('portafolio' => $portafolio, 'estudiante' => $estudiante, 'historico' => $historico));
    }

    function verExamenesGrupoAction($idPortafolio, $idGrupo) {

        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $grupo = $em->getRepository('ProfesorBundle:Grupo')->find($idGrupo);
        $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')
                ->findBy(array('seccion' => $portafolio->getSeccion(), 'grupo' => $grupo));

        $historico = $em->getRepository('ProfesorBundle:Portafolio')->
                findExamenesPresentadosByGrupo($portafolio->getId(), $estudianteGrupo[0]->getId(),$idGrupo);

        return $this->render('ProfesorBundle:Default:verExamenesGrupo.html.twig', array('portafolio' => $portafolio, 'grupo' => $grupo, 'historico' => $historico));
    }

    function preguntasACalificarAction($idHistoricoExamen, $idExamenPortafolio, $idEstudianteSeccion) {
        $em = $this->getDoctrine()->getManager();
        $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')->find($idEstudianteSeccion);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $examen = $examenPortafolio->getExamen();

        $preguntas = $em->getRepository('ProfesorBundle:Pregunta')->findBy(array('examen' => $examen, 'tipo' => 'escrito'));

        return $this->render('ProfesorBundle:Default:preguntasACalificar.html.twig', array('portafolio' => $examenPortafolio->getPortafolio(),
                    'estudiante' => $estudianteSeccion->getEstudiante(),
                    'preguntas' => $preguntas, 'idHistoricoExamen' => $idHistoricoExamen,
                    'idExamenPortafolio' => $idExamenPortafolio, 'idEstudianteSeccion' => $idEstudianteSeccion));
    }

    function preguntasACalificarGrupoAction($idHistoricoExamen, $idExamenPortafolio, $idEstudianteGrupo) {
        $em = $this->getDoctrine()->getManager();
        $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')->find($idEstudianteGrupo);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $examen = $examenPortafolio->getExamen();

        $preguntas = $em->getRepository('ProfesorBundle:Pregunta')->findBy(array('examen' => $examen, 'tipo' => 'escrito'));

        return $this->render('ProfesorBundle:Default:preguntasACalificarGrupo.html.twig', array('portafolio' => $examenPortafolio->getPortafolio(),
                    'grupo' => $estudianteGrupo->getGrupo(),
                    'preguntas' => $preguntas, 'idHistoricoExamen' => $idHistoricoExamen,
                    'idExamenPortafolio' => $idExamenPortafolio, 'idEstudianteGrupo' => $idEstudianteGrupo));
    }

    function finalizarCorreccionExamenAction($idHistoricoExamen, $idExamenPortafolio, $idEstudianteSeccion, $idPregunta) {
        //enviar Portafolio y Estudiante 
        $em = $this->getDoctrine()->getManager();
        $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')->find($idEstudianteSeccion);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);

        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        $respuestaEstudiante = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                ->findBy(array('estudianteSeccion' => $estudianteSeccion, 'examenPortafolio' => $examenPortafolio,
            'pregunta' => $pregunta));
        $editForm = $this->createForm(new RespuestaType(), $respuestaEstudiante[0]);


        return $this->render('ProfesorBundle:Default:finalizarCorreccionExamen.html.twig', array('portafolio' => $examenPortafolio->getPortafolio(),
                    'estudiante' => $estudianteSeccion->getEstudiante(),
                    'pregunta' => $pregunta, 'idHistoricoExamen' => $idHistoricoExamen,
                    'idExamenPortafolio' => $idExamenPortafolio, 'idEstudianteSeccion' => $idEstudianteSeccion,
                    'formulario' => $editForm->createView()));
    }

    function finalizarCorreccionExamenGrupoAction($idHistoricoExamen, $idExamenPortafolio, $idEstudianteGrupo, $idPregunta) {
        //enviar Portafolio y Estudiante 
        $em = $this->getDoctrine()->getManager();
        $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')->find($idEstudianteGrupo);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);

        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        $respuestaEstudiante = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                ->findBy(array('estudianteGrupo' => $estudianteGrupo, 'examenPortafolio' => $examenPortafolio,
            'pregunta' => $pregunta));
        $editForm = $this->createForm(new RespuestaType(), $respuestaEstudiante[0]);


        return $this->render('ProfesorBundle:Default:finalizarCorreccionExamenGrupo.html.twig', array('portafolio' => $examenPortafolio->getPortafolio(),
                    'grupo' => $estudianteGrupo->getGrupo(),
                    'pregunta' => $pregunta, 'idHistoricoExamen' => $idHistoricoExamen,
                    'idExamenPortafolio' => $idExamenPortafolio, 'idEstudianteGrupo' => $idEstudianteGrupo,
                    'formulario' => $editForm->createView()));
    }

    
    
    function formCalificarEscritoAction($idHistoricoExamen, $idExamenPortafolio, $idPregunta, $idEstudianteSeccion) {

        //validar si ya califico en respuestaEstudiante
        $em = $this->getDoctrine()->getManager();
        $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')->find($idEstudianteSeccion);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        $respuestaEstudiante = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                ->findBy(array('estudianteSeccion' => $estudianteSeccion, 'examenPortafolio' => $examenPortafolio,
            'pregunta' => $pregunta));
        if ($respuestaEstudiante[0]->getCalificacion() != null) {

            $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Ya fue calificada la pregunta');
            return $this->redirect($this->generateUrl('preguntas_a_calificar', array('idHistoricoExamen' => $idHistoricoExamen,
                                'idExamenPortafolio' => $idExamenPortafolio,
                                'idEstudianteSeccion' => $idEstudianteSeccion,
            )));
        }



        return $this->render('ProfesorBundle:Default:formCalificarEscrito.html.twig', array('portafolio' => $examenPortafolio->getPortafolio(),
                    'estudiante' => $estudianteSeccion->getEstudiante(),
                    'idPregunta' => $idPregunta, 'idHistoricoExamen' => $idHistoricoExamen,
                    'idExamenPortafolio' => $idExamenPortafolio, 'idEstudianteSeccion' => $idEstudianteSeccion,
                    'maxCalificacion' => $pregunta->getCalificacion()));
    }
    
        function formEditarCalificarEscritoAction($idHistoricoExamen, $idExamenPortafolio, $idPregunta, $idEstudianteSeccion) {

        //validar si ya califico en respuestaEstudiante
        $em = $this->getDoctrine()->getManager();
        $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')->find($idEstudianteSeccion);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        $respuestaEstudiante = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                ->findBy(array('estudianteSeccion' => $estudianteSeccion, 'examenPortafolio' => $examenPortafolio,
            'pregunta' => $pregunta));
        if ($respuestaEstudiante[0]->getCalificacion() == null) {

            $this->getRequest()->getSession()->getFlashBag()->add('warning', 'No ha calificado la pregunta');
            return $this->redirect($this->generateUrl('preguntas_a_calificar', array('idHistoricoExamen' => $idHistoricoExamen,
                                'idExamenPortafolio' => $idExamenPortafolio,
                                'idEstudianteSeccion' => $idEstudianteSeccion,
            )));
        }

        return $this->render('ProfesorBundle:Default:formEditarCalificarEscrito.html.twig', array('portafolio' => $examenPortafolio->getPortafolio(),
                    'estudiante' => $estudianteSeccion->getEstudiante(),
                    'idPregunta' => $idPregunta, 'idHistoricoExamen' => $idHistoricoExamen,
                    'idExamenPortafolio' => $idExamenPortafolio, 'idEstudianteSeccion' => $idEstudianteSeccion,
                    'maxCalificacion' => $pregunta->getCalificacion(),
                    'calificacionActual' =>$respuestaEstudiante[0]->getCalificacion()));
    }

    function formCalificarEscritoGrupoAction($idHistoricoExamen, $idExamenPortafolio, $idPregunta, $idEstudianteGrupo) {

        //validar si ya califico en respuestaEstudiante
        $em = $this->getDoctrine()->getManager();
        $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')->find($idEstudianteGrupo);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        $respuestaEstudiante = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                ->findBy(array('estudianteGrupo' => $estudianteGrupo, 'examenPortafolio' => $examenPortafolio,
            'pregunta' => $pregunta));
        if ($respuestaEstudiante[0]->getCalificacion() != null) {

            $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Ya fue calificada la pregunta');

            return $this->redirect($this->generateUrl('preguntas_a_calificar_grupo', array('idHistoricoExamen' => $idHistoricoExamen,
                                'idExamenPortafolio' => $idExamenPortafolio,
                                'idEstudianteGrupo' => $idEstudianteGrupo,
            )));
        }



        return $this->render('ProfesorBundle:Default:formCalificarEscritoGrupo.html.twig', array('portafolio' => $examenPortafolio->getPortafolio(),
                    'grupo' => $estudianteGrupo->getGrupo(),
                    'idPregunta' => $idPregunta, 'idHistoricoExamen' => $idHistoricoExamen,
                    'idExamenPortafolio' => $idExamenPortafolio, 'idEstudianteGrupo' => $idEstudianteGrupo,
                    'maxCalificacion' => $pregunta->getCalificacion()));
    }
    
      function formEditarCalificarEscritoGrupoAction($idHistoricoExamen, $idExamenPortafolio, $idPregunta, $idEstudianteGrupo) {

        //validar si ya califico en respuestaEstudiante
        $em = $this->getDoctrine()->getManager();
        $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')->find($idEstudianteGrupo);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        $respuestaEstudiante = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                ->findBy(array('estudianteGrupo' => $estudianteGrupo, 'examenPortafolio' => $examenPortafolio,
            'pregunta' => $pregunta));
        if ($respuestaEstudiante[0]->getCalificacion() == null) {

            $this->getRequest()->getSession()->getFlashBag()->add('warning', 'no ha calificado la pregunta');

            return $this->redirect($this->generateUrl('preguntas_a_calificar_grupo', array('idHistoricoExamen' => $idHistoricoExamen,
                                'idExamenPortafolio' => $idExamenPortafolio,
                                'idEstudianteGrupo' => $idEstudianteGrupo,
                                 )));
        }

        return $this->render('ProfesorBundle:Default:formEditarCalificarEscritoGrupo.html.twig', array('portafolio' => $examenPortafolio->getPortafolio(),
                    'grupo' => $estudianteGrupo->getGrupo(),
                    'idPregunta' => $idPregunta, 'idHistoricoExamen' => $idHistoricoExamen,
                    'idExamenPortafolio' => $idExamenPortafolio, 'idEstudianteGrupo' => $idEstudianteGrupo,
                    'maxCalificacion' => $pregunta->getCalificacion(),
                    'calificacionActual' =>$respuestaEstudiante[0]->getCalificacion()));
    }

    function calificarEscritoAction($idHistoricoExamen, $idExamenPortafolio, $idPregunta, $idEstudianteSeccion) {

        $calificacion = $this->getRequest()->request->get('form_calificacion');

        var_dump($calificacion);
        $em = $this->getDoctrine()->getManager();
        $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')->find($idEstudianteSeccion);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        $respuestaEstudiante = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                ->findBy(array('estudianteSeccion' => $estudianteSeccion, 'examenPortafolio' => $examenPortafolio,
            'pregunta' => $pregunta));

        $historicoExamen = $em->getRepository('ProfesorBundle:HistoricoExamen')->find($idHistoricoExamen);


        $respuestaEstudiante[0]->setCalificacion($calificacion);
        $em->persist($respuestaEstudiante[0]);
        $historicoExamen->setNota($historicoExamen->getNota() + $calificacion);

        $em->persist($historicoExamen);

        $em->flush();

        $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Pregunta calificada');
        return $this->redirect($this->generateUrl('preguntas_a_calificar', array('idHistoricoExamen' => $idHistoricoExamen,
                            'idExamenPortafolio' => $idExamenPortafolio,
                            'idEstudianteSeccion' => $idEstudianteSeccion)));
    }

    function calificarEscritoGrupoAction($idHistoricoExamen, $idExamenPortafolio, $idPregunta, $idEstudianteGrupo) {

        $calificacion = $this->getRequest()->request->get('form_calificacion');

        var_dump($calificacion);
        $em = $this->getDoctrine()->getManager();
        $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')->find($idEstudianteGrupo);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        $respuestaEstudiante = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                ->findBy(array('estudianteGrupo' => $estudianteGrupo, 'examenPortafolio' => $examenPortafolio,
            'pregunta' => $pregunta));

        $historicoExamen = $em->getRepository('ProfesorBundle:HistoricoExamen')->find($idHistoricoExamen);


        $respuestaEstudiante[0]->setCalificacion($calificacion);
        $em->persist($respuestaEstudiante[0]);
        $historicoExamen->setNota($historicoExamen->getNota() + $calificacion);

        $em->persist($historicoExamen);

        $em->flush();

        $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Pregunta calificada');
        return $this->redirect($this->generateUrl('preguntas_a_calificar_grupo', array('idHistoricoExamen' => $idHistoricoExamen,
                            'idExamenPortafolio' => $idExamenPortafolio,
                            'idEstudianteGrupo' => $idEstudianteGrupo)));
    }
    function editarCalificarEscritoGrupoAction($idHistoricoExamen, $idExamenPortafolio, $idPregunta, $idEstudianteGrupo) {

        $calificacion = $this->getRequest()->request->get('form_calificacion');

        var_dump($calificacion);
        $em = $this->getDoctrine()->getManager();
        $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')->find($idEstudianteGrupo);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        $respuestaEstudiante = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                ->findBy(array('estudianteGrupo' => $estudianteGrupo, 'examenPortafolio' => $examenPortafolio,
            'pregunta' => $pregunta));

        $historicoExamen = $em->getRepository('ProfesorBundle:HistoricoExamen')->find($idHistoricoExamen);

        $restarNota = $respuestaEstudiante[0]->getCalificacion();
        $respuestaEstudiante[0]->setCalificacion($calificacion);
        $em->persist($respuestaEstudiante[0]);
        $historicoExamen->setNota($historicoExamen->getNota() - $restarNota + $calificacion);

        
        $em->persist($historicoExamen);

        $em->flush();

        $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Calificación editada');
        return $this->redirect($this->generateUrl('preguntas_a_calificar_grupo', array('idHistoricoExamen' => $idHistoricoExamen,
                            'idExamenPortafolio' => $idExamenPortafolio,
                            'idEstudianteGrupo' => $idEstudianteGrupo)));
    }
    
     function editarCalificarEscritoAction($idHistoricoExamen, $idExamenPortafolio, $idPregunta, $idEstudianteSeccion) {

        $calificacion = $this->getRequest()->request->get('form_calificacion');

        var_dump($calificacion);
        $em = $this->getDoctrine()->getManager();
        $estudianteSeccion = $em->getRepository('ProfesorBundle:EstudianteSeccion')->find($idEstudianteSeccion);
        $examenPortafolio = $em->getRepository('ProfesorBundle:ExamenPortafolio')->find($idExamenPortafolio);
        $pregunta = $em->getRepository('ProfesorBundle:Pregunta')->find($idPregunta);

        $respuestaEstudiante = $em->getRepository('EstudianteBundle:RespuestaEstudiante')
                ->findBy(array('estudianteSeccion' => $estudianteSeccion, 'examenPortafolio' => $examenPortafolio,
            'pregunta' => $pregunta));

        $historicoExamen = $em->getRepository('ProfesorBundle:HistoricoExamen')->find($idHistoricoExamen);

        $restarNota = $respuestaEstudiante[0]->getCalificacion();
        $respuestaEstudiante[0]->setCalificacion($calificacion);
        $em->persist($respuestaEstudiante[0]);
        $historicoExamen->setNota($historicoExamen->getNota() - $restarNota + $calificacion);

        $em->persist($historicoExamen);

        $em->flush();

        $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Calificación editada');
        return $this->redirect($this->generateUrl('preguntas_a_calificar', array('idHistoricoExamen' => $idHistoricoExamen,
                            'idExamenPortafolio' => $idExamenPortafolio,
                            'idEstudianteSeccion' => $idEstudianteSeccion)));
    }
    
    

    public function obtenerListasRubricasAction() {
        //$var = 
        $opcion = $this->getRequest()->request->get('opcion');
        $em = $this->getDoctrine()->getManager();
        $profesor = $this->get('security.context')->getToken()->getUser();

        if ($opcion == 'lista') {
            $result = $em->getRepository('ProfesorBundle:Portafolio')->findListasByPprof($profesor->getId());
        } else if ($opcion == 'rubrica') {
            $result = $em->getRepository('ProfesorBundle:Portafolio')->findRubricasByPprof($profesor->getId());
        }

        return new Response(json_encode($result));
    }
    
    public function estudiantesEnSeccionAction($idSeccion){
        $em = $this->getDoctrine()->getManager();
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($idSeccion);
     
        //Se obtienen los estudiantes de la seccion
        $estudiantes = $em->getRepository('ProfesorBundle:Seccion')->findEstudiantesEnSeccion($seccion->getId());
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate($estudiantes, $this->getRequest()->query->get('page', 1), 10);
  
      
        return $this->render('ProfesorBundle:Default:viewAlumnos.html.twig',array('estudiantes'=>$pagination,
            'idSeccion' =>$seccion->getId(),'portafolio' => $seccion->getPortafolio()));
       
    }
    
  

}


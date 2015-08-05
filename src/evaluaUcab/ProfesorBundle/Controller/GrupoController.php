<?php

namespace evaluaUcab\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use evaluaUcab\ProfesorBundle\Entity\Seccion;
use evaluaUcab\ProfesorBundle\Entity\Grupo;
use evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class GrupoController extends Controller {

    public function formGrupoAction($idSeccion) {

        $em = $this->getDoctrine()->getManager();
        $estudiantesEnSeccionSinGrupo = $em->getRepository('ProfesorBundle:Grupo')->findEstudiantesEnSeccionSinGrupo($idSeccion);
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($idSeccion);

        return $this->render('ProfesorBundle:Default:formGrupo.html.twig', array('estudiantes' => $estudiantesEnSeccionSinGrupo, 'portafolio' => $seccion->getPortafolio()));
    }

    public function viewGruposAction($idPortafolio) {
        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);
        $seccion = $portafolio->getSeccion();

        $grupos = $em->getRepository('ProfesorBundle:Grupo')->findGruposBySeccion($seccion->getId());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($grupos, $this->getRequest()->query->get('page', 1), 5);


        return $this->render('ProfesorBundle:Default:viewGrupos.html.twig', array('pagination' => $pagination, 'portafolio' => $portafolio));
    }

    public function crearGrupoAction($idSeccion) {
        $contenido = $this->getRequest()->request->get('estudianteGrupo');
        $em = $this->getDoctrine()->getManager();
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($idSeccion);

        if($seccion->getPortafolio()->getTipo()== 'mixto'){
        $count = $em->getRepository('ProfesorBundle:Grupo')->findCountGruposEnSeccion($idSeccion);
       
        $numGrupo = $count[0][1] + 1;
        $grupo = new Grupo();
        $grupo->setNombre('Grupo ' . $numGrupo);

        $em->persist($grupo);
        $em->flush();

        foreach ($contenido as $idEstudiante) {
            $estudiante = $em->getRepository('EstudianteBundle:Estudiante')->find($idEstudiante);
            $estGrupo = new EstudianteGrupo();
            $estGrupo->setEstudiante($estudiante);
            $estGrupo->setGrupo($grupo);
            $estGrupo->setSeccion($seccion);
            $em->persist($estGrupo);
            $em->flush();
        }

        $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Grupo creado!');
        return $this->redirect($this->generateUrl('grupos', array('idPortafolio' => $seccion->getPortafolio()->getId())));
     
     }
        $this->getRequest()->getSession()->getFlashBag()->add('warning', 'El portafolio debe ser Mixto para crear Grupos');
        return $this->redirect($this->generateUrl('grupos', array('idPortafolio' => $seccion->getPortafolio()->getId())));
     
     
 }

    public function borrarGrupoAction($idPortafolio, $idGrupo) {
        $em = $this->getDoctrine()->getManager();
        $grupo = $em->getRepository('ProfesorBundle:Grupo')->find($idGrupo);
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);

        $em->remove($grupo);
        $em->flush();
        $cont = 0;

        $gruposUpdate = $em->getRepository('ProfesorBundle:Grupo')->findGruposBySeccion($portafolio->getSeccion()->getId());

        foreach ($gruposUpdate as $grupoUpdate) {
            $cont = $cont + 1;
            $g = $em->getRepository('ProfesorBundle:Grupo')->find($grupoUpdate["id"]);
            $g->setNombre('Grupo ' . $cont);
            $em->persist($g);
        }
        $em->flush();

        return new Response();
    }

    public function verAlumnosGrupoAction($idPortafolio, $idGrupo) {
        $em = $this->getDoctrine()->getManager();
        $estudiantesEnGrupo = $em->getRepository('ProfesorBundle:Grupo')->findEstudiantesByGrupo($idGrupo);
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);

        $count = $em->getRepository('ProfesorBundle:Grupo')->findCountEstudiantesByGrupo($idGrupo);

        return $this->render('ProfesorBundle:Default:viewAlumnosGrupo.html.twig', array('estudiantes' => $estudiantesEnGrupo, 'portafolio' => $portafolio));
    }

    public function formEditarGrupoAction($idPortafolio, $idGrupo) {
        $em = $this->getDoctrine()->getManager();
        $estudiantesEnGrupo = $em->getRepository('ProfesorBundle:Grupo')->findEstudiantesByGrupo($idGrupo);
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);

        return $this->render('ProfesorBundle:Default:formEditarGrupo.html.twig', array('estudiantes' => $estudiantesEnGrupo, 'idGrupo' => $idGrupo, 'portafolio' => $portafolio));
    }

    public function formAddEstudianteGrupoAction($idSeccion, $idGrupo) {

        $em = $this->getDoctrine()->getManager();
        $estudiantesEnSeccionSinGrupo = $em->getRepository('ProfesorBundle:Grupo')->findEstudiantesEnSeccionSinGrupo($idSeccion);
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($idSeccion);

        return $this->render('ProfesorBundle:Default:formAddEstudianteGrupo.html.twig', array('estudiantes' => $estudiantesEnSeccionSinGrupo, 'portafolio' => $seccion->getPortafolio(),
                    'idGrupo' => $idGrupo));
    }

    public function borrarEstudianteGrupoAction($idEstudianteGrupo) {
        $em = $this->getDoctrine()->getManager();
        $estudianteGrupo = $em->getRepository('ProfesorBundle:EstudianteGrupo')->find($idEstudianteGrupo);
        $seccion = $estudianteGrupo->getSeccion();

        $count = $em->getRepository('ProfesorBundle:Grupo')->findCountEstudiantesByGrupo($estudianteGrupo->getGrupo()->getId());
        $num = (int) $count[0][1];

        if ($num > 1) {
            $em->remove($estudianteGrupo);
            $em->flush();
            $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Estudiante eliminado del grupo!');
        } else {

            $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡El grupo debe tener al menos un estudiante!');
        }
        return new Response();
    }

    public function addEstudianteGrupoAction($idSeccion, $idGrupo) {

        $contenido = $this->getRequest()->request->get('estudianteGrupo');
        $em = $this->getDoctrine()->getManager();
        $seccion = $em->getRepository('ProfesorBundle:Seccion')->find($idSeccion);
        $grupo = $em->getRepository('ProfesorBundle:Grupo')->find($idGrupo);

        foreach ($contenido as $idEstudiante) {
            $estudiante = $em->getRepository('EstudianteBundle:Estudiante')->find($idEstudiante);
            $estGrupo = new EstudianteGrupo();
            $estGrupo->setEstudiante($estudiante);
            $estGrupo->setGrupo($grupo);
            $estGrupo->setSeccion($seccion);
            $em->persist($estGrupo);
        }
        $em->flush();

        $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Grupo editado!');
        return $this->redirect($this->generateUrl('grupos', array('idPortafolio' => $seccion->getPortafolio()->getId())));


        return new Response();
    }

}


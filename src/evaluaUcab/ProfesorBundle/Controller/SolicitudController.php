<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evaluaUcab\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use evaluaUcab\ProfesorBundle\Entity\EstudianteSeccion;

class SolicitudController extends Controller {

    public function solicitudesAction() {

        return $this->render('ProfesorBundle:Default:solicitudes.html.twig');
    }

    public function consultarSolicitudAction(Request $request) {

        $idSeccion = $request->request->get('form_seccion');
        $em = $this->getDoctrine()->getManager();
        $solicitudes = $em->getRepository('EstudianteBundle:Solicitud')->findBy(array('seccion' => $idSeccion, 'status' => 'Pendiente'));

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($solicitudes, $this->getRequest()->query->get('page', 1), 10);


        return $this->render('ProfesorBundle:Default:consultaSolicitud.html.twig', compact('pagination'));
    }

    public function aceptarAction($id) {

        //Enviar correo al estudiante
        $em = $this->getDoctrine()->getManager();
        $solicitud = $em->getRepository('EstudianteBundle:Solicitud')->find($id);

        $estudiante = $solicitud->getEstudiante();
        $seccion = $solicitud->getSeccion();

        //se incluye el estudiante en la seccion
        $es = new EstudianteSeccion();
        $es->setEstudiante($estudiante);
        $es->setSeccion($seccion);

        $message = \Swift_Message::newInstance('Solicitud Aceptada');
        $message->setFrom('evalua.ucab@gmail.com');
        $message->setTo($estudiante->getCorreo());

        $message->setBody(
                '<html>' .
                ' <head> <img src="' . $message->embed(\Swift_Image::fromPath('images/logoHeader.png')) . '" /></head>' .
                ' <body>' .
                ' <br><br><br><span style="font-size:18px">Estimado estudiante, su solicitud a la sección ' . $solicitud->getSeccion()->getMateria()->getNombre() . '-' . $solicitud->getSeccion()->getCodigo() . ' ha sido aceptada por el profesor. </span> ' .
                ' </body>' .
                '<br><br><br><footer>EvaluaUcab Todos los derechos reservados</footer>' .
                '</html>', 'text/html' // Mark the content-type as HTML
        );
        $this->get('mailer')->send($message);

        $solicitud->setStatus('Aceptada');

        $em->persist($solicitud);
        $em->persist($es);
        $em->flush();

        //crear el portafolio

        $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Solicitud Aceptada !');
        return $this->render('ProfesorBundle:Default:solicitudes.html.twig');
    }

    public function rechazarAction($id) {
        //Enviar correo al estudiante
        $em = $this->getDoctrine()->getManager();
        $solicitud = $em->getRepository('EstudianteBundle:Solicitud')->find($id);
        $estudiante = $solicitud->getEstudiante();
        /*         * ********************Mail ****************************** */
        $message = \Swift_Message::newInstance('Solicitud Rechazada');
        $message->setFrom('evalua.ucab@gmail.com');
        $message->setTo($estudiante->getCorreo());

        $message->setBody(
                '<html>' .
                ' <head> <img src="' . $message->embed(\Swift_Image::fromPath('images/logoHeader.png')) . '" /></head>' .
                ' <body>' .
                ' <br><br><br><span style="font-size:18px">Estimado estudiante, su solicitud a la sección ' . $solicitud->getSeccion()->getMateria()->getNombre() . '-' . $solicitud->getSeccion()->getCodigo() . ' ha sido rechazada por el profesor. </span> ' .
                ' </body>' .
                '<br><br><br><footer>EvaluaUcab Todos los derechos reservados</footer>' .
                '</html>', 'text/html' // Mark the content-type as HTML
        );
        $this->get('mailer')->send($message);

        $solicitud->setStatus('Rechazada');
        /*         * ****************************************************** */
        $em->remove($solicitud);
        $em->flush();

        $this->getRequest()->getSession()->getFlashBag()->add('warning', '¡Solicitud Rechazada!');
        return $this->render('ProfesorBundle:Default:solicitudes.html.twig');
    }

}

?>

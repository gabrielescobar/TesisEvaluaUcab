<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evaluaUcab\EstudianteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use evaluaUcab\EstudianteBundle\Entity\Estudiante;
use evaluaUcab\EstudianteBundle\Form\EstudianteRegistroType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistroController extends Controller {

    public function registroAction() {
        $estudiante = new Estudiante();
        $formulario = $this->createForm(new EstudianteRegistroType(), $estudiante);
        $request = $this->getRequest();

        $formulario->handleRequest($request);

        if ($formulario->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $estudiante->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));

            $encoder = $this->get('security.encoder_factory')->getEncoder($estudiante);
            $passwordCodificado = $encoder->encodePassword($estudiante->getPassword(), $estudiante->getSalt());
            $estudiante->setPassword($passwordCodificado);

            $estudiante->upload();

            // Guardamos el objeto en base de datos
            $em->persist($estudiante);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Ha sido registrado con éxito!');

            return $this->redirect($this->generateUrl('inicio'));
        }
        //var_dump($formulario->getErrors());
        return $this->render('EstudianteBundle:Default:formEstudiante.html.twig', array('formulario' => $formulario->createView()));
    }

}

?>

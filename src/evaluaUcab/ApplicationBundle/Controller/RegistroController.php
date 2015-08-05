<?php

namespace evaluaUcab\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use evaluaUcab\ProfesorBundle\Entity\Profesor;
use evaluaUcab\ProfesorBundle\Form\ProfesorRegistroType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistroController extends Controller {
    
 public function registroAction() {
        $profesor = new Profesor();
        $formulario = $this->createForm(new ProfesorRegistroType(), $profesor);
        $request = $this->getRequest();

        $formulario->handleRequest($request);

        if ($formulario->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $profesor->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));

            $encoder = $this->get('security.encoder_factory')->getEncoder($profesor);
            $passwordCodificado = $encoder->encodePassword($profesor->getPassword(), $profesor->getSalt());
            $profesor->setPassword($passwordCodificado);

            $profesor->upload();

            // Guardamos el objeto en base de datos
            $em->persist($profesor);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Profesor registrado con éxito!');

            return $this->redirect($this->generateUrl('inicio'));
        }
        //var_dump($formulario->getErrors());
        return $this->render('ApplicationBundle:Default:formProfesor.html.twig', array('formulario' => $formulario->createView()));
    }



}

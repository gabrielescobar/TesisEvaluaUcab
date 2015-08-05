<?php

namespace evaluaUcab\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use evaluaUcab\ProfesorBundle\Form\ProfesorRegistroType;

class DefaultController extends Controller {

    public function indexAction($name) {
        return $this->render('ProfesorBundle:Default:index.html.twig', array('name' => $name));
    }

    public function editPerfilAction($idProfesor) {
        $em = $this->getDoctrine()->getManager();

        $profesor = $em->getRepository('ProfesorBundle:Profesor')->find($idProfesor);

        if (!$profesor) {
            throw $this->createNotFoundException('No se ha encontrado el profesor solicitado');
        }

        $editForm = $this->createForm(new ProfesorRegistroType(), $profesor);


        return $this->render('ProfesorBundle:Default:modalEditProfesor.html.twig', array(
                    'profesor' => $profesor,
                    'edit_form' => $editForm->createView(),
        ));
    }

    public function updatePerfilAction($idProfesor) {
        $em = $this->getDoctrine()->getManager();

        $profesor = $em->getRepository('ProfesorBundle:Profesor')->find($idProfesor);
         $password = $profesor->getPassword();


        if (!$profesor) {
            throw $this->createNotFoundException('No se ha encontrado el profesor solicitado');
        }

        $editForm = $this->createForm(new ProfesorRegistroType(), $profesor);

        $request = $this->getRequest();

        $editForm->handleRequest($request);
        if ($editForm->isValid()) {


           $profesor->setPassword($password);
            $profesor->upload();
            $em->persist($profesor);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Perfil Modificado!');

            return $this->redirect($this->generateUrl('profesor_edit', array('idProfesor' => $idProfesor)));
        }
        var_dump($editForm->getErrors());
        return $this->render('ProfesorBundle:Default:modalEditProfesor.html.twig', array(
                    'profesor' => $profesor,
                    'edit_form' => $editForm->createView(),
        ));
    }

}

<?php

namespace evaluaUcab\EstudianteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use evaluaUcab\EstudianteBundle\Form\EstudianteRegistroType;

class DefaultController extends Controller {

    public function indexAction($name) {
        return $this->render('EstudianteBundle:Default:index.html.twig', array('name' => $name));
    }

    public function editPerfilAction($id) {
        $em = $this->getDoctrine()->getManager();

        $estudiante = $em->getRepository('EstudianteBundle:Estudiante')->find($id);

        if (!$estudiante) {
            throw $this->createNotFoundException('No se ha encontrado el estudiante solicitado');
        }

        $editForm = $this->createForm(new EstudianteRegistroType(), $estudiante);


        return $this->render('EstudianteBundle:Default:modalEditEstudiante.html.twig', array(
                    'estudiante' => $estudiante,
                    'edit_form' => $editForm->createView(),
        ));
    }
    
       
    public function updatePerfilAction($id) {
        $em = $this->getDoctrine()->getManager();

        $estudiante = $em->getRepository('EstudianteBundle:Estudiante')->find($id);
        $cedula = $estudiante->getCedula();
        $password = $estudiante->getPassword();

        if (!$estudiante) {
            throw $this->createNotFoundException('No se ha encontrado el estudiante solicitado');
        }

        $editForm = $this->createForm(new EstudianteRegistroType(), $estudiante);

        $request = $this->getRequest();

        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
           
            if (!($cedula === $estudiante->getCedula())){
              rename( __DIR__.'/../../../../web/uploads/estudiantes/'.$cedula,
                      __DIR__.'/../../../../web/uploads/estudiantes/'.$estudiante->getCedula());
            }
            $estudiante->setPassword($password);
       
            $estudiante->upload();
            $em->persist($estudiante);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('notice', '¡Perfil Modificado!');

            return $this->redirect($this->generateUrl('estudiante_edit', array('id' => $id)));
        }
          //var_dump($editForm->getErrors());
        return $this->render('EstudianteBundle:Default:modalEditEstudiante.html.twig', array(
                    'estudiante' => $estudiante,
                    'edit_form' => $editForm->createView(),
        ));
    }

}

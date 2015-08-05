<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evaluaUcab\ProfesorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use evaluaUcab\ProfesorBundle\Entity\MaterialApoyo;

class MaterialApoyoController extends Controller {

    public function addMaterialAction($idPortafolio, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);

        $material = new MaterialApoyo();
        $form = $this->createFormBuilder($material)
                ->add('file', 'file', array('required' => false))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($material->getFile() == null) {
                $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Debe seleccionar un archivo!');
                return $this->render('ProfesorBundle:Default:addMaterial.html.twig', array('portafolio' => $portafolio,
                            'formulario' => $form->createView()));
            }

            if ($material->validateExtension()) { //si la extension es valida
                $material->upload($idPortafolio);
                $material->setPortafolio($portafolio);
                $material->setTipo($this->getFile()->getClientOriginalExtension());
                // Guardamos el objeto en base de datos
                $em->persist($material);

                $em->flush();

                $this->getRequest()->getSession()->getFlashBag()->add('notice', 'Material agregado!');

                return $this->render('ProfesorBundle:Default:materialDeApoyo.html.twig', array('portafolio' => $portafolio));
            }
        }
        $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Tipo de archivo inválido!');
        return $this->render('ProfesorBundle:Default:addMaterial.html.twig', array('portafolio' => $portafolio,
                    'formulario' => $form->createView()));
    }

    public function viewMaterialApoyoAction($idPortafolio) {
        //listar todos los materiales del portafolio
        $em = $this->getDoctrine()->getManager();
        $portafolio = $em->getRepository('ProfesorBundle:Portafolio')->find($idPortafolio);

        return $this->render('ProfesorBundle:Default:materialDeApoyo.html.twig', array('portafolio' => $portafolio));
    }

}

?>

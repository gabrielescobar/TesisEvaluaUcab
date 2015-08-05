<?php

namespace evaluaUcab\EstudianteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class MapaMentalController extends Controller {


    public function verAction($idEstudiante){
        //dependiendo del id del diario se mostrará 
        $em = $this->getDoctrine()->getManager();
        $mapas = $em->getRepository('EstudianteBundle:MapaMental')->findMapasByEstudiante($idEstudiante);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($mapas, $this->getRequest()->query->get('page', 1), 10);

        
        
        return $this->render('EstudianteBundle:Default:viewMapasMentales.html.twig',array('mapasMentales'=>$pagination));
    }
    
    public function borrarMapaMentalOutsideAction($idMapa){
        
        $em = $this->getDoctrine()->getManager();
        $mapa = $em->getRepository('EstudianteBundle:MapaMental')->find($idMapa);
        $em->remove($mapa);
        $em->flush();
        
        return new Response();
        
        
    }

}
?>

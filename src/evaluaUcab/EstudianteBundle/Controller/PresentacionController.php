<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evaluaUcab\EstudianteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class PresentacionController extends Controller {
    
    public function verAction(){
        return $this->render('EstudianteBundle:Default:presentacion.html.twig');

    }

}
?>

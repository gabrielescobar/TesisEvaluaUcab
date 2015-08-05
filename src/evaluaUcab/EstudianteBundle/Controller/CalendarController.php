<?php

namespace evaluaUcab\EstudianteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CalendarController extends Controller {

    
    public function calendarioAction() {
        return $this->render('EstudianteBundle:Default:calendario.html.twig');
    }

   
}

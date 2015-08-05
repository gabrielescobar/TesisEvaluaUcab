<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evaluaUcab\EstudianteBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SolicitudRepository extends EntityRepository {

    public function findSolicitudesByEstudiante() {
       
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('
    
            SELECT s
            FROM EstudianteBundle:Solicitud s
            WHERE s.estudiante = :id
        ');
        $consulta->setParameter('id', $id);


        return $consulta->getResult();
    }

}

?>

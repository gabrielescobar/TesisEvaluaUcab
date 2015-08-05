<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evaluaUcab\EstudianteBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MapaMentalRepository extends EntityRepository {

    public function findMapasByEstudiante($idEstudiante) {
       
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('
    
            SELECT m.id, m.nombre
            FROM EstudianteBundle:MapaMental m JOIN m.estudiante e
            
            WHERE e.id = :idEstudiante
        ');
        $consulta->setParameter('idEstudiante', $idEstudiante);


        return $consulta->getResult();
    }

}

?>

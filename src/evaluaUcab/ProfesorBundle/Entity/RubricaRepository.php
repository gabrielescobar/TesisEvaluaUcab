<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RubricaRepository extends EntityRepository {
    
        public function findRubricasByProf($idProfesor) {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
    
            SELECT r.id, r.titulo
            FROM ProfesorBundle:Rubrica r JOIN r.profesor p
            WHERE p.id = :idProfesor
        ');
        $consulta->setParameter('idProfesor', $idProfesor);


        return $consulta->getResult();
    }
    

}
?>

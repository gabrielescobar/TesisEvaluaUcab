<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MateriaRepository extends EntityRepository
{
   
    public function findAllOrderByName()
    {
        $em = $this->getEntityManager();
 
        
         $consulta = $em->createQuery('
    
            SELECT m
            FROM ProfesorBundle:Materia m
            ORDER BY m.nombre ASC
        ');
        
        return $consulta->getResult();
    }
    

}

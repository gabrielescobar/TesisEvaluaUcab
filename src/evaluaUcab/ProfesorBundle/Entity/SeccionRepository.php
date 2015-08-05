<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SeccionRepository extends EntityRepository
{
   
    public function findSeccionesByProfesor($id)
    {
        $em = $this->getEntityManager();
 
        
         $consulta = $em->createQuery('
            SELECT s
            FROM ProfesorBundle:Seccion s 
            WHERE s.profesor = :id
            ORDER BY s.codigo ASC


        ');
        $consulta->setParameter('id', $id);
        

        return $consulta->getResult();
    }
    
    public function findSeccionesOrderByMateria($idProfesor){
        
        $em = $this->getEntityManager();
 
        
         $consulta = $em->createQuery('
            SELECT m.nombre,s.id,s.codigo
            FROM ProfesorBundle:Seccion s JOIN s.materia m
            WHERE s.profesor = :id
            ORDER BY m.nombre ASC


        ');
        $consulta->setParameter('id', $idProfesor);
        

        return $consulta->getResult();
        
    }
    
    public function findSeccionesByMateria($id)
    {
        $em = $this->getEntityManager();
      
         $consulta = $em->createQuery('
            SELECT s
            FROM ProfesorBundle:Seccion s 
            WHERE s.materia = :id
            ORDER BY s.codigo ASC

        ');
        $consulta->setParameter('id', $id);
      
        

        return $consulta->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
    
    
    public function findEstudiantesEnSeccion($idSeccion)
    {
        $em = $this->getEntityManager();
 
        
         $consulta = $em->createQuery('
    
            SELECT e.id, e.nombre, e.apellido, es.id as idEstudianteSeccion
            FROM ProfesorBundle:EstudianteSeccion es JOIN es.estudiante e JOIN es.seccion s
            WHERE s.id = :idSeccion

            ORDER BY e.nombre ASC
        ');
         
         $consulta->setParameter('idSeccion', $idSeccion);
        
        return $consulta->getResult();
    }
}


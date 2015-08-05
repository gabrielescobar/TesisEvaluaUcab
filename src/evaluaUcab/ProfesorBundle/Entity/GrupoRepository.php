<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\EntityRepository;

class GrupoRepository extends EntityRepository {

    public function findEstudiantesEnSeccionSinGrupo($idSeccion) {
        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT e.id, e.nombre, e.apellido
            FROM ProfesorBundle:EstudianteSeccion es JOIN es.estudiante e JOIN es.seccion s
            WHERE s.id = :idSeccion AND
            e.id NOT IN (SELECT est.id
            FROM ProfesorBundle:EstudianteGrupo eg JOIN eg.estudiante est JOIN eg.seccion sec
            WHERE sec.id = :idSeccion)

            ORDER BY e.nombre ASC
        ');


        $consulta->setParameter('idSeccion', $idSeccion);

        return $consulta->getResult();
    }

    public function findEstudiantesConGrupoEnSeccion($idSeccion) {
        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT e.id
            FROM ProfesorBundle:EstudianteGrupo eg JOIN eg.estudiante e JOIN eg.seccion s
            WHERE s.id = :idSeccion 

            ORDER BY e.nombre ASC
        ');



        $consulta->setParameter('idSeccion', $idSeccion);

        return $consulta->getResult();
    }

    public function findCountGruposEnSeccion($idSeccion) {
        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT count(eg.id)
            FROM ProfesorBundle:EstudianteGrupo eg JOIN eg.grupo g JOIN eg.seccion s
            WHERE s.id = :idSeccion 
        ');



        $consulta->setParameter('idSeccion', $idSeccion);

        return $consulta->getResult();
    }
    
        public function findGruposBySeccion($idSeccion) {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('
    
            SELECT distinct g.id, g.nombre
            FROM ProfesorBundle:EstudianteGrupo eg JOIN eg.grupo g JOIN eg.seccion s
            WHERE s.id = :idSeccion
            
            
        ');



        $consulta->setParameter('idSeccion', $idSeccion);

        return $consulta->getResult();
    }
    
    public function findEstudiantesByGrupo($idGrupo) {
        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT e.id, e.nombre, e.apellido, eg.id as estudianteGrupoId
            FROM ProfesorBundle:EstudianteGrupo eg JOIN eg.estudiante e JOIN eg.grupo g
            WHERE g.id = :idGrupo

            ORDER BY e.apellido ASC
        ');



        $consulta->setParameter('idGrupo', $idGrupo);

        return $consulta->getResult();
    }
    
      public function findCountEstudiantesByGrupo($idGrupo) {
        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT count(e.id)
            FROM ProfesorBundle:EstudianteGrupo eg JOIN eg.estudiante e JOIN eg.grupo g
            WHERE g.id = :idGrupo

        ');



        $consulta->setParameter('idGrupo', $idGrupo);

        return $consulta->getResult();
    }
    
     public function findEstudianteGrupoByEstudianteYSeccion($idSeccion,$idEstudiante) {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('
    
            SELECT eg
            FROM ProfesorBundle:EstudianteGrupo eg JOIN eg.grupo g JOIN eg.estudiante e JOIN eg.seccion s
            WHERE s.id = :idSeccion AND
                  e.id = :idEstudiante     
        ');

        $consulta->setParameter('idSeccion', $idSeccion);
        $consulta->setParameter('idEstudiante', $idEstudiante);


        return $consulta->getResult();
    }

}


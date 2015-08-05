<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AsignacionRepository extends EntityRepository {
    
    
 public function findAsignacionMapasMentalesByEstudiante($idEstudiante,$idEstudianteSeccion,$idPortafolio) {
        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT a
            FROM ProfesorBundle:Asignacion a JOIN a.portafolio p JOIN p.seccion s JOIN s.estudiantes estsec JOIN estsec.estudiante e
            WHERE estsec.id = :idEstudianteSeccion AND
                  e.id = :idEstudiante AND
                  p.id = :idPortafolio AND
                  a.tipo = :tipo AND
                  a.modoPresentacion = :modoPresentacion
        ');


        $consulta->setParameter('idEstudiante', $idEstudiante);
        $consulta->setParameter('idEstudianteSeccion', $idEstudianteSeccion);
        $consulta->setParameter('idPortafolio', $idPortafolio);
        $consulta->setParameter('tipo','mapamental');
        $consulta->setParameter('modoPresentacion',0);
 
        return $consulta->getResult();
    }
    
     public function findAsignacionMapasMentalesByGrupo($idGrupo,$idPortafolio) {
        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT a
            FROM ProfesorBundle:Asignacion a JOIN a.portafolio p JOIN p.seccion s JOIN s.grupos estg JOIN estg.grupo g
            WHERE 
                  
                  p.id = :idPortafolio AND
                  g.id = :idGrupo AND
                  a.tipo = :tipo AND
                  a.modoPresentacion = :modoPresentacion
        ');


        $consulta->setParameter('idGrupo', $idGrupo);
       // $consulta->setParameter('idEstudianteSeccion', $idEstudianteSeccion);
        $consulta->setParameter('idPortafolio', $idPortafolio);
        $consulta->setParameter('tipo','mapamental');
        $consulta->setParameter('modoPresentacion',1);
  
        return $consulta->getResult();
    }
    
}
?>

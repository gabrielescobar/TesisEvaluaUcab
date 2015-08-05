<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class PortafolioRepository extends EntityRepository {

    public function findPortafolios($estudiante) {
        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT p,e,s
            FROM ProfesorBundle:Portafolio p JOIN p.estudiante e JOIN p.seccion s
            WHERE e.cedula = :cedula
        ');
        $consulta->setParameter('cedula', $estudiante);


        return $consulta->getResult();
    }

    public function findMaxDateExamenes($idPortafolio) {

        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT max (ep.fechaCierre) as fechaMaxima
            FROM ProfesorBundle:ExamenPortafolio ep JOIN ep.portafolio p
            WHERE p.id = :id
        ');
        $consulta->setParameter('id', $idPortafolio);


        return $consulta->getResult();
    }

    public function findMaxDateAsignacion($idPortafolio) {

        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT max (a.fechaCierre) as fechaMaxima
            FROM ProfesorBundle:Asignacion a JOIN a.portafolio p
            WHERE p.id = :id
        ');
        $consulta->setParameter('id', $idPortafolio);


        return $consulta->getResult();
    }

    public function findExamenesPresentadosByEstudiante($idPortafolio, $idEstudianteSeccion) {

        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT e.id as idExamen, e.titulo, e.puntaje as valorExamen, exp.porcentaje as porcentajeExamen, exp.id as idExamenPortafolio, h.id as idHistoricoExamen, estsec.id as idEstudianteSeccion, h.nota as notaObtenida
            FROM ProfesorBundle:ExamenPortafolio exp JOIN exp.portafolio p JOIN exp.examen e JOIN exp.historicoExamen h 
            JOIN h.estudianteSeccion estsec
            WHERE p.id = :idPortafolio AND
                  h.estudianteSeccion = :idEstudianteSeccion
        ');
        $consulta->setParameter('idPortafolio', $idPortafolio);
        $consulta->setParameter('idEstudianteSeccion', $idEstudianteSeccion);


        return $consulta->getResult();
    }
    
   public function findAsignacionesPresentadasByEstudiante($idPortafolio, $idEstudianteSeccion, $tipoAsignacion) {

        $em = $this->getEntityManager();
        $consulta = $em->createQuery('
    
            SELECT a.titulo as titulo, a.id as idAsignacion, c.estudianteSeccion as estudianteSeccion,c.total as nota
            FROM ProfesorBundle:Calificacion c JOIN c.asignacion a JOIN a.portafolio p
            
            WHERE p.id = :idPortafolio AND
                  c.estudianteSeccion = :idEstudianteSeccion AND
                  a.tipo = :tipoAsignacion
        ');
        $consulta->setParameter('idPortafolio', $idPortafolio);
        $consulta->setParameter('idEstudianteSeccion', $idEstudianteSeccion);
        $consulta->setParameter('tipoAsignacion', $tipoAsignacion);
        
           

        return $consulta->getResult();
    }

    public function findExamenesPresentadosByGrupo($idPortafolio, $idEstudianteGrupo, $idGrupo) {

        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT e.id as idExamen, e.titulo, exp.id as idExamenPortafolio, h.id as idHistoricoExamen, estgru.id as idEstudianteGrupo, h.nota as notaObtenida
            FROM ProfesorBundle:ExamenPortafolio exp JOIN exp.portafolio p JOIN exp.examen e JOIN exp.historicoExamen h 
            JOIN h.estudianteGrupo estgru JOIN estgru.grupo g
            WHERE p.id = :idPortafolio AND
                
                  g.id = :idGrupo
        ');
        $consulta->setParameter('idPortafolio', $idPortafolio);
        $consulta->setParameter('idGrupo', $idGrupo);

        return $consulta->getResult();
    }

    public function findRubricasByPprof($idProfesor) {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT r.id, r.titulo
            FROM ProfesorBundle:Rubrica r JOIN r.profesor p
            WHERE p.id = :idProfesor
            ORDER BY r.titulo ASC

        ');
        $consulta->setParameter('idProfesor', $idProfesor);

        return $consulta->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
    
        public function findListasByPprof($idProfesor) {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT l.id, l.titulo
            FROM ProfesorBundle:Lista l JOIN l.profesor p
            WHERE p.id = :idProfesor
            ORDER BY l.titulo ASC

        ');
        $consulta->setParameter('idProfesor', $idProfesor);

        return $consulta->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
    
        public function findPresentoExamenByGrupo($idGrupo, $idExamenPortafolio) {

        $em = $this->getEntityManager();


        $consulta = $em->createQuery('
    
            SELECT h.id
            FROM ProfesorBundle:HistoricoExamen h JOIN h.estudianteGrupo estg JOIN estg.grupo g JOIN h.examenPortafolio examp
            WHERE g.id = :idGrupo AND
                  examp.id = :idExamenPortafolio
        ');
        $consulta->setParameter('idGrupo', $idGrupo);
        $consulta->setParameter('idExamenPortafolio', $idExamenPortafolio);


        return $consulta->getResult();
    }
    /* 
     * Muestra los examenes presentados por el estudiante
     * SELECT e.titulo as tituloExamen, h.nota as notaExamen
            FROM ProfesorBundle:HistoricoExamen h JOIN h.examenPortafolio examp
            JOIN examp.portafolio p JOIN p.seccion s JOIN examp.examen e 
            
            JOIN h.estudianteSeccion ests 
           

            WHERE p.id = :idPortafolio AND
                  s.id = :idSeccion AND
                  ests.id = :idEstudiante          
     */
    function findNotasExamenesToEstudiante($idPortafolio,$idSeccion,$idEstudianteSeccion){
        
          $em = $this->getEntityManager();
          
           $consulta = $em->createQuery('
    
            SELECT e.titulo as tituloExamen, h.nota as notaExamen, examp.porcentaje as porcentaje
            FROM ProfesorBundle:HistoricoExamen h JOIN h.examenPortafolio examp
            JOIN examp.portafolio p JOIN p.seccion s JOIN examp.examen e    
            JOIN h.estudianteSeccion ests 

            WHERE p.id = :idPortafolio AND
                  s.id = :idSeccion AND 
                  ests.id = :idEstudianteSeccion               
        ');
        $consulta->setParameter('idPortafolio', $idPortafolio);
        $consulta->setParameter('idSeccion', $idSeccion);
        $consulta->setParameter('idEstudianteSeccion', $idEstudianteSeccion);
        
       
           return $consulta->getResult();

        
    }
    
      function findNotasExamenesToGrupo($idPortafolio,$idSeccion,$idGrupo){
        
          $em = $this->getEntityManager();
          
           $consulta = $em->createQuery('
    
            SELECT e.titulo as tituloExamen, h.nota as notaExamen, examp.porcentaje as porcentaje
            FROM ProfesorBundle:HistoricoExamen h JOIN h.examenPortafolio examp
            JOIN examp.portafolio p JOIN p.seccion s JOIN examp.examen e   
            
            JOIN h.estudianteGrupo estg JOIN estg.estudiante est 
            JOIN estg.grupo g

            WHERE p.id = :idPortafolio AND
                  s.id = :idSeccion AND  
                  g.id = :idGrupo
        ');
        $consulta->setParameter('idPortafolio', $idPortafolio);
        $consulta->setParameter('idSeccion', $idSeccion);
        $consulta->setParameter('idGrupo', $idGrupo);
        
       
           return $consulta->getResult();

        
    }

}

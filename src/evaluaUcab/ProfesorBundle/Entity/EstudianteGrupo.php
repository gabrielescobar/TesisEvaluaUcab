<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * EstudianteGrupo
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class EstudianteGrupo{
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\EstudianteBundle\Entity\Estudiante", inversedBy="grupos")
     *  @ORM\JoinColumn(name="estudiante_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $estudiante;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Grupo", inversedBy="estudiantes")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $grupo;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Seccion", inversedBy="grupos")
     * @ORM\JoinColumn(name="seccion_id", referencedColumnName="id", onDelete="CASCADE") 
     */
    private $seccion;
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\HistoricoExamen", mappedBy="estudianteGrupo")
     * 
     * 
     */
    private $historicoExamen;
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Calificacion", mappedBy="estudianteGrupo")
     * 
     */
    private $calificaciones;
    
   /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\EstudianteBundle\Entity\MapaMental", mappedBy="estudianteGrupo")
     * 
     */
    private $mapasMentales;
    
     /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\EstudianteBundle\Entity\Presentacion", mappedBy="estudianteGrupo")
     * 
     */
    private $presentaciones;
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set estudiante
     *
     * @param \evaluaUcab\EstudianteBundle\Entity\Estudiante $estudiante
     * @return EstudianteGrupo
     */
    public function setEstudiante(\evaluaUcab\EstudianteBundle\Entity\Estudiante $estudiante = null)
    {
        $this->estudiante = $estudiante;
    
        return $this;
    }

    /**
     * Get estudiante
     *
     * @return \evaluaUcab\EstudianteBundle\Entity\Estudiante 
     */
    public function getEstudiante()
    {
        return $this->estudiante;
    }

    /**
     * Set grupo
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Grupo $grupo
     * @return EstudianteGrupo
     */
    public function setGrupo(\evaluaUcab\ProfesorBundle\Entity\Grupo $grupo = null)
    {
        $this->grupo = $grupo;
    
        return $this;
    }

    /**
     * Get grupo
     *
     * @return \evaluaUcab\ProfesorBundle\Entity\Grupo 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set seccion
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Seccion $seccion
     * @return EstudianteGrupo
     */
    public function setSeccion(\evaluaUcab\ProfesorBundle\Entity\Seccion $seccion = null)
    {
        $this->seccion = $seccion;
    
        return $this;
    }

    /**
     * Get seccion
     *
     * @return \evaluaUcab\ProfesorBundle\Entity\Seccion 
     */
    public function getSeccion()
    {
        return $this->seccion;
    }
    
    public function getHistoricoExamen() {
        return $this->historicoExamen;
    }

    public function setHistoricoExamen($historicoExamen) {
        $this->historicoExamen = $historicoExamen;
    }
    
    public function getCalificaciones() {
        return $this->calificaciones;
    }

    public function setCalificaciones($calificaciones) {
        $this->calificaciones = $calificaciones;
    }

    public function getMapasMentales() {
        return $this->mapasMentales;
    }

    public function setMapasMentales($mapasMentales) {
        $this->mapasMentales = $mapasMentales;
    }

    public function getPresentaciones() {
        return $this->presentaciones;
    }

    public function setPresentaciones($presentaciones) {
        $this->presentaciones = $presentaciones;
    }




    
    
}
<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Calificacion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Calificacion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nota", type="text")
     */
    private $nota;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Asignacion", inversedBy="calificaciones")
     * @ORM\JoinColumn(name="asignacion_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */
    private $asignacion;
    
     /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo", inversedBy="calificaciones")
     * @ORM\JoinColumn(name="estudianteGrupo_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */
    private $estudianteGrupo;
    
       /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteSeccion", inversedBy="calificaciones")
     * @ORM\JoinColumn(name="estudianteSeccion_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */
    private $estudianteSeccion;


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
     * Set nota
     *
     * @param string $nota
     * @return Calificacion
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
    
        return $this;
    }

    /**
     * Get nota
     *
     * @return string 
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return Calificacion
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }
    public function getAsignacion() {
        return $this->asignacion;
    }

    public function setAsignacion($asignacion) {
        $this->asignacion = $asignacion;
    }

    public function getEstudianteGrupo() {
        return $this->estudianteGrupo;
    }

    public function setEstudianteGrupo($estudianteGrupo) {
        $this->estudianteGrupo = $estudianteGrupo;
    }

    public function getEstudianteSeccion() {
        return $this->estudianteSeccion;
    }

    public function setEstudianteSeccion($estudianteSeccion) {
        $this->estudianteSeccion = $estudianteSeccion;
    }


    
}

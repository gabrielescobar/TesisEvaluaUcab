<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Evento
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
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle", type="string", length=255)
     */
    private $detalle;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="inicio", type="string", length=255)
     */
    private $inicio;

    /**
     * @var string
     *
     * @ORM\Column(name="fin", type="string", length=255)
     */
    private $fin;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\EstudianteBundle\Entity\Estudiante" , inversedBy="eventos")
     * @ORM\JoinColumn(name="estudiante_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */
    private $estudiante;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Seccion" , inversedBy="eventos")
     * @ORM\JoinColumn(name="seccion_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */
    private $seccion;
    
    
    /**
     * @ORM\OneToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\ExamenPortafolio" , inversedBy="evento")
     * @ORM\JoinColumn(name="examenPortafolio_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $examenPortafolio;
    
    
    /**
     * @ORM\OneToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Asignacion" , inversedBy="evento")
     * @ORM\JoinColumn(name="asignacion_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */
    private $asignacion;
    
    


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
     * Set titulo
     *
     * @param string $titulo
     * @return Evento
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    
        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Evento
     */
    public function setDetalle($descripcion)
    {
        $this->detalle = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Evento
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set inicio
     *
     * @param string $inicio
     * @return Evento
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
    
        return $this;
    }

    /**
     * Get inicio
     *
     * @return string 
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set fin
     *
     * @param string $fin
     * @return Evento
     */
    public function setFin($fin)
    {
        $this->fin = $fin;
    
        return $this;
    }

    /**
     * Get fin
     *
     * @return string 
     */
    public function getFin()
    {
        return $this->fin;
    }
    
    public function getEstudiante() {
        return $this->estudiante;
    }

    public function setEstudiante(evaluaUcab\EstudianteBundle\Entity\Estudiante $estudiante) {
        $this->estudiante = $estudiante;
    }

    public function getSeccion() {
        return $this->seccion;
    }

    public function setSeccion($seccion) {
        $this->seccion = $seccion;
    }
    
    public function getExamenPortafolio() {
        return $this->examenPortafolio;
    }

    public function setExamenPortafolio($examenPortafolio) {
        $this->examenPortafolio = $examenPortafolio;
    }

    public function getAsignacion() {
        return $this->asignacion;
    }

    public function setAsignacion($asignacion) {
        $this->asignacion = $asignacion;
    }

    public function getVisible() {
        return $this->visible;
    }

    public function setVisible($visible) {
        $this->visible = $visible;
    }




}

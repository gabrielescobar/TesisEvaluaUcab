<?php

namespace evaluaUcab\EstudianteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Diario
 *
 * @ORM\Table()
 * @ORM\Entity
 * 
 */
class Diario
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
     * @ORM\Column(name="titulo", type="string", length=150)
     * @Assert\NotBlank()
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text")
     * @Assert\NotBlank(message="Inserte contenido en el diario")
     */
    private $descripcion;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="evaluaUcab\EstudianteBundle\Entity\Estudiante", inversedBy="diarios")
     * @ORM\JoinColumn(name="estudiante_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $estudiante;
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_modificacion", type="date")
     */
    private $fechaModificacion;
    
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Portafolio", inversedBy="diarios")
     * @ORM\JoinColumn(name="portafolio_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $portafolio;
    
    /**
     * Get fechaModificacion
     *
     * @return \DateTime 
     */
    public function getFechaModificacion() {
        return $this->fechaModificacion;
    }
    
    /**
     * Set fecha_modificacion
     *
     * @param \DateTime $fechaModificacion
     * @return Diario
     */
    public function setFechaModificacion($fecha_modificacion) {
        $this->fechaModificacion = $fecha_modificacion;

        return $this;
    }

     public function getEstudiante() {
        return $this->estudiante;
    }
    
     public function setEstudiante(\evaluaUcab\EstudianteBundle\Entity\Estudiante $estudiante)
    {
        $this->estudiante = $estudiante;
    }
        
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
     * @return Diario
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
     * @return Diario
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    public function getPortafolio() {
        return $this->portafolio;
    }

    public function setPortafolio($portafolio) {
        $this->portafolio = $portafolio;
    }


}

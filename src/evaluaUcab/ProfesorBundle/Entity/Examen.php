<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Examen
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Examen {

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
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Pregunta", mappedBy="examen")
     * 
     */
    private $preguntas;
    
    /**
     * @var float 
     * 
     * @ORM\Column(name="puntaje", type="float")
     */
    
    private $puntaje;
    

    
     /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Profesor", inversedBy="examenes")
     * @ORM\JoinColumn(name="profesor_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $profesor;
    
   /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\ExamenPortafolio", mappedBy="examen")
     *
     */
    private $portafolios;
    

    public function __construct() {
        $this->preguntas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }
    
    
    public function getPuntaje() {
        return $this->puntaje;
    }

    public function setPuntaje($puntaje) {
        $this->puntaje = $puntaje;
    }

        /**
     * Set titulo
     *
     * @param string $titulo
     * @return Examen
     */
    public function setTitulo($titulo) {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Examen
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion() {
        return $this->descripcion;
    }
    
    public function getPreguntas() {
        return $this->preguntas;
    }




    /**
     * Add preguntas
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Pregunta $preguntas
     * @return Examen
     */
    public function addPregunta(\evaluaUcab\ProfesorBundle\Entity\Pregunta $preguntas)
    {
        $this->preguntas[] = $preguntas;
    
        return $this;
    }

    /**
     * Remove preguntas
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Pregunta $preguntas
     */
    public function removePregunta(\evaluaUcab\ProfesorBundle\Entity\Pregunta $preguntas)
    {
        $this->preguntas->removeElement($preguntas);
    }
    
    public function getProfesor() {
        return $this->profesor;
    }

    public function setProfesor(\evaluaUcab\ProfesorBundle\Entity\Profesor $profesor) {
        $this->profesor = $profesor;
    }
    
    public function getPortafolios() {
        return $this->portafolios;
    }
   





}
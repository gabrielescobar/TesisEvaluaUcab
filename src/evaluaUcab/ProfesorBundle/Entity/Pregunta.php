<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pregunta
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Pregunta
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
     * @ORM\Column(name="titulo", type="text")
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=50)
     */
    private $tipo;

    /**
     * @var float
     *
     * @ORM\Column(name="calificacion", type="float")
     */
    private $calificacion;
    
   /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Examen", inversedBy="preguntas")
     * @ORM\JoinColumn(name="examen_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $examen;
    
      /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Respuesta", mappedBy="pregunta")
     * */
    private $respuestas;
    
     public function __construct() {
        $this->respuestas = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function getRespuestas() {
        return $this->respuestas;
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
     * @return Pregunta
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
     * Set tipo
     *
     * @param string $tipo
     * @return Pregunta
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
     * Set calificacion
     *
     * @param integer $calificacion
     * @return Pregunta
     */
    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;
    
        return $this;
    }

    /**
     * Get calificacion
     *
     * @return integer 
     */
    public function getCalificacion()
    {
        return $this->calificacion;
    }
    
    public function getExamen() {
        return $this->examen;
    }
    public function setExamen($examen) {
        $this->examen = $examen;
    }




}
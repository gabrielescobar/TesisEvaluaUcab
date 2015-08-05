<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Respuesta
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Respuesta
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
     * @ORM\Column(name="contenido", type="text")
     */
    private $contenido;

    /**
     * @var boolean
     *
     * @ORM\Column(name="validacion", type="integer")
     */
    private $validacion;

    /**
     * @var float
     *
     * @ORM\Column(name="puntaje", type="float")
     */
    private $puntaje;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Pregunta", inversedBy="respuestas")
     * @ORM\JoinColumn(name="pregunta_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $pregunta; 


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
     * Set contenido
     *
     * @param string $contenido
     * @return Respuesta
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    
        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set validacion
     *
     * @param boolean $validacion
     * @return Respuesta
     */
    public function setValidacion($validacion)
    {
        $this->validacion = $validacion;
    
        return $this;
    }

    /**
     * Get validacion
     *
     * @return boolean 
     */
    public function getValidacion()
    {
        return $this->validacion;
    }

    /**
     * Set puntaje
     *
     * @param integer $puntaje
     * @return Respuesta
     */
    public function setPuntaje($puntaje)
    {
        $this->puntaje = $puntaje;
    
        return $this;
    }

    /**
     * Get puntaje
     *
     * @return integer 
     */
    public function getPuntaje()
    {
        return $this->puntaje;
    }
    
    public function setPregunta($pregunta) {
        $this->pregunta = $pregunta;
    }
    public function getPregunta() {
        return $this->pregunta;
    }



}

<?php

namespace evaluaUcab\EstudianteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RespuestaEstudiante
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class RespuestaEstudiante
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
     * @ORM\Column(name="respuesta", type="string", length=255, nullable=true)
     */
    private $respuesta;
    
   /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteSeccion")
     *  @ORM\JoinColumn(name="estudianteSeccion_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $estudianteSeccion;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo")
     *  @ORM\JoinColumn(name="estudianteGrupo_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $estudianteGrupo;

     /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Pregunta")
     * @ORM\JoinColumn(name="pregunta_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $pregunta; 
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\ExamenPortafolio" , inversedBy="respuestasEstudiante")
     * @ORM\JoinColumn(name="examenPortafolio_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $examenPortafolio; 
    
    /**
     * @var float
     *
     * @ORM\Column(name="calificacion", type="float", nullable=true)
     */
    private $calificacion;

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
     * Set respuesta
     *
     * @param string $respuesta
     * @return RespuestaEstudiante
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;
    
        return $this;
    }

    /**
     * Get respuesta
     *
     * @return string 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }
    
    public function getEstudianteSeccion() {
        return $this->estudianteSeccion;
    }

    public function getEstudianteGrupo() {
        return $this->estudianteGrupo;
    }
    
    public function setEstudianteSeccion(\evaluaUcab\ProfesorBundle\Entity\EstudianteSeccion $estudianteSeccion) {
        $this->estudianteSeccion = $estudianteSeccion;
    }

    public function setEstudianteGrupo(\evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo $estudianteGrupo) {
        $this->estudianteGrupo = $estudianteGrupo;
    }

    public function getPregunta() {
        return $this->pregunta;
    }
    public function setPregunta(\evaluaUcab\ProfesorBundle\Entity\Pregunta $pregunta) {
        $this->pregunta = $pregunta;
    }

    public function getExamenPortafolio() {
        return $this->examenPortafolio;
    }

    public function setExamenPortafolio($examenPortafolio) {
        $this->examenPortafolio = $examenPortafolio;
    }

    public function getCalificacion() {
        return $this->calificacion;
    }

    public function setCalificacion($calificacion) {
        $this->calificacion = $calificacion;
    }





}

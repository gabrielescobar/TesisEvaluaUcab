<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExamenPortafolio
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ExamenPortafolio
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
     * @var \DateTime
     *
     * @ORM\Column(name="fechaInicio", type="date")
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCierre", type="date")
     */
    private $fechaCierre;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaCierre", type="time")
     */
    private $horaCierre;
    
   /**
     * @var \DateTime
     *
     * @ORM\Column(name="duracion", type="time")
     */
    private $duracion;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="tipo", type="boolean")
     */
    private $tipo; /*Si es 0 el examen es INDIVIDUAL, si es 1 es GRUPAL*/
    
        /**
     * @var float 
     * 
     * @ORM\Column(name="porcentaje", type="float")
     */
    
    private $porcentaje;
    

   /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Examen", inversedBy="portafolios")
     * @ORM\JoinColumn(name="examen_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $examen;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Portafolio", inversedBy="examenes")
     * @ORM\JoinColumn(name="portafolio_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $portafolio;
    
    /**
     * @ORM\OneToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Evento" , mappedBy="examenPortafolio")
     * 
     * 
     */
    private $evento;
    
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\HistoricoExamen", mappedBy="examenPortafolio")
     * 
     * 
     */
    private $historicoExamen;
    
    
   /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\EstudianteBundle\Entity\RespuestaEstudiante", mappedBy="examenPortafolio")
     **/
    private $respuestasEstudiante;


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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return ExamenPortafolio
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    
        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     * @return ExamenPortafolio
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;
    
        return $this;
    }

    /**
     * Get fechaCierre
     *
     * @return \DateTime 
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }
    
    public function getExamen() {
        return $this->examen;
    }

    public function getPortafolio() {
        return $this->portafolio;
    }

     public function setExamen(\evaluaUcab\ProfesorBundle\Entity\Examen $examen) {
        $this->examen = $examen;
        
    }
    
     public function setPortafolio(\evaluaUcab\ProfesorBundle\Entity\Portafolio $portafolio) {
        $this->portafolio = $portafolio;
    }
    public function getDuracion() {
        return $this->duracion;
    }

    public function setDuracion(\DateTime $duracion) {
        $this->duracion = $duracion;
    }
    
       public function getHoracierre() {
        return $this->horaCierre;
    }

    public function setHoraCierre(\DateTime $horaCierre) {
        $this->horaCierre = $horaCierre;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getEvento() {
        return $this->evento;
    }

    public function setEvento($evento) {
        $this->evento = $evento;
    }
    
    public function getRespuestasEstudiante() {
        return $this->respuestasEstudiante;
    }

    public function setRespuestasEstudiante($respuestasEstudiante) {
        $this->respuestasEstudiante = $respuestasEstudiante;
    }

    public function getHistoricoExamen() {
        return $this->historicoExamen;
    }

    public function setHistoricoExamen($historicoExamen) {
        $this->historicoExamen = $historicoExamen;
    }

    public function getPorcentaje() {
        return $this->porcentaje;
    }

    public function setPorcentaje($porcentaje) {
        $this->porcentaje = $porcentaje;
    }




    
}

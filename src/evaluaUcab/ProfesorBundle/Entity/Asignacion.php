<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asignacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="evaluaUcab\ProfesorBundle\Entity\AsignacionRepository")
 */
class Asignacion
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
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="estatus", type="boolean")
     */
    private $estatus;
    
    /**
     * @var float
     *
     * @ORM\Column(name="porcentaje", type="float")
     */
    private $porcentaje;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Portafolio", inversedBy="asignaciones")
     * @ORM\JoinColumn(name="portafolio_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */
    private $portafolio;
    
      /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaInicio", type="date")
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCierre", type="datetime")
     */
    private $fechaCierre;
 
    
       /**
     * @var boolean
     *
     * @ORM\Column(name="modoPresentacion", type="boolean")
     */
    private $modoPresentacion; /*Si es 0 la asignacion es INDIVIDUAL, si es 1 es GRUPAL*/
    
    
    
    /**
     * @ORM\OneToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Evento" , mappedBy="asignacion")
     * 
     * 
     */
    private $evento;
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Calificacion", mappedBy="asignacion")
     * 
     */
    private $calificaciones;
    
    /**
     * @ORM\OneToOne(targetEntity="evaluaUcab\EstudianteBundle\Entity\MapaMental" , mappedBy="asignacion")
     * 
     * 
     */
    private $mapaMental;
    
    /**
     * @ORM\OneToOne(targetEntity="evaluaUcab\EstudianteBundle\Entity\Presentacion" , mappedBy="asignacion")
     * 
     * 
     */
    private $presentacion;
    
     /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Rubrica", inversedBy="asignaciones")
     *
     */
    private $rubrica;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Lista", inversedBy="asignaciones")
     *
     */
    private $lista;
    
    

    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTime $fechaInicio) {
        $this->fechaInicio = $fechaInicio;
    }

    public function getFechaCierre() {
        return $this->fechaCierre;
    }

    public function setFechaCierre(\DateTime $fechaCierre) {
        $this->fechaCierre = $fechaCierre;
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
     * @return Asignacion
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
     * @return Asignacion
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

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Asignacion
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
    
    public function getPortafolio() {
        return $this->portafolio;
    }
    

    public function getEvento() {
        return $this->evento;
    }

    public function setEvento($evento) {
        $this->evento = $evento;
    }

    public function setPortafolio($portafolio) {
        $this->portafolio = $portafolio;
    }

    public function getCalificaciones() {
        return $this->calificaciones;
    }

    public function setCalificaciones($calificaciones) {
        $this->calificaciones = $calificaciones;
    }

    public function getMapaMental() {
        return $this->mapaMental;
    }

    public function setMapaMental($mapaMental) {
        $this->mapaMental = $mapaMental;
    }

    public function getPresentacion() {
        return $this->presentacion;
    }

    public function setPresentacion($presentacion) {
        $this->presentacion = $presentacion;
    }

    public function getRubrica() {
        return $this->rubrica;
    }

    public function setRubrica($rubrica) {
        $this->rubrica = $rubrica;
    }

    public function getLista() {
        return $this->lista;
    }

    public function setLista($lista) {
        $this->lista = $lista;
    }
    
    public function getPorcentaje() {
        return $this->porcentaje;
    }

    public function setPorcentaje($porcentaje) {
        $this->porcentaje = $porcentaje;
    }
    public function getModoPresentacion() {
        return $this->modoPresentacion;
    }

    public function setModoPresentacion($modoPresentacion) {
        $this->modoPresentacion = $modoPresentacion;
    }

    public function getEstatus() {
        return $this->estatus;
    }

    public function setEstatus($estatus) {
        $this->estatus = $estatus;
    }







}

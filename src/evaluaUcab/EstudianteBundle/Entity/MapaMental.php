<?php

namespace evaluaUcab\EstudianteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MapaMental
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="evaluaUcab\EstudianteBundle\Entity\MapaMentalRepository")
 */
class MapaMental
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
     * @ORM\Column(name="tituloMapamental", type="string", length=255)
     */
    private $tituloMapamental;
    
       /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="text")
     */
    private $contenido;
    
   /**
     * @ORM\OneToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Asignacion" , inversedBy="mapaMental")
     * @ORM\JoinColumn(name="asignacion_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */
    private $asignacion;
    

    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo", inversedBy="mapasMentales")
     * @ORM\JoinColumn(name="estudianteGrupo_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $estudianteGrupo;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteSeccion", inversedBy="mapasMentales")
     * @ORM\JoinColumn(name="estudianteSeccion_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $estudianteSeccion;
    
        /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\EstudianteBundle\Entity\Estudiante", inversedBy="mapasMentales")
     * @ORM\JoinColumn(name="estudiante_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $estudiante;
    
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
     * Set tituloMapamental
     *
     * @param string $tituloMapamental
     * @return MapaMental
     */
    public function setTituloMapamental($tituloMapamental)
    {
        $this->tituloMapamental = $tituloMapamental;
    
        return $this;
    }

    /**
     * Get tituloMapamental
     *
     * @return string 
     */
    public function getTituloMapamental()
    {
        return $this->tituloMapamental;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     * @return MapaMental
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
    
    public function getEstudiante() {
        return $this->estudiante;
    }

    public function setEstudiante($estudiante) {
        $this->estudiante = $estudiante;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }




}

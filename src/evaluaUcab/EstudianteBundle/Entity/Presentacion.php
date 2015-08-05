<?php

namespace evaluaUcab\EstudianteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Presentacion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Presentacion
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
     * @ORM\Column(name="tituloPresentacion", type="string", length=255)
     */
    private $tituloPresentacion;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="text")
     */
    private $contenido;
    
    /**
     * @ORM\OneToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Asignacion" , inversedBy="presentacion")
     * @ORM\JoinColumn(name="asignacion_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */
    private $asignacion;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo", inversedBy="presentaciones")
     * @ORM\JoinColumn(name="estudianteGrupo_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $estudianteGrupo;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteSeccion", inversedBy="presentaciones")
     * @ORM\JoinColumn(name="estudianteSeccion_id", referencedColumnName="id", onDelete="CASCADE")
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
     * Set tituloPresentacion
     *
     * @param string $tituloPresentacion
     * @return Presentacion
     */
    public function setTituloPresentacion($tituloPresentacion)
    {
        $this->tituloPresentacion = $tituloPresentacion;
    
        return $this;
    }

    /**
     * Get tituloPresentacion
     *
     * @return string 
     */
    public function getTituloPresentacion()
    {
        return $this->tituloPresentacion;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     * @return Presentacion
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


}

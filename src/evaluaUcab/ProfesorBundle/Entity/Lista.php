<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Lista
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Lista
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
     * @ORM\Column(name="contenido", type="text")
     */
    private $contenido;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal")
     */
    private $total;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Profesor", inversedBy="listas")
     * @ORM\JoinColumn(name="profesor_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $profesor;
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Asignacion", mappedBy="lista")
     * 
     */
    private $asignaciones;


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
     * @return Lista
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
     * Set contenido
     *
     * @param string $contenido
     * @return Lista
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
     * Set total
     *
     * @param string $total
     * @return Lista
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return string 
     */
    public function getTotal()
    {
        return $this->total;
    }
    
    public function getProfesor() {
        return $this->profesor;
    }

    public function setProfesor($profesor) {
        $this->profesor = $profesor;
    }

    public function getAsignaciones() {
        return $this->asignaciones;
    }

    public function setAsignaciones($asignaciones) {
        $this->asignaciones = $asignaciones;
    }


}

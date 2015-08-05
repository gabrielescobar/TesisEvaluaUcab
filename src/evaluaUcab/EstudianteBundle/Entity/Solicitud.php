<?php

namespace evaluaUcab\EstudianteBundle\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Solicitud
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="evaluaUcab\EstudianteBundle\Entity\SolicitudRepository")
 * @UniqueEntity(fields = {"materia", "seccion"})
 */
class Solicitud {

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
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     *
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Seccion")
     * @ORM\JoinColumn(name="seccion_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $seccion;

    /**
     *
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Profesor")
     */
    private $profesor;

    /**
     *
     * @ORM\ManyToOne(targetEntity="evaluaUcab\EstudianteBundle\Entity\Estudiante")
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
     * Set status
     *
     * @param string $status
     * @return Solicitud
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set seccion
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Seccion $seccion
     * @return Solicitud
     */
    public function setSeccion(\evaluaUcab\ProfesorBundle\Entity\Seccion $seccion = null)
    {
        $this->seccion = $seccion;
    
        return $this;
    }

    /**
     * Get seccion
     *
     * @return \evaluaUcab\ProfesorBundle\Entity\Seccion 
     */
    public function getSeccion()
    {
        return $this->seccion;
    }

    /**
     * Set profesor
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Profesor $profesor
     * @return Solicitud
     */
    public function setProfesor(\evaluaUcab\ProfesorBundle\Entity\Profesor $profesor = null)
    {
        $this->profesor = $profesor;
    
        return $this;
    }

    /**
     * Get profesor
     *
     * @return \evaluaUcab\ProfesorBundle\Entity\Profesor 
     */
    public function getProfesor()
    {
        return $this->profesor;
    }

    /**
     * Set estudiante
     *
     * @param \evaluaUcab\EstudianteBundle\Entity\Estudiante $estudiante
     * @return Solicitud
     */
    public function setEstudiante(\evaluaUcab\EstudianteBundle\Entity\Estudiante $estudiante = null)
    {
        $this->estudiante = $estudiante;
    
        return $this;
    }

    /**
     * Get estudiante
     *
     * @return \evaluaUcab\EstudianteBundle\Entity\Estudiante 
     */
    public function getEstudiante()
    {
        return $this->estudiante;
    }
}
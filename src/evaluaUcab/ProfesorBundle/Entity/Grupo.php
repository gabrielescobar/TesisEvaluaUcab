<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grupo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="evaluaUcab\ProfesorBundle\Entity\GrupoRepository")
 */
class Grupo
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
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;
       
     /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo", mappedBy="grupo")
     **/
    private $estudiantes;

    public function __construct() {
        $this->estudiantes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get estudiantes
     * 
     */
    public function getEstudiantes()
    {
        return $this->estudiantes;
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
     * Set nombre
     *
     * @param string $nombre
     * @return Grupo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add estudiantes
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo $estudiantes
     * @return Grupo
     */
    public function addEstudiante(\evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo $estudiantes)
    {
        $this->estudiantes[] = $estudiantes;
    
        return $this;
    }

    /**
     * Remove estudiantes
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo $estudiantes
     */
    public function removeEstudiante(\evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo $estudiantes)
    {
        $this->estudiantes->removeElement($estudiantes);
    }
}
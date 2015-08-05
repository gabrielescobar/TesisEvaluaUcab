<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Seccion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="evaluaUcab\ProfesorBundle\Entity\SeccionRepository")
 * @UniqueEntity(fields = {"codigo", "materia"}, message="Esta sección ya está registrada")
 */
class Seccion {

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
     * @ORM\Column(name="codigo", type="string", length=100)
     */
    private $codigo;

    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Profesor", inversedBy="secciones")
     * 
     */
    private $profesor;

    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Materia")
     *  
     */
    private $materia;

    /**
     * @ORM\OneToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Portafolio" , mappedBy="seccion")
     * 
     * 
     */
    private $portafolio;

    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Evento" , mappedBy="seccion")
     * 
     * 
     */
    private $eventos;

    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo" , mappedBy="seccion")
     * 
     * 
     */
    private $grupos;

   /**
     *@ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteSeccion", mappedBy="seccion")
     * 
     */
    private $estudiantes;

    public function __construct() {
        $this->portafolios = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->grupos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estudiantes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set materia
     *
     * @param evaluaUcab\ProfesorBundle\Entity\Materia $materia
     */
    public function setMateria(\evaluaUcab\ProfesorBundle\Entity\Materia $materia) {
        $this->materia = $materia;
    }

    /**
     * Set profesor
     *
     * @param evaluaUcab\ProfesorBundle\Entity\Profesor $profesor
     */
    public function setProfesor(\evaluaUcab\ProfesorBundle\Entity\Profesor $profesor) {
        $this->profesor = $profesor;
    }

    /**
     * Get profesor
     *
     */
    public function getProfesor() {
        return $this->profesor;
    }

//    /**
//     * Get eventos
//     *
//     */
//    public function getEventos() {
//        return $this->eventos;
//    }

    /**
     * Get materia
     *
     */
    public function getMateria() {
        return $this->materia;
    }
    
    /**
     * Get grupos
     *
     */
    public function getGrupos() {
        return $this->grupos;
    }
    
    /**
     * Get grupos
     *
     */
    public function getEstudiantes() {
        return $this->estudiantes;
    }
    
    public function getPortafolio() {
        return $this->portafolio;
    }

        

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Seccion
     */
    public function setCodigo($codigo) {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo() {
        return $this->codigo;
    }


    /**
     * Set portafolio
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Portafolio $portafolio
     * @return Seccion
     */
    public function setPortafolio(\evaluaUcab\ProfesorBundle\Entity\Portafolio $portafolio = null)
    {
        $this->portafolio = $portafolio;
    
        return $this;
    }

    /**
     * Add eventos
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Evento $eventos
     * @return Seccion
     */
    public function addEvento(\evaluaUcab\ProfesorBundle\Entity\Evento $evento)
    {
        $this->eventos[] = $evento;
    
        return $this;
    }

    /**
     * Remove eventos
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Evento $eventos
     */
    public function removeEvento(\evaluaUcab\ProfesorBundle\Entity\Evento $evento)
    {
        $this->eventos->removeElement($evento);
    }

    /**
     * Add grupos
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo $grupos
     * @return Seccion
     */
    public function addGrupo(\evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo $grupo)
    {
        $this->grupos[] = $grupo;
    
        return $this;
    }

    /**
     * Remove grupos
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo $grupos
     */
    public function removeGrupo(\evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo $grupo)
    {
        $this->grupos->removeElement($grupo);
    }

    
    public function __toString()
{
    return (string) $this->getMateria()->getNombre().'-'.$this->getCodigo();
}
}
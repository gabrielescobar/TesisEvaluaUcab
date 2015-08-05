<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricoExamen
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class HistoricoExamen
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
     * @var integer
     *
     * @ORM\Column(name="nota", type="integer")
     */
    private $nota;
    
    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteSeccion", inversedBy="historicoExamen")
     * @ORM\JoinColumn(name="estudianteSeccion_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $estudianteSeccion;

       /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo", inversedBy="historicoExamen")
     * @ORM\JoinColumn(name="estudianteGrupo_id", referencedColumnName="id", onDelete="CASCADE") 
     */
    private $estudianteGrupo;
    
   /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\ExamenPortafolio", inversedBy="historicoExamen")
     * @ORM\JoinColumn(name="examenPortafolio_id", referencedColumnName="id", onDelete="CASCADE") 
     */
    private $examenPortafolio;
    


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
     * Set nota
     *
     * @param integer $nota
     * @return HistoricoExamen
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
    
        return $this;
    }

    /**
     * Get nota
     *
     * @return integer 
     */
    public function getNota()
    {
        return $this->nota;
    }
    
    public function getEstudianteSeccion() {
        return $this->estudianteSeccion;
    }

    public function setEstudianteSeccion($estudianteSeccion = null ) {
        $this->estudianteSeccion = $estudianteSeccion;
    }

    public function getEstudianteGrupo() {
        return $this->estudianteGrupo;
    }

    public function setEstudianteGrupo($estudianteGrupo) {
        $this->estudianteGrupo = $estudianteGrupo;
    }

    public function getExamenPortafolio() {
        return $this->examenPortafolio;
    }

    public function setExamenPortafolio(\evaluaUcab\ProfesorBundle\Entity\ExamenPortafolio $examenPortafolio = null) {
        $this->examenPortafolio = $examenPortafolio;
    }



}

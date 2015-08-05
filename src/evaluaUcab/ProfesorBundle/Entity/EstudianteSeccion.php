<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstudianteSeccion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class EstudianteSeccion
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
     *
     * @ORM\ManyToOne(targetEntity="evaluaUcab\EstudianteBundle\Entity\Estudiante", inversedBy="secciones")
     * @ORM\JoinColumn(name="estudiante_id", referencedColumnName="id", onDelete="CASCADE") 
     */
    private $estudiante;
    
    
   /**
     *
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Seccion", inversedBy="estudiantes")
     * @ORM\JoinColumn(name="seccion_id", referencedColumnName="id", onDelete="CASCADE") 
     */
    private $seccion;
    
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\HistoricoExamen", mappedBy="estudianteSeccion")
     * 
     * 
     */
    private $historicoExamen;
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Calificacion", mappedBy="estudianteSeccion")
     * 
     */
    private $calificaciones;
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\EstudianteBundle\Entity\MapaMental", mappedBy="estudianteSeccion")
     * 
     */
    private $mapasMentales;
    
   /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\EstudianteBundle\Entity\Presentacion", mappedBy="estudianteSeccion")
     * 
     */
    private $presentaciones;
    
    
   

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getEstudiante() {
        return $this->estudiante;
    }

    public function getSeccion() {
        return $this->seccion;
    }

    public function setEstudiante(\evaluaUcab\EstudianteBundle\Entity\Estudiante $estudiante) {
        $this->estudiante = $estudiante;
    }

    public function setSeccion(\evaluaUcab\ProfesorBundle\Entity\Seccion $seccion) {
        $this->seccion = $seccion;
    }
    
    public function getHistoricoExamen() {
        return $this->historicoExamen;
    }

    public function setHistoricoExamen($historicoExamen) {
        $this->historicoExamen = $historicoExamen;
    }
    
    public function getCalificaciones() {
        return $this->calificaciones;
    }

    public function setCalificaciones($calificaciones) {
        $this->calificaciones = $calificaciones;
    }

    public function getMapasMentales() {
        return $this->mapasMentales;
    }

    public function setMapasMentales($mapasMentales) {
        $this->mapasMentales = $mapasMentales;
    }

    public function getPresentaciones() {
        return $this->presentaciones;
    }

    public function setPresentaciones($presentaciones) {
        $this->presentaciones = $presentaciones;
    }







 
}

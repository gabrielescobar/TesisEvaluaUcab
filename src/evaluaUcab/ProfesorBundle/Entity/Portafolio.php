<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Portafolio
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="evaluaUcab\ProfesorBundle\Entity\PortafolioRepository")
 * @UniqueEntity(fields="seccion", message="Ya fue creado un portafolio para esta sección")
 */
class Portafolio {

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
     * @ORM\Column(name="tipo", type="string", length=50)
     */
    private $tipo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_cierre", type="date")
     */
    private $fechaCierre;

    /**
     * @ORM\OneToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Seccion" , inversedBy="portafolio")
     * @ORM\JoinColumn(name="seccion_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $seccion;

    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Asignacion", mappedBy="portafolio")
     */
    private $asignaciones;

    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\ExamenPortafolio", mappedBy="portafolio")
     *
     */
    private $examenes;

    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\EstudianteBundle\Entity\Diario", mappedBy="portafolio")
     *
     */
    private $diarios;
    
        /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\MaterialApoyo", mappedBy="portafolio")
     *
     */
    private $materiales;
    
    

    public function __construct() {
        $this->asignaciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get seccion
     *
     * @return seccion
     */
    public function getSeccion() {
        return $this->seccion;
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
     * Set tipo
     *
     * @param string $tipo
     * @return Portafolio
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Portafolio
     */
    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     * @return Portafolio
     */
    public function setFechaCierre($fechaCierre) {
        $this->fechaCierre = $fechaCierre;

        return $this;
    }

    public function getDiarios() {
        return $this->diarios;
    }

    public function setDiarios($diarios) {
        $this->diarios = $diarios;
    }

    /**
     * Get fechaCierre
     *
     * @return \DateTime 
     */
    public function getFechaCierre() {
        return $this->fechaCierre;
    }

    public function setSeccion(\evaluaUcab\ProfesorBundle\Entity\Seccion $seccion) {
        $this->seccion = $seccion;
    }

    public function getAsignaciones() {
        return $this->asignaciones;
    }

    public function getExamenes() {
        return $this->examenes;
    }
    
    public function getMateriales() {
        return $this->materiales;
    }

    public function setMateriales($materiales) {
        $this->materiales = $materiales;
    }

    
    protected function getUploadRootDir() {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__ . '/../../../../web/uploads/portafolios/' . $this->getId();
    }

    protected function getUploadRootDirPdf() {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__ . '/../../../../web/uploads/portafolios/' . $this->getId() . '/pdf';
    }

    protected function getUploadRootDirImg() {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__ . '/../../../../web/uploads/portafolios/' . $this->getId() . '/img';
    }

    protected function getUploadDir() {

        return 'uploads/portafolios/' . $this->getId();
    }

    public function onCreatePortafolio() {
        /* Se crea la carpeta con el id del portafolio */
        if (!file_exists($this->getUploadRootDir())) {
            mkdir($this->getUploadRootDir(), 0777, true);
            mkdir($this->getUploadRootDirPdf(), 0777, true);
            mkdir($this->getUploadRootDirImg(), 0777, true);
        }

    }

}
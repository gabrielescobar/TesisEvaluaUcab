<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * MaterialApoyo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class MaterialApoyo {

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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="evaluaUcab\ProfesorBundle\Entity\Portafolio", inversedBy="materiales")
     * @ORM\JoinColumn(name="portafolio_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $portafolio;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return MaterialApoyo
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    public function getPortafolio() {
        return $this->portafolio;
    }

    public function setPortafolio($portafolio) {
        $this->portafolio = $portafolio;
    }

    protected function getUploadRootDirPdf($id) {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__ . '/../../../../web/uploads/portafolios/' . $id . '/pdf';
    }

    protected function getUploadRootDirImg($id) {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__ . '/../../../../web/uploads/portafolios/' . $id . '/img';
    }

    public function validateExtension() {
        $extensions = array("jpeg", "jpg", "gif", "png", "pdf");
        $var = $this->getFile()->getClientOriginalExtension();

        foreach ($extensions as $ext) {
            if ($var === $ext)
                return true;
        }
        return false;
    }

    public function upload($idPortafolio) {
        /* Se crea la carpeta con la cedula del estudiante */

        /* Se mueve el archivo a la carpeta creada anteriormente */
        $this->getFile()->move($this->getUploadRootDirPdf($idPortafolio), $this->getFile()->getClientOriginalName());
        $this->setNombre($this->getFile()->getClientOriginalName());

        $this->file = null;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

}

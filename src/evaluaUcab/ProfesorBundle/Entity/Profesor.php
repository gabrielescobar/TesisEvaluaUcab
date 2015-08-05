<?php

namespace evaluaUcab\ProfesorBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Profesor
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Profesor implements UserInterface, \Serializable {

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
     * @ORM\Column(name="cedula", type="integer")
     */
    private $cedula;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=100)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=100)
     */
    private $correo;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=100)
     */
    private $foto;

    /**
     * @var string
     * 
     * @ORM\Column(name="salt", type="string", length=100)
     */
    private $salt;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Seccion", mappedBy="profesor")
     * 
     * 
     */
    private $secciones;
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Examen", mappedBy="profesor")
     * 
     * 
     */
    private $examenes;
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Lista", mappedBy="profesor")
     * 
     * 
     */
    private $listas;
    
        /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Rubrica", mappedBy="profesor")
     * 
     * 
     */
    private $rubricas;

    public function __construct() {
        $this->secciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->examenes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getExamenes() {
        return $this->examenes;
    }

    public function setExamenes($examenes) {
        $this->examenes = $examenes;
    }

    /**
     * Get secciones
     * 
     */
    public function getSecciones() {
        return $this->secciones;
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
     * Set cedula
     *
     * @param integer $cedula
     * @return Profesor
     */
    public function setCedula($cedula) {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return integer 
     */
    public function getCedula() {
        return $this->cedula;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Profesor
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
     * Set apellido
     *
     * @param string $apellido
     * @return Profesor
     */
    public function setApellido($apellido) {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido() {
        return $this->apellido;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return Profesor
     */
    public function setCorreo($correo) {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string 
     */
    public function getCorreo() {
        return $this->correo;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Profesor
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set foto
     *
     * @param string $foto
     * @return Profesor
     */
    public function setFoto($foto) {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt) {
        $this->salt = $salt;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto() {
        return $this->foto;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {

        return array('ROLE_PROFESOR');
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->getCorreo();
    }

    public function serialize() {
        return serialize(array(
            $this->id,
        ));
    }

    public function unserialize($serialized) {
        list (
                $this->id,
                ) = unserialize($serialized);
    }

    public function getAbsolutePath() {
        return null === $this->foto ? null : $this->getUploadRootDir() . '/' . $this->foto;
    }

    public function getWebPath() {
        return null === $this->foto ? null : $this->getUploadDir() . '/' . $this->foto;
    }

    protected function getUploadRootDir() {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__ . '/../../../../web/uploads/profesores/' . $this->getCedula();
    }

    protected function getUploadDir() {

        return 'uploads/profesores/' . $this->getCedula();
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

    public function upload() {
        /* Se crea la carpeta con la cedula del estudiante */
        if (!file_exists($this->getUploadRootDir())) {
            mkdir($this->getUploadRootDir(), 0777, true);
        }
        //Si el file es null, entonces la foto será defaultPerfil.png
        if ((null === $this->getFile())) {
              if ($this->getFoto() ==''){
                $this->setFoto('defaultPerfil.png');
              }
            return;
        }

        /* Se mueve el archivo a la carpeta creada anteriormente */
        $this->getFile()->move($this->getUploadRootDir(), $this->getFile()->getClientOriginalName());
        $this->setFoto($this->getFile()->getClientOriginalName());

        $this->file = null;
    }

    public function __toString() {
        return $this->getNombre() . ' ' . $this->getApellido();
    }


    /**
     * Add secciones
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Seccion $secciones
     * @return Profesor
     */
    public function addSeccione(\evaluaUcab\ProfesorBundle\Entity\Seccion $secciones)
    {
        $this->secciones[] = $secciones;
    
        return $this;
    }

    /**
     * Remove secciones
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Seccion $secciones
     */
    public function removeSeccione(\evaluaUcab\ProfesorBundle\Entity\Seccion $secciones)
    {
        $this->secciones->removeElement($secciones);
    }
    
    public function getListas() {
        return $this->listas;
    }

    public function setListas($listas) {
        $this->listas = $listas;
    }

    public function getRubricas() {
        return $this->rubricas;
    }

    public function setRubricas($rubricas) {
        $this->rubricas = $rubricas;
    }


}
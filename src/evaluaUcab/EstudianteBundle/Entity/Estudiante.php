<?php

namespace evaluaUcab\EstudianteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;


//use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Estudiante
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="evaluaUcab\EstudianteBundle\Entity\EstudianteRepository")
 * @UniqueEntity(fields="correo", message="Este correo ya fue registrado")
 * @UniqueEntity(fields="cedula", message="Esta cédula ya fue registrada")
 */
class Estudiante implements UserInterface, \Serializable {

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
     * @Assert\NotBlank()
     */
    private $cedula;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $apellido;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nacimiento", type="date")
     * @Assert\NotBlank()
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=100)
     * @Assert\NotBlank()
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
     * @ORM\Column(name="salt", type="string", length=100)
     */
    private $salt;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo", mappedBy="estudiante")
     **/
    private $grupos;
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\EstudianteBundle\Entity\Diario" , mappedBy="estudiante")
     * 
     * 
     */
    private $diarios;
    
    /**
     * @ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\Evento" , mappedBy="estudiante")
     * 
     * 
     */
    private $eventos;
    
    /**
     *@ORM\OneToMany(targetEntity="evaluaUcab\ProfesorBundle\Entity\EstudianteSeccion", mappedBy="estudiante")
     * 
     */
    private $secciones;
    
     /**
     *@ORM\OneToMany(targetEntity="evaluaUcab\EstudianteBundle\Entity\MapaMental", mappedBy="estudiante")
     * 
     */
    private $mapasMentales;
 
    
     
    public function __construct() {
        $this->grupos = new \Doctrine\Common\Collections\ArrayCollection();
       // $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->diarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->secciones = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
     public function getGrupos() {
        return $this->grupos;
    }
    
     public function getEventos() {
        return $this->eventos;
    }
    
     public function getPortafolios() {
        return $this->portafolios;
    }
    
     public function getDiarios() {
        return $this->diarios;
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
     * @return Estudiante
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
     * @return Estudiante
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
     * @return Estudiante
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
     * Set fecha_nacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Estudiante
     */
    public function setFechaNacimiento($fecha_nacimiento) {
        $this->fechaNacimiento = $fecha_nacimiento;

        return $this;
    }

    /**
     * Get fecha_nacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return Estudiante
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
     * @return Estudiante
     */
    public function setPassword($password) {
        $this->password = $password;

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
     * @return Estudiante
     */
    public function setFoto($foto) {
        $this->foto = $foto;

        return $this;
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
        return array('ROLE_ESTUDIANTE');
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
    
    public function getAbsolutePath()
    {
        return null === $this->foto
            ? null
            : $this->getUploadRootDir().'/'.$this->foto;
    }

    public function getWebPath()
    {
        return null === $this->foto
            ? null
            : $this->getUploadDir().'/'.$this->foto;
    }

    protected function getUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__.'/../../../../web/uploads/estudiantes/'.$this->getCedula();
    }
    
    protected  function getUploadDir(){
        
        return 'uploads/estudiantes/'.$this->getCedula();
    }



    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
  
    
    public function upload()
{
    /*Se crea la carpeta con la cedula del estudiante*/    
    if (!file_exists($this->getUploadRootDir())){
        mkdir($this->getUploadRootDir(), 0777, true);
    }
    //Si el file es null, entonces la foto será defaultPerfil.png
    if (null === $this->getFile()) {
         if($this->getFoto()=== null){
             if ($this->getFoto() =='')
               $this->setFoto('defaultPerfil.png');
            return;
         }else{
             return;
         }
     }

    /*Se mueve el archivo a la carpeta creada anteriormente*/
    $this->getFile()->move($this->getUploadRootDir(),$this->getFile()->getClientOriginalName());
    $this->setFoto($this->getFile()->getClientOriginalName());
    
    $this->file = null;
}

 public function __toString() {
        return $this->getNombre() . ' ' . $this->getApellido();
    }


    /**
     * Add grupos
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\EstudianteGrupo $grupos
     * @return Estudiante
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

    /**
     * Add diarios
     *
     * @param \evaluaUcab\EstudianteBundle\Entity\Diario $diarios
     * @return Estudiante
     */
    public function addDiario(\evaluaUcab\EstudianteBundle\Entity\Diario $diarios)
    {
        $this->diarios[] = $diarios;
    
        return $this;
    }

    /**
     * Remove diarios
     *
     * @param \evaluaUcab\EstudianteBundle\Entity\Diario $diarios
     */
    public function removeDiario(\evaluaUcab\EstudianteBundle\Entity\Diario $diario)
    {
        $this->diarios->removeElement($diario);
    }

    /**
     * Add eventos
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Evento $eventos
     * @return Estudiante
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
     * Remove secciones
     *
     * @param \evaluaUcab\ProfesorBundle\Entity\Seccion $secciones
     */
    public function removeSeccion(\evaluaUcab\ProfesorBundle\Entity\Seccion $seccione)
    {
        $this->secciones->removeElement($seccione);
    }

    /**
     * Get secciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSecciones()
    {
        return $this->secciones;
    }
    
    public function getMapasMentales() {
        return $this->mapasMentales;
    }

    public function setMapasMentales($mapasMentales) {
        $this->mapasMentales = $mapasMentales;
    }


}
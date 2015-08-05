<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace evaluaUcab\EstudianteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use evaluaUcab\EstudianteBundle\Entity\Estudiante;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Estudiantes extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface{
    
        public function getOrder()
    {
        return 3;
    }
    
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
     public function load(ObjectManager $manager)
    {
      for ($i=1; $i<=3; $i++) {
          $estudiante = new Estudiante();
          $estudiante->setNombre('estudiante'.$i);
          
          $estudiante->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
          
          $passwordEnClaro = 'clave'.$i;
          $encoder = $this->container->get('security.encoder_factory')->getEncoder($estudiante);
          $passwordCodificado = $encoder->encodePassword($passwordEnClaro, $estudiante->getSalt());
          $estudiante->setPassword($passwordCodificado);
          
     
          $estudiante->setApellido('apellido'.$i);
          $estudiante->setCedula($i);
          $estudiante->setCorreo('l'.$i.'@gmail.com');
          $estudiante->setFoto('foto'.$i);
          
          $estudiante->setFecha_nacimiento(new \DateTime('1990-05-10 18:35:50'));
          
          $manager->persist($estudiante);
      }
        $manager->flush();
    }

  
}
?>

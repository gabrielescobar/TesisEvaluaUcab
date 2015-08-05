<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace evaluaUcab\ProfesorBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use evaluaUcab\ProfesorBundle\Entity\Profesor;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Profesores extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface{
    
        public function getOrder()
    {
        return 1;
    }
    
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
     public function load(ObjectManager $manager)
    {
      for ($i=1; $i<=3; $i++) {
          $profesor = new Profesor();
          $profesor->setNombre('profesor'.$i);
          
          $profesor->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
          
          $passwordEnClaro = 'clave'.$i;
          $encoder = $this->container->get('security.encoder_factory')->getEncoder($profesor);
          $passwordCodificado = $encoder->encodePassword($passwordEnClaro, $profesor->getSalt());
          $profesor->setPassword($passwordCodificado);
          
     
          $profesor->setApellido('apellido'.$i);
          $profesor->setCedula($i);
          $profesor->setCorreo('p'.$i.'@gmail.com');
          $profesor->setFoto('foto'.$i);
          
       
          $manager->persist($profesor);
      }
        $manager->flush();
    }

  
}
?>

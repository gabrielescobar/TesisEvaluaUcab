<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evaluaUcab\ProfesorBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use evaluaUcab\ProfesorBundle\Entity\Materia;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Materias extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 2;
    }

    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        $materia1 = new Materia();
        $materia2 = new Materia();
        $materia3 = new Materia();
        $materia4 = new Materia();
        
        $materia1->setNombre("Trigonometría");
        $materia1->setDescripcion("Materia de trigonometría");
        $materia2->setNombre("Cálculo I");
        $materia2->setDescripcion("Materia de calculo I");
        $materia3->setNombre("Cálculo II");
        $materia3->setDescripcion("Materia de calculo II");
        $materia4->setNombre("Matemática Básica");
        $materia4->setDescripcion("Materia de matemática básica");
        
        $manager->persist($materia1);
        $manager->persist($materia2);
        $manager->persist($materia3);
        $manager->persist($materia4);

        $manager->flush();
    }

}

?>

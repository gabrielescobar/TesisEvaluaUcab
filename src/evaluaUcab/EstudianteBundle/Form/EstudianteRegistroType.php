<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evaluaUcab\EstudianteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EstudianteRegistroType extends AbstractType {

    public function getName() {
        return 'evaluaUcab_estudiantebundle_estudiantetype';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        /*Por defecto todos los campos son requeridos en Symfony2*/
       /*validacion de fecha, maximo el dia de hoy*/
        $date = new \DateTime('today');
        $var = $date->format('Y-m-d');
        
        $builder
                ->add('nombre','text',array('label'=>false))
                ->add('apellido','text',array('label'=>false))
                ->add('cedula','text',array('label'=>false))
                ->add('fechaNacimiento','date',array(
                            'widget' => 'single_text',
                            'format' => 'yyyy-MM-dd',
                            'attr'=> array('max'=>$var)))
                ->add('correo','email',array('label'=>false))
                ->add('password','password',array('label'=>false))
                ->add('file','file',array('required'=>false));    
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'evaluaUcab\EstudianteBundle\Entity\Estudiante'
        ));
    }

}

?>

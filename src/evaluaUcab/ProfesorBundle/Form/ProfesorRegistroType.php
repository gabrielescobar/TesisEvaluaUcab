<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evaluaUcab\ProfesorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfesorRegistroType extends AbstractType {

    public function getName() {
        return 'evaluaUcab_profesorbundle_profesortype';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        /*Por defecto todos los campos son requeridos en Symfony2*/
        $builder
                ->add('nombre','text',array('label'=>false))
                ->add('apellido','text',array('label'=>false))
                ->add('cedula','text',array('label'=>false))
                ->add('correo','email',array('label'=>false))
                ->add('password','password',array('label'=>false))
                ->add('file','file',array('required'=>false));    
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'evaluaUcab\ProfesorBundle\Entity\Profesor'
        ));
    }

}

?>

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace evaluaUcab\EstudianteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DiarioType extends AbstractType {

    public function getName() {
        return 'evaluaUcab_estudiantebundle_diariotype';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        /*Por defecto todos los campos son requeridos en Symfony2*/
        
      
        $builder
             ->add('titulo')
                ->add('descripcion', 'ckeditor', array(
                    'config' => array(                           
                        
                        'resize_dir' => 'disable',
                        'fontSize_defaultLabel' => 'Tamaño',
                        'extraPlugins' => 'ckeditor_wiris'
                    ),
      
                )); 
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'evaluaUcab\EstudianteBundle\Entity\Diario'
        ));
    }

}

?>

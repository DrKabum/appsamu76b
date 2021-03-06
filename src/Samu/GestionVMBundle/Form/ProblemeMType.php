<?php

namespace Samu\GestionVMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProblemeMType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',          'text', array(
                'label' => 'Titre'))
            ->add('content',        'textarea', array(
                'label' => 'Description du problème'))
            ->add('dateDebut',      'date', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label'  => 'Date de survenue du problème'))
            ->add('materiel',       'entity', array(
                'class'    => 'SamuGestionVMBundle:Materiel',
                'property' => 'name',
                'group_by' => 'category',
                'multiple' => false,
                'label'    => 'Materiel'))
            ->add('Envoyer',        'submit')
            ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Samu\GestionVMBundle\Entity\ProblemeVM'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'samu_gestionvmbundle_problemevm';
    }
}

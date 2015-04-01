<?php

namespace Samu\GestionVMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VehiculeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',            'text', array(
                'label'    => 'Indicatif',
            ))
            ->add('immatriculation', 'text', array(
                'label'    => 'Immatriculation',
            ))
            ->add('modele',          'text', array(
                'label'    => 'Modèle',
            ))
            ->add('annee',           'date', array(
                'label'    => 'Année d\'acquisition',
                'widget'   => 'choice',
                'years'    => range(date('Y') - 10, date('Y'))
            ))
            ->add('ordreDepart',     'number', array(
                'label'    => 'Ordre de départ'
            ))
            ->add('typeVehicule',    'choice', array(
                'label'    => 'Type de véhicule',
                'choices'  => array('u' => 'UMH', 'v' => 'VML', 'm' => 'Moto', 'a' => 'Autre'),
                'required' => true))
            ->add('operationnel',    'checkbox', array(
                'label'    => 'Véhicule armé/opérationnel',
                'required' => false ))
            ->add('Envoyer',         'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Samu\GestionVMBundle\Entity\Vehicule'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'samu_gestionvmbundle_vehicule';
    }
}

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
            ->add('name',            'text')
            ->add('immatriculation', 'text')
            ->add('modele',          'text')
            ->add('annee',           'date', array(
                'widget' => 'choice',
                'years'  => range(date('Y') - 10, date('Y'))
            ))
            ->add('ordreDepart',     'number')
            ->add('typeVehicule',    'choice', array(
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

<?php

namespace Samu\GestionVMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MaterielType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name' , 'text', array(
                'label' => 'Nom ou référence'))
            ->add('categories', 'entity', array(
                'class'    => 'SamuGestionVMBundle:MaterielCategory',
                'property' => 'name',
                'multiple' => 'true',
                'label'    => 'Catégorie'))
            ->add('annee',           'date', array(
                'label'    => 'Année d\'acquisition',
                'widget'   => 'choice',
                'years'    => range(date('Y') - 10, date('Y'))))
            ->add('operationnel',    'checkbox', array(
                'label'    => 'Matériel opérationnel',
                'required' => false ))
            ->add('Envoyer', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Samu\GestionVMBundle\Entity\Materiel'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'samu_gestionvmbundle_materiel';
    }
}

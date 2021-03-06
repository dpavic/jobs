<?php

namespace Dpavic\JobsBundle\Form;

use Dpavic\JobsBundle\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JobType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('type', 'choice', array('choices' => Job::getTypes(),
                    'expanded' => true))
                ->add('company')
                ->add('file', 'file', array('label' => 'Company Logo', 
                    'required' => false))
                ->add('url')
                ->add('position')
                ->add('location')
                ->add('description')
                ->add('howToApply')
                ->add('isPublic')
                ->add('email')
                ->add('category')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dpavic\JobsBundle\Entity\Job'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dpavic_jobsbundle_job';
    }

}

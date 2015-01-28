<?php

namespace Dpavic\JobsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Dpavic\JobsBundle\Entity\Affiliate;

class AffiliateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url')
                ->add('email')
                ->add('categories', null, array('expanded' => true));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dpavic\JobsBundle\Entity\Affiliate',
        ));
    }

    public function getName()
    {
        return 'affiliate';
    }

}

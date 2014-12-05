<?php

namespace Dpavic\JobsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class CategoryAdmin extends Admin
{

    //setup the default sort column and order
    protected $datagridValues = array(
        '_sortOrder' => 'ASC',
        '_sortBy' => 'name'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name')
                ->add('slug')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
                ->add('slug');
    }

}

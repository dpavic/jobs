<?php

namespace Dpavic\JobsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Dpavic\JobsBundle\Entity\Job;

class JobAdmin extends Admin
{

    //setup the default sort column and order
    protected $datagridValues = array(
        '_sortOrder' => 'DESC',
        '_sortBy' => 'createdAt'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('category')
                ->add('type', 'choice', array('choices' => Job::getTypes(), 'expanded' => true))
                ->add('company')
                ->add('file', 'file', array('label' => 'Company Logo', 'required' => false))
                ->add('url')
                ->add('position')
                ->add('location')
                ->add('description')
                ->add('howToApply')
                ->add('isPublic')
                ->add('email')
                ->add('isActivated')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
                ->add('category')
                ->add('company')
                ->add('position')
                ->add('description')
                ->add('isActivated')
                ->add('isPublic')
                ->add('email')
                ->add('expiresAt')
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('company')
                ->add('position')
                ->add('location')
                ->add('url')
                ->add('isActivated')
                ->add('email')
                ->add('category')
                ->add('expiresAt')
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'view' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    )
        ));
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
                ->add('category')
                ->add('type')
                ->add('company')
                ->add('webPath', 'string', array('template' =>
                    'DpavicJobsBundle:JobAdmin:list_image.html.twig'))
                ->add('url')
                ->add('position')
                ->add('location')
                ->add('description')
                ->add('howToApply')
                ->add('isPublic')
                ->add('isActivated')
                ->add('token')
                ->add('email')
                ->add('expiresAt')
        ;
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        if ($this->hasRoute('edit') && $this->isGranted('EDIT') && $this->hasRoute('delete') && $this->isGranted('DELETE')) {
            $actions['extend'] = array(
                'label' => 'Extend',
                'ask_confirmation' => true //IF true, a confirmation will be asked before performing action
            );

            $actions['deleteNeverActivated'] = array(
                'label' => 'Delete never activated jobs',
                'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
            );
        }
        return $actions;
    }

    
}

<?php

namespace Dpavic\JobsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class AffiliateAdmin extends Admin
{

    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'isActive',
        'isActive' => array('value' => 2) // Value 2 represents affiliate accounts 
    );                                  // that are not activated yet );

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('email')
                ->add('url');
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('email')
                ->add('url');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->add('isActive')
                ->addIdentifier('email')
                ->add('url')
                ->add('createdAt')
                ->add('token')
                ->add('_action', 'actions', array('actions' => array(
                    'activate' => array(
                        'template' => 'DpavicJobsBundle:AffiliateAdmin:list_action_activate.html.twig'),
                    'deactivate' => array(
                        'template' => 'DpavicJobsBundle:AffiliateAdmin:list_action_deactivate.html.twig'),
                )))
        ;
    }

    public function getBatchActions()
    {
        parent::getBatchActions();

        if ($this->hasRoute('edit') && $this->isGranted('EDIT') &&
                $this->hasRoute('delete') && $this->isGranted('DELETE')) {
            $actions['activate'] = array(
                'label' => 'Activate',
                'ask_confirmation' => true
            );

            $actions['deactivate'] = array(
                'label' => 'Deactivate',
                'ask_confirmation' => true
            );
        }
        return $actions;
    }

    public function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);

        $collection->add('activate', $this->getRouterIdParameter() . '/activate');
        $collection->add('deactivate', $this->getRouterIdParameter() . '/deactivate');
    
    }

}

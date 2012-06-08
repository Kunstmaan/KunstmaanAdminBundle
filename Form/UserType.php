<?php

namespace Kunstmaan\AdminBundle\Form;

use Symfony\Component\DependencyInjection\Container;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Kunstmaan\AdminBundle\Form\EventListener\AddUserNameFieldSubscriber; 

class UserType extends AbstractType
{
	private $container;
	
	public function __construct(Container $container){
		$this->container = $container;
	}
	
    public function buildForm(FormBuilder $builder, array $options)
    {
    	// get roles from the service container
    	/*$definedRoles = $this->container->getParameter('security.role_hierarchy.roles');
    	
    	$roles = array();
    	foreach ($definedRoles as $name => $rolesHierarchy) {
    		$roles[$name] = $name . ': ' . implode(', ', $rolesHierarchy);

    		foreach ($rolesHierarchy as $role) {
    			if (!isset($roles[$role])) {
    				$roles[$role] = $role;
    			}
    		}
    	}*/
    	
        //This is an event used to add the username, based on if it is a new user or an existing user that 
        //is being edited, the username field will be read only or not. 
        $subscriber = new AddUserNameFieldSubscriber($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber); 
        
        $builder->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'required' => $options['password_required'],
            	'invalid_message' => "The passwords don't match!"));
        $builder->add('email', null, array(
                'invalid_message' => "This value is already used")
                );
        $builder->add('enabled', 'checkbox', array('required' => false));
        $builder->add('groups', null, array(
            'expanded'  => false //change to true to expand to checkboxes
        ));
    }

    public function getName()
    {
        return 'user';
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'password_required' => false,
        );
    }
    
    public function getAllowedOptionValues(array $options)
    {
        return array(
            'password_required' => array(
                true,
                false
            ),
        );
    }    
}
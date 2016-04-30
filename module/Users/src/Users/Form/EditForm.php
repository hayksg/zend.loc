<?php

namespace Users\Form;

use Zend\Form\Form;

class EditForm extends Form
{
    public function __construct()
    {
        parent::__construct('UserManager');
        
        $this->setAttributes(array(
            'class' => 'form-horizontal',
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
        
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'name',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Name:',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
                'id'    => 'email',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Email:',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
                'id'    => 'password',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Password:',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Update',
                'class' => 'btn btn-default',
            ),
        ));
    }
}
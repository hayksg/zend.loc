<?php

namespace Users\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct()
    {
        parent::__construct('login');
        
        $this->setAttributes(array(
            'class' => 'form-horizontal',
        ));
        
        $email = new Element\Email('email');
        $email->setLabel('Email:');
        $email->setAttributes(array(
            'type'  => 'email',
            'id'    => 'email',
            'class' => 'form-control',
        ));
        $this->add($email);

        $password = new Element\Password('password');
        $password->setLabel('Password:');
        $password->setAttributes(array(
            'type'  => 'password',
            'id'    => 'password',
            'class' => 'form-control',
        ));
        $this->add($password);

        $submit = new Element\Submit('submit');
        $submit->setAttributes(array(
            'type'  => 'submit',
            'value' => 'Login',
            'class' => 'btn btn-default',
        ));
        $this->add($submit);
    }
}
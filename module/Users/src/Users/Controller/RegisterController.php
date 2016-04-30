<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Users\Form\RegisterForm;
use Users\Model\User;

class RegisterController extends AbstractActionController
{
    public function indexAction()
    {
        $form = new RegisterForm();
        
        $view = new ViewModel(array(
            'form' => $form,
        ));
        return $view;
    }

    public function processAction()
    {
        $messages = array();
        
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(null, array(
                'controller' => 'register',
                'action'     => 'index',
            ));
        }

        $sm = $this->getServiceLocator();
        
        $post = $this->request->getPost();
        $form = $sm->get('RegisterForm');
        $form->setData($post);
        
        $userTable = $sm->get('Users\Model\UserTable');
        
        if ($userTable->getUserByEmail($this->request->getPost('email'))) {
            $messages[] = 'Email address exist already';
        }

        if ($this->request->getPost('password') != $this->request->getPost('confirmPassword')) {
            $messages[] = 'Passwords does not match';
        }

        if (!$form->isValid() || !empty($messages)) {
            $view = new ViewModel(array(
                'error'    => true,
                'form'     => $form,
                'messages' => $messages,
            ));
            $view->setTemplate('users/register/index');
            return $view;
        }
        
        if ($this->saveUser($form->getData())) {
            return $this->redirect()->toRoute(null, array(
                'controller' => 'register',
                'action'     => 'confirm',
            ));
        }
    }

    public function saveUser($data)
    {
        $sm = $this->getServiceLocator();
        $userTable = $sm->get('Users\Model\UserTable');
        
        $user = new User();
        $user->exchangeArray($data);

        $userTable->saveUser($user);
        return true;
    }

    public function confirmAction()
    {
        $view = new ViewModel();
        return $view;
    }
}
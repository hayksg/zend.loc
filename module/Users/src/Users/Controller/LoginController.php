<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Users\Form\LoginForm;

class LoginController extends AbstractActionController
{
    protected $authService;

    public function indexAction()
    {
        $form = new LoginForm();
        
        $view = new ViewModel(array(
            'form' => $form,
        ));
        return $view;
    }

    public function processAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(null, array(
                'controller' => 'login',
                'action'     => 'index',
            ));
        }
        
        $post = $this->request->getPost();
        $sm = $this->getServiceLocator();
        $form = $sm->get('LoginForm');
        $form->setData($post);
        
        if (!$form->isValid()) {
            $view = new ViewModel(array(
                'error' => true,
                'form'  => $form,
            ));
            $view->setTemplate('users/login/index');
            return $view;
        }

        $this->getAuthService()->getAdapter()->setIdentity($this->request->getPost('email'));
        $this->getAuthService()->getAdapter()->setCredential($this->request->getPost('password'));
        $result = $this->getAuthService()->authenticate();

        if ($result->isValid()) {
            $this->getAuthService()->getStorage()->write($this->request->getPost('email'));
            return $this->redirect()->toRoute(null, array(
                'controller' => 'login',
                'action'     => 'confirm',
            ));
        } else {
            $view = new ViewModel(array(
                'message' => 'Email address or password is not valid',
                'form'    => $form,
            ));
            $view->setTemplate('users/login/index');
            return $view;
        }

    }

    public function getAuthService()
    {
        if (!$this->authService) {
            $sm = $this->getServiceLocator();
            $this->authService = $sm->get('AuthService');
        }
        return $this->authService;
    }

    public function confirmAction()
    {
        $email = $this->getAuthService()->getStorage()->read();

        $view = new ViewModel(array(
            'email' => $email,
        ));
        return $view;
    }
}
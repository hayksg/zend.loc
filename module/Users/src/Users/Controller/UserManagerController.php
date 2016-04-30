<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

use Users\Form\EditForm;

class UserManagerController extends AbstractActionController
{
    public function indexAction()
    {
        $session = new Container('delete');
        $sessionMessage = $session->message;
        $session->getManager()->getStorage()->clear('delete');

        $sm = $this->getServiceLocator();
        $userTable = $sm->get('Users\Model\UserTable');
        $paginator = $userTable->getAll(true);
        $paginator->setCurrentPageNumber((int)$this->params()->fromRoute('page', 1));
        $paginator->setItemCountPerPage(3);

        $view = new ViewModel(array(
            'paginator'      => $paginator,
            'sessionMessage' => $sessionMessage,
        ));
        return $view;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute(null, array(
                'controller' => 'UserManager',
                'action'     => 'index',
            ));
        }

        $sm = $this->getServiceLocator();
        $userTable = $sm->get('Users\Model\UserTable');
        $user = $userTable->getUserById($id);

        $form = new EditForm();
        $form->bind($user);
        
        $view = new ViewModel(array(
            'form' => $form,
            'id'   => $id,
        ));
        return $view;
    }

    public function processAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute(null, array(
                'controller' => 'UserManager',
                'action'     => 'index',
            ));
        }

        $sm = $this->getServiceLocator();
        $userTable = $sm->get('Users\Model\UserTable');
        $user = $userTable->getUserById($id);



        $post = $this->request->getPost();
        $form = $sm->get('EditForm');
        $form->bind($user);
        $form->setData($post);

        $emailOld = $user->email;
        $emailNew = $form->get('email')->getValue();
        if ($userTable->getUserByEmail($emailNew) && $emailNew != $emailOld) {
            $message = 'Email address exists already';
        }
        

        if (!$form->isValid() || $message) {
            $view = new ViewModel(array(
                'error'   => true,
                'form'    => $form,
                'id'      => $id,
                'message' => $message,
            ));
            $view->setTemplate('users/user-manager/edit');
            return $view;
        }

        $userTable->saveUser($user);
        return $this->redirect()->toRoute(null, array(
            'controller' => 'UserManager',
            'action'     => 'index',
        ));
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute(null, array(
                'controller' => 'UserManager',
                'action'     => 'index',
            ));
        }

        $sm = $this->getServiceLocator();
        $userTable = $sm->get('Users\Model\UserTable');
        $userTable->deleteUser($id);
        $session = new Container('delete');
        $session->message = 'User successfully deleted';

        return $this->redirect()->toRoute(null, array(
            'controller' => 'UserManager',
            'action'     => 'index',
        ));
    }
}
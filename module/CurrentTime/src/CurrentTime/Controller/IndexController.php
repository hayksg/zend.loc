<?php

namespace CurrentTime\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $time = date('H:i:s');
        
        $view = new ViewModel();
        $view->setVariables(array(
            'time' => $time,
        ));
        return $view;
    }
}
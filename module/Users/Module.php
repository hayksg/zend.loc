<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;

use Users\Form\RegisterFilter;
use Users\Form\RegisterForm;
use Users\Form\LoginFilter;
use Users\Form\LoginForm;
use Users\Form\EditFilter;
use Users\Form\EditForm;
use Users\Model\UserTable;
use Users\Model\User;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(

                // Db
                'Users\Model\UserTable' => function($sm)
                {
                    $tableGateway = $sm->get('UserTableGateway');
                    $userTable = new UserTable($tableGateway);
                    return $userTable;
                },
                'UserTableGateway' => function($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                
                // Forms
                'RegisterForm' => function($sm)
                {
                    $form = new RegisterForm();
                    $form->setInputFilter($sm->get('RegisterFilter'));
                    return $form;
                },
                'LoginForm' => function($sm)
                {
                    $form = new LoginForm();
                    $form->setInputFilter($sm->get('LoginFilter'));
                    return $form;
                },
                'EditForm' => function($sm)
                {
                    $form = new EditForm();
                    $form->setInputFilter($sm->get('EditFilter'));
                    return $form;
                },

                // Filters
                'RegisterFilter' => function($sm)
                {
                    return new RegisterFilter();
                },
                'LoginFilter' => function($sm)
                {
                    return new LoginFilter();
                },
                'EditFilter' => function($sm)
                {
                    return new EditFilter();
                },

                // AuthService
                'AuthService' => function($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $authAdapter = new AuthAdapter($dbAdapter, 'user', 'email', 'password', 'md5(?)');
                    $authService = new AuthenticationService();
                    $authService->setAdapter($authAdapter);
                    return $authService;
                },
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
}

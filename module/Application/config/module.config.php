<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Users\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            'home' => array(
                'label' => 'Users',
                'route' => 'home',
            ),
            'current_time' => array(
                'label' => 'Current Time',
                'route' => 'current-time',
            ),
            'users' => array(
                'label' => 'Users',
                'route' => 'users',
                'pages' => array(
                    'register' => array(
                        'label'      => 'Register',
                        'controller' => 'register',
                        'action'     => 'index',
                        'pages' => array(
                            'confirm' => array(
                                'label'      => 'Confirm',
                                'controller' => 'register',
                                'action'     => 'confirm',
                            ),
                        ),
                    ),
                    'register_process' => array(
                        'label'      => 'Register',
                        'controller' => 'register',
                        'action'     => 'process',
                    ),
                    'login' => array(
                        'label'      => 'Login',
                        'controller' => 'login',
                        'action'     => 'index',
                        'pages' => array(
                            'confirm' => array(
                                'label'      => 'Confirm',
                                'controller' => 'login',
                                'action'     => 'confirm',
                            ),
                        ),
                    ),
                    'login_process' => array(
                        'label'      => 'Login',
                        'controller' => 'login',
                        'action'     => 'process',
                    ),
                    'manage_users' => array(
                        'label'      => 'Manage Users',
                        'controller' => 'UserManager',
                        'action'     => 'index',
                        'pages' => array(
                            'edit' => array(
                                'label'      => 'Edit',
                                'controller' => 'UserManager',
                                'action'     => 'edit',
                            ),
                            'edit_process' => array(
                                'label'      => 'Edit',
                                'controller' => 'UserManager',
                                'action'     => 'process',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => Controller\IndexController::class
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);

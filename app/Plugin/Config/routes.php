<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('CakeSession', 'Model/Datasource');

/**
 * Parse extensions for file downalods
 */
Router::parseExtensions('csv','pdf');

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/',              array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'home'));
Router::connect('/home',          array('plugin' => 'accounts', 'controller' => 'accounts', 'action' => 'home'));
Router::connect('/app_admin',     array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'app_admin'));
Router::connect('/program_admin', array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'program_admin'));
Router::connect('/my_account',    array('plugin' => 'accounts', 'controller' => 'accounts', 'action' => 'my_account'));
//Router::connect('/my_account',    array('plugin' => 'accounts', 'controller' => 'accounts', 'action' => 'view', CakeSession::read('Auth.User.id')));

/**
 * Admin redirect
 */
Router::connect('/admin', array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'admin'));

/**
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Pages routes
 * As long as the slug does not match any loaded plugin names it will
 * consider it a page slug and attempt to load it's content from database.
 */
$plugins = Inflector::underscore(implode('|', CakePlugin::loaded()));

Router::connect(
    '/:slug',
    array('plugin' => 'pages', 'controller' => 'pages', 'action' => 'display'),
    array(
        'pass' => array('slug'),
        //'slug' => sprintf('((?!%s)).*', $plugins) // this conflicts with app level controller routes
    )
);

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';

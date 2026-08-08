<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * This file is loaded in the context of the `Application` class.
  * So you can use  `$this` to reference the application class instance
  * if required.
 */
return function (RouteBuilder $routes): void {
    /*
     * The default class to use for all routes
     *
     * The following route classes are supplied with CakePHP and are appropriate
     * to set as the default:
     *
     * - Route
     * - InflectedRoute
     * - DashedRoute
     *
     * If no call is made to `Router::defaultRouteClass()`, the class used is
     * `Route` (`Cake\Routing\Route\Route`)
     *
     * Note that `Route` does not do any inflections on URLs which will result in
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
        $builder->connect('/', ['controller' => 'Users', 'action' => 'index']);

        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        $builder->connect('/pages/*', 'Pages::display');

        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/{controller}', ['action' => 'index']);
         * $builder->connect('/{controller}/{action}/*', []);
         * ```
         *
         * You can remove these routes once you've connected the
         * routes you want in your application.
         */
        $builder->fallbacks();
    });

    $routes->prefix('Api', function (RouteBuilder $builder) {
        $builder->scope('/Users', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Users', 'action' => 'index']);
            $builder->connect('/getUsers', ['controller' => 'Users', 'action' => 'getUsers']);
            $builder->connect('/register', ['controller' => 'Users', 'action' => 'register']);
            $builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
            $builder->connect('/add', ['controller' => 'Users', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Users', 'action' => 'edit'], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', ['controller' => 'Users', 'action' => 'delete'], [
                'pass' => ['id']
            ]);
        });

        $builder->scope('/Notifications', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Notifications', 'action' => 'index']);
            $builder->connect('/getNotifications', ['controller' => 'Notifications', 'action' => 'getNotifications']);
            $builder->connect('/add', ['controller' => 'Notifications', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Notifications', 'action' => 'edit'], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', ['controller' => 'Notifications', 'action' => 'delete'], [
                'pass' => ['id']
            ]);
        });

       $builder->scope('/Farmers', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Farmers', 'action' => 'index']);
            $builder->connect('/getFarmers', ['controller' => 'Farmers', 'action' => 'getFarmers']);
            $builder->connect('/add', ['controller' => 'Farmers', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Farmers', 'action' => 'edit'], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', ['controller' => 'Farmers', 'action' => 'delete'], [
                'pass' => ['id']
            ]);
        });
         $builder->scope('/Programs', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Programs', 'action' => 'index']);
            $builder->connect('/getPrograms', ['controller' => 'Programs', 'action' => 'getPrograms']);
            $builder->connect('/add', ['controller' => 'Programs', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Programs', 'action' => 'edit'], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', ['controller' => 'Programs', 'action' => 'delete'], [
                'pass' => ['id']
            ]);
        });
        $builder->scope('/Distributions', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Distributions', 'action' => 'index']);
            $builder->connect('/getDistributions', ['controller' => 'Distributions', 'action' => 'getDistributions']);
            $builder->connect('/add', ['controller' => 'Distributions', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Distributions', 'action' => 'edit'], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', ['controller' => 'Distributions', 'action' => 'delete'], [
                'pass' => ['id']
            ]);
        });
        $builder->scope('/Farms', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Farms', 'action' => 'index']);
            $builder->connect('/getFarms', ['controller' => 'Farms', 'action' => 'getFarms']);
            $builder->connect('/add', ['controller' => 'Farms', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Farms', 'action' => 'edit'], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', ['controller' => 'Farms', 'action' => 'delete'], [
                'pass' => ['id']
            ]);
        });
        $builder->scope('/Pests', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Pests', 'action' => 'index']);
            $builder->connect('/getPests', ['controller' => 'Pests', 'action' => 'getPests']);
            $builder->connect('/add', ['controller' => 'Pests', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Pests', 'action' => 'edit'], [
                'pass' => ['id']
            ]);
            $builder->connect('/delete/{id}', ['controller' => 'Pests', 'action' => 'delete'], [
                'pass' => ['id']
            ]);
        });
        $builder->scope('/Analytics', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Analytics', 'action' => 'index']);
        });

        $builder->scope('/Evaluations', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Evaluations', 'action' => 'index']);
            $builder->connect('/getEvaluations', ['controller' => 'Evaluations', 'action' => 'getEvaluations']);
            $builder->connect('/add', ['controller' => 'Evaluations', 'action' => 'add']);
        });
        $builder->scope('/Feedbacks', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Feedbacks', 'action' => 'index']);
            $builder->connect('/getFeedbacks', ['controller' => 'Feedbacks', 'action' => 'getFeedbacks']);
            $builder->connect('/survey', ['controller' => 'Feedbacks', 'action' => 'survey']);
        });

        $builder->scope('/Audit_Logs', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Audit_Logs', 'action' => 'index']);
            $builder->connect('/getAudit_Logs', ['controller' => 'Audit_Logs', 'action' => 'getAudit_Logs']);
        });

        $builder->scope('/Token', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Token', 'action' => 'index']);
        });
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder): void {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};

<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Core\Router;

use Windwalker\Core\Facade\AbstractProxyFacade;
use Windwalker\Router\Route;

/**
 * The Router class.
 * 
 * @since  2.0.
 *
 * @method  static  string  build($route, $queries = array(), $type = MainRouter::TYPE_RAW)
 * @method  static  string  route($route, $queries = array(), $type = MainRouter::TYPE_RAW)
 * @method  static  string  fullRoute($route, $queries = array())
 * @method  static  string  rawRoute($route, $queries = array())
 * @method  static  Route   match($rawRoute, $method = 'GET', $options = array())
 * @method  static  Route   addRouteByConfig($name, $route, $package = null)
 * @method  static  MainRouter  addRouteByConfigs($routes, $package = null)
 * @method  static  MainRouter  addRoute($name, $pattern = null, $variables = array(), $method = array(), $options = array())
 * @method  static  MainRouter  addRoutes(array $routes)
 * @method  static  Route       getRoute($name)
 *
 * @see \Windwalker\Router\Router
 * @see \Windwalker\Core\Router\MainRouter
 */
abstract class CoreRouter extends AbstractProxyFacade
{
	const TYPE_RAW = 'raw';
	const TYPE_PATH = 'path';
	const TYPE_FULL = 'full';

	/**
	 * Property _key.
	 *
	 * @var  string
	 */
	protected static $_key = 'router';
}

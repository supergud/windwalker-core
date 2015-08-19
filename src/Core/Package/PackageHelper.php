<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2015 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Core\Package;

use Windwalker\Application\AbstractWebApplication;
use Windwalker\Console\Console;
use Windwalker\Core\Application\WebApplication;
use Windwalker\Core\Facade\AbstractFacade;
use Windwalker\DI\Container;
use Windwalker\Core\Ioc;
use Windwalker\IO\Input;
use Windwalker\Registry\Registry;
use Windwalker\Web\Application;

/**
 * The PackageHelper class.
 *
 * @method  static  PackageResolver  getInstance()
 * 
 * @since  2.0
 */
class PackageHelper extends AbstractFacade
{
	/**
	 * Property _key.
	 *
	 * @var  string
	 */
	protected static $_key = 'package.resolver';

	/**
	 * getPackage
	 *
	 * @param string $name
	 *
	 * @return  AbstractPackage
	 */
	public static function getPackage($name)
	{
		return static::getInstance()->getPackage($name);
	}

	/**
	 * getPackages
	 *
	 * @see  PackageResolver::getPackages
	 *
	 * @return  AbstractPackage[]
	 */
	public static function getPackages()
	{
		return static::getInstance()->getPackages();
	}

	/**
	 * registerPackages
	 *
	 * @param AbstractPackage[] $packages
	 *
	 * @return  PackageResolver
	 */
	public static function registerPackages(array $packages)
	{
		return static::getInstance()->registerPackages($packages);
	}

	/**
	 * getPath
	 *
	 * @param string $package
	 *
	 * @see  PackageResolver::getPath
	 *
	 * @return  string
	 */
	public static function getPath($package)
	{
		return static::getPackage($package)->getDir();
	}

	/**
	 * getClassName
	 *
	 * @param string $package
	 *
	 * @see  PackageResolver::getClassName
	 *
	 * @return  string
	 */
	public static function getClassName($package)
	{
		return get_class(static::getPackage($package));
	}

	/**
	 * getConfig
	 *
	 * @param string $package
	 *
	 * @return  Registry
	 */
	public static function getConfig($package)
	{
		return static::getInstance()->getConfig($package);
	}

	/**
	 * has
	 *
	 * @param string $package
	 *
	 * @see  PackageResolver::exists
	 *
	 * @return  boolean
	 */
	public static function exists($package)
	{
		return static::getInstance()->exists($package);
	}

	/**
	 * Execute a package in CLI environment.
	 *
	 * @param string $package
	 * @param string $task
	 * @param array  $variables
	 * @param string $appClass
	 *
	 * @return  mixed
	 */
	public static function execute($package, $task, $variables = array(), $appClass = 'Windwalker\Web\Application')
	{
		$_SERVER['HTTP_HOST']      = isset($_SERVER['HTTP_HOST'])      ? $_SERVER['HTTP_HOST']      : 'localhost';
		$_SERVER['REQUEST_METHOD'] = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		$_SERVER['SERVER_PORT']    = isset($_SERVER['SERVER_PORT'])    ? $_SERVER['SERVER_PORT']    : '80';

		$package = static::getPackage($package);

		$container = $package->getContainer();

		if (!class_exists($appClass))
		{
			throw new \LogicException($appClass . ' not found, you have to provide an exists Application class name.');
		}

		if (!is_subclass_of($appClass, 'Windwalker\Core\Application\WebApplication'))
		{
			throw new \LogicException('Application class should be sub class of Windwalker\Core\Application\WebApplication.');
		}

		/** @var WebApplication $app */
		$app = new $appClass($container, new Input, array('name' => 'cli'));

		$container->share('system.application', $app);

		$app->getRouter();

		return $package->execute($task, $variables);
	}
}

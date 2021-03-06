<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Windwalker\Core\Provider;

use Windwalker\Core\Logger\LoggerManager;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;

/**
 * The LoggerProvider class.
 * 
 * @since  2.1.1
 */
class LoggerProvider implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container $container The DI container.
	 *
	 * @return  void
	 */
	public function register(Container $container)
	{
		$closure = function(Container $container)
		{
			return new LoggerManager($container->get('config')->get('path.logs'));
		};

		$container->share(LoggerManager::class, $closure);
	}
}

<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Core\Provider;

use Windwalker\Core\Cache\CacheFactory;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;
use Windwalker\Registry\Registry;

/**
 * The CacheProvider class.
 * 
 * @since  2.0
 */
class CacheProvider implements ServiceProviderInterface
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
		// Get cache factory object.
		$closure = function(Container $container)
		{
			return CacheFactory::getInstance($container);
		};

		$container->share(CacheFactory::class, $closure);

		// Get global cache object.
		$container->share('cache', function(Container $container)
		{
			/** @var Registry $config */
			$config = $container->get('config');

			$storage = $config->get('cache.storage', 'file');
			$handler = $config->get('cache.handler', 'serialized');

			return $container->get('cache.factory')->create('windwalker', $storage, $handler);
		});
	}
}

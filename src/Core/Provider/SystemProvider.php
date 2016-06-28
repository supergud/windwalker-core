<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Core\Provider;

use Windwalker\Application\AbstractWebApplication;
use Windwalker\Console\Console;
use Windwalker\Core\Application\WindwalkerApplicationInterface;
use Windwalker\Core\Package\PackageResolver;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;

/**
 * The SystemProvider class.
 * 
 * @since  2.0
 */
class SystemProvider implements ServiceProviderInterface
{
	/**
	 * Property app.
	 *
	 * @var AbstractWebApplication|Console
	 */
	protected $app;

	/**
	 * Class init.
	 *
	 * @param AbstractWebApplication|Console $app
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container $container The DI container.
	 *
	 * @return  void
	 */
	public function register(Container $container)
	{
		$container->share(Container::class, $this);

		$container->share(WindwalkerApplicationInterface::class, $this->app);

		$container->share('config', $this->app->config);

		$container->share(PackageResolver::class, function(Container $container)
		{
			return new PackageResolver($container);
		});
	}
}
 
<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Windwalker\Core\Provider;

use Illuminate\View\Compilers\BladeCompiler;
use Windwalker\Core\View\Twig\WindwalkerExtension;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;
use Windwalker\Renderer;

/**
 * The TemplateEngineProvider class.
 *
 * @since  2.1.1
 */
class TemplateEngineProvider implements ServiceProviderInterface
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
		// Blade
		Renderer\Blade\GlobalContainer::addCompiler('translate', function($expression)
		{
			return "<?php echo \$translator->translate{$expression} ?>";
		});

		Renderer\Blade\GlobalContainer::addCompiler('sprintf', function($expression)
		{
			return "<?php echo \$translator->sprintf{$expression} ?>";
		});

		Renderer\Blade\GlobalContainer::addCompiler('plural', function($expression)
		{
			return "<?php echo \$translator->plural{$expression} ?>";
		});

		Renderer\Blade\GlobalContainer::setCachePath($container->get('system.config')->get('path.cache') . '/view');

		// B/C for 4.*
		if (!method_exists('Illuminate\View\Compilers\BladeCompiler', 'directive'))
		{
			Renderer\Blade\GlobalContainer::setContentTags('{{', '}}');
			Renderer\Blade\GlobalContainer::setEscapedTags('{{{', '}}}');

			Renderer\Blade\GlobalContainer::addExtension('rawTag', function($view, BladeCompiler $compiler)
			{
				$pattern = sprintf('/(@)?%s\s*(.+?)\s*%s(\r?\n)?/s', '{!!', '!!}');

				$callback = function ($matches) use ($compiler)
				{
					$whitespace = empty($matches[3]) ? '' : $matches[3].$matches[3];

					return $matches[1] ? substr($matches[0], 1) : '<?php echo ' . $compiler->compileEchoDefaults($matches[2]).'; ?>' . $whitespace;
				};

				return preg_replace_callback($pattern, $callback, $view);
			});
		}

		// Twig
		Renderer\Twig\GlobalContainer::addExtension('windwalker', new WindwalkerExtension($container));
	}
}

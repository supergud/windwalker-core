<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Core\Language;

use Windwalker\Core\Package\AbstractPackage;
use Windwalker\DI\Container;
use Windwalker\Language\Language;
use Windwalker\Language\LanguageNormalize;
use Windwalker\Registry\Registry;

/**
 * The CoreLanguage class.
 *
 * @since  {DEPLOY_VERSION}
 */
class CoreLanguage extends Language
{
	/**
	 * Property config.
	 *
	 * @var  Registry
	 */
	protected $config;
	/**
	 * Property container.
	 *
	 * @var  Container
	 */
	protected $container;

	/**
	 * CoreLanguage constructor.
	 *
	 * @param Registry  $config
	 * @param Container $container
	 */
	public function __construct(Registry $config, Container $container)
	{
		$this->config = $config;
		$this->container = $container;

		parent::__construct(
			$config->get('language.locale', 'en-GB'),
			$config->get('language.default', 'en-GB')
		);
	}

	/**
	 * load
	 *
	 * @param string $file
	 * @param string $format
	 * @param string $package
	 *
	 * @return static
	 */
	public function loadFile($file, $format = 'ini', $package = null)
	{
		$config = $this->config;

		$format = $format ? : $config->get('language.format', 'ini');

		$default = $config['language.default'] ? : 'en-GB';
		$locale  = $config['language.locale']  ? : 'en-GB';

		$ext = $format == 'yaml' ? 'yml' : $format;

		$default = LanguageNormalize::toLanguageTag($default);
		$locale  = LanguageNormalize::toLanguageTag($locale);

		// If package name exists, we load package language first, that global can override it.
		if (is_string($package))
		{
			$package = $this->container->get('package.resolver')->getPackage($package);
		}

		if ($package instanceof AbstractPackage)
		{
			$path = $package->getDir() . '/Resources/language/%s/%s.%s';

			// Get Package language
			if (!$config->get('language.debug') || $locale == $default)
			{
				$this->loadLanguageFile(sprintf($path, $default, $file, $ext), $format);
			}

			// If locale not equals default locale, load it to override default
			if ($locale != $default)
			{
				$this->loadLanguageFile(sprintf($path, $locale, $file, $format), $format);
			}
		}

		// Get Global language
		$path = $config->get('path.languages') . '/%s/%s.%s';

		if (!$config->get('language.debug') || $locale == $default)
		{
			$this->loadLanguageFile(sprintf($path, $default, $file, $format), $format);
		}

		// If locale not equals default locale, load it to override default
		if ($locale != $default)
		{
			$this->loadLanguageFile(sprintf($path, $locale, $file, $format), $format);
		}
		
		return $this;
	}

	/**
	 * loadFile
	 *
	 * @param string $file
	 * @param string $format
	 *
	 * @return  static
	 */
	protected function loadLanguageFile($file, $format)
	{
		if (is_file($file))
		{
			$this->load($file, $format);

			$loaded = $this->config['language.loaded'];

			$loaded[] = $file;

			$this->config->set('language.loaded', $loaded);
		}

		return $this;
	}

	/**
	 * Alias of translate().
	 *
	 * @param string $string
	 *
	 * @return  string
	 */
	public function _($string)
	{
		$this->setTraceLevelOffset(1);

		$result = $this->translate($string);

		$this->setTraceLevelOffset(0);

		return $result;
	}
}
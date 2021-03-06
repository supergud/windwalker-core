<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Core\Renderer;

use Windwalker\Core\Renderer\Finder\PackageFinderInterface;

/**
 * The CoreRendererInterface class.
 *
 * @since  3.0
 */
interface CoreRendererInterface
{
	/**
	 * setRendererFinder
	 *
	 * @param PackageFinderInterface $finder
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setPackageFinder(PackageFinderInterface $finder);
}

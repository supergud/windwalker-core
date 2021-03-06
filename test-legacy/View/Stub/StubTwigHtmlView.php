<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Core\Test\View\Stub;

use Windwalker\Core\View\TwigHtmlView;

/**
 * The StubTwigHtmlView class.
 * 
 * @since  2.1.1
 */
class StubTwigHtmlView extends TwigHtmlView
{
	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function init()
	{
		$this->renderer->addExtension(new StubExtension);
	}
}

<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Core\Controller\Traits;

use Windwalker\Core\Controller\AbstractController;
use Windwalker\Core\Controller\Middleware\JsonResponseMiddleware;

/**
 * The HtmlResponseTrait class.
 *
 * @since  {DEPLOY_VERSION}
 */
trait JsonResponseTrait
{
	/**
	 * bootHtmlResponseTrait
	 *
	 * @param AbstractController $controller
	 *
	 * @return  void
	 */
	public function bootJsonResponseTrait(AbstractController $controller)
	{
		$controller->addMiddleware(JsonResponseMiddleware::class);
	}
}
<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

return [
	'providers' =>[
	],

	'routing' => [
		'files' => [
		]
	],

	'middlewares' => [

	],

	'configs' => [

	],

	'listeners' => [

	],

	'console' => [
		'commands' => [
			\Windwalker\SystemPackage\Command\SystemCommand::class,
			\Windwalker\SystemPackage\Command\RunCommand::class
		]
	]
];

<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\SystemPackage\Command\System;

use Windwalker\Console\Exception\WrongArgumentException;
use Windwalker\Core\Console\CoreCommand;
use Windwalker\Filesystem\File;

/**
 * The ModeCommand class.
 *
 * @since  3.0
 */
class ModeCommand extends CoreCommand
{
	/**
	 * Console(Argument) name.
	 *
	 * @var  string
	 */
	protected $name = 'mode';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Change system mode (dev or prod).';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = '%s <mode> [options]';

	/**
	 * Execute this command.
	 *
	 * @return int
	 *
	 * @since  2.0
	 */
	protected function doExecute()
	{
		$mode = $this->getArgument(0);

		if (!trim($mode))
		{
			throw new WrongArgumentException('Please provide mode name.');
		}

		$file = WINDWALKER_ROOT . '/.mode';

		File::write($file, trim($mode));

		$this->out('Set <comment>.mode</comment> file to <info>' . $mode . '</info>');
	}
}

<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Core\Migration\Command\Migration;

use Windwalker\Console\Command\AbstractCommand;
use Windwalker\Core\Migration\Model\MigrationsModel;

/**
 * The StatusCommand class.
 * 
 * @since  2.0
 */
class StatusCommand extends AbstractCommand
{
	/**
	 * An enabled flag.
	 *
	 * @var bool
	 */
	public static $isEnabled = true;

	/**
	 * Console(Argument) name.
	 *
	 * @var  string
	 */
	protected $name = 'status';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Show migration status';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'status <option>[option]</option>';

	/**
	 * Configure command information.
	 *
	 * @return void
	 */
	public function init()
	{
	}

	/**
	 * Execute this command.
	 *
	 * @return int|void
	 */
	protected function doExecute()
	{
		$migration = new MigrationsModel;

		$migration['path'] = $this->console->get('migration.dir');

		$migrations = $migration->getMigrations();

		if (!count($migrations))
		{
			throw new \RuntimeException('No migrations found.');
		}

		$this->out();
		$this->out(' Status  Version         Migration Name ');
		$this->out('-----------------------------------------');

		$versions = $migration->getVersions();

		$migrations->ksort();

		foreach ($migrations as $migItem)
		{
			$status = (in_array($migItem['id'], $versions)) ? '    <info>up</info>' : '  <error>down</error>';

			$info = sprintf(
				'%s   %14.0f  %s',
				$status,
				$migItem['id'],
				'<comment>' . $migItem['name'] . '</comment>'
			);

			$this->out($info);

			// Remove printed versions
			$index = array_search($migItem['id'], $versions);

			unset($versions[$index]);
		}

		foreach ($versions as $version)
		{
			$info = sprintf(
				'%s   %14.0f  %s',
				'    <info>up</info>',
				$version,
				'** Missing **'
			);

			$this->out($info);
		}

		$this->out();

		return true;
	}
}
 
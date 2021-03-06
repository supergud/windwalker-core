<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Windwalker\Core\Migration\Command;

use Windwalker\Core\Console\CoreCommand;
use Windwalker\Core\Migration\Command\Migration;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Provider\DatabaseProvider;
use Windwalker\Core\Ioc;

/**
 * The MigrationCommand class.
 *
 * @since  2.0
 */
class MigrationCommand extends CoreCommand
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
	protected $name = 'migration';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Database migration system.';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'migration <cmd><command></cmd> <option>[option]</option>';

	/**
	 * Configure command information.
	 *
	 * @return void
	 */
	public function init()
	{
		$this->addCommand(new Migration\CreateCommand);
		$this->addCommand(new Migration\StatusCommand);
		$this->addCommand(new Migration\MigrateCommand);
		$this->addCommand(new Migration\ResetCommand);
		$this->addCommand(new Migration\DropAllCommand);

		$this->addGlobalOption('d')
			->alias('dir')
			->description('Set migration file directory.');

		$this->addGlobalOption('p')
			->alias('package')
			->description('Package to run migration.');
	}

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$config = Ioc::getConfig();

		// Auto create database
		$name = $config['database.name'];

		$config['database.name'] = null;

		$db = Ioc::getDatabase();

		$db->getDatabase($name)->create(true);

		$db->select($name);

		$config['database.name'] = $name;

		DatabaseProvider::strictMode(Ioc::factory());

		// Prepare migration path
		$packageName = $this->getOption('p');

		/** @var AbstractPackage $package */
		$package = $this->console->getPackage($packageName);

		if ($package)
		{
			$dir = $package->getDir() . '/Migration';
		}
		else
		{
			$dir = $this->getOption('d');
		}

		$dir = $dir ? : $this->console->get('path.migrations');

		$this->console->set('migration.dir', $dir);
	}

	/**
	 * Execute this command.
	 *
	 * @return int|void
	 */
	protected function doExecute()
	{
		return parent::doExecute();
	}
}

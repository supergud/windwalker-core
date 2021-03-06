<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\SystemPackage\Command;

use Symfony\Component\Process\Process;
use Windwalker\Console\Command\Command;
use Windwalker\Core\Console\ConsoleHelper;
use Windwalker\Core\Console\CoreCommandTrait;
use Windwalker\Utilities\ArrayHelper;

/**
 * The DeployCommand class.
 *
 * @since  3.0
 */
class RunCommand extends Command
{
	use CoreCommandTrait;

	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'run';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'Run custom scripts.';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 *
	 * @since  2.0
	 */
	protected $usage = '%s <cmd><command1></cmd> <cmd><command2></cmd>... <option>[option]</option>';

	/**
	 * init
	 *
	 * @return  void
	 */
	protected function init()
	{
		$this->addOption('l')
			->alias('list')
			->description('List available scripts.')
			->defaultValue(false);
	}

	/**
	 * doExecute
	 *
	 * @return  boolean
	 */
	protected function doExecute()
	{
		$resolver = ConsoleHelper::getAllPackagesResolver();

		$scripts = (array) $this->console->get('console.scripts');

		foreach ((array) ConsoleHelper::loadPackages() as $name => $package)
		{
			$scripts = array_merge($scripts, (array) $resolver->getPackage($name)->get('console.script'));
		}

		if ($this->getOption('l'))
		{
			$this->listScripts($scripts);

			return true;
		}

		$profiles = $this->io->getArguments();

		if (!$profiles)
		{
			throw new \InvalidArgumentException('Please enter at least one profile name.');
		}

		foreach ($profiles as $profile)
		{
			$this->out()->out('Start Custom Script: <info>' . $profile . '</info>')
				->out('---------------------------------------');

			if (isset($scripts[$profile]))
			{
				$this->executeScriptProfile($scripts[$profile]);
			}
		}

		$this->out()->out('Complete script running.');

		return true;
	}

	/**
	 * executeScriptProfile
	 *
	 * @param   array  $scripts
	 *
	 * @return  void
	 */
	protected function executeScriptProfile($scripts)
	{
		if (ArrayHelper::getColumn($scripts, 'in'))
		{
			$msg = "We noticed you entered input data for auto answer the prompt. \nPlease install symfony/process ~3.0 to support auto answer by custom input.";

			$this->out('<fg=black;bg=yellow>' . $msg . '</fg=black;bg=yellow>');
		}

		foreach ($scripts as $script)
		{
			if (!is_array($script))
			{
				$script = ['cmd' => $script, 'in' => null];
			}

			$command = ArrayHelper::getValue($script, 'cmd');
			$input   = ArrayHelper::getValue($script, 'in');

			if ($this->executeScript($command, $input) !== 0)
			{
				throw new \RuntimeException('Running script fail...');
			}
		}
	}

	/**
	 * executeScript
	 *
	 * @param   string  $script
	 * @param   string  $input
	 *
	 * @return int
	 */
	protected function executeScript($script, $input = null)
	{
		$this->out()->out();
		$this->console->addMessage('>>> ' . $script, 'info');

		if (class_exists(Process::class))
		{
			$process = new Process($script);

			if ($input !== null)
			{
				$process->setInput($input);
			}

			$process->run(function ($type, $buffer)
			{
				if (Process::ERR === $type)
				{
					$this->err($buffer, false);
				}
				else
				{
					$this->out($buffer, false);
				}
			});
		}
		else
		{
			system($script);
		}

		return 0;
	}

	/**
	 * listScripts
	 *
	 * @param   array  $scripts
	 *
	 * @return  void
	 */
	protected function listScripts($scripts)
	{
		if (!$scripts)
		{
			$this->out()->out('No custom scripts.');

			return;
		}

		$this->out()->out('<comment>Available Scripts</comment>')
			->out('-----------------------------');

		foreach ($scripts as $name => $script)
		{
			$this->out(sprintf('<info>%s</info>', $name));

			foreach ($script as $cmd)
			{
				if (!is_array($cmd))
				{
					$cmd = ['cmd' => $cmd, 'in' => null];
				}

				$input = ArrayHelper::getValue($cmd, 'in');
				$cmd   = ArrayHelper::getValue($cmd, 'cmd');

				$this->out('    <comment>$</comment> ' . $cmd, false);

				if ($input !== null)
				{
					$this->out(sprintf('  <option>(Input: %s)</option>', preg_replace('/\s+/', ' ', $input)), false);
				}

				$this->out();
			}

			$this->out();
		}
	}
}

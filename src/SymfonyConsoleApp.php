<?php declare(strict_types = 1);
/*
 * PoiXson phpShell - Shell Utilities Library
 * @copyright 2019-2021
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpShell;

use pxn\phpUtils\Debug;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SymfonyConsoleApp extends \Symfony\Component\Console\Application {

	protected $app;
	protected $isHelp;



	public function __construct(ShellApp $app, ?bool &$isHelp) {
		$this->app = $app;
		$this->isHelp = &$isHelp;
		parent::__construct($app->getName(), $app->getVersion());
	}



	protected function configureIO(InputInterface $input, OutputInterface $output): void {
		parent::configureIO($input, $output);
		// no command
		if (!$this->getCommandName($input)) {
			$this->isHelp = TRUE;
		} else
		// --help
		if ($input->hasParameterOption(['--help', '-h'], true)) {
			$this->isHelp = TRUE;
		} else
		// help command
		if ($this->getCommandName($input) == 'help') {
			$this->isHelp = TRUE;
		// wantHelps
		} else {
			$reflect = new \ReflectionClass('Symfony\\Component\\Console\\Application');
			$prop = $reflect->getProperty('wantHelps');
			$prop->setAccessible(TRUE);
			if ($prop->getValue($this)) {
				$this->isHelp = TRUE;
			}
		}
	}



	public function doRun(InputInterface $input, OutputInterface $output): bool {
		{
			$newline = FALSE;
			if (Debug::isDebug()) {
				echo " [Debug Mode] \n";
				$newline = TRUE;
			}
//			if ($this->app->isHelp()) {
//				echo " [Help] \n";
//				$newline = TRUE;
//			}
			if ($newline) {
				echo "\n";
			}
		}
		return parent::doRun($input, $output);
	}



}

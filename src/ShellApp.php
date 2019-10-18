<?php
/*
 * PoiXson phpShell - Shell Utilities Library
 * @copyright 2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpShell;

use pxn\phpUtils\SystemUtils;
use pxn\phpUtils\Defines;
use pxn\phpUtils\Debug;


abstract class ShellApp extends \pxn\phpUtils\app\App {

	// symfony console
	protected $console = NULL;
	protected $consoleDispatch = NULL;

	protected $isHelp = NULL;

	protected $exitCode = NULL;



	public function __construct() {
		self::ValidateShell();
		parent::__construct();
		$this->initSymfonyConsole();
	}
	protected function initSymfonyConsole() {
		$this->console = new SymfonyConsoleApp($this, $this->isHelp);
		$this->console->setAutoExit(FALSE);
		$this->consoleDispatch = new \Symfony\Component\EventDispatcher\EventDispatcher();
		// default flags
		{
			$def = $this->console->getDefinition();
			// --debug
			$def->addOptions([
				new \Symfony\Component\Console\Input\InputOption(
					'debug', 'd',
					\Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
					'Enable or disable debug mode.',
					TRUE
				)
			]);
			$this->console->setDispatcher($this->consoleDispatch);
			$this->addConsoleListener([ $this, 'consoleListener' ]);
		}
	}



	public function run() {
		if (Debug::isDebug()) {
			echo " [Debug Mode] \n";
		}
		$this->console->run();
		// exit
		if ($this->isHelp()) {
			exit(Defines::EXIT_CODE_HELP);
		}
		if ($this->exitCode === NULL) {
			exit(Defines::EXIT_CODE_OK);
		}
		exit( (int)$this->exitCode );
	}



	public function consoleListener(\Symfony\Component\Console\Event\ConsoleCommandEvent $event) {
		$input = $event->getInput();
		// --debug
		if ($input->hasParameterOption('--debug', '-d')) {
			$debug = $input->getParameterOption(['--debug', '-d'], FALSE);
			$desc = 'by cli flag';
			Debug::setDebug(
				($debug === NULL ? TRUE : GeneralUtils::castBoolean($debug)),
				$desc
			);
		}
	}
	public function addConsoleListener(callable $func) {
		if ($this->consoleDispatch == NULL) {
			throw new \NullPointerException('Symfony console not initialized');
		}
		$this->consoleDispatch->addListener(
			\Symfony\Component\Console\ConsoleEvents::COMMAND,
			[ $this, 'consoleListener' ]
		);
	}



/*
	public function printFail($msg) {
		echo "\n *** FATAL: $msg *** \n\n";
	}
*/



	public function isHelp() {
		return $this->isHelp;
	}



	public static function ValidateShell() {
		if (!SystemUtils::isShell()) {
			$name = $this->getName();
			\fail("This ShellApp class can only run as shell! $name",
				Defines::EXIT_CODE_NOPERM);
		}
	}



}

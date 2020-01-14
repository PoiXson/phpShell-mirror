<?php
/*
 * PoiXson phpShell - Shell Utilities Library
 * @copyright 2019-2020
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpShell;

use pxn\phpUtils\SystemUtils;
use pxn\phpUtils\Defines;


abstract class ShellApp extends \pxn\phpUtils\app\App {

	// symfony console
	protected $console = NULL;
	protected $consoleDispatch = NULL;

	protected $isHelp = NULL;

	protected $exitCode = NULL;



	public function __construct() {
		self::AssertShell();
		parent::__construct();
		$this->initSymfonyConsole();
	}
	protected function initSymfonyConsole(): void {
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
					'Enable or disable debug mode.'
				)
			]);
			$this->console->setDispatcher($this->consoleDispatch);
			$this->addConsoleListener([ $this, 'consoleListener' ]);
		}
	}



	public function run(): void {
		$this->exitCode = $this->console->run();
		$this->doExit();
	}
	public function doExit(): void {
		// is help
		if ($this->exitCode == Defines::EXIT_CODE_HELP) {
			$this->isHelp = TRUE;
		}
		if ($this->isHelp()) {
			exit(Defines::EXIT_CODE_HELP);
		}
		if ($this->exitCode === NULL) {
			exit(Defines::EXIT_CODE_OK);
		}
		exit( (int)$this->exitCode );
	}



	public function consoleListener(\Symfony\Component\Console\Event\ConsoleCommandEvent $event): void {
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
	public function addConsoleListener(callable $func): void {
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



	public function isHelp(): bool {
		if ($this->isHelp === NULL)
			return FALSE;
		return ($this->isHelp != FALSE);
	}



	public static function AssertShell(): void {
		if (!SystemUtils::isShell()) {
			$name = $this->getName();
			throw new \RuntimeException("This script can only run as shell! $name");
		}
	}



}

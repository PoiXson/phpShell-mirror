<?php
/*
 * PoiXson phpShell - Shell Utilities Library
 * @copyright 2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpShell;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SymfonyConsoleApp extends \Symfony\Component\Console\Application {

	protected $app;
	protected $isHelp;



	public function __construct(ShellApp $app, &$isHelp) {
		$this->app = $app;
		$this->isHelp = &$isHelp;
		parent::__construct($app->getName(), $app->getVersion());
	}



	protected function configureIO(InputInterface $input, OutputInterface $output) {
		parent::configureIO($input, $output);
		// --help
		if ($input->hasParameterOption(['--help', '-h'], true)) {
			$this->isHelp = TRUE;
		} else
		// help command
		if ($this->getCommandName($input) == 'help') {
			$this->isHelp = TRUE;
		}
	}



}

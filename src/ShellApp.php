<?php
/*
 * PoiXson phpShell - Shell Utilities Library
 * @copyright 2019
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpShell;

use pxn\phpUtils\System;
use pxn\phpUtils\Defines;


abstract class ShellApp extends \pxn\phpUtils\app\App {

	protected $symfonyApp = NULL;



	public function __construct() {
		self::ValidateShell();
		$this->symfonyApp = new \Symfony\Component\Console\Application();
		parent::__construct();
	}



	public function run() {
		if (Debug()) {
			echo " [Debug Mode] \n";
		}
		$this->symfonyApp->run();
	}



/*
	public function printFail($msg) {
		echo "\n *** FATAL: $msg *** \n\n";
	}
*/



	public static function ValidateShell() {
		if (!System::isShell()) {
			$name = $this->getName();
			\fail("This ShellApp class can only run as shell! $name",
				Defines::EXIT_CODE_NOPERM);
		}
	}



}

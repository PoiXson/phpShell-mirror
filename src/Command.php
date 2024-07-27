<?php declare(strict_types = 1);
/*
 * PoiXson phpShell - Shell Utilities Library
 * @copyright 2019-2024
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpShell;

use \pxn\phpShell\ShellApp;


abstract class Command {

	protected ShellApp $app;



	public function __construct(ShellApp $app) {
		$this->app = $app;
	}



	public abstract function run(): int;



}

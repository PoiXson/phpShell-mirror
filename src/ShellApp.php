<?php declare(strict_types = 1);
/*
 * PoiXson phpShell - Shell Utilities Library
 * @copyright 2019-2024
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link https://poixson.com/
 */
namespace pxn\phpShell;

use \pxn\phpUtils\utils\SystemUtils;


abstract class ShellApp extends \pxn\phpUtils\app\xApp {

	protected array $commands = [];

	protected ?bool $is_help  = null;
	protected ?int $exit_code = null;



	public function __construct() {
		$this->assert_is_shell();
		parent::__construct();
	}



	public function run(): void {
		// load commands and flags
		$this->load_commands();
		$count_commands = count($this->commands);
		if ($count_commands == 0)
			throw new \RuntimeException('No shell commands loaded');
		if ($count_commands == 1) {
			$cmd = \reset($this->commands);
			$cmd->run();
		} else {



		}
//TODO
//		if ($this->isHelp())
		$this->doExit();
	}
	public function doExit(): void {
		if ($this->isHelp())
			$this->display_help();
		exit( (int)$this->exit_code );
	}



	protected abstract function load_commands(): void;

	public function addCommand(Command $cmd): void {
		$this->commands[] = $cmd;
	}



	public function display_help(): void {
//TODO
	}

	public function isHelp(): bool {
		return ($this->is_help===null ? false : $this->is_help);
	}
	public function notHelp(): bool {
		return ($this->is_help===null ? true : !$this->is_help);
	}



	public function assert_is_shell(): void {
		if (!SystemUtils::IsShell())
			throw new \RuntimeException('This script can only run as shell');
	}



}

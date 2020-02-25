<?php

/**
 * Loging test suite
 *
 * @package Logging.Test
 * @author  Iowa Interactive, LLC.
 */

class LoggingTest extends CakeTestSuite
{
	/**
	 * Generate and return a new test suite object
	 *
	 * @return CakeTestSuite
	 * @access public
	 */
	public static function suite()
	{
		$suite = new CakeTestSuite(__('All model tests for Loging plugin.'));
		$suite->addTestDirectory(APP_DIR.DS.'Plugin'.DS.'Logging'.DS.'Test'.DS.'Case'.DS.'Behavior');

		return $suite;
	}
}

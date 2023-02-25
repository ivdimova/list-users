<?php
/**
 * Main test class.
 */

namespace UsersIntegration\Tests;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

abstract class TestCase extends \PHPUnit\Framework\TestCase {
	use MockeryPHPUnitIntegration;

	protected function setUp() : void {

		parent::setUp();
		Monkey\setUp();

		// Require the files to be tested
		require_once dirname( __DIR__ ) . '/inc/namespace.php';
	}

	protected function tearDown() : void {

		Monkey\tearDown();
		parent::tearDown();
	}
}
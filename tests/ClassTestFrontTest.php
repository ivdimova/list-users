<?php
/**
 * Unit tests for the ApiUserIntegrationFront class.
 */

namespace UsersIntegration\Tests;

use Brain\Monkey;
use UsersIntegration as Plugin;

class ClassTestFront extends TestCase {

	public function test_rewrite_rules() {
		$rules = ['test'=>'test-content'];
		$data = new Plugin\ApiUserIntegration();
		static::assertIsArray( $data->addRules($rules));
		static::assertArrayHasKey( 'test', $data->addRules($rules));
	}

	public function test_query_vars() {
		$vars = ['test'=>'test-content'];
		$data = new Plugin\ApiUserIntegration();
		static::assertIsArray( $data->addQueryVars($vars));
		static::assertArrayHasKey( 'test', $data->addRules($vars));
	}

	public function test_users_page_template() {
		$template = 'content-users_table.php';
		$data = new Plugin\ApiUserIntegration();
		Monkey\Functions\when( 'get_query_var' )
			->justReturn( 'users_table' );
		Monkey\Functions\when( 'load_template' )
			->justReturn( null );
		Monkey\Functions\when( 'get_transient' )
			->justReturn( array('user'=>'1') );
		static::assertSame( 'content-users_table.php', $data->usersPageTemplate($template));
	}
}
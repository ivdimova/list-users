<?php
/**
 * Unit tests for the ApiUserIntegrationData class.
 */

namespace UsersIntegration\Tests;

use Brain\Monkey;
use UsersIntegration\Data;

class ClassTestData extends TestCase {
	public function test_api_response() {
		$path = 'users';

		$result = [
			'headers' => 'test-headers',
			'body' => 'test',
		];
		$data = new Data\ApiUserIntegrationData();
		$error     = \Mockery::mock( 'WP_Error' );
		Monkey\Functions\when( 'wp_remote_get' )
			->justReturn( $result );

		Monkey\Functions\when( 'wp_remote_retrieve_response_code' )
			->justReturn( 200 );
		
		Monkey\Functions\when( 'wp_remote_retrieve_body' )
			->justReturn( '[ { "id": 1, "name": "Leanne Graham" }]' );
		static::assertIsArray( $data->apiResponse($path));
	}

	public function test_cron_jobs() {
		$data = new Data\ApiUserIntegrationData();

		Monkey\Functions\when('wp_next_scheduled')
			->justReturn( false );
		Monkey\Functions\when('wp_schedule_event')
			->justReturn( true );
		$schedules = $data->registerCronJobs();
		static::assertTrue(
			$schedules
		);
	}

	public function test_all_users() {
		$data = new Data\ApiUserIntegrationData();
		$transient = ['foo', 'bar'];

		Monkey\Functions\when('get_transient')
			->justReturn($transient);
		
		$users = $data->allUsers();
		static::assertIsArray(
			$users
		);
	}
}
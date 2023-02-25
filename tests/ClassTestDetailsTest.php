<?php
/**
 * Unit tests for the ApiUserIntegrationDetails class.
 */

namespace UsersIntegration\Tests;

use Brain\Monkey;
use UsersIntegration\Details;

class ClassTestDetails extends TestCase {

	public function test_rewrite_rules() {

		$data = new Details\ApiUserIntegrationDetails();
		static::assertNotFalse( has_action( 'wp_enqueue_scripts', $data->setup() ) );
		static::assertNotFalse( has_action( 'wp_ajax_api_user_details', $data->setup() ) );
		static::assertNotFalse( has_action( 'wp_ajax_nopriv_api_user_details', $data->setup() ) );
	}

}
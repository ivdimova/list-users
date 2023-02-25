<?php

/**
 * Plugin Name: List Users.
 * Plugin URI: http:/it.ivdimova.com/
 * Author: ivdimova
 * Description: Gets users data from external API.
 * Author URI: http://it.ivdimova.com/
 * Version: 1.0
 * License: GPL2
 */

declare(strict_types=1);

namespace ListUsers;

use WP_CLI;

require_once __DIR__ . '/inc/namespace.php';

register_activation_hook(__FILE__, __NAMESPACE__ . '\\api_users_integration_activate');
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\\api_users_integration_deactivate');

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	include_once __DIR__ . '/inc/ApiUserIntegrationSync.php';

	\WP_CLI::add_command( 'users-api-sync', 'ListUsers\\Sync\\WP_CLI_Sync' );
}

function list_users_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', __NAMESPACE__ . '\\list_users_block_init' );
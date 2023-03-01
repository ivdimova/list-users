<?php
/**
 * Plugin Name: List Users.
 * Plugin URI: http:/it.ivdimova.com/
 * Author: Ivelina Dimova
 * Description: Gets list of users data from external API.
 * Author URI: http://it.ivdimova.com/
 * Version: 1.0
 * License: GPL2
 */

declare(strict_types=1);

namespace ListUsers;

use WP_CLI;

require_once __DIR__ . '/inc/namespace.php';
require_once __DIR__ . '/build/render.php';

register_activation_hook( __FILE__, __NAMESPACE__ . '\\api_users_integration_activate' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\api_users_integration_deactivate' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	include_once __DIR__ . '/inc/ListUsersSync.php';

	\WP_CLI::add_command( 'users-api-sync', 'ListUsers\\Sync\\WP_CLI_Sync' );
}

/**
 * Add the list users custom block.
 *
 * @return void.
 */
function list_users_block_init() : void {
	register_block_type( __DIR__ . '/build', [
		'render_callback' => __NAMESPACE__ . '\\list_users_render_callback',
		]
	);
}
add_action( 'init', __NAMESPACE__ . '\\list_users_block_init' );


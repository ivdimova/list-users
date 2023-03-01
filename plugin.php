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

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	include_once('inc/WPCLISync.php');

	\WP_CLI::add_command( 'users-api-sync', 'ListUsers\\WPCLISync' );
}

if (!class_exists(ListUsers::class) && is_readable(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

$data = new ListUsersData();
$plugin = new ListUsers(__FILE__, $data);
$plugin->setup();

$plugin_admin = new ListUsersAdmin($data);
$plugin_admin->setup();


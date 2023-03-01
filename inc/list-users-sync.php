<?php
/**
 * Users sync command.
 */

declare( strict_types=1 );

namespace ListUsers\Sync;

use ListUsers\Data;
use WP_CLI;
use WP_CLI_Command;

/**
 * Class containing commands to sync users from API.
 */
class WP_CLI_Sync extends WP_CLI_Command {
	/**
	 * Sync the users with WP CLI command.
	 *
	 * @return void.
	 */
	public function sync_users() : void {
		$users = new Data\ListUsersData();
		$data = $users->apiResponse( 'challenge/1/' );

		if ( is_wp_error( $data ) ) {
			WP_CLI::error( 'Could not sync users ' );
		}

		if ( ! is_array( $data ) || empty( $data ) ) {
			WP_CLI::error( 'Could not sync users: no data received from the API.' );
		}

		$users = $data['data']['rows'];
		set_transient( 'users_data', $users, HOUR_IN_SECONDS * 2 );
		WP_CLI::line( 'Success.' );
	}
}

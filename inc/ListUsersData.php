<?php
/**
 * Get the data.
 */

declare(strict_types=1);

namespace ListUsers;

use WP_Error;

/**
 * Get users data from the API.
 */
class ListUsersData {

	/**
	 * The URL that resolves to the API endpoint.
	 *
	 * @var string
	 */
	protected $apiUrl;

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->apiUrl = 'https://miusage.com/v1/';
	}

	/**
	 * Start it all.
	 */
	public function setup() : void {
		add_action( 'update_users_cron_job', [ $this, 'updateUsers' ] );
		add_action( 'init', [ $this, 'registerCronJobs' ] );
	}

	/**
	 * Register cron jobs for updating the users.
	 *
	 * @return bool|WP_Error $data.
	 */
	public function registerCronJobs(): bool|WP_Error {

		if ( ! wp_next_scheduled( 'update_users_cron_job' ) ) {
			$cron = wp_schedule_event( time(), 'hourly', 'update_users_cron_job' );
		}
		return $cron;
	}

	/**
	 * Helper function to get the API response.
	 *
	 * @param string $path The needed path to get response from the API.
	 * @return array|WP_Error $data.
	 */
	public function apiResponse( string $path ): array|WP_Error {

		$url = $this->apiUrl . $path;

		$response = wp_remote_get($url, [
			'headers' => [
				'Accept' => 'application/json',
			],
		]);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( $code !== 200 ) {
			return new WP_Error(
				$code,
				'API response error',
				compact( 'response' )
			);
		}

		$body = wp_remote_retrieve_body( $response );

		$data = json_decode( $body, true );

		return $data;
	}

	/**
	 * Update users data.
	 *
	 * @return mixed.
	 */
	public function updateUsers(): void {

		$data = $this->apiResponse( 'challenge/1/' );

		if ( ! empty( $data ) && is_array( $data ) ) {
			$users = $data['data']['rows'];
			set_transient( 'users_data', $users, HOUR_IN_SECONDS * 2 );
		}
	}

	/**
	 * Get users data.
	 *
	 * @return array $data Users data.
	 */
	public function allUsers(): array|WP_Error {

		$data = get_transient( 'users_data' );
		if ( empty( $data ) ) {
			$this->updateUsers();
			$data = get_transient( 'users_data' );
		}
		if ( ! is_array( $data ) ) {
			return new WP_Error(
				'No data',
			);
		}
		return $data;
	}
}

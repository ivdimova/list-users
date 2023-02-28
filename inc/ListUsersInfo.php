<?php

/**
 * Display the users info.
 */

declare(strict_types=1);

namespace ListUsers\Info;

use ListUsers\Data;

class ListUsersInfo
{
    /**
     * Setup.
     *
     * @return void
     */
    public function setup(): void
    {

		add_action('wp_enqueue_scripts', [$this, 'ajaxUsersScript']);
        add_action('wp_ajax_api_user_details', [$this, 'usersInfo']);
        add_action('wp_ajax_nopriv_api_user_details', [$this, 'usersInfo']);
    }

	 /**
     * Data for the user details ajax.
     *
     * @return void
     */
    public function usersInfo(): void
    {
		if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'api_user_details' ) ) {
			wp_send_json_error( __( 'Unauthorized', 'list-users' ), 403 );
		}
		$users = new Data\ListUsersData();
        $usersData = $users->allUsers();
		wp_send_json_success($usersData);
	}

    /**
     * Render user data.
     *
     * @return void
     */
    public function userDetails($idChecked, $nameChecked, $emailChecked): void
    {

        $users = new Data\ListUsersData();
        $usersData = $users->allUsers();
		
        if (empty($usersData)) {
            return;
        } ?>        
        <table id="user-details"
		action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'api_user_details' ) ); ?>"
		>
        <tbody>
            <tr>
				<?php if ( $idChecked !== true ) {  ?>
					<th><?php echo esc_html__('User ID', 'list-users'); ?></th>
				<?php } ?>
				<?php if ( $nameChecked !== true ) { ?>
					<th><?php echo esc_html__('User Name', 'list-users'); ?></th>
				<?php } ?> 
				<?php if ( $emailChecked !== true ) { ?>
					<th><?php echo esc_html__('Email', 'users'); ?></th>
				<?php } ?>  
            </tr>
			<?php foreach ($usersData as $user) {
				
				$userName = isset($user['fname']) ? $user['fname'] : '';
				$userLast = isset($user['lname']) ? $user['lname'] : '';
				$userId = isset($user['id']) ? $user['id'] : '';
				$userEmail = isset($user['email']) ? $user['email'] : '';
				?>
            <tr class="user-data">
				<?php if ( $idChecked !== true ) { ?>
					<td class="user_id">
						<?php echo esc_html($userId); ?>
					</td>
				<?php } ?>
				<?php if ( $nameChecked !== true ) { ?>
					<td class="user_name">
						<?php echo esc_html($userName) . ' ' . esc_html($userLast); ?>
					</td>
				<?php } ?>
				<?php if ( $emailChecked !== true ) { ?>
					<td class="user_email">
						<?php echo esc_html($userEmail); ?>
					</td>
				<?php } ?>
            </tr>
		<?php
        } 
		?>
            </tbody>
        </table>
	<?php
    }

    /**
     * Enqueue scripts.
     *
     * @return void
     */
    public function ajaxUsersScript(): void
    {

        wp_register_script(
            'api-users-script',
            plugins_url('js/custom.js', __FILE__),
            [],
            '0.0.2',
            true
        );

        $scriptDataArray = [
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('api_user_details'),
        ];
        wp_localize_script('api-users-script', 'ajax_object', $scriptDataArray);

        wp_enqueue_script('api-users-script');
    }

}
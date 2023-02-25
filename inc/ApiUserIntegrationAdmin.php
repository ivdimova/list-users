<?php

/**
 * Setup Admin Settings.
 */

 declare(strict_types=1);

namespace ListUsers\Admin;

use ListUsers\Data;
use WP_Error;

class ListUsersAdmin
{
	public function __construct()
	{
	}
	/**
	 * Start the admin settings.
	 *
	 * @return void.
	 */
	function setup() : void {
		add_action( 'admin_menu', [ $this, 'list_users_admin_page' ] );
		add_action( 'admin_init', [ $this, 'list_users_register_settings' ] );
	}

	/**
	 * Add admin page for the List users.
	 *
	 * @return void.
	 */
	public function list_users_admin_page() : void {
		add_menu_page(
			esc_html__( 'List Users', 'users' ),
			esc_html__( 'List Users', 'users' ),
			'manage_options',
			'apiusers',
			[$this, 'api_users_settings'],
			'dashicons-admin-generic',
			99
		);
	}

	/**
	 * Register admin settings.
	 *
	 * @return void.
	 */
	public function list_users_register_settings() : void {
		$page_slug = 'apiusers_admin';
		$option_group = 'apiusers_settings';

		add_settings_section(
			'api_users_settings_section',
			'',
			'',
			$page_slug
		);

		register_setting( $option_group, 'az_taxonomy', 'sanitize_text_field' );

		add_settings_field(
			'api-users-refresh',
			esc_html__( 'Click to Refresh: ', 'users' ),
			[$this, 'api_users_field'],
			$page_slug,
			'api_users_settings_section'
		);
	}

	/**
	 * Refresh hidden field.
	 *
	 * @return void.
	 */
	public function api_users_field() : void {
	?>
	<label>
		<input type="hidden" name="api-users-refresh" value="true"/>
	</label>
	<?php
}

	/**
	 * Settings display for API Users plugin.
	 *
	 * @return void.
	 */
	public function api_users_settings() : void {
		$users = new Data\ListUsersData();
		$users_data = $users->allUsers();
		?>
		<div class="wrap">
			<div id="icon-edit" class="icon32 icon32-base-template"><br></div>
			<h1><?php echo esc_html( get_admin_page_title( 'apiusers-admin' ) ); ?></h1>
			<table id="users-table">
        <tbody>
            <tr>
                <th><?php echo esc_html__('Name', 'users'); ?></th>
                <th><?php echo esc_html__('ID', 'users'); ?></th>
                <th><?php echo esc_html__('Email', 'users'); ?></th>
            </tr>
            <?php
            foreach ($users_data as $user) { 
                $userId = isset($user['id']) ? $user['id'] : 0;
                $userName = isset($user['fname']) ? $user['fname'] : '';
				$userLast = isset($user['lname']) ? $user['lname'] : '';
                $userUsername = isset($user['email']) ? $user['email'] : '';
                ?>
                 <tr>
                    <td>
                        <span data-user_id="<?php echo esc_attr($userId); ?>">
                        <?php echo esc_html($userName); ?> <?php echo esc_html($userLast); ?></span>
                    </td>
                    <td>
                        <span data-user_id="<?php echo esc_attr($userId); ?>">
                        <?php echo esc_html($userId); ?></span>
                    </td>
                    <td>
                        <span data-user_id="<?php echo esc_attr($userId); ?>">
                        <?php echo esc_html($userUsername); ?></span>
                    </td>
                </tr>
            <?php }
            ?>
       </tbody>
    </table>

			<form id="api-users-form" action="options.php?page=apiusers" method="POST">
			<?php 
			
			if (
				isset($_POST['_wpnonce'])
				//check_admin_referer('refresh_button')
			) {
				$this->refresh_users();
			}
				wp_nonce_field('refresh_button'); 
				settings_fields( 'apiusers_settings' );
				do_settings_sections( 'apiusers_admin' );
				submit_button( esc_html__( 'Refresh users ', 'users' ));
				
				?>
	
				</form>
			</div>
		<?php
	}

	/**
	 * Settings display for API Users plugin.
	 *
	 * @return void.
	 */
	public function refresh_users() : void {
		
		if (! isset($_POST['api-users-refresh'])) {
           return;
        }

		if ( 'true' === $_POST['api-users-refresh'] ) {
			$users = new Data\ListUsersData();
			$users->updateUsers();
			
			echo '<h2>' . esc_html__( 'Users Updated', 'users' ) . '</h2>';
		}
	}
}
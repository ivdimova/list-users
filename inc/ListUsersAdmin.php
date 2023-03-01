<?php

/**
 * Setup Admin Settings.
 */

 declare(strict_types=1);

namespace ListUsers;

class ListUsersAdmin {

	protected $data;

	public function __construct($data) {
		$this->data = $data;
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
			esc_html__( 'List Users', 'list-users' ),
			esc_html__( 'List Users', 'list-users' ),
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

		add_settings_field(
			'api-users-refresh',
			esc_html__( 'Click to Refresh: ', 'list-users' ),
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
		$users_data = $this->data->allUsers();
		?>
		<div class="wrap">
			<div id="icon-edit" class="icon32 icon32-base-template"><br></div>
			<h1><?php echo esc_html( get_admin_page_title( 'list-users-admin' ) ); ?></h1>
			<table id="users-table">
        <tbody>
            <tr>
                <th><?php echo esc_html__('Name', 'list-users'); ?></th>
                <th><?php echo esc_html__('ID', 'list-users'); ?></th>
                <th><?php echo esc_html__('Email', 'list-users'); ?></th>
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
                        <span data-user_id="<?php echo esc_attr( $userId ); ?>">
                        <?php echo esc_html( $userName ); ?> <?php echo esc_html( $userLast ); ?></span>
                    </td>
                    <td>
                        <span data-user_id="<?php echo esc_attr( $userId ); ?>">
                        <?php echo esc_html( $userId ); ?></span>
                    </td>
                    <td>
                        <span data-user_id="<?php echo esc_attr( $userId ); ?>">
                        <?php echo esc_html( $userUsername ); ?></span>
                    </td>
                </tr>
            <?php }
            ?>
       </tbody>
    </table>

	<form id="api-users-form" action="options.php?page=apiusers" method="POST"?>
		<?php if ( isset($_POST['api-users-refresh'] ) )  {
				$this->refresh_users();
			}
				settings_fields( 'apiusers_settings' );
				do_settings_sections( 'apiusers_admin' );
				submit_button( esc_html__( 'Refresh users ', 'list-users' ));
				?>
				</form>
			</div>
		<?php
	}

	/**
	 * Settings display for List Users plugin.
	 *
	 * @return void.
	 */
	public function refresh_users() : void {

		if ( isset($_POST['api-users-refresh'] ) && 'true' === $_POST['api-users-refresh'] ) {
			$this->data->updateUsers();
			echo '<h2>' . esc_html__( 'Users Updated', 'list-users' ) . '</h2>';
		}
	}
}
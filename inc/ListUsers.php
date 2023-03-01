<?php
/**
 * Users front end display.
 */

declare(strict_types=1);

namespace ListUsers;

class ListUsers

{	
	/**
     * The API.
     *
     * @var string
     */
    protected $api;

	/**
     * The API.
     *
     * @var string
     */
    protected $admin;

    /**
     * The URL that resolves to the API endpoint.
     *
     * @var array
     */
    private $rules;

	protected $data;

	protected $path;

    public function __construct( $path, $data)
    {
        $this->rules = [
            '^users_table/?' => 'index.php?users_table=1',
        ];
		$this->data = $data;
		register_activation_hook( $path, [$this, '\api_users_integration_activate' ] );
		register_deactivation_hook( $path, [$this, '\api_users_integration_activate' ] );
    }

    /**
     * Start it all.
     */
    public function setup(): void
    {

        add_filter( 'rewrite_rules_array', [$this, 'addRules'] );
        add_filter( 'query_vars', [$this, 'addQueryVars'] );
		add_action( 'parse_request', [$this, 'showAjaxRequest'] ) ;
		add_action( 'enqueue_block_editor_assets', [ $this, 'editorScript' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'ajaxUsersScript' ] );
		add_action( 'wp_ajax_api_user_details', [ $this, 'usersInfo' ] );
		add_action( 'wp_ajax_nopriv_api_user_details', [ $this, 'usersInfo' ] );
		add_action( 'init', [$this, 'listUsersBlockInit' ] );
    }

    /**
     * @param array $vars Added query vars.
     *
     * @return array
     */
    public function addQueryVars( array $vars ): array
    {

        $vars[] = 'users_table';

        return $vars;
    }

    /**
     * @param array $rules Rewrite rules to add.
     *
     * @return array
     */
    public function addRules( array $rules ): array
    {
        return $this->rules + $rules;
    }

	public function showAjaxRequest( $query ) {
		if ( isset( $query->query_vars['users_table'] ) &&
			 "1" === $query->query_vars['users_table']
		) {
			wp_send_json( $this->userDetails( false, false, false ) );
		}
	}

	 /**
	  * Data for the user details ajax.
	  *
	  * @return void
	  */
	  public function usersInfo(): void {
		if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'api_user_details' ) ) {
			wp_send_json_error( __( 'Unauthorized', 'list-users' ), 403 );
		}

		$usersData = $this->data->allUsers();
		wp_send_json_success( $usersData );
	}

	/**
	 * Render user data.
	 *
	 * @return void
	 */
	public function userDetails( $idChecked, $nameChecked, $emailChecked ): void {
		//var_dump($this->data);
		//die();
		$usersData = $this->data->allUsers();
		

		if ( empty( $usersData ) ) {
			return;
		} ?>        
		<table id="user-details"
		action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'api_user_details' ) ); ?>"
		>
		<tbody>
			<tr>
				<?php if ( $idChecked !== true ) { ?>
					<th><?php echo esc_html__( 'User ID', 'list-users' ); ?></th>
				<?php } ?>
				<?php if ( $nameChecked !== true ) { ?>
					<th><?php echo esc_html__( 'User Name', 'list-users' ); ?></th>
				<?php } ?> 
				<?php if ( $emailChecked !== true ) { ?>
					<th><?php echo esc_html__( 'Email', 'users' ); ?></th>
				<?php } ?>  
			</tr>
			<?php
			foreach ( $usersData as $user ) {

				$userName = isset( $user['fname'] ) ? $user['fname'] : '';
				$userLast = isset( $user['lname'] ) ? $user['lname'] : '';
				$userId = isset( $user['id'] ) ? $user['id'] : '';
				$userEmail = isset( $user['email'] ) ? $user['email'] : '';
				?>
			<tr class="user-data">
				<?php if ( $idChecked !== true ) { ?>
					<td class="user_id">
						<?php echo esc_html( $userId ); ?>
					</td>
				<?php } ?>
				<?php if ( $nameChecked !== true ) { ?>
					<td class="user_name">
						<?php echo esc_html( $userName ) . ' ' . esc_html( $userLast ); ?>
					</td>
				<?php } ?>
				<?php if ( $emailChecked !== true ) { ?>
					<td class="user_email">
						<?php echo esc_html( $userEmail ); ?>
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
	public function ajaxUsersScript(): void {

		wp_register_script(
			'api-users-script',
			plugins_url( 'js/custom.js', __FILE__ ),
			[],
			'0.0.2',
			true
		);

		$scriptDataArray = [
			'url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'api_user_details' ),
		];
		wp_localize_script( 'api-users-script', 'ajax_object', $scriptDataArray );

		wp_enqueue_script( 'api-users-script' );
	}

	/**
	 * Add the list users custom block.
	 *
	 */
	public function listUsersBlockInit() {
		register_block_type( realpath(__DIR__ . DIRECTORY_SEPARATOR . '../build'), [
			'render_callback' => [$this, 'list_users_render_callback'],
			]
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function editorScript() {
		wp_enqueue_script( 'api-users-script', plugins_url( 'js/custom.js', __FILE__ ) );
	}

	public function api_users_integration_activate()
	{

		flush_rewrite_rules();
	}

	public function api_users_integration_deactivate()
	{
		flush_rewrite_rules();
	}

	public function list_users_render_callback( $block_attributes, $content ) {
		
		$is_block_editor = is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST );
		$idChecked = isset( $block_attributes['idChecked'] ) ? $block_attributes['idChecked'] : false;
		$nameChecked = isset( $block_attributes['nameChecked'] ) ? $block_attributes['nameChecked'] : false;
		$emailChecked = isset( $block_attributes['emailChecked'] ) ? $block_attributes['emailChecked'] : false;
		$backgroundColor = isset( $block_attributes['backgroundColor'] ) ? $block_attributes['backgroundColor'] : '#000';
		$color = isset( $block_attributes['color'] ) ? $block_attributes['color'] : '#fff';
			
		ob_start();?>
			<div class="wp-block-ivdimova-list-users" style="background:<?php echo esc_attr( $backgroundColor ); ?>; color: <?php echo esc_attr( $color ); ?>">
			<?php if ( ! $is_block_editor ) : ?>
				<h2 id="users-title"><?php echo esc_html( $block_attributes['title'] ); ?></h2>
			<?php endif; ?>
				<?php $this->userDetails( $idChecked, $nameChecked, $emailChecked );?>
			</div>
			<script>
				window.listUsers = <?php echo wp_json_encode( $block_attributes ); ?>;
			</script>
		<?php return (string) ob_get_clean();
	}
	
}

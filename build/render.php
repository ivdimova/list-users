<?php
/**
 * Render the user data.
 */
namespace ListUsers;

use ListUsers\Info;

/**
 * Render function for the block.
 * @param array $block_attrubutes 
 * @param string $content
 * @return string.
 */
function list_users_render_callback( $block_attributes, $content ) {
	$is_block_editor = is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST );
	$users_display = new Info\ListUsersInfo();
	$idChecked = isset( $block_attributes['idChecked'] ) ? $block_attributes['idChecked'] : false;
	$nameChecked = isset( $block_attributes['nameChecked'] ) ? $block_attributes['nameChecked'] : false;
	$emailChecked = isset( $block_attributes['emailChecked'] ) ? $block_attributes['emailChecked'] : false;
	$backgroundColor = isset( $block_attributes['backgroundColor'] ) ? $block_attributes['backgroundColor'] : '#000';
	$color = isset( $block_attributes['color'] ) ? $block_attributes['color'] : '#fff';
		
	ob_start();?>
		<div class="wp-block-ivdimova-list-users" style="background:<?php echo esc_attr( $backgroundColor ); ?>; color: <?php echo esc_attr( $color ); ?>">
		<?php if ( ! $is_block_editor ) : ?>
			<h2><?php echo esc_html( $block_attributes['title'] ); ?></h2>
		<?php endif; ?>
			<?php $users_display->userDetails( $idChecked, $nameChecked, $emailChecked );?>
		</div>
	<?php return (string) ob_get_clean();
}
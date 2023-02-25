<?php

/**
 * Display the user details.
 */

declare(strict_types=1);

namespace ListUsers\Details;

use ListUsers\Data;

class ListUsersDetails
{
    /**
     * Setup.
     *
     * @return void
     */
    public function setup(): void
    {

        add_action('wp_enqueue_scripts', [$this, 'ajaxUsersScript']);
        add_action('wp_ajax_api_user_details', [$this, 'userDetails']);
        add_action('wp_ajax_nopriv_api_user_details', [$this, 'userDetails']);
    }

    /**
     * Data for the user details ajax.
     *
     * @return void
     */
    public function userDetails(): void
    {

        if (
            ! isset($_POST['nonce']) ||
            ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'api_user_details')
        ) {
            wp_send_json_error(__('Unauthorized', 'users'), 403);
        }

        if (! isset($_POST['user_id'])) {
            wp_send_json_error(__('Bad request', 'users'), 400);
        }

        $userId = (int) sanitize_text_field(wp_unslash($_POST['user_id']));
        $users = new Data\ListUsersData();
        $usersData = $users->allUsers();
        if (empty($usersData)) {
            return;
        }
        foreach ($usersData as $user) {
            if ($user['id'] === $userId) {
                $userName = isset($user['fname']) ? $user['fname'] : '';
                $userLast = isset($user['lname']) ? $user['lname'] : '';
                $userId = isset($user['id']) ? $user['id'] : '';
                $userEmail = isset($user['email']) ? $user['email'] : '';
                ?>
        <table id="user-details">
        <tbody>
            <tr>
                <th><?php echo esc_html__('Name', 'users'); ?></th>
                <th><?php echo esc_html__('Last name', 'users'); ?></th>
                <th><?php echo esc_html__('Id', 'users'); ?></th>
                <th><?php echo esc_html__('Email', 'users'); ?></th>
            </tr>
                <tr>
                    <td><?php echo esc_html($userName); ?></a></td>
                    <td><?php echo esc_html($userLast); ?></a></td>
                    <td><?php echo esc_html($userId); ?></a></td>
                    <td><?php echo esc_html($userEmail); ?></a></td>
                </tr>
            </tbody>
        </table>
        <a href=<?php echo esc_url(get_home_url() . '/users_table/'); ?>>
                <?php echo esc_html__('Back', 'users'); ?>
        </a>
                <?php
            }
        }

        wp_die();
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
            [ 'jquery' ],
            '0.0.1',
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
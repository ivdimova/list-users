<?php

/**
 * Users front end display.
 */

declare(strict_types=1);

namespace ListUsers;

use ListUsers\Data;
use WP_Error;

class ListUsers
{
    /**
     * The URL that resolves to the API endpoint.
     *
     * @var array
     */
    private $rules;

    public function __construct()
    {
        $this->rules = [
            'users_table' => 'index.php?users_table=$matches[1]',
        ];
    }

    /**
     * Start it all.
     */
    public function setup(): void
    {

        add_filter('rewrite_rules_array', [$this, 'addRules']);
        add_filter('query_vars', [$this, 'addQueryVars']);
        add_filter('template_include', [$this, 'usersPageTemplate'], 99);
    }

    /**
     * @param array $vars Added query vars.
     *
     * @return array
     */
    public function addQueryVars(array $vars): array
    {

        $vars[] = 'users_table';

        return $vars;
    }

    /**
     * @param array $rules Rewrite rules to add.
     *
     * @return array
     */
    public function addRules(array $rules): array
    {
        return $this->rules + $rules;
    }

    /**
     * Loads the template and send the user data.
     *
     * @param string $template Path to template.
     * @return string $template Path to template.
     */
    public function usersPageTemplate(string $template): string
    {

        if (get_query_var('users_table', false) !== false) {
            $templateName = 'content-users_table.php';
            $users = new Data\ListUsersData();
            $usersData = $users->allUsers();
            (load_template(__DIR__ . '/templates/' . $templateName, true, $usersData) );
        }
        return $template;
    }
}

function api_users_integration_activate()
{

    flush_rewrite_rules();
}

function api_users_integration_deactivate()
{

    flush_rewrite_rules();
}

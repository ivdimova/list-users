<?php

/**
 * Users front end display.
 */

declare(strict_types=1);

namespace ListUsers;

use ListUsers\Info;
use PHP_CodeSniffer\Reports\Info as ReportsInfo;
use WP;

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
            '^users_table/?' => 'index.php?users_table=1',
        ];
    }

    /**
     * Start it all.
     */
    public function setup(): void
    {

        add_filter( 'rewrite_rules_array', [$this, 'addRules'] );
        add_filter( 'query_vars', [$this, 'addQueryVars'] );
		add_action( 'parse_request', [$this, 'showAjaxRequest'] ) ;
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
			$info = new Info\ListUsersInfo();
			wp_send_json( $info->userDetails( false, false, false ) );
		}
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

<?php
/**
 * List users setup
 */

declare(strict_types=1);

namespace ListUsers;

require_once __DIR__ . '/list-users.php';
require_once __DIR__ . '/list-users-data.php';
require_once __DIR__ . '/ListUsersInfo.php';
require_once __DIR__ . '/list-users-admin.php';

$plugin = new ListUsers();
$plugin->setup();

$info = new Info\ListUsersInfo();
$info->setup();

$admin = new Admin\ListUsersAdmin();
$admin->setup();

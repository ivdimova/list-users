<?php

/**
 * List users setup
 */

declare(strict_types=1);

namespace ListUsers;

require_once __DIR__ . '/ListUsers.php';
require_once __DIR__ . '/ListUsersData.php';
require_once __DIR__ . '/ListUsersInfo.php';
require_once __DIR__ . '/ListUsersAdmin.php';

$plugin = new ListUsers();
$plugin->setup();

$info = new Info\ListUsersInfo();
$info->setup();

$admin = new Admin\ListUsersAdmin();
$admin->setup();
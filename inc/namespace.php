<?php

/**
 * Users integration setup.
 */

declare(strict_types=1);

namespace ListUsers;

require_once __DIR__ . '/ApiUserIntegration.php';
require_once __DIR__ . '/ApiUserIntegrationData.php';
require_once __DIR__ . '/ApiUserIntegrationDetails.php';
require_once __DIR__ . '/ApiUserIntegrationAdmin.php';

$plugin = new ListUsers();
$plugin->setup();

$details = new Details\ListUsersDetails();
$details->setup();

$admin = new Admin\ListUsersAdmin();
$admin->setup();
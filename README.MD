## User Integration Plugin
This plugin integrates user information from external API. The data can be used as a custom block, called List users.
There is an admin page, List Users, where a data preview can be seen and refreshed.
The data from the API is stored as transient, but can be also refreshed with a `wp cli` command - `wp users-api-sync sync_users `

### WIP 
This plugin is a proof of concept more than a real plugin, it's mostly done for a test.

### Usage
Run `composer install` to install dependencies and to autoload the files, then run `npm install` and `npm run build` if you want to develop more features. Run `vendor/bin/phpcs .` to use coding standards.
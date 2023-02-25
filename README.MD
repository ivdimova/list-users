## User Integration Plugin
This plugin integrates user information from external API. 

### Users page
Navigate to `/users-table/` to see the list of the users and click on their name, id or user name to see more details.

### Installation
The plugin can be just dropped in the plugins folder. Activate the User Integration plugin. If the `/users-table/` page is not found, then navigate to Settings->Permalinks and click Save Changes in order to flush the permalinks. Or run `wp rewrite flush` if you have access to `wp cli` commands.

Navigate to the folder and run `composer install` if you want to work on the plugin.

### Implementation notes
This plugin requests data for the users from external API. It caches the data with transients. While transients are a debatable way of caching as they might disappear at any time, we don't know what caching mechanism for object cache will have the application. If we knew for sure it will have Memcached or Redis, I would better just save it in the object cache.<br>
**Details Note:** I haven't seen any details endpoint, so got the rest of the user data on the details per user page, from the same endpoint. Of course if you have more to add to your task description, please let me know.
**JS Note:** As this was the fastest method to build, I've implemented it with jQuery, however if the requirement is for vanilla js, I am happy to rework it.

### Tests
Run `vendor/bin/phpunit --testsuite APIUserTests` <br>
**Note:** While I am a big fan of well tested code, the testing in isolation is not something I've used before and in my opinion limits the tests. We are not writing an isolated application, but a WordPress plugin, which depends in a huge way of the core functionality. Even if I mock it, which of course, I can do with Brain Monkey, this will allow me to only test a limited part of my plugin. Ex. I can check if a function returns an array by mocking an WP core function that returns an array, but this doesn't mean at all that I will get the right data in the end. So in short, I've done several of these isolated tests, but I believe more in integration and acceptance tests. Of course, I am sure these tests here can be expanded and the code can be better organised, in order to make this more sensible, as I said, this is my very first play with Mockery and isolated tests. 

### Coding Standards
Run `vendor/bin/phpcs .` <br>
**Note:** Again while I am a big fan of the coding standards, these are just against what I've been using for years and in my opinion make the code a way harder to read. Escpecially the camelCase vars and the lack of spacing in the braces. 

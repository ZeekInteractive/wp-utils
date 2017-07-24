# WordPress Utils

A collection of functions that provide utility functionality for WordPress.

## Functions

* `get_current_url()`: Returns the current URL.
* `get_current_url_clean()`: Returns the current URL, but without query args.
* `get_id_from_slug( $slug, $post_type = 'post', $force = false )` : Performs a lookup for a post given a slug.
* `get_raw_option_value( $key )`: Performs a very direct, simple query to the WordPress Options table that bypasses normal WP caching.
* `get_user_display_name( $user_id )`: Easily get the user display name by the user ID.
* `get_env_value( $key, $filter = null )`: Helper function to check for an environmental variable in a variety of places: $_ENV (for setting via .env.php files), Constant (for setting via a define() call), Filter, utilizing a passed in filter
* `remove_filters_for_anonymous_class( $hook_name = '', $class_name = '', $method_name = '', $priority = 10 )`: Remove a filter/action from an anonymous class

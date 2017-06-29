# WordPress Utils

A collection of functions that provide utility functionality for WordPress.

## Functions

* `get_current_url()`: Returns the current URL.
* `get_current_url_clean()`: Returns the current URL, but without query args.
* `get_id_from_slug( $slug, $post_type = 'post', $force = false )` : Performs a lookup for a post given a slug.
* `get_raw_option_value( $key )`: Performs a very direct, simple query to the WordPress Options table that bypasses normal WP caching.
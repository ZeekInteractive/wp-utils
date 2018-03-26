# WordPress Utils
[![Build Status](https://travis-ci.com/ZeekInteractive/wp-utils.svg?token=G7VpgBxZppY89CGiy3Pn&branch=develop)](https://travis-ci.com/ZeekInteractive/wp-utils)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0cea33cc3eb4454ab05b31bf87a721d8)](https://www.codacy.com?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ZeekInteractive/wp-utils&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/0cea33cc3eb4454ab05b31bf87a721d8)](https://www.codacy.com?utm_source=github.com&utm_medium=referral&utm_content=ZeekInteractive/wp-utils&utm_campaign=Badge_Coverage)

Utility functions that make life just a little bit easier.

## Database

### `get_raw_option_value( $key )`
Performs a very direct, simple query to the WordPress Options table that bypasses normal WP caching.

### `get_id_from_slug( $slug, $post_type = 'post', $force = false )`
Performs a lookup for a post given a slug.

### `get_meta_key_from_meta_value()`
Perform a reverse lookup for a meta key based on a meta value.

## ACF

### `get_acf_meta_value_by_acf_key()`
Use this when you know an ACF field key and a post ID, but the field is within a group.

### `is_acf_loadable()` (Deprecated)
Check to see if ACF is loadable and if ACF_LITE is true.

## Misc

### `get_current_url()`
Returns the current URL.

### `get_current_url_clean()`
Returns the current URL, but without query args.

### `get_user_display_name( $user_id )`
Easily get the user display name by the user ID.

### `get_env_value( $key, $filter = null )`
Helper function to check for an environmental variable in a variety of places: $_ENV (for setting via .env.php files), Constant (for setting via a define() call), Filter, utilizing a passed in filter

### `remove_filters_for_anonymous_class( $hook_name = '', $class_name = '', $method_name = '', $priority = 10 )`
Remove a filter/action from an anonymous class

### `add_inline_svg()`
Checks if SVG file exists before grabbing its contents

### `get_current_datetime()`
Gets a DateTime object set to WordPress's local timezone

### `get_site_timezone()`
Get a DateTimeZone object that is set to the site's local timezone.

### `init_term( $slug, $taxonomy )`
Checks for and returns a term by the slug. Initializes the term if it does not yet exist.

## Behaviors / Filters

These must be initiated by creating the `\Zeek\WP_Util\Behaviors()` class.

### `file_mod_allowed`
By default, disable file modifications (plugin adding, deleting, theme file editing, etc). Override with an `env` constant: `FILE_MOD_ALLOWED`.
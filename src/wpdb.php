<?php
/**
 * These functions are wrappers for the respective $wpdb methods, which adds the following functionality:
 *  - Does not output SQL errors to the page, even when WP_DEBUG is enabled. (Normally SQL errors are always output to
 *    the page when WP_DEBUG is enabled, which may be undesirable even during development.)
 *  - Throws an exception when an SQL error occurs. This allows for easier/better error handling.
 *  - Allows for a simpler syntax for calling $wpdb methods without having to import the $wpdb global variable into application functions.
 */

namespace Zeek\WP_Util;

/**
 * Delete a row in the table
 *
 *     wpdb::delete( 'table', array( 'ID' => 1 ) )
 *     wpdb::delete( 'table', array( 'ID' => 1 ), array( '%d' ) )
 *
 * Does not output errors to the page, but throws an exception if an error occurs.
 *
 * @param string       $table        Table name
 * @param array        $where        A named array of WHERE clauses (in column => value pairs).
 *                                   Multiple clauses will be joined with ANDs.
 *                                   Both $where columns and $where values should be "raw".
 *                                   Sending a null value will create an IS NULL comparison - the corresponding format will be ignored in this case.
 * @param array|string $where_format Optional. An array of formats to be mapped to each of the values in $where.
 *                                   If string, that format will be used for all of the items in $where.
 *                                   A format is one of '%d', '%f', '%s' (integer, float, string).
 *                                   If omitted, all values in $where will be treated as strings unless otherwise specified in wpdb::$field_types.
 *
 * @return int The number of rows updated.
 *
 * @throws \Exception
 */
function wpdb_delete( string $table, array $where, $where_format = null ) {
	return _wrap_wpdb_func( 'delete', func_get_args() );
}

/**
 * Retrieve one column from the database.
 *
 * Executes a SQL query and returns the column from the SQL result.
 * If the SQL result contains more than one column, this function returns the column specified.
 * If $query is null, this function returns the specified column from the previous SQL result.
 *
 * Does not output errors to the page, but throws an exception if an error occurs.
 *
 * @param string $query Optional. SQL query. Defaults to previous query.
 * @param int    $x     Optional. Column to return. Indexed from 0.
 *
 * @return array Database query result. Array indexed from 0 by SQL result row number.
 *
 * @throws \Exception
 */
function wpdb_get_col( string $query = null, int $x = 0 ) {
	return _wrap_wpdb_func( 'get_col', func_get_args() );
}

/**
 * Retrieve an entire SQL result set from the database (i.e., many rows)
 *
 * Executes a SQL query and returns the entire SQL result.
 *
 * Does not output errors to the page, but throws an exception if an error occurs.
 *
 * @param string $query  SQL query.
 * @param string $output Optional. Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
 *                       With one of the first three, return an array of rows indexed from 0 by SQL result row number.
 *                       Each row is an associative array (column => value, ...), a numerically indexed array (0 => value, ...), or an object. ( ->column = value ), respectively.
 *                       With OBJECT_K, return an associative array of row objects keyed by the value of each row's first column's value.
 *                       Duplicate keys are discarded.
 *
 * @return array|object Database query results
 *
 * @throws \Exception
 */
function wpdb_get_results( string $query, $output = OBJECT ) {
	return _wrap_wpdb_func( 'get_results', func_get_args() );
}

/**
 * Retrieve one row from the database.
 *
 * Executes a SQL query and returns the row from the SQL result.
 *
 * Does not output errors to the page, but throws an exception if an error occurs.
 *
 * @param string $query  SQL query.
 * @param string $output Optional. The required return type. One of OBJECT, ARRAY_A, or ARRAY_N, which correspond to
 *                       an stdClass object, an associative array, or a numeric array, respectively. Default OBJECT.
 * @param int    $y      Optional. Row to return. Indexed from 0.
 *
 * @return array|object|null Database query result in format specified by $output or null on failure
 *
 * @throws \Exception
 */
function wpdb_get_row( string $query, $output = OBJECT, $y = 0 ) {
	return _wrap_wpdb_func( 'get_row', func_get_args() );
}

/**
 * Retrieve one variable from the database.
 *
 * Executes a SQL query and returns the value from the SQL result.
 * If the SQL result contains more than one column and/or more than one row, this function returns the value in the column and row specified.
 * If $query is null, this function returns the value in the specified column and row from the previous SQL result.
 *
 * Does not output errors to the page, but throws an exception if an error occurs.
 *
 * @param string $query Optional. SQL query. Defaults to null, use the result from the previous query.
 * @param int    $x     Optional. Column of value to return. Indexed from 0.
 * @param int    $y     Optional. Row of value to return. Indexed from 0.
 *
 * @return string Database query result (as string)
 *
 * @throws \Exception
 */
function wpdb_get_var( string $query, $x = 0, $y = 0 ) {
	return _wrap_wpdb_func( 'get_var', func_get_args() );
}

/**
 * Insert a row into a table.
 *
 *     wpdb::insert( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
 *     wpdb::insert( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
 *
 * Does not output errors to the page, but throws an exception if an error occurs.
 *
 * @param string       $table  Table name
 * @param array        $data   Data to insert (in column => value pairs).
 *                             Both $data columns and $data values should be "raw" (neither should be SQL escaped).
 *                             Sending a null value will cause the column to be set to NULL - the corresponding format is ignored in this case.
 * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data.
 *                             If string, that format will be used for all of the values in $data.
 *                             A format is one of '%d', '%f', '%s' (integer, float, string).
 *                             If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
 * @return int|false The number of rows inserted, or false on error.
 *
 * @throws \Exception
 */
function wpdb_insert( string $table, array $data, $format = null ) {
	return _wrap_wpdb_func( 'insert', func_get_args() );
}

/**
 * Perform a MySQL database query, using current database connection.
 *
 * Does not output errors to the page, but throws an exception if an error occurs.
 *
 * @param string $query Database query
 *
 * @return int Number of rows affected/selected
 *
 * @throws \Exception
 */
function wpdb_query( string $query ) {
	return _wrap_wpdb_func( 'query', func_get_args() );
}

/**
 * Replace a row into a table.
 *
 *     wpdb::replace( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
 *     wpdb::replace( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
 *
 * Does not output errors to the page, but throws an exception if an error occurs.
 *
 * @param string       $table  Table name
 * @param array        $data   Data to insert (in column => value pairs).
 *                             Both $data columns and $data values should be "raw" (neither should be SQL escaped).
 *                             Sending a null value will cause the column to be set to NULL - the corresponding format is ignored in this case.
 * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data.
 *                             If string, that format will be used for all of the values in $data.
 *                             A format is one of '%d', '%f', '%s' (integer, float, string).
 *                             If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
 *
 * @return int The number of rows affected.
 *
 * @throws \Exception
 */
function wpdb_replace( string $table, array $data, $format = null ) {
	return _wrap_wpdb_func( 'replace', func_get_args() );
}

/**
 * Update a row in the table
 *
 *     wpdb::update( 'table', array( 'column' => 'foo', 'field' => 'bar' ), array( 'ID' => 1 ) )
 *     wpdb::update( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( 'ID' => 1 ), array( '%s', '%d' ), array( '%d' ) )
 *
 * Does not output errors to the page, but throws an exception if an error occurs.
 *
 * @param string       $table        Table name
 * @param array        $data         Data to update (in column => value pairs).
 *                                   Both $data columns and $data values should be "raw" (neither should be SQL escaped).
 *                                   Sending a null value will cause the column to be set to NULL - the corresponding
 *                                   format is ignored in this case.
 * @param array        $where        A named array of WHERE clauses (in column => value pairs).
 *                                   Multiple clauses will be joined with ANDs.
 *                                   Both $where columns and $where values should be "raw".
 *                                   Sending a null value will create an IS NULL comparison - the corresponding format will be ignored in this case.
 * @param array|string $format       Optional. An array of formats to be mapped to each of the values in $data.
 *                                   If string, that format will be used for all of the values in $data.
 *                                   A format is one of '%d', '%f', '%s' (integer, float, string).
 *                                   If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
 * @param array|string $where_format Optional. An array of formats to be mapped to each of the values in $where.
 *                                   If string, that format will be used for all of the items in $where.
 *                                   A format is one of '%d', '%f', '%s' (integer, float, string).
 *                                   If omitted, all values in $where will be treated as strings.
 *
 * @return int The number of rows updated.
 *
 * @throws \Exception
 */
function wpdb_update( string $table, array $data, array $where, $format = null, $where_format = null ) {
	return _wrap_wpdb_func( 'update', func_get_args() );
}

/**
 * Prepares a SQL query for safe execution. Uses sprintf()-like syntax.
 *
 * See $wpdb->prepare documentation.
 *
 * @param string $query    Query statement with sprintf()-like placeholders
 * @param mixed  $args,... The array of variables to substitute into the query's placeholders if being called with an array of arguments,
 *                         or the first variable to substitute into the query's placeholders if being called with individual arguments.
 *
 * @return string Sanitized query string.
 */
function wpdb_prepare( string $query, $args ) {
	global $wpdb;

	return call_user_func_array( [ $wpdb, 'prepare' ], func_get_args() );
}

/**
 * Helper function used by the various `wpdb_*` functions which calls the specified $wpdb method with the given arguments
 *
 * Does not output errors to the page, but throws an exception if an error occurs.
 *
 * @param string $method
 * @param array $args
 *
 * @return mixed
 *
 * @throws \Exception
 */
function _wrap_wpdb_func( string $method, array $args ) {
	global $wpdb;
	$show_errors = $wpdb->hide_errors();

	$result = call_user_func_array( [ $wpdb, $method ], $args );

	$wpdb->show_errors( $show_errors );

	if ( ! empty( $wpdb->last_error ) ) {
		throw new \Exception( $wpdb->last_error );
	}

	return $result;
}

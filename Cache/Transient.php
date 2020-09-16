<?php

namespace TransientCache;

/**
 * /**
 * Usage example:
 * $query_args = [
 *  'post_type' => 'video'
 * ];
 * $my_data = Transient::cache(
 *	'key_prefix' . md5( json_encode( $params ) ), // key
 *	VideoPostType::get_post_type(), // group - no-group | posttypes | custom-posttype-key
 *	3 * HOUR_IN_SECONDS, // TTL
 *	function() use ( $params ) { // Callback
 *		return $my_data;
 *	},
 *	false // dont force caching
 *);
*/

class TransientCache  {

	private static $namespace = 'pco_mu_';

	public function __construct() {
		add_action( 'save_post', [ __CLASS__, 'save_post' ] );
		add_action( 'edit_post', [ __CLASS__, 'save_post' ] );
		add_action( 'deleted_post', [ __CLASS__, 'save_post' ] );
		add_action( 'delete_attachment', [ __CLASS__, 'save_post' ] );
	}

	private static function transient_key( string $key, $group = 'no-group' ): string {
		return self::get_cache_prefix( $group ) . $key;
	}

	private static function get_namespace() {
		return self::$namespace;
	}

	/**
	 * Get prefix for use with wp_cache_set. Allows all cache in a group to be invalidated at once.
	 *
	 * @param  string $group Group of cache to get.
	 * @return string
	 */
	private static function get_cache_prefix( $group ) {
		// Get cache key - uses cache key to invalidate when needed.
		$microtime = get_transient( self::get_namespace() . $group . '_cache_timestamp_prefix' );

		if ( false === $microtime ) {
			$microtime = microtime();
			set_transient( self::get_namespace() . $group . '_cache_timestamp_prefix', $microtime, DAY_IN_SECONDS ); // no caches should survice more than 24 hours
		}

		return self::get_namespace() . '_cache_' . $microtime . '_';
	}

	/**
	 * Invalidate cache group.
	 *
	 * @param string $group Group of cache to clear.
	 * @since 3.9.0
	 */
	public static function invalidate_cache_group( $group ) {
		set_transient( self::get_namespace() . $group . '_cache_timestamp_prefix', microtime(), DAY_IN_SECONDS );
	}

	/**
	 * Get transient
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public static function get( string $key, $group ) {
		// d( get_transient(self::transient_key($key)), $key );
		return get_transient( self::transient_key( $key, $group ) );
	}

	/**
	 * Set transient
	 *
	 * @param string $key
	 * @param mixed $data
	 * @param integer $ttl
	 *
	 * @return boolean
	 */
	public static function set( string $key, $group, $data, int $ttl ): bool {
		// d( set_transient(self::transient_key($key), $data, $ttl), $key, $ttl );
		return set_transient( self::transient_key( $key, $group ), $data, $ttl );
	}

	/**
	 * Handle cache logically
	 * Sets cache if key does not exist
	 *
	 * @param string $key
	 * @param integer $ttl
	 * @param callable $function
	 * @param boolean $force_cache
	 *
	 * @return mixed
	 */
	public static function cache( string $key, $group, int $ttl, callable $function, bool $force_cache = false ) {
		if ( is_user_logged_in() && ! $force_cache ) {
			// d( 'no-cache');
			return call_user_func( $function );
		}

		// $key  = self::transient_key($key);
		$data = self::get( $key, $group );

		if ( empty( $data ) ) {
			$data = call_user_func( $function );
			self::set( $key, $group, $data, $ttl );
		}

		return $data;
	}

	public static function save_post( $post_id ) {
		$groups = [
			'no-group',
			'posttypes',
		];

		$groups[] = get_post_type( $post_id );
		// $taxonomies = get_post_taxonomies( $post_id );

		foreach ( $groups as $group ) {
			self::invalidate_cache_group( $group );
		}
	}


}


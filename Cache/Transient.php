<?php

namespace TransientCache;

/**
Usage example:
$query_args = [
'post_type' => 'post'
];
$posts = Transient::cache( 'video' . md5(json_encode($query_args ) ), (HOUR_IN_SECONDS / 4), function() use ( $query_args ) {  get_posts( $params ); }, true );

*/

class Transient
{
	public static function transient_key(string $key): string
	{
		return apply_filters('custom_prefix', 'cache_') . $key;
	}

	/**
	 * Get transient
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public static function get(string $key)
	{
		return get_transient(self::transient_key($key));
	}

	/**
	 * Set transient
	 *
	 * @param string $key
	 * @param mixed $data
	 * @param integer $ttl - seconds of lifespan of transient
	 *
	 * @return boolean
	 */
	public static function set(string $key, $data, int $ttl): bool
	{
		return set_transient(self::transient_key($key), $data, $ttl);
	}

	/**
	 * Handle cache logically
	 * Sets cache if key does not exist
	 *
	 * @param string $key
	 * @param integer $ttl
	 * @param callable $function
	 * @param boolean $force_cache - as default logged-in users have no cache, but you can force them to have same cache.
	 *
	 * @return void
	 */
	public static function cache(string $key, int $ttl, callable $function, bool $force_cache = false)
	{
		if (is_user_logged_in() && !$force_cache) {
			return call_user_func($function);
		}

		$key  = self::transient_key($key);
		$data = self::get($key);

		if (empty($data)) {
			$data = call_user_func($function);
			self::set($key, $data, $ttl);
		}

		return $data;
	}
}

<?php

namespace Example\Cache;

class Varnish {

	public function __construct() {
		$this->init();
	}

	/**
	 * Run core bootstrap hooks.
	 */
	public function init() {
    
    		// Append on varnish-http-purge plugin
		add_filter( 'vhp_purge_urls', [$this,'add_urls_to_purge'], 10, 2 );
	}

	/**
	 * Clear specific urls when a post cache is being purged
	 *
	 * @param array $urls
	 * @param string $post_id
	 * @return array $urls
	 */
	public function add_urls_to_purge( $urls = [], $post_id = '' ) {
		// No postid, no fun
		if ( ! $post_id ) {
			return $urls;
		}

		$post_type = get_post_type( $post_id );
		// Not our posttype - abort
		if ( $post_type !== 'post' ) {
			return $urls;
		}

		// Specific-post
		$urls[] = get_permalink( 278 );

		// Specific rest route
		$urls[] = rest_url( "wp/v2/posts/{$post_id}/" );

		// Specific url
		$urls[] = home_url('/custom-url/');

		return $urls;
	}

}

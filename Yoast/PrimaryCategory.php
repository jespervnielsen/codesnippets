
/**
 * Find the primary term. - if no primary is found use first found.
 *
 * @param mixed $post
 * @return object
 */
public function get_primary_category( $post = null ) : object {
	$post = get_post( $post );

	$terms = (array) get_the_category( $post->ID );

	$yoast_primary_category_id = get_post_meta( $post->ID, '_yoast_wpseo_primary_category', true );

	// Find if primary term is part post terms.
	foreach ( $terms as $term ) {
		if ( $term->term_id == $yoast_primary_category_id ) {
			return $term;
		}
	}

	// Primary category not found. return first term;
	foreach ( $terms as $term ) {
		return $term;
	}

	// Terms was empty. - can happen after migrations, and programmatically creation of posts.
	// Find "default" term
	$default_category = get_option( 'default_category' );
	return get_term( $default_category );

}

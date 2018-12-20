<?php 
$attribute_taxonomies = wc_get_attribute_taxonomies();
if ( ! empty( $attribute_taxonomies ) ) {
	foreach ( $attribute_taxonomies as $tax ) {
		$taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
		if ( taxonomy_exists($taxonomy_name ) ) {

			$get_terms_args = [ 'hide_empty' => false ];
			$terms          = get_terms( $taxonomy_name, $get_terms_args );
			if ( 0 < count( $terms ) ) {
				foreach ( $terms as $term ) {

					if( $term->count === 0 ) {
					
						wp_delete_term( $term->term_id, $taxonomy_name );
					}
				}
				
				$terms = get_terms( $taxonomy_name, $get_terms_args );
				
				if ( 0 === count( $terms ) ) {
					//delete tax
					$wpdb->delete(
						"{$wpdb->prefix}woocommerce_attribute_taxonomies",
						[
							'attribute_name' => $tax->attribute_name,
						]
					);

				}
			} else {
				//no terms in attribute
				// woocommerce_attribute_taxonomies
				// @note we cant delete attributes that has been added programmaticly dynamicly (that is not i db), 
				$wpdb->delete(
					"{$wpdb->prefix}woocommerce_attribute_taxonomies",
					[
						'attribute_name' => $tax->attribute_name,
					]
				);

			}
		}
	}
}

<?php

use Pluspack\Woo_adjustments\Frontend\FragmentCache;

// d( get_defined_vars () );

// FragmentCache::
$key = FragmentCache::get_key_pr_url( $located . 'kk'  );
	$ttl = 3600;

	$output = get_transient( $key );

	if ( empty( $output ) ) {

		ob_start();

		$args['pco_cache_template_part'] = true;

		wc_get_template( $template_name, $args, $template_path, $default_path  );

		$output = ob_get_clean();

		set_transient($key, $output, $ttl);
	} else {
		$output = '<!-- Peytz Fragment Cache version -->' . $output;
	}

echo $output;

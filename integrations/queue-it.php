// add queue-items
/**
* Implement queue-it javascript solution in head.
* https://queue-it.com/
**/
function add_queueit() {
  // Toggle for production - could also be done via ENV vars
	// if ( wp_get_environment_type() === 'production' ) {
	// 	return;
	// }

  // Allow OPS to disable queue-it easily.
	if ( getenv( 'DISABLE_QUEUEIT' ) ) {
		return;
	}

  // Depending on where the bottlenecs is, this should be adjusted.
	// For users who already are logged in, we dont need them to go to queue-it, since our bottlenecks is login and checkout. and most of those who are logged in, will properbly already have bought access
	// if we add queue-it for logged in users, editors should be removed then
	if ( is_user_logged_in() ) {
		return;
	}

	ob_start();
  // Simple implementation. queue-it supports many more params, which can be found in their documentation
	?>
	<script type='text/javascript' src='//static.queue-it.net/script/queueclient.min.js'></script>
	<script
		data-queueit-c='<client-id>'
		type='text/javascript'
		src='//static.queue-it.net/script/queueconfigloader.min.js'>
	</script>
	<?php
	echo ob_get_clean();
}

add_action( 'wp_head', add_queueit', 10 );

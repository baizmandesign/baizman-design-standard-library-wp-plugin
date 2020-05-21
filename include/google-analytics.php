<?php

/**
 * Add Google Analytics tracking code to global header.
 */
function bzmndsgn_enqueue_google_analytics ( ) {
//	global $bzmndsgn_config_options ;

	$bzmndsgn_config_options['google_analytics_id'] = 'UA-77345635-1' ;

	if ( $bzmndsgn_config_options['google_analytics_id'] ) {
		?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $bzmndsgn_config_options['google_analytics_id'] ; ?>"></script>
		<script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo $bzmndsgn_config_options['google_analytics_id'] ; ?>');
		</script>
		<?php
	}
}
add_action ( 'wp_head', 'bzmndsgn_enqueue_google_analytics', 1 ) ;

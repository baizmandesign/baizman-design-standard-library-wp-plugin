<?php

if ( ! function_exists ( 'bzmndsgn_enqueue_google_analytics' ) ):
    /**
     * Add Google Analytics tracking code to global header.
     */
    function bzmndsgn_enqueue_google_analytics ( ) {

	    $bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

	    if ( $bzmndsgn_config_options_database['google_analytics_id'] ) {
            ?>
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $bzmndsgn_config_options_database['google_analytics_id'] ; ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '<?php echo $bzmndsgn_config_options_database['google_analytics_id'] ; ?>');
            </script>
            <?php
        }
    }
    add_action ( 'wp_head', 'bzmndsgn_enqueue_google_analytics', 1 ) ;
endif;
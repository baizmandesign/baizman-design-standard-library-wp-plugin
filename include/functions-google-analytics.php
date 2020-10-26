<?php

if ( ! function_exists ( 'bzmndsgn_enqueue_google_analytics' ) ):
    /**
     * Add Google Analytics tracking code to global header.
     * NOTE: it's possible to add both older and newer versions of GA to a page. We may want to prevent this.
     */
    function bzmndsgn_enqueue_google_analytics ( ) {

	    $bzmndsgn_config_options_database = get_option ( BZMNDSGN_CONFIG_OPTIONS );

        // Google Tracking ID. Older version.
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

	    // Google Measurement ID. New default as of fall 2020.
	    if ( $bzmndsgn_config_options_database['google_measurement_id'] ) {
            ?>
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $bzmndsgn_config_options_database['google_measurement_id'] ; ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '<?php echo $bzmndsgn_config_options_database['google_measurement_id'] ; ?>');
            </script>       <?php
	    }

    }
    add_action ( 'wp_head', 'bzmndsgn_enqueue_google_analytics', 1 ) ;
endif;
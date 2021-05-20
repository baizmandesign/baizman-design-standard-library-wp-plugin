jQuery(document).ready(function($) {

    $("li#wp-admin-bar-toggle-qm").on('click', function ( event ) {
        // console.log('clicked query monitor link');
        $.post(my_ajax_obj.ajax_url, {           //post request
                _ajax_nonce: my_ajax_obj.nonce,  //nonce
                action: "toggle_query_monitor"   //action
            }, function(data) {                  //callback
                // TODO: check return value
                // https://stackoverflow.com/questions/3715047/how-to-reload-a-page-using-javascript
                // console.log("data.return_status:",data.return_status)
                window.location.reload();        //refresh page
            }
        );
    } );
} );

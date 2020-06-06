/*

Administrative interface javascript.

*/

// Make link for "Report an Issue" open in an external window.
jQuery ( document ).ready ( function ( ) {
    jQuery('a.toplevel_page_issue-shortcut').attr('target', '_blank');
} ) ;

// Add sticky class to table headers.
jQuery ( document ).ready ( function ( ) {
    var header = jQuery('table.wp-list-table thead') ;
    header.addClass("sticky");
} ) ;

// Set the value of the color field on the WP Dashboard Customization page.
function set_wp_bg_color ( color ) {
    // https://htmlcolorcodes.com/color-names/
    var colors = { 'aliceblue':'#f0f8ff', 'ivory':'#fffff0', 'seashell':'#fff5ee', 'ghostwhite':'#f8f8ff' };

    document.getElementById('local_dashboard_background_color').value = colors[color];

}
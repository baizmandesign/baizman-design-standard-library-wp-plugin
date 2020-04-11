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

(function($) {

    function tut_handle_override_change( selector ) {
        if( selector.find('input:checked').length > 0 ) {
            selector.parent().find( '.tut-twitter-field' ).show();
        }
        else {
            selector.parent().find( '.tut-twitter-field' ).hide();
        }
    }

    $(document).on( 'change', '.twitter-user-timeline .tut-overide', function(){
        tut_handle_override_change( $(this) );
    })

    $(document).ready( function() {
        $.each( $('.twitter-user-timeline .tut-overide'), function(){
            tut_handle_override_change( $(this) );
        })
    })

    $( document ).ajaxComplete(function( event, xhr, settings ) {
        if( settings.data.indexOf( 'widget-twitter-user-timelines' ) > -1 ) {
            $.each( $('.twitter-user-timeline .tut-overide'), function() {
                tut_handle_override_change( $(this) );
            })
        }
    })

})( jQuery );

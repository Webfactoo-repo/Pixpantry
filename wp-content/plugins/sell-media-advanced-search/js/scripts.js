jQuery(document).ready(function( $ ){

    /**
     * Use jQuery autocomplete code from documentation
     * http://jqueryui.com/autocomplete/#multiple
     */
    $(function() {

        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }

        // don't navigate away from the field on tab when selecting an item
        $( "#smas_keywords_text" ).bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                        $( this ).data( "autocomplete" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                minLength: 0,
                source: function( request, response ) {
                    // delegate back to autocomplete, but extract the last term
                    response( $.ui.autocomplete.filter( _smas_keywords, extractLast( request.term ) ) );
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    this.value = terms.join( ", " );
                    return false;
                }
        });
    });


    /**
     * When even action is fired from our submit form we
     * serialize the data and run the following ajax request.
     */
    $('.hentry #smas-ajax-submit').on('submit', function( event ){
        event.preventDefault();

        $('.smas-results-target-container').empty();
        $('.smas-loading-icon').show();

        $.ajax({
            data: $( this ).serialize() + '&action=sell_media_advanced_search_submit&security=' + $('#smas_security').val(),
            url: sell_media.ajaxurl,
            type: "POST",
            success: function( msg ){

                $('.smas-loading-icon').hide();

                $('.smas-results-target-container').html( msg );

                /**
                 * Only run the expander set-up if its present
                 */
                if ( typeof sellMediaExpanderSetup === 'function' ){
                    sellMediaExpanderSetup();
                }
            }
        });
    });
});
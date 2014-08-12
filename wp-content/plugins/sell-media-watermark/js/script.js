jQuery( document ).ready(function( $ ){
    $('#sell_media_watermark_clear_cache_button').on('click',function(event){
        event.preventDefault();

        var $this = $('#sell_media_watermark_clear_cache_button');
        $this.val( sell_media_watermark.clearing_cache );
        $this.attr('disabled',true);

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'sell_media_watermark_clear_cache',
                security: $('#security').val()
            },
            success: function( msg ){
                $this.val( sell_media_watermark.cleared_cache );
                $('.cache-count').hide();
            }
        });

    });
});
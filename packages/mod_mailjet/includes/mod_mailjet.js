/**
 * Created with JetBrains PhpStorm.
 * User: jonathan
 * Date: 10/31/12
 * Time: 10:23 AM
 * To change this template use File | Settings | File Templates.
 */


jQuery(document).ready(function($){
    $('.mailjet-subscribe').submit(function(e) {
        e.preventDefault();
        var f = $(e.currentTarget);
        var p = f.parent();
        $.ajax({
            type: "POST",
            url: "index.php",
            data: f.serialize(),
            dataType: "json",
            success: function(data){
                p.find('.mailjet-error').hide();
                if(data === 'DUPLICATE'){
                    p.find('.mailjet-success').hide();
                    p.find('.mailjet-duplicate').show();
                }else{
                    p.find('.mailjet-duplicate').hide();
                    p.find('.mailjet-success').show();
                }
            },
            error: function() {
                p.find('.mailjet-success').hide();
                p.find('.mailjet-duplicate').hide();
                p.find('.mailjet-error').show();
            }
        });
    });
});

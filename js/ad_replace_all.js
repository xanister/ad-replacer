var args = require('system').args;
var url = args[1];
var render_delay = args[2];

var page = require('webpage').create();
page.settings.userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36';

page.viewportSize = {width: 1200, height: 1024};
page.open(url, function(status) {
    if (status !== 'success') {
        console.log('Unable to access the network!');
    } else {
        page.injectJs('jquery-1.9.1.min.js');
        var result = page.evaluate(function() {
            var placements = '';
            jQuery.each(jQuery('.displayAd'), function(){
                if(jQuery(this).attr('id').indexOf('300x250') !== -1){
                    jQuery(this).css('width', '300px');
                    jQuery(this).css('height', '250px');
                }else if(jQuery(this).attr('id').indexOf('728x90') !== -1){
                    jQuery(this).css('width', '728px');
                    jQuery(this).css('height', '90px');
                }else if(jQuery(this).attr('id').indexOf('200x82') !== -1){
                    jQuery(this).css('width', '200px');
                    jQuery(this).css('height', '82px');
                }else if(jQuery(this).attr('id').indexOf('400x82') !== -1){
                    jQuery(this).css('width', '400px');
                    jQuery(this).css('height', '82px');                    
                }else if(jQuery(this).attr('id').indexOf('970x418') !== -1){
                    jQuery(this).css('width', '970px');
                    jQuery(this).css('height', '418px');                         
                }            
                jQuery(this).css('background-color', 'red');
                jQuery(this).css('color', 'blue');
                jQuery(this).html(jQuery(this).attr('id'));
                
                placements += jQuery(this).attr('id') + ',';
            });
            return placements;
        });
        console.log(result);
        window.setTimeout(function() {         
            page.render('/var/www/html/ad_replacer/snaps/all.png');
            phantom.exit();
        }, render_delay);
    }
});

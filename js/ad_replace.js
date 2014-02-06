var args = require('system').args;
var url = args[1];
var ad_target = args[2];
var replacement_image = args[3];
var render_delay = args[4];

var page = require('webpage').create();
page.settings.userAgent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36';

page.viewportSize = {width: 1200, height: 1024};
page.open(url, function(status) {
    if (status !== 'success') {
        console.log('Unable to access the network!');
    } else {
        page.evaluate(function(ad_target, replacement_image) {
            var ad_width = '100%';
            var ad_height = '100%'; 
            if(ad_target.indexOf('300x250') !== -1){
                ad_width = '300';
                ad_height = '250';
            }else if(ad_target.indexOf('728x90') !== -1){
                ad_width = '728';
                ad_height = '90';        
            }else if(ad_target.indexOf('200x82') !== -1){
                ad_width = '200';
                ad_height = '82';     
            }else if(ad_target.indexOf('400x82') !== -1){
                ad_width = '400';
                ad_height = '82';          
            }else if(ad_target.indexOf('970x418') !== -1){
                ad_width = '970';
                ad_height = '418';                       
            }
                
            var body = document.body;
            body.querySelector('div#'+ad_target).innerHTML = '<img src="'+replacement_image+'" style="width:'+ad_width+'px; height:'+ad_height+'px;" />';
            //body.querySelector('div#'+ad_target).innerHTML = '<iframe width="'+ad_width+'" height="'+ad_height+'" src="http://localhost/ad_replacer/tools/example_iframe.html"></iframe>';
        }, ad_target, replacement_image);

        window.setTimeout(function() {         
            console.log('Replaced ' + ad_target + ' with ' + replacement_image + ' in ' + url);
            page.render('/var/www/html/ad_replacer/snaps/snap.png');
            phantom.exit();
        }, render_delay);
    }
});
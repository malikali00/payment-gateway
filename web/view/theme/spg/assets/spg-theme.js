/**
 * Created by ari on 10/19/2016.
 */


// Initialize
document.addEventListener("DOMContentLoaded", function(e) {

    // if(localStorage.getItem('layout-narrow') === '1') {
    //     document.body.classList.remove('layout-full');
    //     document.body.classList.add('layout-narrow');
    // }
    // function onResize(e) {
    //     if(document.body.classList.contains('layout-vertical'))
    //         return;
    //
    //     var height = (e.srcElement || e.currentTarget).innerHeight;
    //     var width = (e.srcElement || e.currentTarget).innerWidth;
    //     if(width >= 920) { // > height / 1.2
    //         if(document.body.classList.contains('layout-narrow')) {
    //             document.body.classList.remove('layout-narrow');
    //             document.body.classList.add('layout-full');
    //             console.info("Changing body class to: layout-full");
    //         }
    //     } else {
    //         if(!document.body.classList.contains('layout-narrow')) {
    //             document.body.classList.add('layout-narrow');
    //             document.body.classList.remove('layout-full');
    //             console.info("Changing body class to: layout-narrow");
    //         }
    //     }
    // }
    // setTimeout(function(e) {
    //     onResize({
    //         srcElement: window
    //     });
    // }, 100);
    // window.onresize = onResize;

    switch(location.host.toLowerCase()) {
        case 'localhost':
            break;

        case 'access.simonpayments.com':
        case 'dev.simonpayments.com':
        case 'demo.simonpayments.com':
            // If no SSL, force SSL
            if (location.protocol != 'https:')
                location.href = 'https:' + window.location.href.substring(window.location.protocol.length);
            break;
    }


});
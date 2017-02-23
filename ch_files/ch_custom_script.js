

jQuery(document).ready(function () {
    "use strict";


// Revolution slider

    if (jQuery().revolution) {
        jQuery('.banner').revolution({
            delay: 9000,
            startwidth: 1170,
            startheight: 550,

            autoHeight:"off",

			fullScreenAlignForce:"off",

            

            onHoverStop: "on",



            thumbWidth: 100,

            thumbHeight: 50,

            thumbAmount: 3,



            hideThumbsOnMobile: "on",

            hideBulletsOnMobile: "on",

            hideArrowsOnMobile: "on",

            hideThumbsUnderResoluition: 0,

			

			hideThumbs:0,

			hideTimerBar:'on',



			keyboardNavigation:"on",

			

            navigationType: "none",

            navigationArrows: "solo",

            navigationStyle: "round",



            navigationHAlign: "center",

            navigationVAlign: "bottom",

            navigationHOffset: 30,

            navigationVOffset: 30,



            soloArrowLeftHalign: "left",

            soloArrowLeftValign: "center",

            soloArrowLeftHOffset: 20,

            soloArrowLeftVOffset: 0,



            soloArrowRightHalign: "right",

            soloArrowRightValign: "center",

            soloArrowRightHOffset: 20,

            soloArrowRightVOffset: 0,



            touchenabled: "on",

			swipe_velocity:"0.7",

			swipe_max_touches:"1",

			swipe_min_touches:"1",

			drag_block_vertical:"false",



            stopAtSlide: -1,

            stopAfterLoops: -1,

            hideCaptionAtLimit: 0,

            hideAllCaptionAtLilmit: 0,

            hideSliderAtLimit: 0,



            dottedOverlay: "none",



			fullWidth:"off",

			forceFullWidth:"off",

            fullScreen: "off",

            fullScreenOffsetContainer: "#topheader-to-offset",



            shadow: 0



        });

    }

});
/**
 * 2017 - EG (eg@teamgeist-medien.de)
 */
jQuery(function($) {
	/**
	 * Stores the reveal-container temporarily, and re-adds it after clearing the body-tag
	 */
	var revealInit = $('.reveal');
	$('body').empty().append(revealInit);
});

/**
 * Enables Fancybox if required after all images has been loaded
 * TODO: Prevent Fancybox gallery with every image in the presentation (for current slide only).
 */
jQuery(window).on('load', function() {
    if(jQuery('.reveal').attr('data-enableFancybox') == 'true') {
        jQuery('img').each(function() {
            jQuery(this).wrap('<a class="fancybox" rel="fb-group" href="' + jQuery(this).attr('src') + '"></a>');
        });
        jQuery('.fancybox').fancybox();
    }

    jQuery('body').css('display', 'block');
});

/**
 * Checks if the url contains the parameter 'print-pdf' and appends the result file to the header.
 */
var url = 'typo3conf/ext/tgm_reveal/Resources/Public/CSS/' + (window.location.search.match(/print-pdf/gi) ? 'print/pdf.css' : 'print/paper.css');
jQuery('head').append(jQuery('<link rel="stylesheet" type="text/css" href="' + url + '">'));
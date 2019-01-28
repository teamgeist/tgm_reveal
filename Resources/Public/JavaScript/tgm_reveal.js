/**
 * 2017 - EG (eg@teamgeist-medien.de)
 */

$(document).ready(function () {
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
$(window).on('load', function () {
  if ($('.reveal').attr('data-enableFancybox') == 'true') {
    $('img').each(function () {
      $(this).wrap('<a class="fancybox" rel="fb-group" href="' + $(this).attr('src') + '"></a>');
    });
    $('.fancybox').fancybox();
  }

  $('body').css('display', 'block');
});

/**
 * Checks if the url contains the parameter 'print-pdf' and appends the result file to the header.
 */
var url = 'typo3conf/ext/tgm_reveal/Resources/Public/CSS/' + (window.location.search.match(/print-pdf/gi) ? 'print/pdf.css' : 'print/paper.css');
$('head').append($('<link rel="stylesheet" type="text/css" href="' + url + '">'));

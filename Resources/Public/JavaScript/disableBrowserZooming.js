/**
 * 2017 - EG (eg@teamgeist-medien.de)
 */

/* Blocks browser zooming with keys. (effective after javascript execution) */
jQuery(document).keydown(function(e) {
	if(e.ctrlKey && ($.inArray(e.which, [48, 61, 107, 171, 173, 109, 187, 189]) >= 0)) e.preventDefault();
});

/* Blocks mouse scrolling while STRG or CTRL is held. (effective after javascript execution) */
jQuery(window).on('mousewheel DOMMouseScroll', function(e) {
	if(e.ctrlKey) e.preventDefault();
});
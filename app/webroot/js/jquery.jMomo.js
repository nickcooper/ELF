	/*
 * jMomo - simple jquery modal plugin for ajax or iframe content.
 * Version: 0.5 (06/06/2011)
 * Copyright (c) 2011 Andrew Meyer
 * Licensed under the MIT License: http://en.wikipedia.org/wiki/MIT_License
 * Requires: jQuery v1.4+
*/

(function(jQuery) {

	jQuery.fn.jMomo = function(opt){

		var options = jQuery.extend({}, jQuery.fn.jMomo.defaults, opt);

		// iternate and apply behaviors
		return this.each(function(){
			// shortcut var
			var el = jQuery(this);
			// save for later
			var href = el.attr('href');

			var modal = false;

			var trigger = options.trigger;

			window['jQuery'](el)[trigger](function(){
				showModal(href, options);

				return false;
			});
		});	
	};
	
	// private functions
	function loading() {
		jQuery('body').css('cursor','progress');
	}
	function finished() {
		jQuery('body').css('cursor','auto');
	}

	function showModal(url, options) {
		loading();
		if (options.blackout) {
			$('<div id="jMomoBlackout"> </div>').appendTo('body').fadeTo('normal', options.blackoutOpacity);
		}
		if (options.method == 'iframe') {
			var iframe = '<iframe src="' + url + '" frameborder="0"></iframe>';
			var modalMarkup = getModalMarkup(iframe, options.markup);
			var modal = jQuery(modalMarkup).appendTo('body').fadeIn();
			var closeItems = modal.find(options.close);
			applyCloseEvents(modal, closeItems, options.blackoutClose);
			// all done
			finished();
		} else 
		if (options.method == 'ajax') {
			jQuery.get(url, function(data){
				var modalMarkup = getModalMarkup(data, options.markup);
				var modal = jQuery(modalMarkup).appendTo('body').fadeIn();
				var closeItems = modal.find(options.close);
				applyCloseEvents(modal, closeItems, options.blackoutClose);
				// all done
				finished();
			});
		}
	}

	function getModalMarkup(HTML, markup) {
		var markupParts = markup.split('{CONTENT}');
		var output = markupParts[0] + HTML + markupParts[1];

		return output;
	}

	function applyCloseEvents(modal, selection, boClose) {
		if (boClose) {
			selection = selection.add('body');
			modal.click(function(e){
				e.stopPropagation();
			});
		}
		selection.one('click', function(){
			modal.fadeOut(function(){
				modal.remove();
			});
			jQuery('#jMomoBlackout').fadeOut(function(){
				jQuery('#jMomoBlackout').remove();
			});
			return false;
		});
	}

	// set the defaults
	jQuery.fn.jMomo.defaults = {
		trigger : 			'click',	// can be any jquery event handler
		method : 			'iframe',	// can be iframe or ajax 
		markup : 			'<div class="jMomo"><div class="jMomoLiner"><div class="jMomoContent">{CONTENT}</div><a href="#close" class="jMomoClose">Close</a></div></div>', // this markup can be edited, however it is not advised without full knowledge of how the plugin works
		close : 			'.jMomoClose',
		blackoutClose : 	false, 
		blackout : 			true,
		blackoutOpacity : 	0.5,		// 0-1 
	}
	
	// end and return jQuery object
})(jQuery);
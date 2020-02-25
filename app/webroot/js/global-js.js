/* JAVASCRIPT */

/* function to initiate the accordions */

/* Fade notices */
function autoFadeNotices() {
	if ($('#msg_holder .message').length>0) {
		var kill=setTimeout(function(){
			$('#msg_holder .message').fadeTo(3000, 0.2);
			$('#msg_holder .message').hover(function(){
				$(this).fadeTo("slow",1);
			},function(){
				$(this).fadeTo("slow",0.2);
			});
		}, 6000);
	}
}

function tabsInit() {
	$("ul.tab_nav li a").click(function(){
		var sel=$(this).attr('href');
		if ($(sel).length>0) {
			$(sel).show().siblings('.content_panel').hide();
			$(this).parent('li').addClass('selected').siblings('li').removeClass('selected');
			return false;
		} else {
			return true;
		}
	});
}

// behaviors related to forms
function initForms() {
	// focus on first element
	$(':input:first').not(':button, :radio').focus();

	// format SSNs
	$('input.ssn').blur(function(){
		val = $(this).val();
		// remove all non-number chars
		val = val.replace(/\D/g, '');
		if (val != '') {
			// add hypens
			val = val.substring(0,3) + '-' + val.substring(3,5) + '-' + val.substring(5,9);
			// pump it out
			$(this).val(val);
			if (val.length < 11) {
				form_error($(this), 'Oops. Not a valid SSN.');
			}
		} else {
			$(this).val('');
		}
	});
	// shows/hides the next element in the DOM with the class of 'more' based on the value of the rel attribute. 
	$('select.toggleMore').change(function(){
		if ($(this).attr('rel') != '') {
			var matchVal = $(this).attr('rel');
			// shows the .more element if matchVal IS NOT the selected value. This is sort of reverse logic.
			if ($(this).val() != matchVal) {
				$(this).next('.more').show();
			} else {
				$(this).next('.more').hide();
			}
		}
	});

}

// shows an error; designed for forms
// el is jquery object, error is text
function form_error(jel, error) {
	jel.after('<div class="input_error">' + error + '</div>');
	var me = jel.parent().find('.input_error');
	jel.focus(function(){
		me.fadeOut(3000, function(){
			me.remove();
		});
	});
}

function initBasicInteractions() {
	$("ul.button_select_list li ul").hide();
	$("ul.button_select_list").click(function(e){
		var togEl=$(this).find("li ul");
		togEl.slideDown("fast");
		//e.stopPropagation();
		$('body').one('click',function(){
			togEl.slideUp("fast");
		});
		$(this).find('ul li a').click(function(e){
			e.stopPropagation();
			return true;
		});
		return false;
	});
	$("div.block_help").append('<span class="icon">&nbsp;</span>');
	$('div.form_item.required').find('label').after('<span class="req">(Required)</span>');
	$('a.colorbox, a.modal, a.help_tag').colorbox({
		opacity: 0.7,
		maxWidth: '600px',
		onOpen: function() {
			$('#colorbox').find(':input, a').first().focus();
		}
	});
	$('a.modal_inline').colorbox({
		opacity: 0.7,
		maxWidth: '600px',
		inline: true
	});
	// Toggles an element. Uses rel as jQuery selector of element(s) to toggle.
	$('a.toggle').click(function(){
		var togEl = $($(this).attr('rel'));
		if ($(togEl).length > 0) {
			togEl.slideToggle(200);
		}
		return false;
	});
}

/* This specifies what happens when the page loads. Call all functions that need to initiate behavior here. */
$(function(){
	tabsInit();
	autoFadeNotices();
	initForms();
	initBasicInteractions();
	// TEMPORARY!
	$('pre.cake-error').append(' <a href="#" class="closer">[X]</a>').find('.closer').click(function(){
		$(this).parent().remove();
	});
});
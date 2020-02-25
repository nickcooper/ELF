// JavaScript Document

// section selector initiation SCRipT
function ssInit() {
	// set selected
	var here=window.location.href;
	var hasSelected = false;
	$('#section_selector li').each(function(){
		var section=$(this).find('a').first().attr('rel');
		if (here.indexOf(section)>0) {
			$(this).addClass('selected');
			hasSelected = true;
		}
	});
	if (hasSelected == false) {
		$('#section_selector li').first().addClass('selected');
	}
	
	// add picker
	if ($('#ss_holder #section_selector li').length>1) {
		$('#ss_holder').addClass('options');
		$('#ss_holder').append('<span class="trigger"/>');
		var leftWidth=$('#section_selector').width()+0;
		$('#ss_holder .trigger').css('left', leftWidth);
	}
	$('#ss_holder.options span.trigger').click(function(e){
		$('#section_selector li').show();
		
		$('body').one('click', function(){
			$('#section_selector li').not('.selected').hide();
		});
		
		e.stopPropagation();
	});
}

// Allows a slug field to mirror a title field and be updated with a slug ready string
// Use: give slave a class of 'slugify' and set the rel to a jQuery style selector of the master field.
function slugifyInit() {
	$('input.slugify').each(function(){
		if ($(this).attr('rel')!='') {
			var slave = $(this);
			var rel = $(this).attr('rel');
			var master = $(rel);
			master.keyup(function(){
				var val = $(this).val()
				var slug = val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]/g, '');
				slave.val(slug);
			});
		}
	});
}

$(function(){
	ssInit();
	slugifyInit();
});
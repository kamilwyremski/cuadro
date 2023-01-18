$( document ).ready(function(){
	
	$(".set_voice").not('.inactive').click(function(){
		const $this = $(this);
		$.ajax({
			url: "php/ajax.php",
			type: "post",
			dataType: "json",
			data: {'action':'setVoice','file_id':$this.data('file_id'),'voice':$this.data('voice')},
			success: function (response) {
				if(typeof response.plus !== 'undefined' && typeof response.minus !== 'undefined'){
					$this.parents('.set_voice_outside').find('.voice_plus').text(response.plus);
					$this.parents('.set_voice_outside').find('.voice_minus').text(response.minus);
				}
			}
		});
    return false;
	});

	$('.modal').modal();
	
	$('select').formSelect();
	
	$('.sidenav').sidenav();
	
	$(".dropdown-trigger").dropdown();
	
	$('.tooltipped').tooltip();
	
	$('.tabs').tabs();
	
	$('.char-counter').characterCounter();
	
	$('.materialboxed').materialbox();

	$('.add_file_type').click(function(){
		const $this = $(this);
		$('.add_file_type_box').find('input').attr('disabled',true);
		$($this.attr('href')).find('input').attr('disabled',false);
		$('input[name=type]').val($this.data('type'));
	})
	$('.add_file_type.active').click();

	$('.return_false a').click(function(){
		$(this).blur();
		return false;
	})

	$('#back_to_top').on("click", function(){
		$('html, body').stop().animate({scrollTop: 0}, 300);
		$(this).blur();
		return false;
	})

	function scroll() {
		if($(window).scrollTop() > 150){
			$('#back_to_top').removeClass('back_to_top_hidden');
		}else{
			$('#back_to_top').addClass('back_to_top_hidden');
		}
	}
	scroll();
	document.onscroll = scroll;
	window.onresize = scroll;

	$("#facebook2_2").hover(function(){$(this).stop(true,false).animate({right: "0px"}, 500 );},
	function(){$(this).stop(true,false).animate({right: "-300px"}, 500 );});

	if(!localStorage.rodo_accepted) {
		$("#rodo-message").modal('open');
	}
})

if (window.location.href.indexOf('#_=_') > 0) {
	window.location = window.location.href.replace(/#.*/, '');
}

$(window).on("load", function (){
	const $js_scroll_page = $('#js_scroll_page');
  if($js_scroll_page.length>0){
		position = $js_scroll_page.offset().top;
		if($(window).scrollTop()+$(window).height()<position){
			$('html, body').stop().animate({scrollTop: (position-110)}, 300);
		}
	}
});

function checkCookies(){
	if(!localStorage.cookies_accepted) {
		document.getElementById("cookies-message").style.display="block";
	}
}
function closeCookiesWindow(){
	localStorage.cookies_accepted = true;
	const cookie_window = document.getElementById("cookies-message");
	cookie_window.parentNode.removeChild(cookie_window);
}

function closeRodoWindow(){
	localStorage.rodo_accepted = true;
	const cookie_window = document.getElementById("rodo-message");
	cookie_window.parentNode.removeChild(cookie_window);
}

window.onload = checkCookies;

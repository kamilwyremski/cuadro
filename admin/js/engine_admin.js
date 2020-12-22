$(document).ready(function(){

	function checkBoxType(object){
		var $form = object.parents('.parent_check_box_type'), val = object.val();
		if(val == 'text'){
			$form.find('[name=content]').show().attr('disabled',false);
			$form.find('[name=amount]').hide().attr('disabled',true);
		}else if(val == 'new_files' || val == 'waiting_room' || val == 'random_files' || val == 'top_files' || val == 'tags'){
			$form.find('[name=content]').hide().attr('disabled',true);
			$form.find('[name=amount]').show().attr('disabled',false);
		}else{
			$form.find('[name=content]').hide().attr('disabled',true);
			$form.find('[name=amount]').hide().attr('disabled',true);
		}
	}

	$('.check_box_type').change(function(){
		checkBoxType($(this));
	})
	$('.check_box_type').each(function(){
		checkBoxType($(this));
	})

	function set_required(object){
		$target = $('.'+object.data('target'));
		if (object.is(':checked')) {
			$target.prop('required', true);
		}else{
			$target.prop('required', false);
		}
	}

	$('.set_required').click(function(){
		set_required($(this));
	})
	$('.set_required').each(function(){
		set_required($(this));
	})

	$('.select_checkbox').click(function(){
		$this = $(this);
		if ($this.is(':checked')) {
			$this.parents('.parent_select_checkbox').find('input[type=checkbox]').prop('checked', true);
		}else{
			$this.parents('.parent_select_checkbox').find('input[type=checkbox]').prop('checked', false);
		}
	})

	$('.inactive').click(function(){
		return false;
	})

	$(".ajax").not('.inactive').click(function(){
		var mydata = $(this).data();
		$.post('php/ajax.php', {
			'data' : mydata,
			'send': 'ok'},
			function(data) {
				window.location.href = window.location;
		});
        return false;
    });

	$(".ajax_confirm").not('inactive').click(function(){
		$this = $(this);
		var is_confirmed = confirm($this.data('title'));
		if (is_confirmed) {
			var mydata = $this.data();
			$.post('php/ajax.php', {
				'data' : mydata,
				'send': 'ok'},
				function(data) {
					window.location.href = window.location;
			});
		}
        return false;
    });

})

$(document).on('click', '.open_roxy', function(){
	$('.roxy_target').removeClass('roxy_target');
	$(this).find('img').addClass('roxy_target');
	$('#roxySelectFile').modal('show').find('iframe').attr("src",'js/ckeditor/fileman/index.html?integration=custom');
	return false;
})

$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function closeRoxySelectFile(){
	$roxy_target = $('.roxy_target');
	$("[name='"+$roxy_target.data('roxy_name')+"']").val($roxy_target.attr('src'));
	$('#roxySelectFile').modal('hide');
}

function run_ckeditor(id,height=200){
	var roxyFileman = 'js/ckeditor/fileman/index.html';
	$(function(){
		CKEDITOR.replace( id,{height: height,
			filebrowserBrowseUrl:roxyFileman,
			filebrowserImageBrowseUrl:roxyFileman+'?type=image',
			removeDialogTabs: 'link:upload;image:upload'});
	});
}

$(document).on({'show.bs.modal': function () {
	$(this).removeAttr('tabindex');
} }, '.modal');
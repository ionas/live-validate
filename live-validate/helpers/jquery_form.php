<?php

class JqueryFormHelper extends AppHelper {
	
	var $ids = array();
	
	var $helpers = array('Javascript');
	
	function afterRender() {
		
		$forms = array();
		foreach ($this->ids as $id) {
			$forms[] = "#$id :input";
		}
		$forms = implode(', ', $forms);
		
		$js = <<<TEND
		$(document).ready(function() {
		   	$('$forms').change(function(){
			    $(this).parents('form:first').ajaxSubmit({
					dataType: 'json',
			        success:function(response){
						var ids = [];
						$(response).each(function(i, field){
							ids[i] = field.id;
							if (field.message) {
								input = $("#"+field.id);
								if (input.siblings('.error').length > 0) {
									input.siblings('.error').html(field.message);
								} else {
									$('<div class="error">' + field.message + '</div>')
										.data('input.id', field.id)
										.insertAfter(input);
								}
							}
						});

						$("div.error")
							.each(function(i, errorDiv){
								invalid = $.inArray($(errorDiv).data('input.id'), ids);
								if (invalid < 0)
									$(errorDiv).remove();
							});
			        }
			    });
			});
		 });
TEND;
		print $this->Javascript->codeBlock($js);

	}
	
	function validate($id) {
		$this->ids[] = $id;
		return $this->output('<input type="hidden" name="data[validateme]" value="1"');
	}
	
}

?>
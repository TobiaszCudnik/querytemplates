function templateoutput(__data) {
	if (typeof __data != 'undefined')
		for (__dataRow in __data)
			eval('var '+__dataRow+' = __data[__dataRow]');
	var __template = '';
	var print = function(value) {
		__template += value;
	}
	__template += '<form>\
		<legend>JS-only label</legend>\
		<fieldset><dl>\
<dt><label>input[type=text]</label></dt>\
				<dd>\
					<input type="text" name="text-example" value="';
 
 	print(data['text-example']);  
;
	__template += '">\
</dd>\
				<dt><label>input[type=checkbox]</label></dt>\
				<dd>\
					';
 if (data['checkbox-example'] == 'foo') { ;
	__template += '<input type="checkbox" name="checkbox-example" value="foo" checked>';
 } 
 else { ;
	__template += '<input type="checkbox" name="checkbox-example" value="foo">';
 } 
	__template += '\
</dd>\
				<dt>input[type=radio]</dt>\
				<dd>\
					<label>[value=first]\
						';
 if (data['radio-example'] == 'first') { ;
	__template += '<input type="radio" name="radio-example" value="first" checked>';
 } 
 else { ;
	__template += '<input type="radio" name="radio-example" value="first">';
 } 
	__template += '</label>\
				</dd>\
				<dd>\
					<label>[value=second]\
						';
 if (data['radio-example'] == 'second') { ;
	__template += '<input type="radio" name="radio-example" value="second" checked>';
 } 
 else { ;
	__template += '<input type="radio" name="radio-example" value="second">';
 } 
	__template += '</label>\
				</dd>\
				<dt><label>select</label></dt>\
				<dd>\
					<select name="select-example">';
 if (data['select-example'] == 'first') { ;
	__template += '<option value="first" selected>First</option>\
';
 } 
 else { ;
	__template += '<option value="first">First</option>\
';
 } 
 if (data['select-example'] == 'second') { ;
	__template += '<option value="second" selected>Second</option>\
';
 } 
 else { ;
	__template += '<option value="second">Second</option>\
';
 } 
	__template += '</select>\
</dd>\
				<dt><label>textarea</label></dt>\
				<dd>\
					<textarea name="textarea-example">';
 print(data['textarea-example']); ;
	__template += '</textarea>\
</dd>\
			</dl></fieldset>\
</form>';

	return __template;
}
document.write(templateoutput());
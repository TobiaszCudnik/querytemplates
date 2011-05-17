function templateoutput(__data) {
	if (typeof __data != 'undefined')
		for (__dataRow in __data)
			eval('var '+__dataRow+' = __data[__dataRow]');
	var __template = '';
	var print = function(value) {
		__template += value;
	}
	__template += '<ul>\
';
 for (__keya08e5 in data) {
		var row = data[__keya08e5]; 
		if (row instanceof Function)
			continue; ;
	__template += '<li>';
 print(row); ;
	__template += '\
</li>\
';
 } 
	__template += '\
		\
		\
	</ul>';

	return __template;
}
document.write(templateoutput());
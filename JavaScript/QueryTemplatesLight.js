/**
 * QueryTemplates - DOM and CSS oriented template engine
 *
 * Light version for JavaScript.
 *
 * @version 1.0 beta2
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 * 
 * @todo formFromValues, mutation events support
 */
(function($){
var QueryTemplates = {};
var valuesToSelector = function(target, values, selectorPattern, skipFields, fieldCallback){
	if (typeof selectorPattern == 'undefined')
		selectorPattern = '.%k';
	var _target = target;
	var targetData = null;
	if (target.constructor == Array) {
		targetData = target.slice(1);
		target = target[0];
	}
	if (! $.isFunction(fieldCallback))
		fieldCallback = null;
	for (var k in values) {
		var v = values[k];
		if (skipFields && $.inArray(skipFields, k))
			continue;
		if ($.isFunction(v))
			v = v();
		var selector = selectorPattern.replace('%k', k);
		var node = this.find(selector);
		switch(target) {
			case 'attr':
				node[target](targetData[0], v);
				break;
			default:
				node[target](v);
			if (fieldCallback)
				fieldCallback.call(node, k, _target);
		}
	}
	return this;
};
var valuesToStack = function(target, values, skipFields, fieldCallback){
	var _target = target;
	var targetData = null;
	if (target.prototype == Array) {
		targetData = target.slice(1);
		target = target[0];
	}
	if (! $.isFunction(fieldCallback))
		fieldCallback = null;
	var i = 0;
	for (var k in values) {
		var v = values[k];
		if (skipFields && $.inArray(skipFields, k))
			continue;
		if ($.isFunction(v))
			v = v();
		var node = this.eq(i++);
		switch(target) {
			case 'attr':
				node[target](targetData[0], v);
				break;
			default:
				node[target](v);
			if (fieldCallback)
				fieldCallback.call(node, v, _target);
		}
	}
	return this;
};
var valuesToLoop = function(nodes, values, rowCallback, targetNodeSelector, target) {
	if (typeof target == 'undefined')
		target = 'after';
	if (typeof rowCallback == 'undefined')
		throw "rowCallback needs to be provided for valuesToLoop methods";
	var nodeTarget = null, lastNode = nodes;
	var injectMethod = 'insert'+target.slice(0, 1).toUpperCase()+target.slice(1);
	for (var k in values) {
		$(lastNode.get().reverse()).each(function() {
			if ($(this).parent().langth) {
				lastNode = $(this);
				return false;
			}
		});
		if (targetNodeSelector) {
			nodeTarget = typeof targetNodeSelector == 'string'
				? lastNode.parent().find(targetNodeSelector)
				: targetNodeSelector;
		} else
			nodeTarget = lastNode;
		var v = values[k];
		var stack = [];
		nodes.each(function(i, node){
			stack.push($(node).clone()[injectMethod](lastNode).get(0));
		});
		lastNode = $(stack);
		rowCallback.call(lastNode, v, k);
	}
	// we used those nodes as template
	nodes.remove();
	return this;
};
QueryTemplates.valuesToLoop = function(values, rowCallback, targetNodeSelector) {
	return valuesToLoop(this, values, rowCallback, targetNodeSelector);
};
QueryTemplates.valuesToLoopBefore = function(values, rowCallback, targetNodeSelector) {
	return valuesToLoop(this, values, rowCallback, targetNodeSelector, 'before');
};
QueryTemplates.valuesToLoopFirst = function(values, rowCallback, targetNodeSelector) {
	var loopNodes = this.eq(0);
	this.slice(1).remove();
	valuesToLoop(loopNodes, values, rowCallback, targetNodeSelector);
	return loopNodes;
};
QueryTemplates.valuesToLoopSeparate = function(values, rowCallback, targetNodeSelector) {
	this.each(function(i, node) {
		return valuesToLoop(node, values, rowCallback, targetNodeSelector);
	});
	return this;
};
QueryTemplates.valuesToForm = function(values, selectorPattern) {
	if (typeof selectorPattern == 'undefined')
		selectorPattern = "[name*='%k']";
	var form = this.is('form')
		? this.filter('form')
		: this.find('form');
	$.each(values, function(f, v) {
		var selector = selectorPattern.replace('%k', f);
		var input = form.find("input"+selector);
		if (input.length) {
			switch(input.attr('type')) {
				case 'checkbox':
					if (v)
						input.attr('checked', 'checked');
					else
						input.removeAttr('checked');
				break;
				case 'radio':
					var inputChecked = input.filter("[value='"+v+"']")
						.attr('checked', 'checked');
					input.not(inputChecked).removeAttr('checked');
				break;
				default:
					input.attr('value', v);
			}
		}
		var select = form.find("select"+selector);
		if (select.length) {
			select.find('> option[selected]').removeAttr('selected');
			var _v = select.attr('multiple')
				? v : [v];
			for (var i in _v) {  
				var selected = select.find('option')
					.filter("[value='"+_v[i]+"']")
					.attr('selected', 'selected');
			}
		}
		var textarea = form.find("textarea"+selector);
		if (textarea.length)
			textarea.html(v);
	});
	return this;
};
/**
 * @TODO change input type method
 */
QueryTemplates.formFromValues = function(record, structure, errors, data, 
	templateselectors, fieldCallback){
	var self = this;
	if (typeof record != 'object')
		throw "[formFromValues] record should be an object";
	if (typeof structure != 'object')
		throw "[formFromValues] structure should be an object";
	if (typeof data != 'object')
		data = {};
	if (typeof errors != 'undefined')
		var defaultTemplate = '\
<div class="input">\
  <label/>\
  <input/>\
  <ul class="errors">\
    <li/>\
  </ul>\
</div>\
';
  	else
		var defaultTemplate = '\
<div class="input">\
  <label/>\
  <input/>\
</div>\
';
	if (typeof template == 'undefined')
		var template = defaultTemplate;
	else if ((typeof template == 'object') && (typeof template.__default == 'undefined') 
		|| ((typeof template.__default != 'undefined') && ! template.__default))
		template.__default = defaultTemplate;
	// setup $selectors
	if (typeof selectors == 'undefined')
		selectors = new Array();
	selectors = $.extend({
		'errors': '.errors',
		'input': 'input:first',
		'label': 'label:first'
	}, selectors);
	var form = this.is('form')
		? this.filter('form').empty()
		: $('<form/>').insertAfter(this);
	if (typeof structure.__form != 'undefined') {
		for (var attr in structure.__form)
			form.attr(attr, structure.__form[attr]);
		var attr = undefined; 
		delete structure.__form;
	}
	formID = form.attr('id') 
		|| 'f_'+Math.random().toString().replace('.', '').slice(0, 5);
	// no fieldsets
	if (typeof structure[0] == 'undefined')
		structure = [structure];
	var input;
	$.each(structure, function() {
		var fieldsetFields = this;
		var fieldset = $('<fieldset/>');
		if (typeof fieldsetFields.__label != 'undefined') {
			fieldset.append('<legend>'+fieldsetFields.__label+'</legend>');
			delete fieldsetFields.__label;
		}
		for (var field in fieldsetFields) {
			var info = fieldsetFields[field];
			// prepare info
			if (typeof info != 'object')
				info = {0: info};
			if (!(typeof info[0] != 'undefined'))
				info[0] = 'text';
			// prepare id
			var id = typeof info.id != 'undefined'
				? info.id
				: formID+'_'+field;
			// prepare template
			if (typeof template == 'object') {
				if (typeof template[field] != 'undefined')
					var markup = template[field];
				else if (typeof template['__'+info[0]] != 'undefined')
					var markup = template['__'+info[0]];
				else
					var markup = template['__default'];
			} else {
				markup = template;
			}
			markup = $('<div/>').html(markup).contents().remove();
			// setup selectors
			var inputSelector, labelSelector;
			$.each(['input', 'label'], function() {
				var selectorType = this;
				if (typeof selectors[selectorType] == 'object') {
					if (typeof selectors[selectorType][field] != 'undefined') {
						eval(selectorType+'Selector = selectors[selectorType][field];');
					} else if (typeof selectors[selectorType]['__default'] != 'undefined') {
						eval(selectorType+'Selector = selectors[selectorType]["__default"];');
					} else {
						throw "No $selectorType selector for field $field. Provide "
							+"default one or one selector for all fields";
					}
				} else {
					eval(selectorType+'Selector = selectors[selectorType];');
				}
			});
			switch (info[0]) {
				case 'textarea':
				case 'select':
					inputSelector = inputSelector.replace('%t', info[0]);
					break;
				default:
					inputSelector = inputSelector.replace('%t', 'input');
			}
			// we're ready, lets beggin...
			switch (info[0]) {
				// TEXTAREA
				case 'textarea':
					var input = $('<textarea/>')
						.attr('id', id);
					if (typeof record[field] != 'undefined')
						input.html(record['field']);
					markup.find(inputSelector).replaceWith(input);
					markup.find(labelSelector).attr('for', id);
					break;
				// SELECT
				case 'select':
					if (!(typeof data[field] != 'undefined'))
						throw "[formFromValues] data."+field+" should be present to "
							+"populate select element. Otherwise remove structure.\"+field+\".";
					var input = $('<select/>');
					markup.find(inputSelector).replaceWith(input);
					if (typeof info.multiple != 'undefined' && info.multiple)
						input.attr('multiple', 'multipe');
					else
						info.multiple = false;
//					var option = function() {
//						if (!(typeof record[field] != 'undefined'))
//							return;
//						if (info.multiple && $.inArray(option.attr('value'), record[field]))
//							$(this).attr('selected', 'selected')
//					}
					var optgroup = $('<optgroup/>');
					var option = $('<option/>');
					for(var value in data[field]) {
						var label = data[field][value];
						var target;
						if (typeof label == 'object') {
							target = optgroup.clone();
							if (typeof label.__label != 'undefined') {
								target.attr('label', label.__label);
								delete label.__label;
							}
							input.append(target);
							for (var _value in label) {
								var _label = label[_value];
								target.append(
									option.clone()
										.attr('value', _value)
										.html(_label)
								);
							}
						} else {
							target = input; 
							target.append(
								option.clone()
									.attr('value', value)
									.html(label)
							);
						}
					}
					if (typeof record[field] != 'undefined')
						// trust jquery's val()
						input.val(record[field]);
					markup.find(labelSelector).attr('for', id);
					break;
				// RADIO
				case 'radio':
					if (!(typeof info.values != 'undefined'))
						throw "[formFromValues] structure."+field+".values property needed for radio inputs.";
					var inputs = [];
					// change input type
					// http://groups.google.com/group/jquery-en/browse_thread/thread/b1e3421d00104f17/88b1ff6cab469c39
					var attrCopy = ['class', 'rel'];
					var attrs = {
						name: field,
						id: id,
						value: info.values[0]
					};
					var inputTemplate = markup.find(inputSelector);
					$.each(attrCopy, function() {
						var attr = this.toString();
						if (inputTemplate.attr(attr))
							attrs[attr] = inputTemplate.attr(attr);
					});
					var input = $('<input type="'+info[0]+'"/>')
						.attr(attrs)
						.insertAfter(inputTemplate);
					inputTemplate.remove();
					inputTemplate = null;
					// change input type end
					inputs.push(input.get(0));
					var lastInput = input;
					$.each(info.values.slice(1).reverse(), function(){
						lastInput = input.clone()
							.attr('value', this.toString())
							.insertAfter(lastInput);
						inputs.push(lastInput.get(0));
					});
					if (typeof record[field] != 'undefined')
						// XXX trust jquery val()
						$(inputs).val([record[field]]);
					inputs = lastInput = null;
					markup.find(labelSelector).removeAttr('for');
					break;
				// HIDDEN
				case 'hidden':
					markup = null;
					var input = $('<input type="hidden" />')
						.attr('name', field)
						.attr('id', id);
					if (typeof record[field] != 'undefined')
						// XXX trust jquery val()
						$(inputs).val(record[field]);
					fieldset.prepend(input);
					break;
				// CHECKBOX
				case 'checkbox':
					var value = typeof info.value != 'undefined'
						? info.value : 1;
					// change input type
					var inputTemplate = markup.find(inputSelector);
					// http://groups.google.com/group/jquery-en/browse_thread/thread/b1e3421d00104f17/88b1ff6cab469c39
					var attrCopy = ['class', 'rel'];
					var attrs = {
						name: field,
						id: id,
						value: value
					};
					$.each(attrCopy, function() {
						var attr = this.toString();
						if (inputTemplate.attr(attr))
							attrs[attr] = inputTemplate.attr(attr);
					});
					var input = $('<input type="'+info[0]+'"/>')
						.attr(attrs)
						.insertAfter(inputTemplate);
					inputTemplate.remove();
					inputTemplate = null;
					// change input type end
					if ((typeof record[field] != 'undefined') && record[field])
						input.attr('checked', 'checked');
					markup.find(labelSelector).attr('for', id);
					break;
				// TEXT, PASSWORD, others
				default:
					var inputTemplate = markup.find(inputSelector);
					// change input type
					// http://groups.google.com/group/jquery-en/browse_thread/thread/b1e3421d00104f17/88b1ff6cab469c39
					var attrCopy = ['class', 'rel'];
					var attrs = {
						name: field,
						id: id
					};
					$.each(attrCopy, function() {
						var attr = this.toString();
						if (inputTemplate.attr(attr))
							attrs[attr] = inputTemplate.attr(attr);
					});
					var input = $('<input type="'+info[0]+'"/>')
						.attr(attrs)
						.insertAfter(inputTemplate);
					inputTemplate.remove();
					inputTemplate = null;
					// change input type end
					if (typeof record[field] != 'undefined')
						input.val(record[field]);
					markup.find(labelSelector).attr('for', id);
				break;
			}
			if (markup) {
				markup.addClass(info[0]);
				// label
				var label = typeof info.label != 'undefined'
					? info.label
					: field.slice(0, 1).toUpperCase()+field.slice(1);
				markup.find(labelSelector).text(label);
				// errors
				if (typeof errors != 'undefined') {
					if ((typeof errors[field] != 'undefined') && errors[field]) {
						if (! $.isArray(errors[field]))
							errors[field] = [errors[field]];
						markup.find(selectors.errors)
							.find('> *')
								.valuesToLoopFirst(errors[field], function(row){
									this.html(row);
								});
					} else {
						markup.find(selectors.errors).remove();
					}
				}
				if (typeof fieldCallback == 'function')
					fieldCallback.call(markup, field, markup);
				fieldset.append(markup);
			}
		}
		form.append(fieldset);
	});
	input = null;
	return self;
};
// helper functions
function whoisNode(node) {
	var output = [];
	if (node.tagName) {
		node = $(node);
		output.push(node.get(0).tagName.toLowerCase()
			+ (node.attr('id')
				? '#'+node.attr('id') : '')
			+ (node.attr('class')
				? '.'+node.attr('class').split(' ').join('.') : '')
			+ (node.attr('name')
				? '[name="'+node.attr('name')+'"]' : '')
			+ (node.attr('value') && typeof node.attr('value') != 'number'
				? '[value="'+node.attr('value').toString().substr(0, 15).replace("/\n/g", ' ')+'"]' : '')
			+ (node.attr('selected')
				? '[selected]' : '')
			+ (node.attr('checked')
				? '[checked]' : '')
		);
	} else {
		if ($.trim(node.textContent)) {
			output.push("Text:'"+node.textContent
				.substr(0, 15)
				.replace("/\n/g", ' ')
				+"'"
			);
		}
	}
	return output[0];
}
function __dumpTree(node, itend) {
	if (typeof itend == 'undefined')
		itend = 0;
	var output = '';
	var whois = whoisNode(node);
	if (whois) {
		for(var i = 0; i < itend; i++)
			output += ' - ';
		output += whois+"\n";
	}
	if (node.childNodes)
		$.each(node.childNodes, function(i, chNode) {
			output += __dumpTree(chNode, itend+1);
		});
	return output;
}
QueryTemplates.dumpTree = function(returnAsString) {
	var output = '';
	this.each(function(i, node) {
		output += __dumpTree(node);
	});
	if (returnAsString)
		return output;
	else if (typeof console != 'undefined')
		console.log(output);
	else
		alert(output);
	return this;
}
// bind various method types
$.each(['Selector', 'Stack'], function(i, m) {
	$.each(['', 'Before', 'After', 'Prepend', 'Append', 'Attr'], function(ii, t) {
		QueryTemplates['valuesTo'+m+t] = function() {
			if (t == '')
				t = 'html';
			var params = [t];
			$.each(arguments, function(iii, a) {
				params.push(a);
			});
			return eval('valuesTo'+m).apply(this, params);
		};
	});
});
//console.log(QueryTemplates);
$.extend($.fn, QueryTemplates);
})(jQuery);
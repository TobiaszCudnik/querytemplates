<?php
/**
 * Class extending phpQueryObject with templating methods.
 *
 * @abstract
 * @package QueryTemplates
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link http://code.google.com/p/querytemplates/
 */
class QueryTemplatesSyntaxGenerators extends QueryTemplatesSyntaxVars {
	/**
	 * Creates markup with INPUT tags and prepends it to form.
	 * If selected element isn't a FORM then find('form') is executed.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <form>
	 *   <input name='was-here-before'>
	 * </form>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $data = array('field1' => 'foo', 'field2' => 'bar');
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template->inputsFromValues($data);
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <form>
	 *   <input name='field1' value='foo'>
	 *   <input name='field2' value='bar'>
	 *   <input name='was-here-before'>
	 * </form>
	 * </code>
	 *
	 * @param $data
	 * @param $type
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 * @see QueryTemplatesPhpQuery::formFromValues()
	 */
	public function inputsFromValues($data, $type = 'hidden') {
		$form = $this->is('form')
			? $this->filter('form')
			: $this->find('form');
		if ($form->find('fieldset')->size())
			$form = $form->find('fieldset:first');
		$data = array_reverse($data);
		foreach($data as $field => $value) {
			$form->prepend("<input name='$field' value='$value' type='$type'>");
		}
		return $this;
	}
	/**
	 * Method formFromVars acts as flexible form helper. It creates customized 
	 * pure-markup form without the need of suppling a line of markup.
	 * 
	 * Final form is available right after using this method.
	 * 
	 * Created form have following features:
	 * - shows data from record
	 * - shows errors
	 * - supports default values
	 * - supports radios and checkboxes
	 * - supports select elements with optgroups
	 * - supports select multiple
	 * 
	 * Created form can be customized using:
	 * - input wrapper template (per field name, per field type and default)
	 * - selectors (for input, label and errors)
	 * - callbacks (per field)
	 *
	 * == Example ==
	 * <code>
	 * $structure = array(
	 * 	'__form' => array('id' => 'myFormId'),
	 * 	array( 
	 * 		'__label' => 'Fieldset 1 legend',
	 * 		'default-field' => array(	// 'text' is default
	 * 			'label' => 'default-field label',
	 * 			'id' => 'default-field-id',
	 * 		),
	 * 		'text-field' => array('text',
	 * 			'label' => 'text-field label',
	 * 			'id' => 'text-field-id',
	 * 		),
	 * 		'hidden-field' => 'hidden',
	 * 		'checkbox-field' => 'checkbox',
	 * 	),
	 * 	array(
	 * 		'__label' => 'Fieldset 2 legend',
	 * 		'select-field' => array('select', 
	 * 			'label' => 'select-field label',
	 * 		),
	 * 		'radio-field' => array('radio', 
	 * 			'values' => array('possibleValue1', 'possibleValue2')
	 * 		),
	 * 		'textarea-field' => 'textarea',
	 * 	),
	 * );
	 * $data = array(
	 * 	'select-field' => array(
	 * 		// no optgroup
	 * 		'bar1' => 'bar1 label',
	 * 		'bar2' => 'bar2 label',
	 * 		'bar3' => 'bar3 label',
	 * 	),
	 * );
	 * $record = array(
	 *   'text-field' => 'value from record',
	 * );
	 * $tempalte->
	 *   formFromValues($record, $structure, null, $data)
	 * ;
	 * </code>
	 * === Result DOM tree ===
	 * <code>
	 * form#myFormId
	 *  - fieldset
	 *  -  - input#myFormId_hidden-field[name="hidden-field"][value="new hidden-fiel"]
	 *  -  - legend
	 *  -  -  - Text:Fieldset 1 lege
	 *  -  - div.input.text
	 *  -  -  - label
	 *  -  -  -  - Text:default-field l
	 *  -  -  - input#default-field-id[name="default-field"]
	 *  -  - div.input.text
	 *  -  -  - label
	 *  -  -  -  - Text:text-field labe
	 *  -  -  - input#text-field-id[name="text-field"][value="new text-field "]
	 *  -  -  - ul.errors
	 *  -  -  -  - li
	 *  -  -  -  -  - Text:text-field erro
	 *  -  - div.input.checkbox
	 *  -  -  - div
	 *  -  -  -  - Text:Checkbox field 
	 *  -  -  - label
	 *  -  -  -  - Text:Checkbox-field
	 *  -  -  - input#myFormId_checkbox-field[name="checkbox-field"][value="1"][checked]
	 *  - fieldset
	 *  -  - legend
	 *  -  -  - Text:Fieldset 2 lege
	 *  -  - div.input.select
	 *  -  -  - label
	 *  -  -  -  - Text:select-field la
	 *  -  -  - select[name="select-field"]
	 *  -  -  -  - option[value="bar1"]
	 *  -  -  -  -  - Text:bar1 label
	 *  -  -  -  - option[value="bar2"]
	 *  -  -  -  -  - Text:bar2 label
	 *  -  -  -  - option[value="bar3"][selected]
	 *  -  -  -  -  - Text:bar3 label
	 *  -  - div.input.select
	 *  -  -  - label
	 *  -  -  -  - Text:select-field-op
	 *  -  -  - select[name="select-field-optgroups-multiple"]
	 *  -  -  -  - optgroup
	 *  -  -  -  -  - option[value="group1_1"]
	 *  -  -  -  -  -  - Text:group1_1 label
	 *  -  -  -  -  - option[value="group1_2"]
	 *  -  -  -  -  -  - Text:group1_2 label
	 *  -  -  -  - optgroup
	 *  -  -  -  -  - option[value="group2_1"][selected]
	 *  -  -  -  -  -  - Text:group2_1 label
	 *  -  -  -  -  - option[value="group2_2"]
	 *  -  -  -  -  -  - Text:group2_2 label
	 *  -  -  -  - option[value="bar"][selected]
	 *  -  -  -  -  - Text:Bar
	 *  -  -  - ul.errors
	 *  -  -  -  - li
	 *  -  -  -  -  - Text:error1
	 *  -  -  -  - li
	 *  -  -  -  -  - Text:error2
	 *  -  - div.input.radio
	 *  -  -  - label
	 *  -  -  -  - Text:Radio-field
	 *  -  -  - input[name="radio-field"][value="possibleValue1"]
	 *  -  -  - input[name="radio-field"][value="possibleValue2"][checked]
	 *  -  - div.input.textarea
	 *  -  -  - label
	 *  -  -  -  - Text:Textarea-field
	 *  -  -  - textarea#myFormId_textarea-field
	 * </code>
	 *
	 * @param Array $record
	 * TODO doc
	 * 
	 * @param Array $structure
	 * Form structure information. This should be easily fetchable from ORM layer.
	 * Possible types:
	 * - text (default)
	 * - password
	 * - hidden
	 * - checkbox
	 * - radio
	 * - textarea
	 * Convention:
	 * <code>
	 * 'fieldName' => array(
	 *   'fieldType', $fieldOptions
	 * )
	 * </code>
	 * Where $fieldOptions can be (`key => value` pairs):
	 * - label
	 * - id
	 * - multiple (only select)
	 * - values (only radio, MANDATORY)
	 * - value (only checkbox, optional)
	 * There can be special field *`__form`*, which represents form element, as an array.
	 * All values from it will be pushed as form attributes.
	 * If you wrap fields' array within another array, it will represent *fieldsets*,
	 * which value with index *`__label`* will be used as legend (optional).
	 *
	 * @param Array $errors
	 * TODO doc
	 *
	 * @param Array $data
	 * Additional data for fields. For now it's only used for populating select boxes.
	 * Example:
	 * <code>
	 * $defaultData = array(
	 * 	'select-field-optgroups' => array(
	 * 		array(	// 1st optgroup
	 * 			'__label' => 'optgroup 1 label',
	 * 			'group1_1' => 'group1_1 label',
	 * 			'group1_2' => 'group1_2 label',
	 * 		),
	 * 		array(	// 2nd optgroup
	 * 			'__label' => 'optgroup 2 label',
	 * 			'group2_1' => 'group2_1 label',
	 * 			'group2_2' => 'group2_2 label',
	 * 		),
	 * 		'bar' => 'Bar',	// no optgroup
	 * 	),
	 * );
	 * </code>
	 *
	 * @param Array|String $template
	 * Input wrapper template. This template will be used for each field. Use array
	 * to per field template, '__default' means default.
	 * All types allowed in $structure can be used as per-type default template 
	 * when indexed like '__$type' ex '__checkbox'.
	 * To each input wrapper will be added a class which is field's type.
	 * Example:
	 * <code>
	 * $templates['__checkbox'] = '
	 * <div class="input">
	 * 	<div>Checkbox field below</div>
	 *   <label/>
	 *   <input/>
	 * </div>';
	 * </code>
	 * Default template is:
	 * <code>
	 * <div class="input">
	 *   <label/>
	 *   <input/>
	 *   <ul class="errors">
	 *     <li/>
	 *   </ul>
	 * </div>
	 * </code>
	 *
	 * @param Array $selectors
	 * Array of selectors indexed by it's type. Allows to customize lookups inside
	 * inputs wrapper. Possible values are:
	 * <ul>
	 * <li>error - selects field's error wrapper<ul>
	 *   <li>dafault value is '.errors'</li>
	 * </ul></li>
	 * <li>label - selects field's label node (can be div, span, etc)<ul>
	 *   <li>default value is 'label:first'</li>
	 *   <li>use array to per field name selector, '__default' means default</li>
	 * </ul></li>
	 * <li>input - selects field's input node: input, textarea or select<ul>
	 *   <li>default value is 'input:first'</li>
	 *   <li>use array to per field name selector, '__default' means default</li>
	 *   <li>%t is replaced by field node type (use it with customized per field $template)</li>
	 * </ul></li>
	 * </ul>
	 *
	 * @param Array|String|Callback $fieldCallback
	 * Callback triggered after preparation of each field.
	 *
	 * @return QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::formFromVars()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 *
	 * @TODO support objects for record and data
	 * @TODO maybe support callbacks per input type, before/after, maybe for errors too ?
	 * @TODO hidden fields and fieldCallback
	 * @TODO fieldCallback __form special field for form callback 
	 */
	function formFromValues($record, $structure, $errors = null, $data = null, 
		$template = null, $selectors = null, $fieldCallback = null) {
		// setup $template
		if (! is_null($errors))
			$defaultTemplate = <<<EOF
<div class="input">
  <label/>
  <input/>
  <ul class="errors">
    <li/>
  </ul>
</div>
EOF;
		else
			$defaultTemplate = <<<EOF
<div class="input">
  <label/>
  <input/>
</div>
EOF;
		if (! isset($template))
			$template = $defaultTemplate;
		else if (is_array($template) && ! isset($template['__default']) 
			|| (isset($template['__default']) && ! $template['__default']))
			$template['__default'] = $defaultTemplate;
		// setup $selectors
		if (! isset($selectors))
			$selectors = array();
		$selectors = array_merge(array(
			'errors' => '.errors',
			'input' => 'input:first',
			'label' => 'label:first',
		), $selectors);
		$form = $this->is('form')
			? $this->filter('form')->empty()
			: $this->newInstance('<form/>');
		if ($structure['__form']) {
			foreach($structure['__form'] as $attr => $value)
				$form->attr($attr, $value);
			$attr = $value = null;
			unset($structure['__form']);
		}
		$formID = $form->attr('id');
		if (! $formID) {
			$formID = 'f_'.substr(md5(microtime()), 0, 5);
			$form->attr('id', $formID);
		}
		// no fieldsets
		if (! isset($structure[0])) {
			$structure = array($structure);
		}
		foreach($structure as $fieldsetFields) {
			$fieldset = $this->newInstance('<fieldset/>');
			if (isset($fieldsetFields['__label'])) {
				$fieldset->append("<legend>{$fieldsetFields['__label']}</legend>");
				unset($fieldsetFields['__label']);
			}
			foreach($fieldsetFields as $field => $info) {
				// prepare $info
				if (! is_array($info))
					$info = array($info);
				if (! isset($info[0]))
					$info[0] = 'text';
				// prepare id
				$id = isset($info['id'])
					? $info['id']
					: "{$formID}_{$field}";
				// prepare template
				if (is_array($template)) {
					if (isset($template[$field])) {
						$markup = $template[$field];
					} else if (isset($template["__{$info[0]}"])) {
						$markup = $template["__{$info[0]}"];
					} else if (isset($template['__default'])) {
						$markup = $template['__default'];
					}
				} else {
					$markup = $template;
				}
				$markup = $this->newInstance($markup);
				// setup selectors
				$inputSelector = $labelSelector = null;
				foreach(array('input', 'label') as $selectorType) {
					if (is_array($selectors[$selectorType])) {
						if (isset($selectors[$selectorType][$field])) {
							${$selectorType.'Selector'} = $selectors[$selectorType][$field];
						} else if (isset($selectors[$selectorType]['__default'])) {
							${$selectorType.'Selector'} = $selectors[$selectorType]['__default'];
						} else {
							throw new Exception("No $selectorType selector for field $field. Provide "
								."default one or one selector for all fields");
						}
					} else {
						${$selectorType.'Selector'} = $selectors[$selectorType];
					}
				}
				switch($info[0]) {
					case 'textarea':
					case 'select':
						$inputSelector = str_replace('%t', $info[0], $inputSelector);
						break;
					default:
						$inputSelector = str_replace('%t', 'input', $inputSelector);
				}
				switch($info[0]) {
					// TEXTAREA
					case 'textarea':
						$input = $this->newInstance("<textarea></textarea>")
							->attr('id', $id);
						if (isset($record[$field]))
							$input->markup($record[$field]);
						$markup[$inputSelector]->replaceWith($input);
						$markup[$labelSelector]->attr('for', $id);
						break;
					// SELECT
					case 'select':
						if (! isset($data[$field]))
							throw new Exception("\$data['$field'] should be present to "
								."populate select element. Otherwise remove \$structure['$field'].");
						$input = $this->newInstance("<select name='$field'/>");
						$markup[$inputSelector]->replaceWith($input);
						if (isset($info['multiple']) && $info['multiple']) {
							$input->attr('multiple', 'multiple');
						} else {
							$info['multiple'] = false;
						}
						if (! function_exists('option_as8231kdqwhasd')) {
							function option_as8231kdqwhasd($option, $scope) {
								extract($scope);
								if (! isset($record[$field]))
									return;
								if ($info['multiple'] && in_array($option->attr('value'), $record[$field]))
									$option->attr('selected', 'selected');
								else if (! $info['multiple'] && $option->attr('value') == $record[$field])
									$option->attr('selected', 'selected');
							}
						}
						$scope = compact('info', 'record', 'field');
						$optionCallback = new Callback('option_as8231kdqwhasd');
						$option = $this->newInstance("<option/>");
						$optgroup = $this->newInstance("<optgroup/>");
						foreach($data[$field] as $value => $label) {
							if (is_array($label)) {
								$target = $optgroup->clone();
								if (isset($label['__label'])) {
									$target->attr('label', $label['__label']);
									unset($label['__label']);
								}
								$input->append($target);
								foreach($label as $_value => $_label) {
									$target->append(
										$option->clone()->
											attr('value', $_value)->
											markup($_label)->
											callback($optionCallback, $scope)
									);
								}
							} else {
								$input->append(
									$option->clone()->
										attr('value', $value)->
										markup($label)->
										callback($optionCallback, $scope)
								);
							}
						}
						$markup[$labelSelector]->attr('for', $id);
						$option = $target = $optgroup = null; 
						break;
					// RADIO
					case 'radio':
						if (! $info['values'])
							throw new Exception("\$structure[$field]['values'] property needed for radio inputs.");
						$inputs = array();
						// TODO prototype input, dont trust template
						$input = $markup[$inputSelector]->
							attr('type', 'radio')->
							attr('name', $field)->
							attr('value', $info['values'][0])->
							removeAttr('checked');
						$inputs[] = $input->get(0);
						$lastInput = $input;
						foreach(array_reverse(array_slice($info['values'], 1)) as $value) {
							$lastInput = $input->clone()->
								insertAfter($lastInput)->
								attr('value', $value);
							$inputs[] = $lastInput->get(0);
						}
						if (isset($record[$field])) {
							phpQuery::pq($inputs)->
								filter("[value='{$record[$field]}']")->
									attr('checked', 'checked');
						}
						$inputs = $lastInput = null;
						$markup[$labelSelector]->removeAttr('for');
						break;
					// HIDDEN
					case 'hidden':
						$markup = null;
						$input = $this->newInstance('<input/>')->
							attr('type', 'hidden')->
							attr('name', $field)->
							attr('id', $id);
						if (isset($record[$field]))
							$input->attr('value', $record[$field]);
						$fieldset->prepend($input);
						break;
					// CHECKBOX
					case 'checkbox':
						$value = isset($info['value'])
							? $info['value'] : '1';
						$input = $markup[$inputSelector]->
							attr('type', $info[0])->
							attr('name', $field)->
							attr('id', $id)->
							attr('value', $value)->
							removeAttr('checked')
						;
						if (isset($record[$field]) && $record[$field])
							$input->attr('checked', 'checked');
						$markup[$labelSelector]->attr('for', $id);
						break;
					// TEXT, PASSWORD, others
					default:
						$input = $markup[$inputSelector]->
							attr('type', $info[0])->
							attr('name', $field)->
							removeAttr('value')->
							attr('id', $id);
						if (isset($record[$field]))
							$input->attr('value', $record[$field]);
						$markup[$labelSelector]->attr('for', $id);
						break;
				}
				if ($markup) {
					$markup->addClass($info[0]);
					// label
					$label = isset($info['label'])
						? $info['label'] : ucfirst($field);
					$markup[$labelSelector] = $label;
					// errors
					if (isset($errors)) {
						if (isset($errors[$field]) && $errors[$field]) {
							if (! is_array($errors[$field]))
								$errors[$field] = array($errors[$field]);
							$markup[ $selectors['errors'] ]->
								find('>*')->
									valuesToLoopFirst($errors[$field], new CallbackBody(
										'$data, $node', '$node->markup($data);'
									));
						} else {
							$markup[ $selectors['errors'] ]->remove();
						}
					}
					if ($fieldCallback)
						// TODO doc param change
						phpQuery::callbackRun($fieldCallback, array($field, $makrup));
					$fieldset->append($markup);
				}
			}
			$form->append($fieldset);
		}
		$input = null;
		$this->append($form);
		return $this;
	}
	/**
	 * EXPERIMENTAL - works, but not for production code.
	 * 
	 * Method formFromVars acts as flexible form helper. It creates customized 
	 * exacutable form without the need of suppling a line of markup.
	 * 
	 * Form code is executed during template-execution and creates final form 
	 * using record from variable. 
	 * 
	 * Created form have following features:
	 * - shows data from record (array or object)
	 * - shows errors
	 * - supports default values
	 * - supports radios and checkboxes
	 * - supports select elements with optgroups
	 * - overloadable $defaultData
	 * - overloadable $defaultRecord
	 * 
	 * Created form can be customized using:
	 * - input wrapper template (per field name, per field type and default)
	 * - selectors (for input, label and errors)
	 * - callbacks (per field)
	 *
	 * == Example ==
	 * <code>
	 * $structure = array(
	 * 	'__form' => array('id' => 'myFormId'),
	 * 	array( 
	 * 		'__label' => 'Fieldset 1 legend',
	 * 		'default-field' => array(	// 'text' is default
	 * 			'label' => 'default-field label',
	 * 			'id' => 'default-field-id',
	 * 		),
	 * 		'text-field' => array('text',
	 * 			'label' => 'text-field label',
	 * 			'id' => 'text-field-id',
	 * 		),
	 * 		'hidden-field' => 'hidden',
	 * 		'checkbox-field' => 'checkbox',
	 * 	),
	 * 	array(
	 * 		'__label' => 'Fieldset 2 legend',
	 * 		'select-field' => array('select', 
	 * 			'label' => 'select-field label',
	 * 		),
	 * 		'select-field-optgroups-multiple' => array('select',
	 * 			'label' => 'select-field-optgroups label',
	 * 		),
	 * 		'radio-field' => array('radio', 
	 * 			'values' => array('possibleValue1', 'possibleValue2')
	 * 		),
	 * 		'textarea-field' => 'textarea',
	 * 	),
	 * );
	 * </code>
	 *
	 * @param String|Array $varNames
	 * Array of names of following vars:
	 * - record [0]
	 *   Represents actual record as array of fields.
	 * - errors [1]
	 *   Represents actual errors as array of fields. Field can also be an array.
	 * - data [2]
	 *   Overloads $defaultData during template's execution.
	 * Names should be without dollar signs.
	 * Ex:
	 * <code>
	 * array('row', 'errors.row', 'data');
	 * $errors['row'] = array(
	 *   'field1' => 'one error',
	 *   'field2' => array('first error', 'second error')
	 * );
	 * </code>
	 *
	 * @param Array $structure
	 * Form structure information. This should be easily fetchable from ORM layer.
	 * Possible types:
	 * - text (default)
	 * - password
	 * - hidden
	 * - checkbox
	 * - radio
	 * - textarea
	 * Convention:
	 * <code>
	 * 'fieldName' => array(
	 *   'fieldType', $fieldOptions
	 * )
	 * </code>
	 * Where $fieldOptions can be (`key => value` pairs):
	 * - label
	 * - id
	 * - multiple (only select)  // TODO
	 * - values (only radio, MANDATORY)
	 * - value (only checkbox, optional)
	 * There can be special field *`__form`*, which represents form element, as an array.
	 * All values from it will be pushed as form attributes.
	 * If you wrap fields' array within another array, it will represent *fieldsets*,
	 * which value with index *`__label`* will be used as legend (optional).
	 *
	 * @param Array $defaultRecord
	 * Default field's value. Used when field isn't present within supplied record.
	 * Ex:
	 * <code>
	 * $defaultRecord = array(
	 * 	'text-field' => 'text-field default value',
	 * 	'select-field' => 'bar2',
	 * 	'select-field-optgroups-multiple' => array('group2_1', 'group2_2'),
	 * 	'checkbox-field' => false,
	 * );
	 * </code>
	 *
	 * @param Array $defaultData
	 * Additional data for fields. For now it's only used for populating select boxes.
	 * Example:
	 * <code>
	 * $defaultData = array(
	 * 	'select-field-optgroups' => array(
	 * 		array(	// 1st optgroup
	 * 			'__label' => 'optgroup 1 label',
	 * 			'group1_1' => 'group1_1 label',
	 * 			'group1_2' => 'group1_2 label',
	 * 		),
	 * 		array(	// 2nd optgroup
	 * 			'__label' => 'optgroup 2 label',
	 * 			'group2_1' => 'group2_1 label',
	 * 			'group2_2' => 'group2_2 label',
	 * 		),
	 * 		'bar' => 'Bar',	// no optgroup
	 * 	),
	 * );
	 * </code>
	 *
	 * @param Array|String $template
	 * Input wrapper template. This template will be used for each field. Use array
	 * to per field template, '__default' means default.
	 * All types allowed in $structure can be used as per-type default template 
	 * when indexed like '__$type' ex '__checkbox'.
	 * To each input wrapper will be added a class which is field's type.
	 * Example:
	 * <code>
	 * $templates['__checkbox'] = '
	 * <div class="input">
	 * 	<div>Checkbox field below</div>
	 *   <label/>
	 *   <input/>
	 * </div>';
	 * </code>
	 * Default template is:
	 * <code>
	 * <div class="input">
	 *   <label/>
	 *   <input/>
	 *   <ul class="errors">
	 *     <li/>
	 *   </ul>
	 * </div>
	 * </code>
	 *
	 * @param Array $selectors
	 * Array of selectors indexed by it's type. Allows to customize lookups inside
	 * inputs wrapper. Possible values are:
	 * <ul>
	 * <li>error - selects field's error wrapper<ul>
	 *   <li>dafault value is '.errors'</li>
	 * </ul></li>
	 * <li>label - selects field's label node (can be div, span, etc)<ul>
	 *   <li>default value is 'label:first'</li>
	 *   <li>use array to per field name selector, '__default' means default</li>
	 * </ul></li>
	 * <li>input - selects field's input node: input, textarea or select<ul>
	 *   <li>default value is 'input:first'</li>
	 *   <li>use array to per field name selector, '__default' means default</li>
	 *   <li>%t is replaced by field node type (use it with customized per field $template)</li>
	 * </ul></li>
	 * </ul>
	 *
	 * @param Array|String|Callback $fieldCallback
	 * Callback triggered after preparation of each field.
	 *
	 * @return QueryTemplatesParse
	 * @see QueryTemplatesPhpQuery::formFromValues()
	 * @see QueryTemplatesPhpQuery::valuesToForm()
	 * @see QueryTemplatesPhpQuery::varsToForm()
	 *
	 * @TODO support objects for record and data
	 * @TODO select[multiple]
	 * @TODO move radio values to data
	 * @TODO move checkbox values to data
	 * @TODO make checkbox and radios like select
	 * @TODO write methods used here as part of syntax classes
	 * @TODO maybe support callbacks per input type, before/after, maybe for errors too ?
	 * @TODO hidden fields and fieldCallback
	 */
	function formFromVars($varNames, $structure, $defaultRecord = null, $defaultData = null,
		$template = null, $selectors = null, $fieldCallback = null) {
		// setup $varNames
		if (! $varNames)
			throw new Exception("Record's var name (\$varNames or \$varNames[0]) is mandatory.");
		if (! is_array($varNames))
			$varNames = array($varNames);
		$varRecord = $varNames[0];
		$varErrors = isset($varNames[1]) && $varNames[1]
			? $varNames[1] : null;
		$varData = isset($varNames[2]) && $varNames[2]
			? $varNames[2] : null;
		// setup $template
		if ($varErrors)
			$defaultTemplate = <<<EOF
<div class="input">
  <label/>
  <input/>
  <ul class="errors">
    <li/>
  </ul>
</div>
EOF;
		else
			$defaultTemplate = <<<EOF
<div class="input">
  <label/>
  <input/>
</div>
EOF;
		if (! isset($template))
			$template = $defaultTemplate;
		else if (is_array($template) && ! isset($template['__default']) 
			|| (isset($template['__default']) && ! $template['__default']))
			$template['__default'] = $defaultTemplate;
		// setup $selectors
		if (! isset($selectors))
			$selectors = array();
		$selectors = array_merge(array(
			'errors' => '.errors',
			'input' => 'input:first',
			'label' => 'label:first',
		), $selectors);
		// setup lang stuff
//		$lang = strtoupper($this->parent->language);
//		$languageClass = 'QueryTemplatesLanguage'.$lang;
		// setup markup
//		$template = $this->newInstance($template);
		$form = $this->is('form')
			? $this->filter('form')->empty()
			: $this->newInstance('<form/>');
		if ($structure['__form']) {
			foreach($structure['__form'] as $attr => $value)
				$form->attr($attr, $value);
			$attr = $value = null;
			unset($structure['__form']);
		}
		$formID = $form->attr('id');
		if (! $formID) {
			$formID = 'f_'.substr(md5(microtime()), 0, 5);
			$form->attr('id', $formID);
		}
		// no fieldsets
		if (! isset($structure[0])) {
			$structure = array($structure);
		}
		foreach($structure as $fieldsetFields) {
			$fieldset = $this->newInstance('<fieldset/>');
			if (is_string($fieldsetFields['__label'])) {
				$fieldset->append("<legend>{$fieldsetFields['__label']}</legend>");
				unset($fieldsetFields['__label']);
			}
			foreach($fieldsetFields as $field => $info) {
				// prepare $info
				if (! is_array($info))
					$info = array($info);
				if (! isset($info[0]))
					$info[0] = 'text';
				// prepare id
				$id = isset($info['id'])
					? $info['id']
					: "{$formID}_{$field}";
				// prepare template
				if (is_array($template)) {
					if (isset($template[$field])) {
						$markup = $template[$field];
					} else if (isset($template["__{$info[0]}"])) {
						$markup = $template["__{$info[0]}"];
					} else if (isset($template['__default'])) {
						$markup = $template['__default'];
					}
				} else {
					$markup = $template;
				}
				$markup = $this->newInstance($markup);
				// setup selectors
				$inputSelector = $labelSelector = null;
				foreach(array('input', 'label') as $selectorType) {
					if (is_array($selectors[$selectorType])) {
						if (isset($selectors[$selectorType][$field])) {
							${$selectorType.'Selector'} = $selectors[$selectorType][$field];
						} else if (isset($selectors[$selectorType]['__default'])) {
							${$selectorType.'Selector'} = $selectors[$selectorType]['__default'];
						} else {
							throw new Exception("No $selectorType selector for field $field. Provide "
								."default one or one selector for all fields");
						}
					} else {
						${$selectorType.'Selector'} = $selectors[$selectorType];
					}
				}
				switch($info[0]) {
					case 'textarea':
					case 'select':
						$inputSelector = str_replace('%t', $info[0], $inputSelector);
						break;
					default:
						$inputSelector = str_replace('%t', 'input', $inputSelector);
				}
				switch($info[0]) {
					// TEXTAREA
					case 'textarea':
						$input = $this->newInstance("<textarea></textarea>")
							->attr('id', $id);
						$markup[$inputSelector]->replaceWith($input);
						if (isset($defaultRecord[$field])) {
							$input->qt_langMethod('markup',
								self::_formFromVars_CodeValue($this, compact(
									'input', 'field', 'defaultRecord', 'varRecord'
								))
							);
						} else {
							$input->qt_langMethod('markup',
								$input->qt_langCode('printVar', "$varRecord.$field")
							);
						}
						$markup[$labelSelector]->attr('for', $id);
						break;
					// SELECT
					case 'select':
						if (! isset($defaultData[$field]) && ! $varData)
							throw new Exception("\$defaultData['$field'] or \$varNames[2] should be present to "
								."populate select element. Otherwise remove \$structure['$field'].");
						$input = $this->newInstance("<select name='$field'/>");
						$markup[$inputSelector]->replaceWith($input);
						if (isset($info['multiple'])) {
							$input->attr('multiple', true);
						} else {
							$info['multiple'] = false;
						}
						// setup var names
						$varNameRecordAPHP = QueryTemplatesLanguage::get('php',
							'varNameArray', "$varRecord.$field"
						);
						$varNameRecordOPHP = QueryTemplatesLanguage::get('php',
							'varNameObject', "$varRecord.$field"
						);
						$varNameRecordJS = QueryTemplatesLanguage::get('js',
							'varName', "$varRecord.$field"
						);
						$varNameDataAPHP = QueryTemplatesLanguage::get('php',
							'varNameArray', "$varData.$field"
						);
						$varNameDataOPHP = QueryTemplatesLanguage::get('php',
							'varNameObject', "$varData.$field"
						);
						$varNameDataJS = QueryTemplatesLanguage::get('js',
							'varName', "$varData.$field"
						);
						// default_default and record_default callback
						if (! function_exists('optionDefault_34fsdfas23das')) {
						function optionDefault_34fsdfas23das($self, $scope) {
							$self->removeAttr('selected');
							extract($scope);
							if ($useRecord) {
								$unselected = $self->clone();
								$self->
									after($unselected)->
									attr('selected', 'selected')->
									ifCode($self->qt_langCode(
										'compareVarValue', "$varRecord.$field", $self->attr('value')
									))
								;
								$unselected->elseStatement();
							} else {
								if ($defaultRecord[$field] == $self->attr('value'))
									$self->attr('selected', 'selected');
							}
						}}
						$optionDefaultCallback = new Callback('optionDefault_34fsdfas23das');
						// default_default and record_default callback
						if (! function_exists('loop_90qwnby8sgddasju')) {
						function loop_90qwnby8sgddasju($useRecord, $scope, $rowData, $nodes, $rowIndex) {
							extract($scope);
							$scope['useRecord'] = $useRecord;
							// optgroup
							if (is_array($rowData)) {
								$label = isset($rowData['__label'])
									? $rowData['__label'] : '';
								$nodes->
									filter('option')->remove()->end()->
									filter('optgroup')->
										attr('label', $label)->
										find('option')->
											valuesToLoop($rowData, new CallbackBody('$scope, $data, $node, $index', "
												extract(\$scope);
												if (\$index == '__label')
													return;
												\$node->
													filter('option')->
														attr('value', \$index)->
														markup(\$data)->
														callback(\$optionDefaultCallback, \$scope)->
													end()
											", $scope, new CallbackParam, new CallbackParam, new CallbackParam
											), '>option:last')->
										end()->
									end()
								;
							// single option
							} else {
								$nodes->
									filter('optgroup')->remove()->end()->
									filter('option')->
										attr('value', $rowIndex)->
										markup($rowData)->
										callback($optionDefaultCallback, $scope)->
									end()
								;
							}
						}}
						// setup callbacks for default_default and record_default
						$scope = compact('defaultData', 'defaultRecord', 'varRecord', 'varData', 'field', 'optionDefaultCallback');
						$loopDefaultCallback = new Callback('loop_90qwnby8sgddasju',
							false, $scope, new CallbackParam, new CallbackParam, new CallbackParam
						);
						$loopRecordCallback = new Callback('loop_90qwnby8sgddasju',
							true, $scope, new CallbackParam, new CallbackParam, new CallbackParam
						);
						// setup callbacks for default_data and record_data
						if (! function_exists('optionDefaultData_7d12kajs78ascnb2')) {
						function optionDefaultData_7d12kajs78ascnb2($self, $scope) {
							extract($scope);
							$unselected = $self->removeAttr('selected')->clone();
							$self->
								after($unselected)->
								ifCode($self->qt_langCode(
									'compareVarValue', "$varRecord.$field", $defaultRecord[$field]
								))->
								attr('selected', 'selected')
							;
							$unselected->elseStatement();
						}}
						$optionDefaultDataCallback = new Callback('optionDefaultData_7d12kajs78ascnb2',
							new CallbackParam, compact('varRecord', 'field', 'defaultRecord')
						);
						if (! function_exists('optionRecordData_6434fwfa')) {
						function optionRecordData_6434fwfa($self, $scope) {
							extract($scope);
							$unselected = $self->removeAttr('selected')->clone();
							$self->
								after($unselected)->
								ifCode($self->qt_langCode(
									'compareVarVar', "$varRecord.$field", 'value'
								))->
								attr('selected', 'selected')
							;
							$unselected->elseStatement();
						}}
						$optionRecordDataCallback = new Callback('optionRecordData_6434fwfa',
							new CallbackParam, compact('varRecord', 'field', 'defaultRecord')
						);
						// create templates
						$optionTemplate = $this->newInstance("<optgroup><option/></optgroup><option/>");
						$optionTemplates = array(
							'default_default' => $optionTemplate->clone(),
							'record_default' => $optionTemplate->clone(),
							'default_data' => $optionTemplate->clone(),
							'record_data' => $optionTemplate->clone(),
						);
						foreach($optionTemplates as $optionTemplate) {
							$input->append($optionTemplate);
						}
						// apply conditions
						if (isset($defaultData[$field])) {
							$conditionDataPHP = $varData
								? " && ! isset($varNameDataOPHP) && ! isset($varNameDataAPHP)" : '';
							$conditionDataJS = $varData
								? " && typeof $varNameDataJS == 'undefined'" : '';
							$optionTemplates['default_default']->
								onlyPHP()->
									ifPHP("! isset($varNameRecordAPHP) && ! isset($varNameRecordOPHP)$conditionDataPHP")->
								endOnly()->
								onlyJS()->
									ifJS("typeof $varNameRecordJS == 'undefined'$conditionDataJS")->
								endOnly()
							;
							$optionTemplates['record_default']->
								onlyPHP()->
									ifPHP("(isset($varNameRecordAPHP) || isset($varNameRecordOPHP))$conditionDataPHP")->
								endOnly()->
								onlyJS()->
									ifJS("typeof $varNameRecordJS != 'undefined'$conditionDataJS")->
								endOnly()
							;
						}
						if ($varData) {
							$optionTemplates['default_data']->
								onlyPHP()->
									ifPHP("! isset($varNameRecordAPHP) && ! isset($varNameRecordOPHP)
										&& (isset($varNameDataOPHP) || isset($varNameDataAPHP))")->
								endOnly()->
								onlyJS()->
									ifJS("typeof $varNameRecordJS == 'undefined'
										&& typeof $varNameDataJS != 'undefined'")->
								endOnly()
							;
							$optionTemplates['record_data']->
								onlyPHP()->
									ifPHP("(isset($varNameRecordAPHP) || isset($varNameRecordOPHP))
										&& (isset($varNameDataOPHP) || isset($varNameDataAPHP))")->
								endOnly()->
								onlyJS()->
									ifJS("typeof $varNameRecordJS != 'undefined'
										&& typeof $varNameDataJS != 'undefined'")->
								endOnly()
							;
						}
						// loop values & inject code
						if (isset($defaultData[$field])) {
							$loopTargets = array(
								'default_default' => $input['>php:eq(1), >js:eq(1)'],
								'record_default' => $input['>php:eq(3), >js:eq(3)'],
							);
							$optionTemplates['default_default']->
								valuesToLoopBefore($defaultData[$field], $loopDefaultCallback, $loopTargets['default_default'])
							;
							$optionTemplates['record_default']->
								valuesToLoopBefore($defaultData[$field], $loopRecordCallback, $loopTargets['record_default'])
							;
						}
						if ($varData) {
							foreach(array('default_data', 'record_data') as $templateType) {
	 							$optionTemplates[$templateType]->
									varsToLoop("$varData.$field", 'label', 'value')->
									filter('optgroup')->
										onlyPHP()->
											// if it's an optgroup
											// TODO dedicated method
											ifPHP('is_array("$value")')->
											// TODO support objects
											// TODO dedicated method
											attrPHP('label', "print isset({$varNameRecordAPHP}[\$value]['__label'])
												? {$varNameRecordAPHP}[\$value]['__label'] : ''")->
										endOnly()->
										onlyJS()->
											// if it's an optgroup
											// TODO dedicated method
											ifJS('typeof value == "object"')->
											attrJS('label', "print(typeof {$varNameRecordJS}[value]['__label'] != 'undefined'
												? {$varNameRecordJS}[value]['__label'] : ''")->
										endOnly()->
		//								varPrintAttr('label', "$varData.$field")->
										find('option')->
											varsToLoop('label', '_label', '_value')->
											// TODO dedicated method
											onlyPHP()->
												beforePHP('if ($_value == "__label") continue;')->
											endOnly()->
											onlyJS()->
												beforeJS('if (_value == "__label") continue;')->
											endOnly()->
											varPrintAttr('value', '_value')->
											varPrint('_label')->
											callback($templateType == 'default_data'
												? $optionDefaultDataCallback : $optionRecordDataCallback)->
										end()->
									end()->
									filter('option')->
										varPrintAttr('value', 'value')->
										varPrint('label')->
										// if not an optgroup
										elseStatement()->
										callback($templateType == 'default_data'
											? $optionDefaultDataCallback : $optionRecordDataCallback)->
									end()
	 							;
							}
						}
						// unset used vars
						$optionTemplates = $templateType = $optionRecordDataCallback =
							$scope = $loopDefaultCallback = $loopRecordCallback = 
							$optionTemplate = $conditionDataPHP = $conditionDataJS = 
							$optionDefaultDataCallback = $optionRecordDataCallback = 
							$varNameRecordAPHP = $varNameRecordOPHP = $varNameRecordJS =
							$varNameDataAPHP = $varNameDataOPHP = $varNameDataJS = 
							$optgroups = $optgroupsDefault = $option = null;
						$markup[$labelSelector]->attr('for', $id);
						break;
					// RADIO
					case 'radio':
						if (! $info['values'])
							throw new Exception("'values' property needed for radio inputs");
						$inputs = array();
						$input = $markup[$inputSelector]->
							attr('type', 'radio')->
							attr('name', $field)->
							attr('value', $info['values'][0])->
							removeAttr('checked');
						$inputs[] = $input;
						$lastInput = $input;
						// inputFromValues
						// XXX not safe ?
						foreach(array_slice($info['values'], 1) as $value) {
							$lastInput = $input->clone()->
								insertAfter($lastInput)->
								attr('value', $value);
							$inputs[] = $lastInput;
						}
						if (isset($defaultRecord[$field])) {
							phpQuery::pq($inputs)->clone()->
								insertBefore($inputs->eq(0))->
								filter("[value='{$defaultRecord[$field]}']")->
									attr('checked', 'checked')->
								end()->
								// TODO ifNotVarIsset
								ifNotVar("varRecord.$field");
							$inputs->elseStatement();
						}
						foreach($inputs as $input) {
			//				$input = pq($input, $this->getDocumentID());
							$clone = $input->clone()->insertAfter($input);
			//				$input->attr('checked', 'checked')->ifPHP($code, true);
							$code = $this->qt_langCode('compareVarValue',
								"$varRecord.$field", $input->attr('value')
							);
							$input->attr('checked', 'checked')->qt_langMethod('if', $code);
							$clone->removeAttr('checked')->qt_langMethod('else');
						}
						$inputs = null;
						$markup[$labelSelector]->removeAttr('for');
						break;
					// HIDDEN
					case 'hidden':
						$markup = null;
						$input = $this->newInstance('<input/>')->
							attr('type', 'hidden')->
							attr('name', $field)->
							attr('id', $id);
						$code = isset($defaultRecord[$field])
							? self::_formFromVars_CodeValue($this, compact(
									'input', 'field', 'defaultRecord', 'varRecord'
								))
							: $input->qt_langCode('printVar', "$varRecord.$field");
						$input->qt_langMethod('attr', 'value', $code);
						$fieldset->prepend($input);
						$target = $code = null;
						break;
					// CHECKBOX
					case 'checkbox':
						$value = isset($info['value'])
							? $info['value'] : '1';
						// setup var names
						$varNameRecordAPHP = QueryTemplatesLanguage::get('php',
							'varNameArray', "$varRecord.$field"
						);
						$varNameRecordOPHP = QueryTemplatesLanguage::get('php',
							'varNameObject', "$varRecord.$field"
						);
						$varNameRecordJS = QueryTemplatesLanguage::get('js',
							'varName', "$varRecord.$field"
						);
						$input = $markup[$inputSelector]->
							attr('type', $info[0])->
							attr('name', $field)->
							attr('id', $id)->
							attr('checked', 'checked');
//						$codeValue = isset($defaultRecord[$field])
//							? self::_formFromVars_CodeValue($this, compact(
//									'input', 'field', 'defaultRecord', 'varRecord'
//								))
//							: $input->qt_langCode('printVar', "$varRecord.$field");
//						$input->qt_langMethod('attr', 'value', $codeValue);
						$input->attr('value', $value);
//						$inputUnchecked = $input->clone()->
//							removeAttr('checked');
						// TODO JS conditions
						$inputsRecord = $input->clone()->
							add($input->clone()->removeAttr('checked'));
						if (! isset($defaultRecord[$field]) || (isset($defaultRecord[$field]) && ! $defaultRecord[$field])) {
							$input->removeAttr('checked');
						}
						$input->
							ifPHP("! isset($varNameRecordAPHP) && ! isset($varNameRecordOPHP)")->
							next('php, js')->
								after($inputsRecord);
//						if (isset($defaultRecord[$field])) {
//							$inputUnchecked = $input->clone()->
//								removeAttr('checked')->
//								insertAfter($input)
//							;
//							$inputsRecord = $input->add($inputUnchecked)
//								->ifPHP("! isset($varNameRecordAPHP) && ! isset($varNameRecordOPHP)")
//								->clone()->
//									insertAfter($inputUnchecked->next('php', 'js'))
//							;
//							$input->ifVar($input->qt_langCode('compareVarValue', "$varRecord.$field"));
//						} else {
//							$inputsRecord = $input->removeAttr('checked')
//								->ifPHP("! isset($varNameRecordAPHP) && ! isset($varNameRecordOPHP)")
//								->clone()->
//									insertAfter($input->next('php', 'js'))
//							;
//							$inputsRecord = $inputsRecord->add(
//								$input->clone()->
//									removeAttr('checked')->
//									insertAfter($inputsRecord)
//							);
//						}
						$inputsRecord->elseStatement()->
							eq(0)->
								ifPHP("(isset($varNameRecordAPHP) && $varNameRecordAPHP) 
									|| (isset($varNameRecordOPHP) && $varNameRecordOPHP)")->
							end()->
							eq(1)->
								elseStatement()->
							end()
						;
						$markup[$labelSelector]->attr('for', $id);
						$codeValue = $inputUnchecked = $varNameRecordAPHP = $varNameRecordOPHP = 
							$varNameRecordJS = $inputsRecord = null;
						break;
					// TEXT, PASSWORD, others
					default:
						$input = $markup[$inputSelector]->
							attr('type', $info[0])->
							attr('name', $field)->
							attr('id', $id)->
							removeAttr('checked');
						$code = isset($defaultRecord[$field])
							? self::_formFromVars_CodeValue($this, compact(
									'input', 'field', 'defaultRecord', 'varRecord'
								))
							: $input->qt_langCode('printVar', "$varRecord.$field");
						$input->qt_langMethod('attr', 'value', $code);
						$markup[$labelSelector]->attr('for', $id);
						$code = null;
						break;
				}
				if ($markup) {
					$markup->addClass($info[0]);
					// label
					$label = isset($info['label'])
						? $info['label'] : ucfirst($field);
					$markup[$labelSelector] = $label;
					if ($varErrors) {
						$varNamePHP = QueryTemplatesLanguage::get('php',
							'varNameArray', "$varErrors.$field"
						);
						$varNameJS = QueryTemplatesLanguage::get('js',
							'varName', "$varErrors.$field"
						);
						$markup[ $selectors['errors'] ]->
							ifVar("$varErrors.$field")->
							onlyPHP()->
								beforePHP("if (! is_array($varNamePHP))
									$varNamePHP = array($varNamePHP);")->
							endOnly()->
							onlyJS()->
								beforeJS("if (typeof $varNameJS != 'object')
									var $varNameJS = array($varNameJS);")->
							endOnly()->
							find('>*')->
								varsToLoopFirst("$varErrors.$field", 'error')->
									varPrint('error');
						$varNamePHP = $varNameJS = null;
					}
					if ($fieldCallback)
						phpQuery::callbackRun($fieldCallback, array($markup));
					$fieldset->append($markup);
				}
			}
			$form->append($fieldset);
		}
		$input = $code = null;
		$this->append($form);
		return $this;
	}
	protected static function _formFromVars_CodeValue($self, $scope) {
		extract($scope);
		$code = array(
			'if' => $self->qt_langCode('ifVar', "$varRecord.$field"),
			'printVar' => $self->qt_langCode('printVar', "$varRecord.$field"),
			'else' => $self->qt_langCode('elseStatement'),
			'printValue' => $self->qt_langCode('printValue', $defaultRecord[$field]),
		);
		return $code['if'][0].
				$code['printVar'].
			$code['if'][1].
			$code['else'][0].
				$code['printValue'].
			$code['else'][1];
	}
	/**
	 * Behaves as var_export, dumps variables from $varsArray as $key = value for
	 * later use during template execution. Variables are prepended into selected
	 * elements.
	 *
	 * Method doesn't change selected elements stack.
	 *
	 * == Example ==
	 *
	 * === Markup ===
	 * <code>
	 * <node1>
	 * 	<node2></node2>
	 * </node1>
	 * </code>
	 *
	 * === Data ===
	 * <code>
	 * $values = array('<foo/>', '<bar/>');
	 * </code>
	 *
	 * === `QueryTemplates` formula ===
	 * <code>
	 * $template['node1']->
	 *   varsFromValues($values)
	 * ;
	 * </code>
	 *
	 * === Template ===
	 * <code>
	 * <node1><?php  $0 = '<foo/>';
	 * $1 = '<bar/>';  ?><node2></node2></node1>
	 * </code>
	 *
	 * === Template tree before ===
	 * <code>
	 * node1
	 *  - node2
	 * </code>
	 *
	 * === Template tree after ===
	 * <code>
	 * node1
	 *  - PHP
	 *  - node2
	 * </code>
	 *
	 * @param array $varsArray
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function varsFromValues($varsArray) {
		return $this->qt_langMethod('prepend',
			$this->qt_langCode('valuesToVars', $varsArray)
		);
	}
	/**
	 * @deprecated
	 */
	public function valuesToVars($varsArray) {
		return varsFromValues($varsArray);
	}
	/**
	 * Saves markupOuter() as value of variable $var avaible in template scope.
	 *
	 * @param String $name
	 * New variable name.
	 *
	 * @TODO user self::parent for storing vars
	 * @TODO support second $method param
	 */
	public function varsFromStack($name) {
		$object = $this;
		while($object->previous)
			$object = $object->previous;
		$object->vars[$name] = $this->markupOuter();
		return $this;
	}
	// TODO phpdoc
  /**
   * testMethod 
   * 
   * @access public
   * @return void
   */
  public function testMethod() {
    foo();
  }
	/**
	 * @deprecated
	 */
	public function saveAsVar($name) {
		return $this->varsFromStack($name);
	}
	/**
	 * @deprecated
	 */
	public function saveTextAsVar($name) {
		return $this->varsFromStackText($name);
	}
	/**
	 * Saves text() as value of variable $var avaible in template scope.
	 *
	 * @param String $name
	 * New variable name.
	 *
	 * @TODO user self::parent for storing vars
	 */
	public function varsFromStackText($name) {
		$object = $this;
		while($object->previous)
			$object = $object->previous;
		$object->vars[$name] = $this->text();
		return $this;
	}
}

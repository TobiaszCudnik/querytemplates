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
class QueryTemplatesSyntaxDOM extends phpQueryObject {
	/**
	 * Returns array being result of running $method on all stack elements.
	 *
	 * @param string $method
	 * Method used for output.
	 * @return array
	 */
	public function stackToMethod($method = 'markupOuter') {
		$result;
		$avaibleMethods = array(
			'htmlOuter', 'xmlOuter', 'text', 'val', 'html', 'xml', 'markup', 'markupOuter'
		);
		if (! $avaibleMethods[$method])
			return $this;
		foreach($this as $pq) {
			$result[] = call_user_func_array(array($pq, $method), array());
		}
		return $result;
	}
	/**
	 * Removes selected element and moves it's children into parent node.
	 *
	 * @return QueryTemplatesParse|QueryTemplatesPhpQuery
	 */
	public function unWrap() {
		return $this->after($this->contents())->remove();
	}
	/**
	 * @todo use attr() function (encoding issues etc)
	 * @see src/phpQuery-stock/phpQueryObject#attrAppend()
	 */
	public function attrAppend($attr, $value) {
		foreach($this->stack(1) as $node )
			$node->setAttribute($attr,
				$node->getAttribute($attr).$value
			);
		return $this;
	}
	public function attrPrepend($attr, $value) {
		foreach($this->stack(1) as $node )
			$node->setAttribute($attr,
				$value.$node->getAttribute($attr)
			);
		return $this;
	}
}
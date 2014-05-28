<?php
namespace Mouf\Html\Widgets\Form;

use Mouf\Html\Tags\Label;
use Mouf\Html\Renderer\Renderable;
use Mouf\Utils\Value\ValueInterface;
use Mouf\Html\HtmlElement\HtmlElementInterface;
use Mouf\Html\HtmlElement\HtmlBlock;
use Mouf\Utils\Value\ValueUtils;
use Mouf\Html\HtmlElement\HtmlString;
use Mouf\Html\Tags\Select;
use Mouf\Utils\Value\ArrayValueInterface;
use Mouf\Html\Tags\Option;
use Mouf\Html\Tags\Optgroup;

/**
 * A SelectMultipleField represent a couple of &lt;label&gt; and &lt;select fields.
 * This class is "renderable" so you can overload the way label and select fields are displayed.
 * 
 * @author Marc Teyssier <m.teyssier@thecodingmachine.com>
 */
class SelectMultipleField extends SelectField {
	
	/**
	 * The value selected
	 * @var string[]|ArrayValueInterface|null
	 */
	protected $values = null;

	private static $LASTID = 0;
	
	/**
	 * Constructs the textfield.
	 * 
	 * @param string|ValueInterface $label
	 * @param string|ValueInterface $name
	 * @param string[]|ArrayValueInterface $values
	 * @param array<string, string>|ArrayValueInterface|Option[] $options
	 * @param Select $select The Select tag to use (optionnal). If not specified, a default 'select' tag will be used instead.
	 */
	public function __construct($label = null, $name = null, $values = null, $options = array(), Select $select = null) {
		parent::__construct($label, $name, null, $options, $select);
		if ($values !== null) {
			$this->values = $values;
		}
		$this->select->setMultiple('multiple');
	}

	/**
	 * Set array of values checked
	 *
	 * @param string[]|ArrayValueInterface $values
	 * @return static
	 */
	public function setValues($values) {
		$this->values = ValueUtils::val($values);
		return $this;
	}
	
	/**
	 * Return array of values checked
	 *
	 * @return array
	 */
	public function getValues() {
		return $this->values;
	}
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 */
	public function toHtml() {
		if($this->values) {
			foreach ($this->options as $option) {
				if(array_search($option->getValue(), $this->values)) {
					$option->setSelected('selected');
				}
			}
		}
		$this->select->setChildren($this->options);
		if ($this->label->getFor() === null) {
			$id = $this->select->getId();
			if ($id === null) {
				$id = "mouf_html_widgets_selectmultiplefield_".self::$LASTID;
				$this->select->setId($id);
				$this->label->setFor($id);
				self::$LASTID++;
			}
		}
		
		$this->toHtmlParent();
	}
}
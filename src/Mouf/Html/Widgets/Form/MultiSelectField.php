<?php
namespace Mouf\Html\Widgets\Form;
use Mouf\Html\Widgets\Form\Styles\StylableFormField;

use Mouf\Html\Tags\Span;

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
use \ArrayObject;

/**
 * A SelectMultipleField represent a couple of &lt;label&gt; and &lt;select fields.
 * This class is "renderable" so you can overload the way label and select fields are displayed.
 * 
 * @author Kevin Nguyen <k.nguyen@thecodingmachine.com>
 */
class MultiSelectField implements HtmlElementInterface {
	use Renderable {
		Renderable::toHtml as toHtmlParent;
	}
	
	use StylableFormField;
	
	/**
	 * @var Label
	 */
	protected $label;
	/**
	 * @var Name
	 */
	protected $name;

	/**
	 * @var array<Select>
	 */
	protected $selects;

	/**
	 * Boolean, true if the select is required, else false
	 * @var bool
	 */
	protected $required = false;
	
	/**
	 * The value selected
	 * @var array<string>|ArrayValueInterface|null
	 */
	protected $values = null;

	/**
	 * Option list
	 * @var Option[]
	 */
	protected $options = array();
	
	/**
	 * @var HtmlElementInterface
	 */
	protected $helpText;
	
	private static $LASTID = 0;
	
	/**
	 * @var Span
	 */
	protected $removeElement;

	/**
	 * @var Span
	 */
	protected $addElement;
	
	/**
	 * Select
	 */
	protected $selectTemplate;

	/**
	 * Constructs the textfield.
	 * 
	 * @param string|ValueInterface $label
	 * @param string|ValueInterface $name
	 * @param string|ValueInterface $value
	 * @param array<string, string>|ArrayValueInterface|Option[] $options
	 */
	public function __construct($label = null, $name = null, $values = null, $options = array()) {
		$this->name = $name;
		$this->label = new Label();
		$this->selects = array();
		if ($values !== null) {
			$this->values = $values;
			if (count($values)) {
				foreach ($values as $value){
					$this->selects[] = new Select();
				}
			}
		}
		$this->removeElement = new Span();
		$this->addElement = new Span();
		if ($label !== null) {
			$this->label->addText($label);
		}
		if ($name !== null) {
			$i = 0;
			foreach ($this->selects as $select){
				/* @var $select Select */
				$select->setName($name . "[]");
				$select->addDataAttribute('id', $name . '-item-' . $i);
				$i++;
			}
		}
		if($options) {
			$this->setOptions($options);
		}
		$this->selectTemplate = new Select();
		$this->selectTemplate->addDataAttribute('name', $name . "[]");
		$this->selectTemplate->addDataAttribute('id', $name . '-item-mouftemplate');
	}
	
	/**
	 * Specifies one or more classnames for the label
	 *
	 * @param array|ValueInterface $classes
	 * @return static
	 */
	public function setLabelClasses(array $classes) {
		$this->label->setClasses($classes);
	}
	
	/**
	 * Adds a CSS class to the label
	 *
	 * @param string|ValueInterface $class
	 * @return static
	 */
	public function addLabelClass($class) {
		$this->label->addClass($class);
	}
	
	/**
	 * Specifies one or more classnames for the select
	 *
	 * @param array|ValueInterface $classes
	 * @return static
	 */
	public function setSelectClasses(array $classes) {
		foreach ($this->selects as $select){
			$select->setClasses($classes);
		}
	}
	
	/**
	 * Set options of select 
	 * @param array<string, string>|ArrayValueInterface|Option[] $options
	 * @return static
	 */
	public function setOptions($options) {
		$options = ValueUtils::val($options);
		$this->options = $this->checkOptions($options);
		return $this;
	}

	/**
	 * 
	 * @param array<string, string>|ArrayValueInterface|Option[] $options
	 * @return Option[]|Optgroup[]
	 */
	private function checkOptions($setOptions) {
		$options = array();
		foreach ($setOptions as $keyOption => $setOption) {
			if(is_array($setOption)) {
				$optGroup = new Optgroup();
				$optGroup->setLabel($keyOption);
				$optGroup->setChildren($this->checkOptions($setOption));
				$options[] = $optGroup;
			}
			elseif(is_object($setOption)) {
				if(get_class($setOption) == 'ArrayValueInterface') {
					$optionTag = new Option();
					$optionTag->setValue($keyOption);
					$optionTag->addText($setOption->setVal());
					$options[] = $optionTag;
				}
				else {
					$options[] = $setOption;
				}
			}
			else {
				$optionTag = new Option();
				$optionTag->setValue($keyOption);
				$optionTag->addText($setOption);
				$options[] = $optionTag;
			}
		}
		return $options;
	}
	
	/**
	 * Adds a CSS class to the select
	 *
	 * @param string|ValueInterface $class
	 * @return static
	 */
	public function addSelectClass($class) {
		foreach ($this->selects as $select){
			$select->addClass($class);
		}
	}
	
	/**
	 * Completely replaces the label element with the label you provide.
	 * Use this function only if you want to perform very specific tasks you cannot do with the methods provided above.
	 *
	 * @param Label $label
	 * @return static
	 */
	public function setLabel(Label $label) {
		$this->label = $label;
		return $this;
	}

	/**
	 * Returns the label object for this field (as an object implementing the Label class)
	 *
	 * @return Label
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * Returns the name object for this field (as an object implementing the Label class)
	 *
	 * @return Label
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Completely replaces the select element with the select you provide.
	 * Use this function only if you want to perform very specific tasks you cannot do with the methods provided above.
	 *
	 * @param Select $select
	 * @return static
	 */
	public function setSelects($selects) {
		$this->selects = $selects;
		return $this;
	}

	/**
	 * Returns the select object for this field (as an object implementing the Select class)
	 *
	 * @return Select
	 */
	public function getSelects() {
		return $this->selects;
	}
	
	/**
	 * Set the value select
	 *
	 * @param string $required
	 * @return static
	 */
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
	
	/**
	 * Return the value select
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * Set whether the field is required or not
	 *
	 * @param bool $required
	 * @return static
	 */
	public function setRequired($required) {
		$this->required = $required;
		return $this;
	}
	
	/**
	 * Return whether the field is required or not
	 *
	 * @return bool
	 */
	public function isRequired() {
		return $this->required;
	}
	
	/**
	 * Sets the help text for this text field.
	 * 
	 * @param string|ValueInterface|HtmlElementInterface $helpText
	 */
	public function setHelpText($helpText) {
		if ($helpText instanceof ValueInterface) {
			$helpText = ValueUtils::val($helpText);
		}
		if(empty($helpText)) {
			$this->helpText = null;
		}
		elseif ($helpText instanceof HtmlElementInterface) {
			$this->helpText = $helpText;
		} else {
			$this->helpText = new HtmlString((string) $helpText);
		}
	}
	
	/**
	 * Returns the help text for this field (as an object implementing the  HtmlElementInterface)
	 * 
	 * @return HtmlElementInterface
	 */
	public function getHelpText() {
		return $this->helpText;
	}
	
	/**
	 * @return Span
	 */
	public function getRemoveElement(){
		return $this->removeElement;
	}
	
	/**
	 * @return Span
	 */
	public function getAddElement(){
		return $this->addElement;
	}
	
	public function getSelectTemplate(){
		return $this->selectTemplate;
	}
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 */
	public function toHtml() {
		if($this->values) {
			$vals = ValueUtils::val($this->values);
			foreach ($this->selects as $select){
				$copy = $this->cloneOption();
				foreach ($copy as $option) {
					$index = array_search($option->getValue(), $vals);
					if($index !== false) {
						$option->setSelected('selected');
						unset($vals[$index]);
						break;
					}
				}
				$select->setChildren($copy);
			} 
		}
		if ($this->isRequired() && !count($this->selects)){
			$select = new Select();
			$select->setChildren($this->options);
			$this->selects[] = $select;
		}
		$this->selectTemplate->setChildren($this->options);
		
		foreach ($this->selects as $select){
			/* @var $select Select */
			$id = $select->getId();
			if ($id === null){
				$id = "mouf_html_widgets_selectfield_".self::$LASTID;
				$select->setId($id);
				if ($this->label->getFor() === null) {
					$this->label->setFor($id);
				}
				self::$LASTID++;
			}
		}
		
		$this->toHtmlParent();
	}
	
	private function cloneOption(){
		$copy = array();
		foreach ($this->options as $option) {
			$newOpt = clone $option;
			$copy[] = $newOpt;
			;
		}
		return $copy;
	}
}
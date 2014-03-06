<?php
namespace Mouf\Html\Widgets\Form;

use Mouf\Html\Widgets\Form\Styles\StylableFormField;
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
use Mouf\Html\Tags\Input;

/**
 * A CheckboxesField represent a couple of &lt;label&gt; and &lt;select fields.
 * This class is "renderable" so you can overload the way label and select fields are displayed.
 * 
 * @author Marc Teyssier <m.teyssier@thecodingmachine.com>
 */
class CheckboxesField implements HtmlElementInterface {
	use Renderable {
		Renderable::toHtml as toHtmlParent;
	}
	
	use StylableFormField;
	
	/**
	 * @var Label
	 */
	protected $label;

	/**
	 * Boolean, true if the select is required, else false
	 * @var bool
	 */
	protected $required = false;
	
	/**
	 * Values selected
	 * @var string[]|ArrayValueInterface
	 */
	protected $values = array();

	/**
	 * Name of checkboxes
	 * @var string|null
	 */
	private $name = null;
	
	/**
	 * Checkbox list
	 * @var CheckboxField[]
	 */
	protected $checkboxes = array();
	
	/**
	 * @var HtmlElementInterface
	 */
	protected $helpText;
	
	private static $LASTID = 0;

	/**
	 * Constructs the checkboxesField.
	 * 
	 * @param string|ValueInterface $label
	 * @param string|ValueInterface $name
	 * @param string[]|ArrayValueInterface $values
	 * @param array<string, string>|ArrayValueInterface|CheckboxField[] $checkboxes
	 */
	public function __construct($label = null, $name = null, $values = array(), $checkboxes = array()) {
		$this->label = new Label();
		if ($label !== null) {
			$this->label->addText($label);
		}
		if ($name !== null) {
			$this->name = $name;
		}
		if ($values) {
			$this->values = $values;
		}
		if($checkboxes) {
			$this->setCheckboxes($checkboxes);
		}
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
	 * Set checkboxes 
	 * @param array<string, string>|ArrayValueInterface|CheckField[] $checkboxes
	 * @return static
	 */
	public function setCheckboxes($setCheckboxes) {
		$this->checkboxes = array();
		foreach ($setCheckboxes as $keyCheck => $setCheck) {
			if(is_object($setCheck)) {
				if(get_class($setCheck) == 'ArrayValueInterface') {
					$optionTag = new Input();
					$optionTag->setType('checkbox');
					$optionTag->setValue($keyCheck);
					$optionTag->setLabel($setCheck->setVal());
					$this->checkboxes[] = $optionTag;
				}
				else {
					$this->checkboxes[] = $setCheck;
				}
			}
			else {
				$optionTag = new Option();
				$optionTag->setType('checkbox');
				$optionTag->setValue($keyCheck);
				$optionTag->setLabel($setCheck);
				$this->checkboxes[] = $optionTag;
			}
		}
		return $this;
	}

	/**
	 * Returns check list
	 *
	 * @return CheckField[]
	 */
	public function getCheckboxes() {
		return $this->checkboxes;
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
	 * Set name
	 *
	 * @param string $name
	 * @return static
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Return the name of checkboxes
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
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
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 */
	public function toHtml() {
		$name = $this->name;
		if(!$name) {
			$name = "mouf_html_widgets_checkboxesfield_".self::$LASTID;
			self::$LASTID++;
		}
		foreach ($this->checkboxes as $check) {
			if(!$check->getInput()->getName()) {
				$check->getInput()->setName($name.'[]');
			}
			if(array_search($check->getInput()->getValue(), $this->values) !== false) {
				$check->setChecked(true);
			}
			$check->setContext('inline');
		}
		$this->toHtmlParent();
	}
}
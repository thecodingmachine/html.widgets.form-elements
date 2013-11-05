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
use Mouf\Html\Tags\Input;

/**
 * A RadiosField represent a couple of &lt;label&gt; and &lt;select fields.
 * This class is "renderable" so you can overload the way label and select fields are displayed.
 * 
 * @author Marc Teyssier <m.teyssier@thecodingmachine.com>
 */
class RadiosField implements HtmlElementInterface {
	use Renderable {
		Renderable::toHtml as toHtmlParent;
	}
	
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
	 * Value selected
	 * @var string|ValueInterface|null
	 */
	protected $value = null;

	/**
	 * Name of radios
	 * @var string|null
	 */
	private $name = null;
	
	/**
	 * Checkbox list
	 * @var RadioField[]
	 */
	protected $radios = array();
	
	/**
	 * @var HtmlElementInterface
	 */
	protected $helpText;
	
	private static $LASTID = 0;

	/**
	 * Constructs the radiosField.
	 * 
	 * @param string|ValueInterface $label
	 * @param string|ValueInterface $name
	 * @param string|ValueInterface $value
	 * @param array<string, string>|ArrayValueInterface|RadioField[] $radios
	 */
	public function __construct($label = null, $name = null, $value = null, $radios = array()) {
		$this->label = new Label();
		if ($label !== null) {
			$this->label->addText($label);
		}
		if ($name !== null) {
			$this->name = $name;
		}
		if ($value !== null) {
			$this->value = $value;
		}
		if($radios) {
			$this->setRadios($radios);
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
	 * @param array<string, string>|ArrayValueInterface|Radio[] $radios
	 * @return static
	 */
	public function setRadios($setRadios) {
		$this->radios = array();
		foreach ($setRadios as $keyRadio => $setRadio) {
			if(is_object($setRadio)) {
				if(get_class($setRadio) == 'ArrayValueInterface') {
					$optionTag = new Input();
					$optionTag->setType('radio');
					$optionTag->setValue($keyRadio);
					$optionTag->setLabel($setRadio->setVal());
					$this->radios[] = $optionTag;
				}
				else {
					$this->radios[] = $setRadio;
				}
			}
			else {
				$optionTag = new Option();
				$optionTag->setType('radio');
				$optionTag->setValue($keyRadio);
				$optionTag->setLabel($setRadio);
				$this->radios[] = $optionTag;
			}
		}
		return $this;
	}

	/**
	 * Returns radio list
	 *
	 * @return RadioField[]
	 */
	public function getRadios() {
		return $this->radios;
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
	 * Return the name of radios
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Set array of values checked
	 *
	 * @param string|ValueInterface $value
	 * @return static
	 */
	public function setValue($value) {
		$this->value = ValueUtils::val($value);
		return $this;
	}
	
	/**
	 * Return array of value checked
	 *
	 * @return array
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
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 */
	public function toHtml() {
		$name = $this->name;
		if(!$name) {
			$name = "mouf_html_widgets_radiosfield_".self::$LASTID;
			self::$LASTID++;
		}
		foreach ($this->radios as $radio) {
			$radio->getInput()->setName($name);
			if($radio->getInput()->getValue() == $this->value) {
				$radio->setChecked(true);
			}
		}
		$this->toHtmlParent();
	}
}
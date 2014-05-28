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

/**
 * A SelectField represent a couple of &lt;label&gt; and &lt;select fields.
 * This class is "renderable" so you can overload the way label and select fields are displayed.
 * 
 * @author Marc Teyssier <m.teyssier@thecodingmachine.com>
 */
class SelectField implements HtmlElementInterface {
	use Renderable {
		Renderable::toHtml as toHtmlParent;
	}
	
	use StylableFormField;
	
	/**
	 * @var Label
	 */
	protected $label;

	/**
	 * @var Select
	 */
	protected $select;

	/**
	 * Boolean, true if the select is required, else false
	 * @var bool
	 */
	protected $required = false;
	
	/**
	 * The value selected
	 * @var string|ValueInterface|null
	 */
	protected $value = null;

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
	 * Constructs the textfield.
	 * 
	 * @param string|ValueInterface $label
	 * @param string|ValueInterface $name
	 * @param string|ValueInterface $value
	 * @param array<string, string>|ArrayValueInterface|Option[] $options
	 * @param Select $select The Select tag to use (optionnal). If not specified, a default 'select' tag will be used instead.
	 */
	public function __construct($label = null, $name = null, $value = null, $options = array(), Select $select = null) {
		$this->label = new Label();
		if ($select == null) {
			$this->select = new Select();
		} else {
			$this->select = $select;
		}
		if ($label !== null) {
			$this->label->addText($label);
		}
		if ($name !== null) {
			$this->select->setName($name);
		}
		if ($value !== null) {
			$this->value = $value;
		}
		if($options) {
			$this->setOptions($options);
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
	 * Specifies one or more classnames for the select
	 *
	 * @param array|ValueInterface $classes
	 * @return static
	 */
	public function setSelectClasses(array $classes) {
		$this->select->setClasses($classes);
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
		$this->select->addClass($class);
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
	 * Completely replaces the select element with the select you provide.
	 * Use this function only if you want to perform very specific tasks you cannot do with the methods provided above.
	 *
	 * @param Select $select
	 * @return static
	 */
	public function setSelect(Select $select) {
		$this->select = $select;
		return $this;
	}

	/**
	 * Returns the select object for this field (as an object implementing the Select class)
	 *
	 * @return Select
	 */
	public function getSelect() {
		return $this->select;
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
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 */
	public function toHtml() {
		if($this->value) {
			foreach ($this->options as $option) {
				if($this->value == $option->getValue()) {
					$option->setSelected('selected');
				}
			}
		}
		$this->select->setChildren($this->options);
		if ($this->label->getFor() === null) {
			$id = $this->select->getId();
			if ($id === null) {
				$id = "mouf_html_widgets_selectfield_".self::$LASTID;
				$this->select->setId($id);
				$this->label->setFor($id);
				self::$LASTID++;
			}
		}
		
		$this->toHtmlParent();
	}
}
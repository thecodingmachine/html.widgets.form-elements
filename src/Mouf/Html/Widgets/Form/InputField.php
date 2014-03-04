<?php
namespace Mouf\Html\Widgets\Form;

use Mouf\Html\Widgets\Form\Styles\StylableFormField;

use Mouf\Html\Tags\Label;
use Mouf\Html\Tags\Input;
use Mouf\Html\Renderer\Renderable;
use Mouf\Utils\Value\ValueInterface;
use Mouf\Html\HtmlElement\HtmlElementInterface;
use Mouf\Html\HtmlElement\HtmlBlock;
use Mouf\Utils\Value\ValueUtils;
use Mouf\Html\HtmlElement\HtmlString;

/**
 * A InputField represent a couple of &lt;label&gt; and &lt;input type="text"&gt; fields.
 * This class is "renderable" so you can overload the way label and input fields are displayed.
 * 
 * @author Marc Teyssier <m.teyssier@thecodingmachine.com>
 */
class InputField implements HtmlElementInterface {
	use Renderable {
		Renderable::toHtml as toHtmlParent;
	}
	
	use StylableFormField;
	
	/**
	 * @var Label
	 */
	protected $label;

	/**
	 * @var Input
	 */
	protected $input;
	
	/**
	 * Boolean, true if the input is required, else false
	 * @var bool
	 */
	protected $required = false;
	
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
	 */
	public function __construct($label = null, $name = null, $value = null) {
		$this->label = new Label();
		$this->input = new Input();
		if ($label !== null) {
			$this->label->addText($label);
		}
		if ($name !== null) {
			$this->input->setName($name);
		}
		if ($value !== null) {
			$this->input->setValue($value);
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
		return $this;
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
	 * Specifies one or more classnames for the input
	 *
	 * @param array|ValueInterface $classes
	 * @return static
	 */
	public function setInputClasses(array $classes) {
		$this->input->setClasses($classes);
		return $this;
	}
	
	/**
	 * Adds a CSS class to the input
	 *
	 * @param string|ValueInterface $class
	 * @return static
	 */
	public function addInputClass($class) {
		$this->input->addClass($class);
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
	 * Completely replaces the input element with the input you provide.
	 * Use this function only if you want to perform very specific tasks you cannot do with the methods provided above.
	 *
	 * @param Input $input
	 * @return static
	 */
	public function setInput(Input $input) {
		$this->input = $input;
		return $this;
	}

	/**
	 * Returns the input object for this field (as an object implementing the Input class)
	 *
	 * @return Input
	 */
	public function getInput() {
		return $this->input;
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
		
		if ($this->label->getFor() === null) {
			$id = $this->input->getId();
			if ($id === null) {
				$id = "mouf_html_widgets_".$this->input->getType()."field_".self::$LASTID;
				$this->input->setId($id);
				$this->label->setFor($id);
				self::$LASTID++;
			}
			else {
				$this->label->setFor($this->input->getId());
			}
		}
		
		$this->toHtmlParent();
	}
}
<?php
namespace Mouf\Html\Widgets\Form;

use Mouf\Html\Tags\Label;
use Mouf\Html\Tags\Input;
use Mouf\Html\Renderer\Renderable;
use Mouf\Utils\Value\ValueInterface;
use Mouf\Html\HtmlElement\HtmlElementInterface;
use Mouf\Html\HtmlElement\HtmlBlock;
use Mouf\Utils\Value\ValueUtils;
use Mouf\Html\HtmlElement\HtmlString;

/**
 * A CheckboxField represent a couple of &lt;label&gt; and &lt;input type="checkbox"&gt; fields.
 * This class is "renderable" so you can overload the way label and input fields are displayed.
 * 
 * @author Marc Teyssier <m.teyssier@thecodingmachine.com>
 */
class CheckboxField extends InputField {
	
	/**
	 * Constructs the textfield.
	 *
	 * @param string|ValueInterface $label
	 * @param string|ValueInterface $name
	 * @param string|ValueInterface $value
	 * @param bool $checked
	 */
	public function __construct($label = null, $name = null, $value = null, $checked = false) {
		parent::__construct($label, $name, $value);
		$this->input->setType('checkbox');
		$this->setChecked($checked);
	}
	

	/**
	 * Set checked for checkbox input
	 *
	 * @param bool $checked
	 * @return static
	 */
	public function setChecked($checked) {
		if($checked) {
			$this->input->setChecked('checked');
		}
		else {
			$this->input->setChecked(null);
		}
		return $this;
	}

	/**
	 * Returns the input checked
	 *
	 * @return bool
	 */
	public function getChecked() {
		return ($this->input->getChecked()?true:false);
	}
}
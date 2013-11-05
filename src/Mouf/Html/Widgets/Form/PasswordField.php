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
 * A PasswordField represent a couple of &lt;label&gt; and &lt;input type="text"&gt; fields.
 * This class is "renderable" so you can overload the way label and input fields are displayed.
 * 
 * @author Marc Teyssier <m.teyssier@thecodingmachine.com>
 */
class PasswordField extends InputField {

	/**
	 * Constructs the textfield.
	 * 
	 * @param string|ValueInterface $label
	 * @param string|ValueInterface $name
	 * @param string|ValueInterface $value
	 */
	public function __construct($label = null, $name = null, $value = null) {
		parent::__construct($label, $name, $value);
		$this->input->setType('password');
	}
	
}
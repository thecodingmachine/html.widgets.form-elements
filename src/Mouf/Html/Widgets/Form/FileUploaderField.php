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
use Mouf\Html\Widgets\FileUploaderWidget\FileUploaderWidget;

/**
 * A TextField represent a couple of &lt;label&gt; and &lt;input type="text"&gt; fields.
 * This class is "renderable" so you can overload the way label and input fields are displayed.
 * 
 * @author Marc Teyssier <m.teyssier@thecodingmachine.com>
 */
class FileUploaderField implements HtmlElementInterface {
	use Renderable {
		Renderable::toHtml as toHtmlParent;
	}
	
	use StylableFormField;
	
	/**
	 * @var Label
	 */
	protected $label;

	/**
	 * @var FileUploaderWidget
	 */
	protected $fileUploder;
	
	/**
	 * List of file already send
	 * @var string[]
	 */
	protected $fileList;

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
	 * @param FileUploaderWidget $fileUploder
	 * @param string[] $fileList
	 */
	public function __construct($label = null, $fileUploder = null, $fileList = array()) {
		$this->label = new Label();
		if ($label !== null) {
			$this->label->addText($label);
		}
		$this->fileUploder = $fileUploder;
		$this->fileList = $fileList;
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
	 * Set the FileUploaderWidget to rendrere it.
	 *
	 * @param FileUploaderWidget $fileUplodaer
	 * @return static
	 */
	public function setFileUploader(FileUploaderWidget $fileUplodaer) {
		$this->fileUploder = $fileUplodaer;
		return $this;
	}
	
	/**
	 * get FileUploaderWidget
	 *
	 * @return FileUploaderWidget
	 */
	public function getFileUploader() {
		return $this->fileUploder;
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
	 * Set the list of all file already update.
	 * It is an array of string that contain path.
	 *
	 * @param array $fileList
	 * @return static
	 */
	public function setFileList($fileList) {
		$this->fileList = $fileList;
		return $this;
	}

	/**
	 * Returns the array of file upload
	 *
	 * @return Input
	 */
	public function getFileList() {
		return $this->fileList;
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
		
		$this->toHtmlParent();
	}
}
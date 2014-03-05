<?php 
namespace Mouf\Html\Widgets\Form\Styles;

use Mouf;

/**
 * Classes using this trait will implement a forl layout value
 * 
 * @author Kévin Nguyen <k.nguyen@thecodingmachine.com>
 */
trait StylableFormField {
	
	
	protected $layoutStyle;
	
	/**
	 * @param LayoutStyle $layout
	 */
	public function setLayout(LayoutStyle $layout){
		$this->layoutStyle = $layout;
	}

	/**
	 * @return LayoutStyle
	 */
	public function getLayout(){
		return $this->layoutStyle;
	}
	
	
	
}
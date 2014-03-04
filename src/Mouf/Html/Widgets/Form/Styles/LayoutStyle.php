<?php
namespace Mouf\Html\Widgets\Form\Styles;

class LayoutStyle {
	
	const LAYOUT_INLINE = "inline";
	const LAYOUT_CLEAR = "clear";
	
	/**
	 * @var string
	 */
	private $layoutType;
	
	public function __construct($layoutType){
		$this->layoutType = $layoutType;
	}
	
	public function getLayoutType(){
		return $this->layoutType;
	}
	
	public function setLayoutType($layoutType){
		$this->layoutType = $layoutType;
	}
	
}
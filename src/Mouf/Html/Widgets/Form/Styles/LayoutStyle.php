<?php
namespace Mouf\Html\Widgets\Form\Styles;

class LayoutStyle {
	
	const LAYOUT_INLINE = "inline";
	const LAYOUT_CLEAR = "clear";
	
	/**
	 * @OneOf("inline", "clear")
	 * @var string
	 */
	private $layoutType;
	
	/**
	 * Defines the ratio of the label vs field IF display is inline
	 * @var double
	 */
	private $ratio;
	
	public function __construct($layoutType){
		$this->layoutType = $layoutType;
	}
	
	public function getLayoutType(){
		return $this->layoutType;
	}
	
	public function setLayoutType($layoutType){
		$this->layoutType = $layoutType;
	}
	
	public function setLayoutRatio($ratio){
		$this->ratio = $ratio;
	}
	
	public function getLayoutRatio(){
		return $this->ratio;
	}
	
	
	
}
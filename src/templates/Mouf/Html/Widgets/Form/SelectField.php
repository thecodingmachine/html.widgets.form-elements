<?php
	/* @var $object Mouf\Html\Widgets\Form\TextField */
	if($required) {
		$object->getLabel()->addText('<span class="required">*</span>');
	}
	$object->getLabel()->toHtml();
	$object->getSelect()->toHtml();
	if($object->getHelpText()) {
		$object->getHelpText()->toHtml();
	}

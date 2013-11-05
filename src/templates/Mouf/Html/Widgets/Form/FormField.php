<?php
	/* @var $object Mouf\Html\Widgets\Form\FormField */
	if($required) {
		$object->getLabel()->addText('<span class="required">*</span>');
	}
	$object->getLabel()->toHtml();
	$object->getElement()->toHtml();
	if($object->getHelpText()) {
		$object->getHelpText()->toHtml();
	}

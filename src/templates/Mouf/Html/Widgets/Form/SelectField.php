<?php
	/* @var $object Mouf\Html\Widgets\Form\SelectField */
	if($required) {
		$object->getLabel()->addText('<span class="required">*</span>');
	}
	$object->getLabel()->toHtml();
	$object->getSelect()->toHtml();
	if($object->getHelpText()) {
		$object->getHelpText()->toHtml();
	}

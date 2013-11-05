<?php
	/* @var $object Mouf\Html\Widgets\Form\TextAreaField */
	if($required) {
		$object->getLabel()->addText('<span class="required">*</span>');
	}
	$object->getLabel()->toHtml();
	$object->getTextarea()->toHtml();
	if($object->getHelpText()) {
		$object->getHelpText()->toHtml();
	}

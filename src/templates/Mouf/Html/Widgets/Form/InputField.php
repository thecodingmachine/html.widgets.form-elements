<?php
	/* @var $object Mouf\Html\Widgets\Form\TextField */
	if($required) {
		$object->getLabel()->addText('<span class="required">*</span>');
	}
	$object->getLabel()->toHtml();
	if($object->isRequired()) {
		$object->getInput()->setRequired('required');
	}
	$object->getInput()->toHtml();
	if($object->getHelpText()) {
		$object->getHelpText()->toHtml();
	}

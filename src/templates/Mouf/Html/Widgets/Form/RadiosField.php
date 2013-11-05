<?php
	/* @var $object Mouf\Html\Widgets\Form\RadiosField */
	if($required) {
		$object->getLabel()->addText('<span class="required">*</span>');
	}
	$object->getLabel()->toHtml();
	foreach ($object->getRadios() as $radio) {
		$radio->toHtml();
	}
	if($object->getHelpText()) {
		$object->getHelpText()->toHtml();
	}

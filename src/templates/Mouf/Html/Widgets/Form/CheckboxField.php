<?php
	/* @var $object Mouf\Html\Widgets\Form\CheckboxField */
	$object->getLabel()->toHtml();
	$object->getInput()->toHtml();
	if($object->getHelpText()) {
		$object->getHelpText()->toHtml();
	}

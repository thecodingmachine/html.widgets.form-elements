<?php
	/* @var $object Mouf\Html\Widgets\Form\CheckboxField */
	$object->getInput()->toHtml();
	$object->getLabel()->toHtml();
	if($object->getHelpText()) {
		$object->getHelpText()->toHtml();
	}

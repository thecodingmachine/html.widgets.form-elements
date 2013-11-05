<?php
	/* @var $object Mouf\Html\Widgets\Form\RadioField */
	$object->getLabel()->toHtml();
	$object->getInput()->toHtml();
	if($object->getHelpText()) {
		$object->getHelpText()->toHtml();
	}

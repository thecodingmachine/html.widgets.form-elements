<?php
use Mouf\Html\HtmlElement\HtmlString;

use Mouf\Html\Tags\Select;
/* @var $object Mouf\Html\Widgets\Form\MultiSelectField */

if($object->isRequired()) {
	$object->getLabel()->addText('<span class="required">*</span>');
}
$object->getLabel()->toHtml();
foreach ($object->getSelects() as $select){
	/* @var $select Select */
	$select->toHtml();
	$removeElem = $object->getRemoveElement();
	$removeElem->addDataAttribute('target', $select->getDataAttributes()['id']);
	$removeElem->addClass("mouf-remove-dd-item");
	$removeElem->setChildren(array(new HtmlString('remove')));
	$removeElem->toHtml();
}

if($object->getHelpText()) {
	$object->getHelpText()->toHtml();
}
?>
<pre style="display: none" id="<?php echo $object->getSelectTemplate()->getDataAttributes()['id']?>">
<?php
$object->getSelectTemplate()->toHtml();
$removeElem = $object->getRemoveElement();
$removeElem->addDataAttribute('target', $object->getSelectTemplate()->getDataAttributes()['id']);
$removeElem->addClass("mouf-remove-dd-item");
$removeElem->setChildren(array(new HtmlString('remove')));
$removeElem->toHtml();
?>
</pre>
<?php
$addElem = $object->getAddElement();
$addElem->addDataAttribute('target', $object->getSelectTemplate()->getDataAttributes()['id']);
$addElem->addDataAttribute('next', count($object->getSelects()));
$addElem->addClass('mouf-add-dd-item');
$addElem->setChildren(array(new HtmlString('add')));
$addElem->toHtml();
?>
<script type="text/javascript">
<!--
$('.mouf-remove-dd-item').click(function(){
	var target = $('select[data-id='+$(this).attr('data-target')+']');
	target.remove();
	$(this).remove();
});
$('.mouf-add-dd-item').click(function(){
	var index = parseInt($(this).data('next'));
	var selector = $(this).attr('data-target');
	var template = $('#'+selector);
	var html = template.html();
	html.replace(new RegExp('mouftemplate', 'g'), index);
	html.replace("data-name", "name");
	template.before(html);
	$(this).data('next', index + 1 );
	$(this).attr('data-next', index + 1 );
});
//-->
</script>

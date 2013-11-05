<?php
/* @var $object Mouf\Html\Widgets\Form\FileUploaderField */
if($object->getFileList()) {
	$scriptVals = array();
	$html = '<table>';
	$i = 0;
	foreach ($object->getFileList() as $value) {
		if($value) {
			$html .= '<tr class="file-upload-'.$object->getFileUploader()->inputName.'-'.$i.'">';
				$html .= '<td>';
					$ext = substr($value, strrpos($value, '.') + 1);
					$fileUrl = ROOT_URL.str_replace('\\', '/', $value);
					if($ext == 'jpeg' || $ext == 'jpg' || $ext == 'gif' || $ext == 'png'){
						$html .= '<img src="'.$fileUrl.'" style="width: 50px; height: 50px" /> (<a href="'.$fileUrl.'">link here</a>)';
					}else
						$html .= $value . "(<a href='$fileUrl'>link here</a>)";
				$html .= '</td>';
				$html .= '<td>';
					$html .= '<a href="#" onclick="return removeFileUpload_'.$object->getFileUploader()->inputName.'('.$i.', \''.str_replace('\\', '\\\\', $value).'\')">remove</a>';
				$html .= '</td>';
			$html .= '</tr>';
			
			$paths = explode(DIRECTORY_SEPARATOR, $value);
			$scriptVals[ROOT_URL.str_replace(DIRECTORY_SEPARATOR, "/", $value)] = $paths[count($paths) - 1];
			
			$i ++;
		}
	}
	
	$html .= '</table>';
	$html .= '<div id="remove-file-upload-'.$object->getFileUploader()->inputName.'"></div>';
	$html .= '
		<script>
			if (typeof bce_files === "undefined"){
				bce_files = {};
			}
			bce_files = $.extend(bce_files, '.json_encode($scriptVals).');
		</script>';
}

if($required) {
	$object->getLabel()->addText('<span class="required">*</span>');
}
$object->getLabel()->toHtml();
$object->getFileUploader()->toHtml();
if($object->getHelpText()) {
	$object->getHelpText()->toHtml();
}

<?php
/* @var $object Mouf\Html\Widgets\Form\FileUploaderField */

// This display all files already send. If you remove it, the validators required file will not work !
if($object->getFileList()) {
	$scriptVals = array();
	?>
	<table style="clear: both;">
		<?php
		$i = 0;
		foreach ($object->getFileList() as $value) {
			if($value) {
				?>
				<tr class="file-upload-<?php echo $object->getFileUploader()->inputName.'-'.$i?>">
					<td>
						<?php
						$ext = substr($value, strrpos($value, '.') + 1);
						$fileUrl = ROOT_URL.str_replace('\\', '/', $value);
						if($ext == 'jpeg' || $ext == 'jpg' || $ext == 'gif' || $ext == 'png'){
							?>
							<img src="<?php echo $fileUrl ?>" style="width: 50px; height: 50px" /> (<a href="<?php echo $fileUrl ?>">link here</a>)
							<?php
						} else {
							echo $value;
							?>
							(<a href="<?php echo $fileUrl ?>">link here</a>)
							<?php 
						}
						// This input is used to detect if a file is already send
						?>
						<input type="hidden" class="has-fileupload-<?php echo $object->getFileUploader()->inputName?>" name="has-fileupload-<?php echo $object->getFileUploader()->inputName?>[]" value="<?php echo $fileUrl ?>" />
					</td>
					<td>
						<?php 
						// Protect value
						$valueProtected = str_replace('\\', '\\\\', $value);
						$valueProtected = str_replace("'", "\\'", $valueProtected);
						?>
						<a href="#" onclick="return removeFileUpload_<?php echo $object->getFileUploader()->inputName."(".$i.", '".$valueProtected."')"?>">remove</a>
					</td>
				</tr>
				<?php 
				$paths = explode(DIRECTORY_SEPARATOR, $value);
				$scriptVals[ROOT_URL.str_replace(DIRECTORY_SEPARATOR, "/", $value)] = $paths[count($paths) - 1];
			
				$i ++;
			}
		}
		?>
	</table>
	<div id="remove-file-upload-<?php echo $object->getFileUploader()->inputName?>"></div>
	<script>
		if (typeof bce_files === "undefined"){
			bce_files = {};
		}
		bce_files = $.extend(bce_files, '.json_encode($scriptVals).');
	</script>
	<?php 
}
if($required) {
	$object->getLabel()->addText('<span class="required">*</span>');
}
$object->getLabel()->toHtml();
$object->getFileUploader()->toHtml();
if($object->getHelpText()) {
	$object->getHelpText()->toHtml();
}

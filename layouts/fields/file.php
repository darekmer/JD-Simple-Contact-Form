<?php
/**
 * @package   JD Simple Contact Form
 * @author    JoomDev https://www.joomdev.com
 * @copyright Copyright (C) 2021 Joomdev, Inc. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */
// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Component\ComponentHelper;

extract($displayData);
$attrs = [];
$attrs[] = 'id="' . $field->name . '-' . $module->id . '"';
if ($field->required) {
   $attrs[] = 'required';
   if (isset($field->custom_error) && !empty(trim($field->custom_error))) {
      $attrs[] = 'data-parsley-required-message="' . Text::sprintf($field->custom_error) . '"';
   } else {
      $attrs[] = 'data-parsley-required-message="' . Text::sprintf('MOD_JDSCF_REQUIRED_ERROR', strip_tags($label)) . '"';
   }
}

// fetching allowed types
$params = ComponentHelper::getParams('com_media');
$allowable = array_map('trim', explode(',', $params->get('restrict_uploads_extensions')));
$allowedMaxSize = $params->get('upload_maxsize');
$document = Factory::getDocument();
$style = '.filesize-err {'
        . 'display: none;'
        . 'margin-top: 10px;'
        . '}'
        . '.custom-file-label::after {'
        . 'content: "' . Text::sprintf('MOD_JDSCF_FILE_BTN_LBL') . '" !important;'
        . '}';
$document->addStyleDeclaration($style);
?>
<div class="custom-file">
   <input accept="<?php echo '.' . implode( ',.', $allowable ); ?>" type="file" name="jdscf[<?php echo $field->name; ?>]" class="custom-file-input" <?php echo implode(' ', $attrs); ?>>
   <label class="custom-file-label" for="<?php echo $field->name; ?>-<?php echo $module->id; ?>"><?php echo Text::_('MOD_JDSCF_FILE_LBL'); ?></label>
</div>

<div class="filesize-err filesize-error-<?php echo $field->name; ?>-<?php echo $module->id; ?> alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      File size is too big!
</div>

<?php
// File size validation
$js = 'var uploadField_'.$field->name.' = document.getElementById("' . $field->name . '-' .$module->id .'");';
$js .= 'uploadField_' . $field->name .'.onchange = function() {';
	$js .= 'var fileSizeBytes = this.files[0].size;';
	// $js .= 'var i = parseInt(Math.floor(Math.log(fileSizeBytes) / Math.log(1024)));';
	// $js .= 'var fileSizeMb = parseFloat(Math.round(fileSizeBytes / Math.pow(1024, i), 2));';
	$js .= 'var filesizeMb = fileSizeBytes/1024/1024;';

	$js .= 'if(filesizeMb > ' . $allowedMaxSize .'){';
		$js .= 'uploadField_'.$field->name.'.value = "";';
		$js .= 'jQuery(".filesize-error-' . $field->name . '-' . $module->id . '").show();';
	$js .= '}';
$js .= '};';

$js .= 'jQuery("#' . $field->name . '-' .$module->id . '").on("change", function() {';
    $js .= 'var fileName = jQuery(this).val().split("\\\").pop();';
    $js .= 'jQuery(this).siblings(".custom-file-label").addClass("selected").html(fileName);';
$js .= '});';

ModJDSimpleContactFormHelper::addJS($js, $module->id);
?>

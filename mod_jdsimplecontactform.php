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
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Uri\Uri;

require_once dirname(__FILE__) . '/helper.php';

$document = Factory::getDocument();
$document->addStylesheet(URI::root() . 'media/mod_jdsimplecontactform/assets/css/style.css?v=' . $document->getMediaVersion());
$document->addStylesheet('//cdn.jsdelivr.net/npm/pikaday/css/pikaday.css');

$layout = $params->get('layout', 'default');
// Adding Module Class Suffix.
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8');
require ModuleHelper::getLayoutPath('mod_jdsimplecontactform', $layout);

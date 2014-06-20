<?php
/**
 * @package		Mailjet
 * @subpackage	mod_mailjet
 * @copyright	Copyright (C) 2011 - 2012 Mailjet SAS All rights reserved.
 * @license		MIT license
 */

// no direct access
defined('_JEXEC') or die;
JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');

$module->label = $params->get('label');
$module->list_id = $params->get('list');
$module->success = $params->get('success');
$module->error = $params->get('error');
$module->title = $params->get('title');
$module->button_text = $params->get('button_text');
$module->confirm_text = $params->get('confirm_text');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_mailjet', $params->get('layout', 'default'));

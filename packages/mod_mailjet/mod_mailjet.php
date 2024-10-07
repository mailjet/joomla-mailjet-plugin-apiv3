<?php
/**
 * @author     Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// no direct access
defined('_JEXEC') or die;
JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');

$module->label        = $params->get('label');
$module->list_id      = $params->get('list');
$module->success      = $params->get('success');
$module->duplicate    = $params->get('duplicate');
$module->error        = $params->get('error');
$module->title        = $params->get('title');
$module->button_text  = $params->get('button_text');
$module->confirm_text = $params->get('confirm_text');
$moduleclass_sfx      = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_mailjet', $params->get('layout', 'default'));

<?php
/**
 * @author     Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Version;


$jversion = new Version();
if (version_compare($jversion->getShortVersion(), '2.5', '<=')) {
    if (Factory::getApplication()->get('jquery') !== true) {
        // load jQuery here
        Joomla\CMS\HTML\HTMLHelper::script(Juri::base() . 'modules/mod_mailjet/includes/jquery-2.1.3.min.js');
        Factory::getApplication()->set('jquery', true);
    }
} else {
    HTMLHelper::_('jquery.framework');
}

Factory::getApplication()->getDocument()->addStyleSheet(JURI::base() . "components/com_mailjet/styles.css");

if (version_compare($jversion->getShortVersion(), '2.5.6', 'lt')) {
    HTMLHelper::script('mod_mailjet.js', 'modules/mod_mailjet/includes/', false);
} else {
    HTMLHelper::script(Juri::base() . 'modules/mod_mailjet/includes/mod_mailjet.js');
}

// no direct access
defined('_JEXEC') or die;
?>


<div class="mailjet<?php echo $moduleclass_sfx ?>">
    <h3><?php echo $module->title; ?></h3>
    <form class="mailjet-subscribe">
        <label for="mailjet-email"><?php echo $module->label; ?></label>
        <input type="email" name="mailjet-email" id="mailjet-email"/>
        <button type="submit"><?php echo $module->button_text; ?></button>


        <input type="hidden" name="option" value="com_mailjet"/>
        <input type="hidden" name="mailjet-list_id" value="<?php echo $module->list_id; ?>"/>
        <input type="hidden" name="view" value="mailjet"/>
        <input type="hidden" name="task" value="save"/>
        <input type="hidden" name="format" value="json"/>
        <?php echo HTMLHelper::_('form.token'); ?>

    </form>
    <div class="mailjet-success"
         style="display:none"><?php echo($module->success ? $module->success : JText::_('COM_MAILJET_SUBSCRIBE_SUCCESS')) ?></div>
    <div class="mailjet-duplicate"
         style="display:none"><?php echo($module->duplicate ? $module->duplicate : JText::_('COM_MAILJET_SUBSCRIBE_DUPLICATE')) ?></div>
    <div class="mailjet-error"
         style="display:none"><?php echo($module->error ? $module->error : JText::_('COM_MAILJET_SUBSCRIBE_ERROR')) ?></div>
</div>

<?php 
/**
 * @author Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access'); ?>

<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="main-card p-lg-3 p-md-3">
    <form action="<?php echo JRoute::_('index.php?option=com_mailjet&layout=edit') ?>" method="post" id="adminForm" name="adminForm">
        <div id="editcell">
            <fieldset class="options-form">
                <legend><?php echo Text::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_TITLE'); ?></legend>
                <ol>
                    <li>
                        <?php echo Text::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_CREATE_ACCOUNT'); ?>
                    </li>
                    <li>
                        <?php echo Text::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_CREATE_LIST'); ?>
                    </li>
                    <li>
                        <?php echo Text::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_CREATE_WIDGET'); ?>
                    </li>
                    <li>
                        <?php echo Text::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_CREATE_CAMPAIGN'); ?>
                    </li>
                </ol>

            </fieldset>
            <fieldset class="options-form">
                <legend><?php echo Text::_ ('COM_MAILJET_GENERAL_SETTINGS'); ?></legend>
                <p><?php echo Text::_ ('COM_MAILJET_MAILJET_SETTINGS_API_KEYS_HELP'); ?></p>
                <div class="control-group">
                    <div class="control-label"><label for="enable"><?php echo Text::_ ('COM_MAILJET_GENERAL_SETTINGS_ENABLED'); ?></label></div>
                    <div class="controls">
                        <input type="checkbox" name="enable" id="enable" <?php if ($this->params ['enable']) echo 'checked="checked"'; ?> />
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label for="test"><?php echo Text::_ ('COM_MAILJET_GENERAL_SETTINGS_SEND_TEST'); ?></label>
                    </div>
                    <div class="controls">
                        <input type="checkbox" name="test" id="test" <?php if ($this->params ['test']) echo 'checked="checked"'; ?> />
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label for="test_address"><?php echo Text::_ ('COM_MAILJET_GENERAL_SETTINGS_TEST_RECIPIENT'); ?></label>
                    </div>
                    <div class="controls">
                        <input type="text" name="test_address" id="test_address" class="form-control" value="<?php echo $this->params ['test_address']; ?>" />
                    </div>
                </div>
            </fieldset>

            <fieldset class="options-form">
                <legend><?php echo Text::_ ('COM_MAILJET_MAILJET_SETTINGS'); ?></legend>
                <div class="control-group">
                    <div class="control-label">
                        <label for="username"><?php echo Text::_ ('COM_MAILJET_MAILJET_SETTINGS_API_KEY'); ?></label>
                    </div>
                    <div class="controls">
                        <input type="text" name="username" id="username" class="form-control" value="<?php echo $this->params['username']; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label for="password"><?php echo Text::_ ('COM_MAILJET_MAILJET_SETTINGS_SECRET_KEY'); ?></label>
                    </div>
                    <div class="controls">
                        <input type="text" name="password" id="password" class="form-control" value="<?php echo $this->params['password']; ?>" />
                    </div>
                </div>
            </fieldset>
        </div>
        <?php echo JHTML::_( 'form.token' ); ?>
        <input type="hidden" name="option" value="com_mailjet" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="controller" value="mailjetedit" />
    </form>
</div>
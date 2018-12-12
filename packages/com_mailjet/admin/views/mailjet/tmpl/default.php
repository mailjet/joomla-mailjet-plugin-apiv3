<?php 
/**
 * @author Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
 defined('_JEXEC') or die('Restricted access'); ?>

<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10 iframe">
    <form action="<?php echo JRoute::_('index.php?option=com_mailjet&layout=edit') ?>" method="post" id="adminForm" name="adminForm">
        <div class="social" style="width:25%;float:right;border: 1px #CCC solid; border-radius: 6px; padding:8px">
            <h3><?php echo JText::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_SHARE'); ?></h3>
            <div style="margin-bottom:10px">
                <?php echo JText::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_FACEBOOK_LINK'); ?>
            </div>
            <div>
                <?php echo JText::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_TWITTER_LINK'); ?>
            </div>
        </div>
        <div id="editcell"style="width:70%">
            <fieldset class="adminform">
                <legend><?php echo JText::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_TITLE'); ?></legend>

                <ol>
                    <li>
                        <?php echo JText::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_CREATE_ACCOUNT'); ?>
                    </li>
                    <li>
                        <?php echo JText::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_CREATE_LIST'); ?>
                    </li>
                    <li>
                        <?php echo JText::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_CREATE_WIDGET'); ?>
                    </li>
                    <li>
                        <?php echo JText::_ ('COM_MAILJET_PLUGIN_INSTRUCTIONS_CREATE_CAMPAIGN'); ?>
                    </li>
                </ol>

            </fieldset>
            <fieldset class="adminform">
                <legend><?php echo JText::_ ('COM_MAILJET_GENERAL_SETTINGS'); ?></legend>
                <p><?php echo JText::_ ('COM_MAILJET_MAILJET_SETTINGS_API_KEYS_HELP'); ?></p>

                <label for="enable"><?php echo JText::_ ('COM_MAILJET_GENERAL_SETTINGS_ENABLED'); ?></label> <input type="checkbox" name="enable" id="enable" <?php if ($this->params ['enable']) echo 'checked="checked"'; ?> />
                <label for="test"><?php echo JText::_ ('COM_MAILJET_GENERAL_SETTINGS_SEND_TEST'); ?></label> <input type="checkbox" name="test" id="test" <?php if ($this->params ['test']) echo 'checked="checked"'; ?> />
                <label for="test_address"><?php echo JText::_ ('COM_MAILJET_GENERAL_SETTINGS_TEST_RECIPIENT'); ?></label> <input type="text" name="test_address" id="test_address" value="<?php echo $this->params ['test_address']; ?>" style="width:220px;" />
            </fieldset>

            <fieldset class="adminform">
                <legend><?php echo JText::_ ('COM_MAILJET_MAILJET_SETTINGS'); ?></legend>

                <label for="username"><?php echo JText::_ ('COM_MAILJET_MAILJET_SETTINGS_API_KEY'); ?></label> <input type="text" name="username" id="username" value="<?php echo $this->params ['username']; ?>" style="width:220px;" />
                <label for="password"><?php echo JText::_ ('COM_MAILJET_MAILJET_SETTINGS_SECRET_KEY'); ?></label> <input type="text" name="password" id="password" value="<?php echo $this->params ['password']; ?>" style="width:220px;" />
            </fieldset>
        </div>
        <?php echo JHTML::_( 'form.token' ); ?>
        <input type="hidden" name="option" value="com_mailjet" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="controller" value="mailjetedit" />
    </form>
</div>
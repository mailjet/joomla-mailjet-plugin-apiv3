<?php
/**
 * @author     Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

$lib_dir = __DIR__ . '/../../../lib/';

require_once($lib_dir . 'lib/vendor/autoload.php');
require_once($lib_dir . 'config.php');
require_once($lib_dir . 'lib/Auth.php');
require_once($lib_dir . 'lib/mailjet-api-helper.php');

try {

    $auth          = new Auth();
    $mailjetIframe = Mailjet_Api_Helper::getMailjetIframe($auth->getApiKey(), $auth->getApiSecret());
    $mailjetIframe->setInitialPage(\MailjetIframe\MailjetIframe::PAGE_STATS);
    ?>

    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10" style="width:100%; height: 1300px;">
        <?php echo $mailjetIframe->getHtml(); ?>
    </div>
    <?php
}
catch (\MailjetIframe\MailjetException $e) {
    // Log the error for administrators
    \Joomla\CMS\Log\Log::add(
        'Mailjet Statistics Error: ' . $e->getMessage(),
        \Joomla\CMS\Log\Log::ERROR,
        'com_mailjet'
    );
    ?>
    <div id="j-main-container" class="span10" style="width:100%; padding: 20px;">
        <div class="alert alert-error alert-danger">
            <h3><?php echo \Joomla\CMS\Language\Text::_('COM_MAILJET_ERROR_TITLE'); ?></h3>
            <p><strong><?php echo \Joomla\CMS\Language\Text::_('COM_MAILJET_ERROR_MESSAGE'); ?></strong></p>
            <p><?php echo htmlspecialchars($e->getMessage()); ?></p>
            <hr>
            <p><?php echo \Joomla\CMS\Language\Text::_('COM_MAILJET_ERROR_HELP'); ?></p>
            <ul>
                <li><?php echo \Joomla\CMS\Language\Text::_('COM_MAILJET_ERROR_HELP_1'); ?></li>
                <li><?php echo \Joomla\CMS\Language\Text::_('COM_MAILJET_ERROR_HELP_2'); ?></li>
                <li><?php echo \Joomla\CMS\Language\Text::_('COM_MAILJET_ERROR_HELP_3'); ?></li>
            </ul>
            <p>
                <a href="index.php?option=com_mailjet&view=mailjet" class="btn btn-primary">
                    <?php echo \Joomla\CMS\Language\Text::_('COM_MAILJET_GO_TO_SETTINGS'); ?>
                </a>
            </p>
        </div>
    </div>
    <?php
}
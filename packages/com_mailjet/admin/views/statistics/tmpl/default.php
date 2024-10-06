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
    ?>
    <div id="j-main-container" class="span10" style="width:100%; height: 1300px;">
        <b>Error</b>
    </div>
    <?php
}
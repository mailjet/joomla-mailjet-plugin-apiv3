<?php
/**
 * @author     Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\Factory;

error_reporting(E_ALL & ~E_NOTICE);
// no direct access
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

if(!class_exists('JViewLegacy')) {
    class_alias('JView', 'JViewLegacy');
}

class MailjetViewMailjet extends JViewLegacy
{
    /**
     * @param $tpl
     *
     * @return void
     * @throws Exception
     * @since 4.0
     */
    public function display($tpl = null)
    {
        global $result;
        $document = Factory::getApplication()->getDocument();
        $document->setMimeEncoding('application/json');

        echo json_encode($result);
    }
}

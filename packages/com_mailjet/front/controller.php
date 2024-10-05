<?php
/**
 * @author Mailjet SAS
 *
 * @copyright  Copyright (C) 2014 Mailjet SAS.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
error_reporting(E_ALL & ~E_NOTICE);
// no direct access
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controller');

if (!class_exists('JControllerLegacy')) {
    class_alias('JController', 'JControllerLegacy');
}

class MailjetController extends JControllerLegacy
{

    public function save()
    {
        global $result;

        $model = $this->getModel();

        $result = $model->store();
        if ($result != false) {
            $this->display();
        } else {
            header('HTTP/1.0 404 Not Found');
        }
    }
}

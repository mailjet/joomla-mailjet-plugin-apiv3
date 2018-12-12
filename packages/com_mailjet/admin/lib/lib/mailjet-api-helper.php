<?php

/*
 * LICENSE BLOCK
 *
 * This program is free software. It comes without any warranty, to the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want To Public License, Version 2, as published by Sam Hocevar. See
 * http://sam.zoy.org/wtfpl/COPYING for more details.
 *
 */

defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).'/vendor/autoload.php');


 class Mailjet_Api_Helper
 {
     private $username;
     private $password;

     public function __construct($username, $password)
     {
         $this->username = $username;
         $this->password = $password;
     }

     /**
      * Static method to call Mailjet API client
      * @param $username
      * @param $password
      * @return \Mailjet\Client
      */
      public static function getMailjetClient($username, $password)
      {
          $mjClient = new \Mailjet\Client($username, $password);
          $mjClient->addRequestOption(CURLOPT_USERAGENT, 'joomla-3.0');
          $mjClient->setSecureProtocol(false);

          return $mjClient;
      }


      public static function getMailjetIframe($username, $password)
      {
          $mailjetIframe = new \MailjetIframe\MailjetIframe($username, $password, false);

          // Get the current Joomla language tag (code like 'en-GB')
          $lang = JFactory::getLanguage();

          $mailjetIframe
              ->setCallback('')
              ->setTokenExpiration(600)
              ->setLocale(str_ireplace('-', '_', $lang->getTag()))
              ->setTokenAccess(array(
                  'campaigns',
                  'contacts',
                  'stats',
              ))
              ->turnDocumentationProperties(\MailjetIframe\MailjetIframe::OFF)
              ->turnNewContactListCreation(\MailjetIframe\MailjetIframe::ON)
              ->turnMenu(\MailjetIframe\MailjetIframe::OFF)
              ->turnFooter(\MailjetIframe\MailjetIframe::ON)
              ->turnBar(\MailjetIframe\MailjetIframe::ON)
              ->turnCreateCampaignButton(\MailjetIframe\MailjetIframe::ON)
              ->turnSendingPolicy(\MailjetIframe\MailjetIframe::ON);

          return $mailjetIframe;
      }
 }

<?php
/**
 *
 * CART2QUOTE CONFIDENTIAL
 * __________________
 *
 *  [2009] - [2016] Cart2Quote B.V.
 *  All Rights Reserved.
 *
 * NOTICE OF LICENSE
 *
 * All information contained herein is, and remains
 * the property of Cart2Quote B.V. and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to Cart2Quote B.V.
 * and its suppliers and may be covered by European and Foreign Patents,
 * patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Cart2Quote B.V.
 *
 * @category    Ophirah
 * @package     Crmaddon
 * @copyright   Copyright (c) 2016 Cart2Quote B.V. (https://www.cart2quote.com)
 * @license     https://www.cart2quote.com/ordering-licenses(https://www.cart2quote.com)
 */

/**
 * Class Ophirah_Crmaddon_MessageController
 */
class Ophirah_Crmaddon_MessageController extends Mage_Core_Controller_Front_Action
{
    CONST XML_PATH_CRMADDON_EMAIL_TEMPLATE = 'qquoteadv_sales_representatives_messaging_crmaddon_container_admin';
    CONST CHECKBOX_ENABLED = "on";

    /**
     * Send action to send messages from the CRM addon to the admin
     */
    public function sendAction(){
        $quote_id = $this->getRequest()->getParam('crm_id');
        $quote = Mage::getModel('qquoteadv/qqadvcustomer')->load($quote_id);

        // Check for a valid Enterprise License
        if (!Mage::helper('qquoteadv/license')->validLicense('messaging', $quote->getCreateHashArray())) {
            $this->_redirectUrl($this->_getRefererUrl());
            return;
        }

        $customer =  Mage::getSingleton('customer/session')->getCustomer();
        $customerId = $customer->getId();
        $adminUserId = $quote->getUserId();

        if($quote->getCustomerId() == $customerId){
            //customer is allowed
            //add message
            $crmData = $this->getCrmdata();
            Mage::dispatchEvent('ophirah_crmaddon_send_before', array('crm_data' => $crmData));
            $saveData = $this->prepareSaveData($crmData);
            if(!isset($saveData['subject']) || empty($saveData['subject'])){
                $saveData['subject'] = $this->__('Quotation #%s - Proposal', $quote->getIncrementId());
            }
            $saveData['customer_id']    = (int)$customerId;
            $saveData['user_id']        = (int)$adminUserId;
            $saveData['email_address']  = Mage::getModel('admin/user')->load($adminUserId)->getEmail();

            try{
                $crmaddonmessages = Mage::getModel('crmaddon/crmaddonmessages')->setData($saveData)->save();
                $sendMail = $this->sendEmail($crmData);

                if (empty($sendMail)) {
                    //$message = $this->__("CRM message couldn't be sent");
                    //Mage::getSingleton('adminhtml/session')->addError($message);
                } elseif (is_string($sendMail) && $sendMail == Ophirah_Crmaddon_Model_System_Config_Source_Email_Templatedisable::VALUE_DISABLED_EMAIL) {
                    //Mage::getSingleton('adminhtml/session')->addNotice($this->__('Sending CRM Email is disabled'));
                } else {
                    //Mage::getSingleton('adminhtml/session')->addSuccess($this->__('CRM Email was sent'));
                }

                Mage::getSingleton('core/session')->addSuccess(Mage::helper('crmaddon')->__('Message sent'));
                Mage::dispatchEvent('ophirah_crmaddon_send_after_save', array('crm_addon_messages_model' => $crmaddonmessages));
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('core/session')->addError(Mage::helper('crmaddon')->__('Could not send message'));
                Mage::log('Exception: CRMAddon: ' .$e->getMessage(), null, 'c2q_exception.log', true);
            }
        } else {
            Mage::getSingleton('core/session')->addError(Mage::helper('crmaddon')->__('Could not send message'));
        }

        //return to last page
        $this->_redirectUrl($this->_getRefererUrl());
        Mage::dispatchEvent('ophirah_crmaddon_send_after', array('crm_data' => $crmData));
    }

    /**
     *  Select CRM_addon data only
     *  from the Form Post data
     *
     * @return array
     */
    public function getCrmdata()
    {
        $return['createHash'] = null;
        foreach ($this->getRequest()->getPost() as $key => $value) {
            if (substr($key, 0, 4) == "crm_" || $key == 'createHash') {
                $return[$key] = $value;
            }

            if ($key == 'crm_notifyCustomer' && $value == self::CHECKBOX_ENABLED) {
                $return[$key] = 1;
            }
        }

        return $return;
    }

    /**
     * Prepare data from Form to save to the database
     *
     * @param $crmData
     * @return array
     */
    public function prepareSaveData($crmData)
    {

        $returnData = array();
        $translateArray = array('quote_id' => 'crm_id',
            'email_address' => 'crm_customerEmail',
            'subject' => 'crm_subject',
            'template_id' => 'crm_message_template',
            'message' => 'crm_message',
            'customer_notified' => 'crm_notifyCustomer'
        );

        foreach ($translateArray as $key => $value) {
            if (isset($crmData[$value])){
                $crmData[$value] = trim($crmData[$value]);
                if ($key == 'message') {
                    $crmData[$value] = htmlentities($crmData[$value], ENT_QUOTES, "UTF-8");
                }
                $returnData[$key] = $crmData[$value];
            }
        }

        $returnData['created_at']           = now();
        $returnData['updated_at']           = now();
        $returnData['template_id']          = null;
        $returnData['send_from_frontend']   = 1;

        return $returnData;
    }

    /**
     * Send email to client to informing about the quote proposition
     * @param   Array ()     // $params customer address
     * @return mixed
     */
    public function sendEmail($crmData)
    {
        //Create an array of variables to assign to template
        $vars = array();
        $storeId = $crmData['crm_storeId'];

        // Setting vars
        $vars['crmaddonBody'] = $crmData['crm_message'];
        $vars['message'] = $crmData['crm_message'];
        $vars['store'] = Mage::app()->getStore($storeId);

        // Prepare data for saving to database
        $saveData = $this->prepareSaveData($crmData);

        // Check if customer needs to be notified
        $template = Mage::helper('crmaddon')->getEmailTemplateModel($storeId);

        $disabledEmail = Ophirah_Crmaddon_Model_System_Config_Source_Email_Templatedisable::VALUE_DISABLED_EMAIL;

        //check for disabled template for admin
        $default_admin_template = Mage::getStoreConfig('qquoteadv_sales_representatives/messaging/crmaddon_container_admin', $storeId);
        if(($default_admin_template != $disabledEmail) && ($default_admin_template != false)) {
            $res = $this->sendEmailWithTemplate($crmData, $default_admin_template, $storeId, $template, $vars, $saveData);
        }

        return $res;
    }

    /**
     * @param $crmData
     * @param $default_template
     * @param $storeId
     * @param $template
     * @param $vars
     * @param $saveData
     * @return mixed
     */
    public function sendEmailWithTemplate($crmData, $default_template, $storeId, $template, $vars, $saveData)
    {
        // getting vars
        $qquote = Mage::getModel('qquoteadv/qqadvcustomer')->load($saveData['quote_id']);
        $customer = Mage::getModel('customer/customer')->load($qquote->getCustomerId());

        if ($default_template) {
            $templateId = $default_template;
        } else {
            $templateId = self::XML_PATH_CRMADDON_EMAIL_TEMPLATE;
        }

        // get locale of quote sent so we can sent email in that language
        $storeLocale = Mage::getStoreConfig('general/locale/code', $storeId);

        if (is_numeric($templateId)) {
            $template->load($templateId);
        } else {
            $template->loadDefault($templateId, $storeLocale);
        }

        if (isset($crmData['crm_subject'])){
            $subject = $crmData['crm_subject'];
        } else {
            $subject = $template['template_subject'];
        }
        if(!isset($subject) || empty($subject)){
            $subject = $this->__('Quotation #%s - Proposal', $qquote->getIncrementId());
        }
        $vars['subject'] = $subject;

        $sender = Mage::getModel('qquoteadv/qqadvcustomer')->load($saveData['quote_id'])->getEmailSenderInfo();
		
		$trackid = $saveData['quote_id'];
		
        $template->setSenderName($sender['name']);
        $template->setSenderEmail($sender['email']);
        $template->setTemplateSubject($subject.'('.$trackid.')');
        $template->setDesignConfig(array('store' => $storeId));

        //get vars for template
        $admin = Mage::getModel('admin/user')->load($qquote->getUserId());
        $adminName = $admin->getFirstname() . ' ' . $admin->getLastname();
        $adminEmail = $admin->getEmail();
        $remark = Mage::getStoreConfig('qquoteadv_quote_configuration/proposal/qquoteadv_remark', $qquote->getStoreId());
        $sender = Mage::getModel('qquoteadv/qqadvcustomer')->load($qquote->getId())->getEmailSenderInfo();

        $adminBackendCode = Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName')->asArray();
        $url = $adminBackendCode."/qquoteadv/edit/id/".$qquote->getId().'/';
        $useKey = Mage::getModel('adminhtml/url')->useSecretKey(); //admin/security/use_form_key
        $baseUrl = Mage::getModel('adminhtml/url')->turnOffSecretKey()->getUrl();
        //$secretKey = Mage::getModel('adminhtml/url')->getSecretKey('qquoteadv', 'edit');
        if(!$useKey){
            $adminLink = $baseUrl.$url;
        } else {
            //$adminLink = $baseUrl.$url.'key/'.$secretKey.'/';
            $adminLink = $baseUrl.$adminBackendCode;
        }

        //set vars for template
        $varsExtra = array(
            'quote' => $qquote,
            'customer' => Mage::getModel('customer/customer')->load($qquote->getCustomerId()),
            'quoteId' => $qquote->getId(),
            'storeId' => $qquote->getStoreId(),
            'adminname' => $adminName,
            'adminphone' => $admin->getTelephone(),
            'remark' => $remark,
            'link' => Mage::getUrl("qquoteadv/view/view/", array('id' => $qquote->getId())),
            'adminlink' => $adminLink,
            'sender' => $sender,
            'CRMcustomername' => $customer->getName(),
            'CRMsendername' => $sender['name']
        );

        $vars = array_merge($vars, $varsExtra);

        /**
         * Opens the qquote_request.html, throws in the variable array
         * and returns the 'parsed' content that you can use as body of email
         */
        //$template->getProcessedTemplate($vars);

        /*
         * getProcessedTemplate is called inside send()
         */
        Mage::dispatchEvent('ophirah_crmaddon_addSendMail_before', array('template' => $template));
        if(isset($adminEmail) && !empty($adminEmail)){
            $res = $template->send($adminEmail, $adminName, $vars);
        }

        Mage::dispatchEvent('ophirah_crmaddon_addSendMail_after', array('template' => $template, 'result' => $res));
        return $res;
    }
	
	
	public function getEmailReplyAction()
    {
		/* connect to gmail */
	
	
		$emailhostinbox = Mage::getStoreConfig('qquoteadv_quote_emails/sales_representatives/sender_email_hostnamei');
		$emailhostsent = Mage::getStoreConfig('qquoteadv_quote_emails/sales_representatives/sender_email_hostnames');
		$emailuser = Mage::getStoreConfig('qquoteadv_quote_emails/sales_representatives/sender_email_username');
		$emailpass = Mage::getStoreConfig('qquoteadv_quote_emails/sales_representatives/sender_email_password');
		
		
	    //$emailhost.'INBOX';
		$hostname = $emailhostinbox;
		$username = $emailuser;
		$password = $emailpass;

		/* $hostname = '{imap.exmail.qq.com:993/imap/ssl}INBOX';
		$username = 'sales@supplyguitar.com';
		$password = 'GoodAALuck@@D@bA3';  */
		
		/* $hostname = '{imap.exmail.qq.com:993/imap/ssl}Sent Messages';
		$username = 'sales@supplyguitar.com';
		$password = 'GoodAALuck@@D@bA3';  */
	
		/* try to connect */
		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

		/* grab emails */
		$emails = imap_search($inbox,'ALL');

		/* print_r($emails);
		exit; */
		/* if emails are returned, cycle through each... */
		if($emails) {
			
			/* begin output var */
			$output = '';
			$subject= '';
			/* put the newest emails on top */
						
			/* for every email... */
			foreach($emails as $email_number) {
				
				$messageData = array();
				
				/* get information specific to this email */
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$body = imap_fetchbody($inbox,$email_number,1);
				//echo "<br><br><br> ==>".$message;
				
				echo '<pre>';
				echo $overview[0]->date;
				
				
				//print_r($body);
				
				$body_array = explode("\n",$body);
				$message = "";
				foreach($body_array as $key => $value){
					
					//remove hotmail sig
					if($value == "_________________________________________________________________"){
						break;
					
					//original message quote
					} elseif(preg_match("/^-*(.*)Original Message(.*)-*/i",$value,$matches)){
						break;
					
					//check for date wrote string
					} elseif(preg_match("/^On(.*)wrote:(.*)/i",$value,$matches)) {
						break;
					
					//check for From Name email section
					} elseif(preg_match("/^On(.*)$fromName(.*)/i",$value,$matches)) {
						break;
					
					//check for To Name email section
					} elseif(preg_match("/^On(.*)$toName(.*)/i",$value,$matches)) {
						break;
					
					//check for To Email email section
					} elseif(preg_match("/^(.*)$toEmail(.*)wrote:(.*)/i",$value,$matches)) {
						break;
						
					//check for From Email email section
					} elseif(preg_match("/^(.*)$fromEmail(.*)wrote:(.*)/i",$value,$matches)) {
						break;
						
					//check for quoted ">" section
					} elseif(preg_match("/^>(.*)/i",$value,$matches)){
						break;
					
					//check for date wrote string with dashes
					} elseif(preg_match("/^---(.*)On(.*)wrote:(.*)/i",$value,$matches)){
						break;
							
					//add line to body
					} else {
						$message .= "$value\n";
					}
							
				}

				$subject = $overview[0]->subject;
				$mdate = $overview[0]->date;
				$st_po = strpos($subject,"(");
				$end_po = strpos($subject,")");
				$messageData = explode(',',substr($subject,($st_po+1),($end_po-($st_po+1))));
				$output.= ($overview[0]->seen ? 'read' : 'unread');
				
				
				echo $subject;
				
				$qquote = Mage::getModel('qquoteadv/qqadvcustomer')->load($messageData[0]);
				
				$qquotemessage = Mage::getModel('crmaddon/crmaddonmessages')->getCollection()
				->addFieldToFilter('quote_id',$messageData[0])
				->addFieldToFilter('messages_date',$mdate)
				->addFieldToFilter('send_from_frontend',1);
				
				echo '<pre>';
			//	print_r($qquotemessage->getData());
				
				echo $message;
				
				
				if(count($qquotemessage) == 0)
				{	
					$saveData = array('quote_id' => $messageData[0],
						'created_at' => now(),
						'updated_at' => now(),
						'email_address' => $qquote->getEmail(),
						'subject' => $subject,
						'template_id' => null,
						'message' => $message,
						'user_id' => (int)$qquote->getUserId(),
						'customer_id' => (int)$qquote->getCustomerId(),
						'send_from_frontend' => 1,
						'messages_date' => $mdate,
					);
					
					try {
						
						$crmaddonmessages = Mage::getModel('crmaddon/crmaddonmessages')->setData($saveData)->save();
					} catch (Exception $e) {
					   
					}
				}
			
			}
		} 


		/* close the connection */
		imap_close($inbox);
	   
	}  
	   
	   
	public function getEmailAdminReplyAction()
    {
		/* connect to gmail */
		
		$emailhostinbox = Mage::getStoreConfig('qquoteadv_quote_emails/sales_representatives/sender_email_hostnamei');
		$emailhostsent = Mage::getStoreConfig('qquoteadv_quote_emails/sales_representatives/sender_email_hostnames');
		$emailuser = Mage::getStoreConfig('qquoteadv_quote_emails/sales_representatives/sender_email_username');
		$emailpass = Mage::getStoreConfig('qquoteadv_quote_emails/sales_representatives/sender_email_password');
		
		
	    //$emailhost.'Sent';
		$hostname = $emailhostsent;
		$username = $emailuser;
		$password = $emailpass;

		/* try to connect */
		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

		/* grab emails */
		$emails = imap_search($inbox,'ALL');

		
		/* echo '<pre>';
		print_r($emails); */
				
				
		/* if emails are returned, cycle through each... */
		if($emails) {
			
			/* begin output var */
			$output = '';
			$subject= '';
			/* put the newest emails on top */
			rsort($emails);
			
			/* for every email... */
			foreach($emails as $email_number) {
				
				$messageData = array();
				
				/* get information specific to this email */
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$body = imap_fetchbody($inbox,$email_number,1);
				//echo "<br><br><br> ==>".$message;
				
				echo '<pre>';
				echo $overview[0]->date;
				
				
				$body_array = explode("\n",$body);
				$message = "";
				foreach($body_array as $key => $value){
					
					//remove hotmail sig
					if($value == "_________________________________________________________________"){
						break;
					
					//original message quote
					} elseif(preg_match("/^-*(.*)Original Message(.*)-*/i",$value,$matches)){
						break;
					
					//check for date wrote string
					} elseif(preg_match("/^On(.*)wrote:(.*)/i",$value,$matches)) {
						break;
					
					//check for From Name email section
					} elseif(preg_match("/^On(.*)$fromName(.*)/i",$value,$matches)) {
						break;
					
					//check for To Name email section
					} elseif(preg_match("/^On(.*)$toName(.*)/i",$value,$matches)) {
						break;
					
					//check for To Email email section
					} elseif(preg_match("/^(.*)$toEmail(.*)wrote:(.*)/i",$value,$matches)) {
						break;
						
					//check for From Email email section
					} elseif(preg_match("/^(.*)$fromEmail(.*)wrote:(.*)/i",$value,$matches)) {
						break;
						
					//check for quoted ">" section
					} elseif(preg_match("/^>(.*)/i",$value,$matches)){
						break;
					
					//check for date wrote string with dashes
					} elseif(preg_match("/^---(.*)On(.*)wrote:(.*)/i",$value,$matches)){
						break;
							
					//add line to body
					} else {
						$message .= "$value\n";
					}
							
				}

				$subject = $overview[0]->subject;
				$mdate = $overview[0]->date;
				$st_po = strpos($subject,"(");
				$end_po = strpos($subject,")");
				$messageData = explode(',',substr($subject,($st_po+1),($end_po-($st_po+1))));
				$output.= ($overview[0]->seen ? 'read' : 'unread');
				
				$qquote = Mage::getModel('qquoteadv/qqadvcustomer')->load($messageData[0]);
				
				$qquotemessage = Mage::getModel('crmaddon/crmaddonmessages')->getCollection()
				->addFieldToFilter('quote_id',$messageData[0])
				->addFieldToFilter('messages_date',$mdate)
				->addFieldToFilter('send_from_frontend',0);
				
				
				echo $subject;
				echo '<pre>';
				echo $message;
				
				//print_r($qquotemessage->getData());
				
				if(count($qquotemessage) == 0)
				{	
					$saveData = array('quote_id' => $messageData[0],
						'created_at' => now(),
						'updated_at' => now(),
						'email_address' => $qquote->getEmail(),
						'subject' => $subject,
						'template_id' => null,
						'message' => $message,
						'user_id' => (int)$qquote->getUserId(),
						'customer_id' => (int)$qquote->getCustomerId(),
						'send_from_frontend' => 0,
						'messages_date' => $mdate,
					);
					
					try {
						
						$crmaddonmessages = Mage::getModel('crmaddon/crmaddonmessages')->setData($saveData)->save();
					} catch (Exception $e) {
					   
					}
				}
			}
		} 
		
		/* close the connection */
		imap_close($inbox);
	}     
}

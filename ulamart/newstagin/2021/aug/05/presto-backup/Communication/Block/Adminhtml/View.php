<?php
/**
 * Askan Technology
 *
 * @category  AskanTech
 * @package   Askantech_Communication
 * @author    Askan
 * @copyright Copyright (c) AskanTech (https://www.askantech.com/)
 * @license   https://www.askantech.com/Communication/LICENSE.txt
 */
namespace Askantech\Communication\Block\Adminhtml;

use Magento\Backend\Block\Template;

class View extends Template
{
   protected $timezone;
   public $_template = 'Askantech_Communication::view.phtml';

     public function __construct(
       \Magento\Backend\Block\Template\Context $context,
       \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
   ) {
       $this->timezone = $timezone;
       parent::__construct($context);
   }
   public function getModificationDetails()
   {
       $id = $this->getActionsId();
       $querry = $this->getquerry();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseurl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $key_form = $objectManager->get('Magento\Framework\Data\Form\FormKey');
        $connection = $resource->getConnection();
        $chat = $resource->getTableName('askantech_communication_chatdata');
        $chatdata = $resource->getTableName('askantech_communication_data');
        $sql = "Select * FROM " . $chat. " where querry_id ='".$querry."' ORDER BY created_at ASC";
        $result = $connection->fetchAll($sql);
        $sqll = "Select * FROM " . $chatdata. " where querry_id ='".$querry."'";
        $resultt = $connection->fetchAll($sqll);
        $grid = '';
        $from_key = $key_form->getFormKey();


        foreach ($resultt as $items) { 
        $customerObj = $objectManager->create('Magento\Customer\Model\Customer')
            ->load($items['cus_id']);        
        $billingAddressId = $customerObj->getDefaultBilling();
        $address = $objectManager->get('Magento\Customer\Model\AddressFactory')->create()->load($billingAddressId);
        $telephone= $address->getData('telephone');

            $grid .= "<div class='parent-left-right' style='display: block;height: auto;'>";
            $grid .= "<div class='child-left' style='font-size: 20px;float: left;line-height:33px;'><b>Subject: </b> ".$items['subject']."<br>
            <b>Product: </b> ".$items['Product_name']."</div>";
            $grid .= "<div class='child-right' style='font-size: 20px;line-height:32px;'><b>Customer Email: </b> ".$items['contact_email']."<br>
            <b>Customer Mobile Number: </b> ".$telephone."</div>";
            $grid .= '</div>';
        }
        $grid .='<div class="scroll-message"</div>';
        foreach ($result as $item) { 
            $current = $item['created_at'];
            $formatDate1 = $this->timezone->date($current)->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
            $date_formate1 = date('M j Y g:i A', strtotime($formatDate1));
            $grid .= "<div style='background-color: #dbeaf173;box-shadow: 2px 1px 7px #f1e3e3;border-radius: 5px;padding: 6px 15px 0;padding-bottom: 30px;margin: 10px 0;'>
            <div style='display:block;font-size: 18px;'><b>From ".$item['chat_from']." ".$item['chat_from_name']." </b></div><div>".$item['content']."</div></br>
            <div style='float:right'>".$date_formate1."</div></div>";
        }
        $grid .='</div>';
        $grid .='<style>.scroll-message {max-height: 300px;overflow: auto;}.parent-left-right:after {content: "";display: table;clear: both;}.child-right {;padding-right: 24px;text-align: right;}.child-left {width: 65%;}</style>';
       return $grid;
   }
   public function getActionsId()
   {
       return $this->getRequest()->getParam('id');
   }
   public function getquerry()
   {
       return $this->getRequest()->getParam('querry');
   }
   public function getNewFunctionUrl(){

    return $this->getUrl('askantech_communication/index/index');

   }
}

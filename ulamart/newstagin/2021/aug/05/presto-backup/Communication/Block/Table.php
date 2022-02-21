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
namespace Askantech\Communication\Block;

class Table extends \Magento\Framework\View\Element\Template
{
    protected $timezone;
    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    ) {
        $this->timezone = $timezone;
        parent::__construct($context);
    }

    /**
     * Get form action URL for POST booking request
     *
     * @return string
     */
    public function chatdata()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $storeID = $storeManager->getStore()->getStoreId();
        $storeName = $storeManager->getStore()->getName();
        $baseurl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            $cus_id = $customerSession->getCustomer()->getId(); // get ID
        }
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $query_table = $resource->getTableName('askantech_communication_chatdata');

        $chatdata = $resource->getTableName('askantech_communication_data');
        $sqll = "Select * FROM " . $chatdata . " where cus_id ='" . $cus_id . "' AND status != 'deleted' ORDER BY created_at DESC;";
        $items = $connection->fetchAll($sqll);

        if ($items) {
            $grid = "<table class='chathistory paginated'>";
            $grid .= "<tr><th>Subject</th><th>Product Name</th><th>Status</th><th>Created at</th><th>Action</th></tr>";
            foreach ($items as $item) {
                $current =$item['created_at'];
                $formatDate1 = $this->timezone->date($current)->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
                $date_formate1 = date('M j Y g:i A', strtotime($formatDate1));
                $sql_qu = "Select * FROM " . $query_table . " where querry_id ='" . $item['querry_id'] . "' ORDER BY created_at ASC;";

                $results = $connection->fetchAll($sql_qu);
                $chat = '<div class="chat-content"><span class="close-chat">&times;</span>';
                $chat .= "<div class='parent-left-right' style='display: block;height:auto;'>";
                $chat .= "<div class='child-left' style='font-size: 20px;float: left;line-height:35px;'><b>Subject: </b> " . $item['subject'] . "<br>
                <b>Product: </b> " . $item['Product_name'] . "</div>";
                $chat .= "<div  class='child-right' style='font-size: 20px;float:right;line-height:35px;'><b>Status: </b> " . $item['status'] . "</div>";
                $chat .="<div id='submit_status'></div><div id='submit_status_error'></div>";
                $chat .= "</div><div class='message-content-only scroll'>";
                foreach ($results as $result) {
                    $currenttime = $result['created_at'];
                    $formatDate2 = $this->timezone->date($currenttime)->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
                    $date_formate2 = date('M j Y g:i A', strtotime($formatDate2));
                    if ($result['chat_from'] == 'customer') {
                        $chat .= "<div style='background-color: #dbeaf173;box-shadow: 2px 1px 7px #f1e3e3;border-radius: 5px;padding: 6px 15px 0;padding-bottom: 30px;margin: 10px 0;'>
                        <div style='display:block;font-size: 18px;'><b>You</b></div><div>" . $result['content'] . "</div></br>
                        <div style=float:right;font-size:13px;>" .$date_formate2 . "</div></div>";
                    } else if ($result['chat_from'] == 'admin') {
                        $chat .= "<div style='background-color: #dbeaf173;box-shadow: 2px 1px 7px #f1e3e3;border-radius: 5px;padding: 6px 15px 0;padding-bottom: 30px;margin: 10px 0;'>
                        <div style='display:block;font-size: 18px;'><b>Support</b></div><div>" . $result['content'] . "</div></br>
                        <div style=float:right;font-size:13px;>" . $date_formate2 . "</div></div>";
                    }
                }
                $form_ky=$this->getFormKey();
                 //    action='" . $this->escapeUrl($this->getUrl('askantechcommunication/customer/form')) . "'
                $chat .= "</div><form id='customer_reply_form".$item['querry_id']."'  class='reply-form' action='" . $this->escapeUrl($this->getUrl('askantechcommunication/customer/form')) . "' method='post'>
                <input name='form_key' id='frm_key' type='hidden' value='" . $this->getFormKey() . "' />
                    <input type='hidden' id='querry_id" . $item['querry_id'] . "' name='querry_id' value='" . $item['querry_id'] . "'>
                    <input type='hidden' id='chatfrom" . $item['querry_id'] . "' name='chatfrom' value='customer'/>
                    <input type='hidden' id='chatfromname" . $item['querry_id'] . "' name='chatfromname' value='" . $item['contact_name'] . "'/>
                    <Textarea id='queries".$item['querry_id']."' name='queries' placeholder='Write your comment..' style='margin: 10px 0px; height: 130px; padding: 12px; border: 1px solid rgb(204, 204, 204); border-radius: 4px; resize: vertical; width: 50%; display: block;' required></Textarea>
                    <input type='submit' value='Send' style='width: 20%;padding: 12px;border: 1px solid #ccc;border-radius: 4px;resize: vertical;background: #373330;color: #ffffff;'/>
                </form>";
                $chat .= "</div>";
                $grid .= "<tr class='readstatus" . $item['customer_read_status'] . "' ><td class='subject-before-mobile'><a class='chat-action-sub'>" . $item['subject'] . "</a></td><td class='subject-before-desktop'>" . $item['subject'] . "</td><td class='productname-before-mobile'>" . $item['Product_name'] . "</td><td class='status-before-mobile'>" . $item['status'] . "</td><td class='date-before-mobile'>" . $date_formate1 . "</td><td class='action-btn'><button class='chat-action'>View</button><div class='chat-popup' style='display:none'>" . $chat . "</div></td></tr>";
                
            }
            $grid .= "</table>";
       
        } else {
            $grid = "<div>You havent contacted seller.</div>";
        }
        return $grid;
    }
}

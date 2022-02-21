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
namespace Askantech\Communication\Controller\Index;

use Askantech\Communication\Model\FormDataFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * Form action
     *
     * @return void
     */
    protected $_formData;
    protected $resultRedirect;
    public function __construct(\Magento\Framework\App\Action\Context $context,
        \Askantech\Communication\Model\FormDataFactory $formData,
    \Magento\Framework\Controller\ResultFactory $result){
        parent::__construct($context);
        $this->_formData = $formData;
        $this->resultRedirect = $result;
    }
    public function execute()
    {
        $post = (array) $this->getRequest()->getPost();

        if (!empty($post)) {
            $querryid = strtotime(date('Y-m-d H:i:s'));
            $name = $post['contactname'];
            $email = $post['contactemail'];
            $subject = $post['subject'];
            $productid = $post['Productid'];
            $productname = $post['Productname'];
            $customer_read_status = "1";
            // $timezone_offset_minutes=$post['cus_time'];
            // $timezone_name = '';
            // $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
            // if($timezone_name != ''){
            //     date_default_timezone_set($timezone_name);
            //     $customer_time = date("Y-m-d H:i:s");
            // }else{
            //     $customer_time = date("Y-m-d H:i:s");

            // }
            // echo $customer_time;
            // exit();
            $cusid = $post['cusid'];
            $status = 'pending';
            // $queries = $post['queries'];
            $queries = nl2br($post['queries']);
            $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            $model = $this->_formData->create();
            $model->addData([
                "querry_id" => $querryid,
                "contact_name" => $name,
                "contact_email" => $email,
                "subject" => $subject,
                "Product_id" => $productid,
                "Product_name" => $productname,
                "cus_id" => $cusid,
                "status" => $status,
                "queries" => $queries,
                "customer_read_status" => $customer_read_status,
                ]);
            $saveData = $model->save();
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $chatdata = $resource->getTableName('askantech_communication_chatdata');
            $sql = "Insert Into " . $chatdata . " (chat_from, chat_from_name, content, querry_id) Values ('customer','".$name."','".$queries."','".$querryid."')";
            $savechatData = $connection->query($sql);
            if($saveData && $savechatData){
                $this->messageManager->addSuccess( __('Thanks for reaching out we will contact you soon!') );
            }
            return $resultRedirect;
        }
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}

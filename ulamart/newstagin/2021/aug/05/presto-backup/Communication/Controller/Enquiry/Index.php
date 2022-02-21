<?php
namespace Askantech\Communication\Controller\Enquiry;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Index extends \Magento\Framework\App\Action\Action
{

     /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $resultJsonFactory; 

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory
        )
    {

        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory; 
        return parent::__construct($context);
    }


    public function execute()
    {
        // echo "enquiry controller";
        // exit();

        $Productid = $this->getRequest()->getParam('Productid');
        $Productname = $this->getRequest()->getParam('Productname');
		$cusid = $this->getRequest()->getParam('cusid');
		$contactname = $this->getRequest()->getParam('contactname');
		$contactemail = $this->getRequest()->getParam('contactemail');
		$subject = $this->getRequest()->getParam('subject');
        $queries = nl2br($this->getRequest()->getParam('queries'));
        $querryid = strtotime(date('Y-m-d H:i:s'));
		// $queries = $this->getRequest()->getParam('queries');
		$status = 'pending';
        $admin_read_status='0';
        $customer_read_status="1";
        $returnvalue['status'] = "0";

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        
        $queryraised = $resource->getTableName('askantech_communication_data');
        $querysql = "INSERT INTO " . $queryraised . "(querry_id, contact_name, contact_email, subject, Product_id, Product_name, cus_id, status, customer_read_status, admin_read_status) VALUES ('" . $querryid . "','" . $contactname . "','" . $contactemail . "','" . $subject . "','" . $Productid . "','" . $Productname . "','" . $cusid . "','" . $status . "','" . $customer_read_status . "','" . $admin_read_status . "')";
        $saveData = $connection->query($querysql);
        
        $chatdata = $resource->getTableName('askantech_communication_chatdata');
        $chatsql = "Insert Into " . $chatdata . " (chat_from, chat_from_name, content, querry_id) Values ('customer','" . $contactname . "','" . $queries . "','" . $querryid . "')";
        $savechatData = $connection->query($chatsql);
        if ($saveData && $savechatData) {
            $returnvalue['status'] = "1";
        }

        

		$resultJson = $this->resultJsonFactory->create();
        $resultJson->setData($returnvalue);
        return $resultJson;
        //echo json_encode($returnvalue);
    } 
}
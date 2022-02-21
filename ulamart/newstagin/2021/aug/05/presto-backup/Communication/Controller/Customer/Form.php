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
namespace Askantech\Communication\Controller\Customer;

class Form extends \Magento\Framework\App\Action\Action
{
    protected $resultRedirect;
    public function __construct(\Magento\Framework\App\Action\Context $context,
    \Magento\Framework\Controller\ResultFactory $result){
        parent::__construct($context);
        $this->resultRedirect = $result;
    }
	public function execute()
	{
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $chatdata = $resource->getTableName('askantech_communication_chatdata');
        $data = $resource->getTableName('askantech_communication_data');
        
        $post = (array) $this->getRequest()->getPost();
            if (!empty($post)) {
                $querryid = $post['querry_id'];
                $name = $post['chatfrom'];
                $fromname = $post['chatfromname'];
                // $querry = $post['queries'];
                $querry = nl2br($post['queries']);
                $content = str_replace("'", "\'", $querry);
                $status_check = "Select * FROM " . $data. " where querry_id ='".$querryid."'LIMIT 1;";
                $status = $connection->fetchAll($status_check);
                foreach ($status as $result) { 
                if($result['status']!='deleted'){
                    $sql = "Insert Into " . $chatdata . " (chat_from, chat_from_name, content, querry_id) Values ('".$name."','".$fromname."','".$content."','".$querryid."')";
                    $saveData = $connection->query($sql);

                    $sqll_read = "UPDATE " . $data . " SET admin_read_status = '0'  WHERE querry_id = '" . $querryid . "'";
                    $saveData = $connection->query($sqll_read);
                    $sqll_read_customer = "UPDATE " . $data . " SET customer_read_status = '1'  WHERE querry_id = '" . $querryid . "'";
                    $saveData = $connection->query($sqll_read_customer);

                    if($saveData){
                        $this->messageManager->addSuccess( __('Message Sent to Support Team. Will reach out to you soon.') );
                    } else {
                        $this->messageManager->addError( __('Some Problem accured try again later') );
                    }
                }else{
                    $this->messageManager->addError(__('Your Conversation has been Deleted. Please Contact Support Team'));

                }
            }
            }
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('askantechcommunication/customer/index');
            return $resultRedirect;
	}
}
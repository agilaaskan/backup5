<?php
namespace Askantech\Communication\Controller\Enquiry;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Creply extends \Magento\Framework\App\Action\Action
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
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $chatdata = $resource->getTableName('askantech_communication_chatdata');
        $data = $resource->getTableName('askantech_communication_data');

        $querryid = $this->getRequest()->getParam('querry_id');
        $name = $this->getRequest()->getParam('chatfrom');
		$fromname = $this->getRequest()->getParam('chatfromname');
		$querry = nl2br($this->getRequest()->getParam('queries'));

        // $querryid = $post['querry_id'];
        // $name = $post['chatfrom'];
        // $fromname = $post['chatfromname'];
        // // $querry = $post['queries'];
        // $querry = nl2br($post['queries']);

        $content = str_replace("'", "\'", $querry);
        $status_check = "Select * FROM " . $data. " where querry_id ='".$querryid."'LIMIT 1;";
        $status = $connection->fetchAll($status_check);
        $returnvalue['status'] = "0";
        foreach ($status as $result) { 
        if($result['status']!='deleted'){
            $sql = "Insert Into " . $chatdata . " (chat_from, chat_from_name, content, querry_id) Values ('".$name."','".$fromname."','".$content."','".$querryid."')";
            $saveData = $connection->query($sql);

            $sqll_read = "UPDATE " . $data . " SET admin_read_status = '0', customer_read_status = '1' WHERE querry_id = '" . $querryid . "'";
            $saveData2 = $connection->query($sqll_read);
            // $sqll_read_customer = "UPDATE " . $data . " SET customer_read_status = '1'  WHERE querry_id = '" . $querryid . "'";
            // $saveData = $connection->query($sqll_read_customer);

            if($saveData && $saveData2){
                $returnvalue['status'] = "1";

            }else {
                $returnvalue['status'] = "0";
            }
        }else{
            $returnvalue['status'] = "2";
        }
    }

        // $returnvalue['status'] = "1";

		$resultJson = $this->resultJsonFactory->create();
        $resultJson->setData($returnvalue);
        return $resultJson;
        //echo json_encode($returnvalue);
    } 
}
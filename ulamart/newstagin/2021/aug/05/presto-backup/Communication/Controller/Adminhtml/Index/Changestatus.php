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
namespace Askantech\Communication\Controller\Adminhtml\Index;

class Changestatus extends \Magento\Backend\App\Action
{
    protected $resultRedirect;
    public function __construct(\Magento\Backend\App\Action\Context $context,
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
        $chat = $resource->getTableName('askantech_communication_data');
        
        $post = (array) $this->getRequest()->getPost();
            if (!empty($post)) {
                $querryid = $post['value'];
  
                
                //Update Data into table
                $sqll = "UPDATE ".$chat." SET read_status = '1'  WHERE querry_id = '".$querryid."'";
                $saveData = $connection->query($sqll);
                if($saveData){
                    // $this->messageManager->addSuccess( __('Message Updated.') );
                } else {
                    // $this->messageManager->addSuccess( __('Some Problem accured try again later') );
                }
                // return $this->getUrl('askantech_communication/post/index');
            }
            $resultRedirect = $this->resultRedirectFactory->create();
            
            return $resultRedirect;
	}
}
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
namespace Askantech\Communication\Controller\Adminhtml\Grid;

class Delete extends \Magento\Backend\App\Action
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
        $chat = $resource->getTableName('askantech_communication_data');
        
        $post = (array) $this->getRequest()->getPost();
            if (!empty($post)) {
                $querryid = $post['id'];
                $sql = "UPDATE ".$chat." SET status = 'deleted', admin_read_status='1' WHERE id = '".$querryid."'";
                $saveData = $connection->query($sql);
                if($saveData){
                    $this->messageManager->addSuccess( __('Deleted Succesfully.') );
                } else {
                    $this->messageManager->addSuccess( __('Some Problem accured try again later') );
                }
                // return $this->getUrl('askantech_communication/post/index');
            }
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('askantech_communication/post/index');
            return $resultRedirect;
	}
}
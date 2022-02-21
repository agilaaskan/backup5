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
namespace Askantech\Communication\Controller\Adminhtml\Reply;

class Reply extends \Magento\Backend\App\Action
{
    // echo "test";
    // exit();
    protected $resultRedirect;
    public function __construct(\Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $result) {
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
            $querryid = $post['querry_id'];
            $name = $post['chatfrom'];
            // $querry = $post['queries'];
            $querry = nl2br($post['queries']);
            $status = $post['status'];
            $content = str_replace("'", "\'", $querry);
            //Insert Data into table
            $sql = "Insert Into " . $chatdata . " (chat_from, content, querry_id) Values ('" . $name . "','" . $content . "','" . $querryid . "')";
            $connection->query($sql);

            //Update Data into table
            $sqll_read = "UPDATE " . $chat . " SET admin_read_status = '1'  WHERE querry_id = '" . $querryid . "'";
            $saveData = $connection->query($sqll_read);
            $sqll = "UPDATE " . $chat . " SET status = '" . $status . "' WHERE querry_id = '" . $querryid . "'";
            $saveData = $connection->query($sqll);
            if ($saveData) {
                $this->messageManager->addSuccess(__('Message Updated.'));

                $sqll_read = "UPDATE " . $chat . " SET customer_read_status = '0'  WHERE querry_id = '" . $querryid . "'";
                $saveData = $connection->query($sqll_read);
            } else {
                $this->messageManager->addSuccess(__('Some Problem accured try again later'));
            }
            // return $this->getUrl('askantech_communication/post/index');
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('askantech_communication/post/index');
        return $resultRedirect;
    }
}

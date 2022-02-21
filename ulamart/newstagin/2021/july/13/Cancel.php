<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Controller\Adminhtml\Order;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;

class Cancel extends \Magento\Sales\Controller\Adminhtml\Order implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::cancel';

    /**
     * Cancel order
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->isValidPostRequest()) {
            $this->messageManager->addErrorMessage(__('You have not canceled the item.'));
            return $resultRedirect->setPath('sales/*/');
        }
        $order = $this->_initOrder();
						
        if ($order) {
            try {
                $this->orderManagement->cancel($order->getEntityId());
				$this->update_custom_product_stock($order->getId());
                $this->messageManager->addSuccessMessage(__('You canceled the order.'));				
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('You have not canceled the item.'));
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
            }
            return $resultRedirect->setPath('sales/order/view', ['order_id' => $order->getId()]);
        }
        return $resultRedirect->setPath('sales/*/');
    }
	
	
	public function update_custom_product_stock($corder) {
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
		
		$sql_ord = "SELECT * FROM `sales_order_grid` WHERE entity_id = '$corder' AND status='canceled' LIMIT 1";
		//$sql_ord = "SELECT * FROM `sales_order_grid` WHERE entity_id = '$corder' LIMIT 1";
		$ord_result = $connection->fetchAll($sql_ord);		
		if(count($ord_result)> 0){
		
			//Select Data from magento
			$sql = "SELECT product_id,weight,qty_ordered,name FROM `sales_order_item` WHERE order_id = '$corder' AND product_type = 'simple'";
			$result = $connection->fetchAll($sql);
			foreach($result as $row){
				if(isset($row["weight"]) && $row["weight"]!=null){
					$cweight=$row["weight"];
				}else{
					$cweight=1;
				}
				
				$cid = $row["product_id"];
				$cqty = $row["qty_ordered"];
				$sql1 = "SELECT parent_id FROM `catalog_product_super_link` WHERE product_id = '$cid'";
				$result1 = $connection->fetchAll($sql1);
				if(count($result1)>0){
					foreach($result1 as $row1){
						$cparent = $row1["parent_id"];
					}							
					$ctotal = $cweight * $cqty;						
				}else{
					$cparent = $cid;						
					$ctotal = $cqty;						
				}
				
				$sql2 = "SELECT stock_in_hand FROM `ps_custom_stock_management` WHERE m2_product_id = '$cparent' LIMIT 1";					
				$result2 = $connection->fetchAll($sql2);				
				if(count($result2)> 0){					
					$psqty= $result2[0]['stock_in_hand'];						
					$newqty = $psqty + $ctotal;						
					$sql3 = "UPDATE  `ps_custom_stock_management` SET stock_in_hand = $newqty WHERE m2_product_id = '$cparent'";						
					$connection->query($sql3);  
				}
				
			}
			
		}
		
	}	
	
	
}

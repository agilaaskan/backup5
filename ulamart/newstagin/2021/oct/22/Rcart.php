<?php
namespace Ashsmith\HelloWorld\Controller\Index;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Action;

class Rcart extends Action {
protected $formKey;   
protected $cart;
protected $productFactory;
protected $resultPageFactory;
protected $resultJsonFactory; 

    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\Data\Form\FormKey $formKey,
    \Magento\Checkout\Model\Cart $cart,
    \Magento\Framework\Controller\ResultFactory $resultFactory,
    \Magento\Catalog\Model\ProductFactory $productFactory,
    PageFactory $resultPageFactory,
    JsonFactory $resultJsonFactory,
    array $data = []) {
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->resultFactory = $resultFactory;
        $this->productFactory = $productFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;       
        parent::__construct($context);
    }

    public function execute()
    { 
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productId=$this->getRequest()->getParam('prd_id');
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
        $cart = $objectManager->create('\Magento\Checkout\Model\Cart');
        // $this->cart->removeItem($productId)->save();
        $returnvalue['status']="0";
        foreach ($cart->getQuote()->getAllItems() as $item) {
            /** @var \Magento\Quote\Model\Quote\Item $item */
            if ($item->getProductId() == $productId) {
                $item->delete();
                $this->cart->save();
                $returnvalue['status']="1";
            }
        }
               
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData($returnvalue);
            return $resultJson;
    }
}
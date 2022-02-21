<?php
namespace Ashsmith\HelloWorld\Controller\Index;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Action;

class Product extends Action {
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
        $TaxHelper = $objectManager->get('Magento\Catalog\Helper\Data');
        $productId=$this->getRequest()->getParam('pr_id');
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);

        $ptype = $product->getTypeId();
        $childpotion = array();
        $childid = array();
        $cprice = array();
        if($ptype =='configurable') {
        $productTypeInstance = $objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable');
        $productAttributeOptions = $productTypeInstance->getConfigurableAttributesAsArray($product);
        foreach ($productAttributeOptions as $key => $value) {
            $tmp_option = $value['values'];
            if(count($tmp_option) > 0)
            {
                $attr = strtolower($value['label']);   
            }
        }
            $_children = $product->getTypeInstance()->getUsedProducts($product); 
            foreach ($_children as $child){ 
                if($child->getIsSalable()) {
                    if(isset($child[$attr])) {                                          
                         $check = $child->getResource()->getAttribute($attr)->getFrontend()->getValue($child); 
                         $childpotion[] = str_replace($value['label'],'',$check); 
                         $childid[] = $child->getId(); 
                         $cprice[] = $TaxHelper->getTaxPrice($child, $child->getFinalPrice(), true);
                    }
                }
            } 
     } else {
        $childpotion [] = "simple";
     }
 
        $pr_sku = $product->getSku();
        $cart = $objectManager->create('\Magento\Checkout\Model\Cart');
        // $this->cart->removeItem($productId)->save();
            $returnvalue['status']="1";
            $returnvalue['option']= $childpotion;
            $returnvalue['childid']= $childid;
            $returnvalue['attr']= $attr;
            $returnvalue['cprice']= $cprice;
               
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData($returnvalue);
            return $resultJson;
    }
}
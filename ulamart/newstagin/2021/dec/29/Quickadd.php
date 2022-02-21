<?php
namespace Ashsmith\HelloWorld\Controller\Index;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Action;

class Quickadd extends Action {
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
        $productId=$this->getRequest()->getParam('pr_id'); //child
        $parentId=$this->getRequest()->getParam('parentid'); //parent
        $qty = $this->getRequest()->getParam('qty'); //qty
            // new
                $parent = $this->productFactory->create()->load($parentId);
                $child = $this->productFactory->create()->load($productId);

                    $params = [];
                    $params['product'] = $parent->getId();
                    $params['qty'] = $qty;
                    $options = [];

                    $productAttributeOptions = $parent->getTypeInstance(true)->getConfigurableAttributesAsArray($parent);

                    foreach ($productAttributeOptions as $option) {
                        $options[$option['attribute_id']] = $child->getData($option['attribute_code']);
                    }
                    $params['super_attribute'] = $options;

                    /*Add product to cart */
                    $this->cart->addProduct($parent, $params);
                    $this->cart->save();
            // new
            $subTotal = $this->cart->getQuote()->getSubtotal();
            $grandTotal = $this->cart->getQuote()->getGrandTotal();
            $returnvalue['status']="1";
            $returnvalue['pname']= $child->getName();
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData($returnvalue);
            return $resultJson;
    }
}
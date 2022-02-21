<?php
namespace Ashsmith\HelloWorld\Controller\Index;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Action;

class Index extends Action {
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
        $productId=$this->getRequest()->getParam('pr_id');
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
        $gift_pr_sku = $product->getSku();

        $qty =$this->getRequest()->getParam('qty');


        $gift_pr = $objectManager->create('Magento\Catalog\Model\Product')->loadByAttribute('sku', $gift_pr_sku);


        $cart = $objectManager->create('\Magento\Checkout\Model\Cart');

        // foreach ($cart->getQuote()->getAllItems() as $item) {
        //     /** @var \Magento\Quote\Model\Quote\Item $item */
        //     $item->delete();
        // }

            $_product = $this->productFactory->create()->load($productId);
            $gift_pr = $this->productFactory->create()->load($gift_pr_sku);

            $returnvalue['status']="1";
               
                if ($_product) {
                        $params = array(
                        'qty'   => $qty
                    ); 
                    $_product->addCustomOption('gift_pr_id', $gift_pr_sku);
                    $this->cart->addProduct($_product, $params);
                }


            $this->cart->save();
            $returnvalue['status']="1";
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData($returnvalue);
            return $resultJson;
    }
}
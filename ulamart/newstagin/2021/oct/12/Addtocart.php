<?php
namespace Vap\CustomerAccount\Controller\Customer;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Action;

class Addtocart extends Action {
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
        $gift_pr_sku =$this->getRequest()->getParam('gift_pr_sku');

        $gift_pr = $objectManager->create('Magento\Catalog\Model\Product')->loadByAttribute('sku', $gift_pr_sku);


        $cart = $objectManager->create('\Magento\Checkout\Model\Cart');

        foreach ($cart->getQuote()->getAllItems() as $item) {
            /** @var \Magento\Quote\Model\Quote\Item $item */
            $item->delete();
        }

            $_product = $this->productFactory->create()->load($productId);
            $gift_pr = $this->productFactory->create()->load($gift_pr_sku);
            // $gift_pr = $objectManager->create('Magento\Catalog\Model\Product')->loadByAttribute('sku', $gift_pr_sku);
            // $gift_pr_id

            $returnvalue['status']="1";
               
                if ($_product) {
                    $additionalOptions[] = array(
                        'label' => "Installment",
                        'value' => "1"
                    );
                    $product_option = json_encode($additionalOptions);
                    $params = array(
                        'qty'   =>1
                    ); 
                    $_product->addCustomOption('additional_options', $product_option);
                    $_product->addCustomOption('gift_pr_id', $gift_pr_sku);
                    $this->cart->addProduct($_product, $params);
                }
                if($gift_pr){
                    $additionalOptions_g[] = array(
                        'label' => "Gift Product",
                        'value' => ""
                    );
                    $gift_product_option = json_encode($additionalOptions_g);
                    $params_g = array(
                        'qty'   =>1
                    ); 
                    $gift_pr->addCustomOption('additional_options', $gift_product_option);
                    $gift_pr->addCustomOption('product_id', $productId);
                    $this->cart->addProduct($gift_pr, $params_g);
                }


            $this->cart->save();
            $returnvalue['status']="1";
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData($returnvalue);
            return $resultJson;
    }
}

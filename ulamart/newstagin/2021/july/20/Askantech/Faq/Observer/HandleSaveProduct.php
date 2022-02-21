<?php
namespace Askantech\Faq\Observer;

use Magento\Framework\App\RequestInterface;

class productfaq implements RequestInterface
{
protected $request;

    /**
     * HandleSaveProduct constructor.
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(\Magento\Framework\App\RequestInterface $request)
    {
        $this->request = $request;
    }
    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $params = $this->request->getParams();
        $customFieldData = $params['custom_faq'];
        //logic to process custom fields data
        // ...
        return $customFieldData;
    }
    public function modifyData(array $data)
    {
        $product   = $this->locator->getProduct();
        $productId = $product->getId();
        $data = array_replace_recursive(
            $data, [
                $productId => [
                    'magenest' => [
                        'textField' => 'Magenest Welcome',
                        'status'=> 1
                    ]
                ]
            ]);
        return $data;
    }
}
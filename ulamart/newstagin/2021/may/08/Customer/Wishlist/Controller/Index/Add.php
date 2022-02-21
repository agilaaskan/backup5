<?php

namespace Customer\Wishlist\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Controller\ResultFactory;

class Add extends \Magento\Wishlist\Controller\Index\Add
{
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest()))
        {
            return $resultRedirect->setPath('*/');
        }

        $wishlist = $this->wishlistProvider->getWishlist();
        if (!$wishlist)
        {
            throw new NotFoundException(__('Page not found.'));
        }

        $customer_session = $this->_customerSession;

        $reqparams = $this->getRequest()->getParams();

        if ($customer_session->getBeforeWishlistRequest())
        {
            $reqparams = $customer_session->getBeforeWishlistRequest();
            $customer_session->unsBeforeWishlistRequest();
        }

        $product_id = isset($reqparams['product']) ? (int)$reqparams['product'] : null;
        if (!$product_id)
        {
            $resultRedirect->setPath('*/');
            return $resultRedirect;
        }

        try
        {
            $product = $this->productRepository->getById($product_id);
        }
        catch (NoSuchEntityException $e)
        {
            $product = null;
        }

        if (!$product || !$product->isVisibleInCatalog())
        {
            $this->messageManager->addErrorMessage(__('We can\'t specify a product.'));
            $resultRedirect->setPath('*/');
            return $resultRedirect;
        }

        try
        {
            $buyRequest = new \Magento\Framework\DataObject($reqparams);

            $result = $wishlist->addNewItem($product, $buyRequest);
            if (is_string($result))
            {
                throw new \Magento\Framework\Exception\LocalizedException(__($result));
            }
            if ($wishlist->isObjectNew())
            {
                $wishlist->save();
            }
            $this->_eventManager->dispatch(
                'wishlist_add_product',
                ['wishlist' => $wishlist, 'product' => $product, 'item' => $result]
            );

            $referer = $customer_session->getBeforeWishlistUrl();
            if ($referer)
            {
                $customer_session->setBeforeWishlistUrl(null);
            }
            else
            {
                $referer = $this->_redirect->getRefererUrl();
            }

            $this->_objectManager->get(\Magento\Wishlist\Helper\Data::class)->calculate();

            $this->messageManager->addComplexSuccessMessage(
                'addProductSuccessMessage',
                [
                    'product_name' => $product->getName(),
                    'referer' => $referer
                ]
            );
        }
        catch (\Magento\Framework\Exception\LocalizedException $e)
        {
            $this->messageManager->addErrorMessage(
                __('We can\'t add the item to Wish List: %1.', $e->getMessage())
            );
        }
        catch (\Exception $e)
        {
            $this->messageManager->addExceptionMessage(
                $e,
                __('We can\'t add the item to Wish List.')
            );
        }

        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
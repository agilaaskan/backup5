<?php
/**
 * @author oxss
 */

namespace OX\AjaxWishlist\Model\Api;

use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Wishlist\Controller\WishlistProviderInterface;
use Magento\Wishlist\Model\Item;
use Magento\Wishlist\Model\Product\AttributeValueProvider;
use Magento\Wishlist\Model\Wishlist as wishlistItem;

class AjaxWishlist
{

    protected $customerSession;

    protected $session;

    protected $wishlistItem;

    protected $productRepository;

    protected $wishlistProvider;

    protected $item;

    protected $eventManager;

    protected $attributeProvider;

    protected $messageManager;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        WishlistProviderInterface $wishlistProvider,
        wishlistItem $wishlistItem,
        Item $item,
        AttributeValueProvider $attributeProvider = null,
        Session $session
    ) {
        $this->productRepository = $productRepository;
        $this->wishlistProvider = $wishlistProvider;
        $this->wishlistItem = $wishlistItem;
        $this->item = $item;
        $this->eventManager = $context->getEventManager();
        $this->messageManager = $context->getMessageManager();
        $this->attributeProvider = $attributeProvider;
        $this->session = $session;
    }

    public function wishlistAction($productId)
    {
        $customerId = $this->session->getCustomerId(); //If not logged in customer return error message
        if (isset($customerId) && $customerId != 'null') {
            $wishlistCollection = $this->wishlistItem->loadByCustomerId($customerId)->getItemCollection();
            $itemId = null;

            if (count($wishlistCollection)) {
                foreach ($wishlistCollection as $item) {
                    if ($item->getProduct()->getId() == $productId) {
                        $itemId = $item->getWishlistItemId();
                    }
                }
            }
            $itemId == null ? $this->addToWishlist($productId) : $this->removeToWishlist($itemId);
            return true;
        }
        return false;
    }

    public function addToWishlist($productId)
    {
        $wishlist = $this->wishlistProvider->getWishlist();
        $productId = isset($productId) ? (int)$productId : null;

        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            $product = null;
        }

        try {
            $result = $wishlist->addNewItem($product);
            if (is_string($result)) {
                throw new LocalizedException(__($result));
            }
            if ($wishlist->isObjectNew()) {
                $wishlist->save();
            }
            $this->eventManager->dispatch(
                'wishlist_add_product',
                ['wishlist' => $wishlist, 'product' => $product, 'item' => $result]
            );
            $this->messageManager->addComplexSuccessMessage(
                'addProductSuccessMessage',
                [
                    'product_name' => $product->getName()

                ]
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(
                __('We can\'t add the item to Wish List right now: %1.', $e->getMessage())
            );
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('We can\'t add the item to Wish List right now.')
            );
        }
        return true;
    }

    public function removeToWishlist($itemId)
    {
        $item = $this->item->load($itemId);
        if (!$item->getId()) {
            throw new NotFoundException(__('Page not found.'));
        }
        $wishlist = $this->wishlistProvider->getWishlist($item->getWishlistId());
        if (!$wishlist) {
            throw new NotFoundException(__('Page not found.'));
        }
        try {
            $item->delete();
            $wishlist->save();

            $productName = $this->productRepository->getById($item->getProductId())->getName();
            $this->messageManager->addComplexSuccessMessage(
                'removeWishlistItemSuccessMessage',
                [
                    'product_name' => $productName,
                ]
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(
                __('We can\'t delete the item from Wish List right now because of an error: %1.', $e->getMessage())
            );
        } catch (Exception $e) {

            $this->messageManager->addErrorMessage(__('We can\'t delete the item from the Wish List right now.'));
        }
        return true;
    }
}

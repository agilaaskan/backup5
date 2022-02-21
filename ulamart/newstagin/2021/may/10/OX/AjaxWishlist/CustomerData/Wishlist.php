<?php
/**
 * @author oxss
 */

namespace OX\AjaxWishlist\CustomerData;

use Magento\Wishlist\CustomerData\Wishlist as WishlistData;

class Wishlist extends WishlistData
{
    public function getSectionData()
    {
        $counter = $this -> getCounter();

        return [
            'counter' => $counter,
            'all_wishlist_items' => $counter ? $this -> getAllItems() : [],
            'items' => $counter ? $this -> getItems() : [],
        ];
    }

    protected function getAllItems()
    {

        $collection = $this -> wishlistHelper -> getWishlistItemCollection();
        $collection -> clear()
            -> setInStockFilter(true) -> setOrder('added_at');

        $items = [];
        foreach ($collection as $wishlistItem) {
            $product = $wishlistItem -> getProduct();
            $items[$product -> getId()] = $wishlistItem;
        }
        return $items;
    }
}

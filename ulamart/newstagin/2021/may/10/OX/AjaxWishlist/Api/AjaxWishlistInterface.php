<?php

namespace OX\AjaxWishlist\Api;

interface AjaxWishlistInterface
{
    /**
     * GET for Post api
     * @param string $productId
     * @return boolean
     */

    public function wishlistAction($productId);
}

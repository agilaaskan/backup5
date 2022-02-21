<?php
/**
 * @author oxss
 */

namespace OX\AjaxWishlist\ViewModel;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use OX\AjaxWishlist\Helper\Data;

class AjaxWishlistStatus implements ArgumentInterface
{
    protected $helperData;
    protected $request;

    public function __construct(Data $helperData, Context $context)
    {
        $this -> helperData = $helperData;
        $this -> request = $context -> getRequest();
    }

    public function moduleStatus()
    {
        return $this -> helperData -> isModuleEnabled();
    }

    public function isWishlistPage()
    {

        return $this -> request -> getFullActionName() == "wishlist_index_index";
    }
}

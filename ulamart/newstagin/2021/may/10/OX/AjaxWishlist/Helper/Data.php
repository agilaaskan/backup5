<?php
/**
 * @author oxss
 */

namespace OX\AjaxWishlist\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const AJAX_WISHLIST_ENABLED = 'wishlist/ajax_wishlist/status';
    /**
     * ScopeConfig
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeInterface
    ) {
        parent ::__construct($context);
        $this -> scopeConfig = $scopeInterface;
    }

    public function isModuleEnabled()
    {
        return (bool)$this -> getConfigValue(self::AJAX_WISHLIST_ENABLED);
    }

    public function getConfigValue($path)
    {
        return $this -> scopeConfig -> getValue($path, ScopeInterface::SCOPE_STORE);
    }
}

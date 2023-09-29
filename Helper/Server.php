<?php

namespace Revenuehunt\ProductQuiz\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class Server extends AbstractHelper
{
    /** @var StoreManagerInterface */
    private $storeManager;

    /** @var string */
    protected $serverEnvironment;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->serverEnvironment = $this->getServerEnvironment();
    }

    /**
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isSslEnabled()
    {
        $currentStore = $this->storeManager->getStore();
        $baseUrl = $currentStore->getBaseUrl(UrlInterface::URL_TYPE_WEB);
        return strpos($baseUrl, 'https://') === 0;
    }

    /**
     * @return bool
     */
    public function isLocalEnvironment()
    {
        return $this->serverEnvironment === 'local';
    }

    /**
     * @return string
     */
    protected function getServerEnvironment()
    {
        $serverIp = $_SERVER['SERVER_ADDR'];
        if ($serverIp === '127.0.0.1' || $serverIp === '::1' || strpos($serverIp, '192.168.') === 0) {
            return 'local';
        } else {
            return 'server';
        }
    }
}

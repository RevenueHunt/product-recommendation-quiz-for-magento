<?php

namespace Revenuehunt\ProductQuiz\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Server extends AbstractHelper
{
    /** @var string */
    protected $serverEnvironment;

    /**
     * @param Context $context
     */
    public function __construct(
        Context               $context
    ) {
        parent::__construct($context);
        $this->serverEnvironment = $this->getServerEnvironment();
    }

    /**
     * @return bool
     */
    public function isLocalEnvironment()
    {
        return $this->serverEnvironment === 'local';
    }

    /**
     * @return bool
     */
    public function isServerEnvironment()
    {
        return $this->serverEnvironment === 'server';
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

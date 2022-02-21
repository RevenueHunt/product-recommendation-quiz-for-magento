<?php
/**
 * Copyright © BoxLeafDigital 2021 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Revenuehunt\ProductQuiz\Block\Adminhtml\Index;

use Revenuehunt\ProductQuiz\Model\Config;
use Revenuehunt\ProductQuiz\UiComponent\ProductQuizJsLayoutDataProvider;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\HTTP\Client\Curl;

/**
 * Class Index
 * @package Revenuehunt\ProductQuiz\Block\Adminhtml\Index
 */
class Index extends Template
{
    /**
     * @var Config
     */
    private $_config;

    /**
     * @var UrlInterface
     */
    private $_backendUrl;


    /**
     * Constructor
     *
     * @param Context $context
     * @param UrlInterface $backendUrl
     * @param ProductQuizJsLayoutDataProvider $jsLayoutDataProvider
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        UrlInterface $backendUrl,
        ProductQuizJsLayoutDataProvider $jsLayoutDataProvider,
        Config  $config,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->_config = $config;
        $this->_backendUrl = $backendUrl;

        if (isset($data['jsLayout'])) {
            $this->jsLayout = array_merge_recursive($jsLayoutDataProvider->getData(), $data['jsLayout']);
            unset($data['jsLayout']);
        } else {
            $this->jsLayout = $jsLayoutDataProvider->getData();
        }
    }
}
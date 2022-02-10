<?php
/**
 * Copyright © BoxLeaf Digital All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Revenuehunt\ProductQuiz\Controller\Adminhtml\Index;

use Revenuehunt\ProductQuiz\Model\Service;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Auth
 * @package Revenuehunt\ProductQuiz\Controller\Adminhtml\Index
 */
class Auth extends Action
{

    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Revenuehunt_ProductQuiz::product_quiz';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Data
     */
    protected $jsonHelper;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Service
     */
    private $service;
    /**
     * @var RawFactory
     */
    private $resultRawFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param FormKey $formKey
     * @param PageFactory $resultPageFactory
     * @param RawFactory $resultRawFactory
     * @param Data $jsonHelper
     * @param Service $service
     * @param LoggerInterface $logger
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RawFactory $resultRawFactory,
        Data $jsonHelper,
        Service  $service,
        LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->service = $service;
        $this->resultRawFactory = $resultRawFactory;

        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->resultRawFactory->create();
            $result->setContents($this->service->auth());
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
            $result->setContents(false);
        }
        return  $result;
    }

    /**
     * Create json response
     *
     * @param string $response
     * @return ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}


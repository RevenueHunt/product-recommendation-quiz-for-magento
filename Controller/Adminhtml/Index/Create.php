<?php
/**
 * Copyright © BoxLeaf Digital All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Revenuehunt\ProductQuiz\Controller\Adminhtml\Index;

use Revenuehunt\ProductQuiz\Helper\Server;
use Revenuehunt\ProductQuiz\Model\Service;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Create
 * @package Revenuehunt\ProductQuiz\Controller\Adminhtml\Index
 */
class Create extends Action
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
     * @var Server
     */
    private $serverHelper;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Data $jsonHelper
     * @param Service $service
     * @param LoggerInterface $logger
     * @param Server $serverHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Data $jsonHelper,
        Service  $service,
        LoggerInterface $logger,
        Server $serverHelper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->service = $service;
        $this->serverHelper = $serverHelper;
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
            if ($this->serverHelper->isLocalEnvironment()) {
                $this->messageManager->addErrorMessage('This plugin does not work on local environments.
                It needs to be installed on a live website. Your website needs to be public and not hidden by a
                site under construction plugin because it needs connection to our server in order to work.');
                return $this->jsonResponse(['error' => true, 'data' => []]);
            } elseif (!$this->serverHelper->isSslEnabled()) {
                $this->messageManager->addErrorMessage('Product Recommendation Quiz for eCommerce requires
                 your website to have a valid HTTPS/SSL certificate.');
                return $this->jsonResponse(['error' => true, 'data' => []]);
            }
            return $this->jsonResponse( $this->service->create());
        } catch (Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
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


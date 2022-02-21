<?php
/**
 * Copyright © BoxLeafDigital 2021 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Revenuehunt\ProductQuiz\Controller\Adminhtml\Index;

use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use Revenuehunt\ProductQuiz\Model\Config;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class Revoke
 * @package Revenuehunt\ProductQuiz\Controller\Adminhtml\Index
 */
class Revoke extends Action
{

    protected $resultJsonFactory;
    /**
     * @var Config
     */
    private $confg;
    /**
     * @var TypeListInterface
     */
    private $_cacheTypeList;
    /**
     * @var Pool
     */
    private $_cacheFrontendPool;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Config $confg
     * @param TypeListInterface $cacheTypeList
     * @param Pool $cacheFrontendPool
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Config $confg,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->confg = $confg;

        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;

        parent::__construct($context);
    }

    /**
     * Collect relations data
     *
     * @return Json
     */
    public function execute()
    {
        try {
           $this->confg->setConfig('RH_HASH', null);
           $this->confg->setConfig('RH_TOKEN', null);
           $this->confg->setConfig('RH_DOMAIN',null );
        } catch (\Exception $e) {
           /* die(var_dump($e->getMessage())); */
           $result = $this->resultJsonFactory->create();
           return $result->setData(['success' => false ]);
        }

        $types = array('config','reflection','db_ddl','config_integration','config_integration_api','config_webservice');
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }


        $result = $this->resultJsonFactory->create();

        return $result->setData(['success' => true ]);
    }

}
?>

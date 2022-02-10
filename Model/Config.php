<?php
/**
 * Copyright © BoxLeafDigital 2021 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Revenuehunt\ProductQuiz\Model;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\Resolver;
use Magento\Framework\Module\ResourceInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Config
 * @package Revenuehunt\ProductQuiz\Model
 */
class Config
{
    const ADMIN_EMAIL = 'trans_email/ident_support/name';

    const IS_TEST = 'product_quiz/general/prq_is_test';
    const API_TEST_URL = 'product_quiz/general/prq_api_url_test';
    const API_URL = 'product_quiz/general/prq_api_url';
    const RH_CREATE = 'product_quiz/general/prq_iframe_url';

    const RH_TOKEN = 'product_quiz/hidden/rh_token';
    const RH_HASH = 'product_quiz/hidden/rh_shop_hashid';
    const RH_DOMAIN = 'product_quiz/hidden/rh_domain';
    const CHANNEL = 'product_quiz/hidden/channel';


    private  $_configWriter;
    /**
     * @var ScopeConfigInterface
     */
    private  $_scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    private  $_storeManager;
    /**
     * @var ProductMetadataInterface
     */
    private  $_meta;

    /**
     * @var ResourceInterface
     */
    private  $_moduleResource;

    /**
     * @var UrlInterface
     */
    private  $_backendUrl;

    /**
     * @var EncryptorInterface
     */
    private  $_encryptor;

    /**
     * @var Resolver
     */
    private  $_locale;
    /**
     * @var Http
     */
    private $_request;

    /**
     * Config constructor.
     * @param Http $request
     * @param \Magento\Config\Model\ResourceModel\Config $configWriter
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param Resolver $locale
     * @param ProductMetadataInterface $meta
     * @param ResourceInterface $moduleResource
     * @param UrlInterface $backendUrl
     * @param EncryptorInterface $encryptor
     * @param array $data
     */
    public function __construct(
        Http $request,
        \Magento\Config\Model\ResourceModel\Config $configWriter,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface  $storeManager,
        Resolver $locale,
        ProductMetadataInterface $meta,
        ResourceInterface $moduleResource,
        UrlInterface $backendUrl,
        EncryptorInterface $encryptor,
        array $data = []
    ){
        $this->_configWriter = $configWriter;
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_meta = $meta;
        $this->_moduleResource = $moduleResource;
        $this->_backendUrl = $backendUrl;
        $this->_encryptor = $encryptor;
        $this->_locale = $locale;
        $this->_request = $request;
    }

    /**
     * @return string
     */
    public function getBaseUrl() {
        return $this->getStore()->getBaseUrl();
    }

    /**
     * @return string
     */
    public function getBaseUrlAdmin() {
        return  $this->_backendUrl->getUrl();
    }

    /**
     * @return string
     */
    public function getAreaFrontName() {
        return $this->_backendUrl->getAreaFrontName();
    }

    /**
     * @return UrlInterface
     */
    public function getBackend() {
        return $this->_backendUrl;
    }


    /**
     * @return string
     */
    public function getVersion() {
        return $this->_meta->getVersion();
    }

    /**
     * @return false|string
     */
    public function getModuleVersion() {
        return $this->_moduleResource->getDbVersion('Revenuehunt_ProductQuiz');
    }


    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId(){

        if($storeId = $this->_request->getParam('store')) {
            return $storeId;
        }
        return (int) $this->_storeManager->getStore()->getId();
    }

    /**
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore() {
        return $this->_storeManager->getStore($this->getStoreId());
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getStoreName(){
        return $this->getStore()->getName();
    }

    /**
     * @return string
     */
    public function getLocale(){
        return $this->_locale->getLocale();
    }


    /**
     * @return string
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getBaseCurrency() {
       return $this->getStore()->getCurrentCurrency()->getCode();
    }

    /**
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCurrencySymbol() {
        return $this->getStore()->getCurrentCurrency()->getCurrencySymbol();
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getAdminEmail() {
        return $this->getConfig('ADMIN_EMAIL');
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getChannel() {
        return $this->getConfig('CHANNEL');
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getCreateUrl() {
        return $this->getConfig('RH_CREATE');
    }


    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getApiUrl() {
         if($this->getConfig('IS_TEST')) {
             return $this->getConfig('API_TEST_URL');
         }else {
             return $this->getConfig('API_URL');
         }
    }


    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getHashId() {
        return $this->getConfig('RH_HASH');

    }


    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getSecret() {
        return $this->getConfig('RH_TOKEN');
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getDomain() {
        return $this->getConfig('RH_DOMAIN');
    }


    /**
     * @param $path
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getConfig($path)
    {
        $class = new \ReflectionClass($this);
        $const = $class->getConstant($path);
        return $this->_scopeConfig->getValue(
            $const,
            'stores',
            $this->getStoreId()
        );
    }

    /**
     * @param $path
     * @param $value
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function setConfig($path, $value) {
        $class = new \ReflectionClass($this);
        $const = $class->getConstant($path);
        $this->_configWriter->saveConfig($const, $value, 'stores', $this->getStoreId());
        return $value;
    }

    /**
     * @param $path
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function deleteConfig($path) {
        $class = new \ReflectionClass($this);
        $const = $class->getConstant($path);

        $this->_configWriter->deleteConfig($const, 'stores', $this->getStoreId());
        return true;
    }
}


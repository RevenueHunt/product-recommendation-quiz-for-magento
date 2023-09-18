<?php
/**
 * Copyright © BoxLeafDigital 2021 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Revenuehunt\ProductQuiz\Model;


use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Oauth\Oauth;
use Magento\Framework\Oauth\TokenProviderInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Integration\Model\IntegrationFactory;
use Magento\Integration\Model\Oauth\ConsumerFactory;
use Magento\Integration\Model\Oauth\Token;
use Magento\TestFramework\Authentication\Rest\OauthClient;
use Psr\Log\LoggerInterface;

/**
 * Class Config
 * @package Revenuehunt\ProductQuiz\Model
 */
class Service
{

    /**
     * @var Config
     */
    private  $_config;
    /**
     * @var Json
     */
    private  $_json;
    /**
     * @var Curl
     */
    private  $_curl;

    /**
     * @var IntegrationFactory
     */
    private  $_integrationFactory;

    /**
     * @var ConsumerFactory
     */
    private  $_consumerFactory;

    /**
     * @var Token
     */
    private  $_token;
    /**
     * @var TypeListInterface
     */
    private $_cacheTypeList;

    /**
     * @var Pool
     */
    private  $_cacheFrontendPool;

    /**
     * @var array
     */
    private  $data;
    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * Service constructor.
     * @param IntegrationFactory $integration
     * @param ConsumerFactory $consumerFactory
     * @param Token $token
     * @param Config $config
     * @param Json $json
     * @param Curl $curl
     * @param TypeListInterface $cacheTypeList
     * @param Pool $cacheFrontendPool
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct
    (
        IntegrationFactory $integration,
        ConsumerFactory $consumerFactory,
        Token $token,
        Config $config,
        Json $json,
        Curl $curl,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool,
        LoggerInterface  $logger,
        array $data = []
    )
    {

        $this->_config = $config;
        $this->_json = $json;
        $this->_curl = $curl;
        $this->data = $data;
        $this->_integrationFactory = $integration;
        $this->_consumerFactory = $consumerFactory;
        $this->_token = $token;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->logger = $logger;

    }

    /**
     * Create the curl request by token
     */
    public function initCurl()
    {
        $this->_curl->addHeader('Content-Type','application/json');
        $this->_curl->setOptions([CURLOPT_HTTP_VERSION=>'1.1', CURLOPT_VERBOSE => 'ON']);
        return $this;
    }

    public function getReferer()
    {
        return Http::getUrlNoScript($this->_config->getUrl('prqfw/index/index'));
    }


    /**
     * @return string
     */
    public function auth() {
        $this->initCurl();
        $create =  trim($this->_config->getCreateUrl(),'/') . '/public/magento/oauth';
        //$create = 'https://937c427dc1f850.localhost.run'. '/public/magento/oauth';
        $hashId = (string)$this->_config->getHashId();
        $domain = (string)$this->_config->getDomain();
        $version =(string) $this->_config->getModuleVersion();

        $domain = preg_replace('#^https?://#', '', rtrim($domain,'/'));


        $data = sprintf('hashid=%s&domain=%s&plugin_version=%s&timestamp=%s',$hashId,$domain,$version,(string)time());
        $secret = $this->_config->getSecret();
        $hmac = base64_encode(hash_hmac('sha256', $data, $secret ?? "", true));

        $request = [
            'timestamp' => time(),
            'domain' => trim($domain),
            'hashid' => $hashId,
            'plugin_version' => $version,
            'hmac' =>urlencode($hmac)
        ];
        $create .= '?';
        foreach($request as $key => $value) {
            $create .= $key.'='.$value.'&';
        }

        $create = trim($create,' &');


        return $create;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function create() {
        $this->initCurl();
        $create =  trim($this->_config->getApiUrl(),'/') . '/api/v1/magento/create';
        //$create = 'https://d57883ad653649.localhost.run'. '/api/v1/magento/create';
        $name = 'product recommendation quiz';
        $email = 'no-reply@revenuehunt.com';

        $integrationFactory = $this->_integrationFactory->create();
        $consumerFactory = $this->_consumerFactory->create();

        //load the integration and consumer
        $integratrion = $integrationFactory->load($name, 'name');

        $consumerName = 'Integration' . $integratrion->getId();
        $consumer = $consumerFactory->load($consumerName, 'name');

        $data = $this->_token->load($consumer->getId(), 'consumer_id');

        $domain = (string)$this->_config->getDomain() == '' ? $this->_config->getBaseUrl() : $this->_config->getDomain();
        $domain = preg_replace('#^https?://#', '', rtrim($domain,'/'));

        //send the create request
        $request = [
            'domain' => $domain,
            'name' => $this->_config->getStoreName(),
            'store_code' => $this->_config->getStore()->getCode(),
            'channel' => $this->_config->getChannel(),
            'currency' => $this->_config->getBaseCurrency(),
            'symbol' => $this->_config->getCurrencySymbol(),
            'locale' => $this->_config->getLocale(),
            'access_token' => $data->getData('token')
        ];


        try {
            $this->_curl->post($create, $this->_json->serialize($request));
            $this->logger->info(print_r($request, true));
            $this->logger->info(print_r($this->_curl->getBody(), true));
            $this->logger->info(print_r($this->_curl->getStatus(), true));

            $body =  $this->_json->unserialize($this->_curl->getBody());
        }catch (\Exception $e) {
            return ['error' => true, 'message'=>  $e->getMessage(), 'data' => []];
        }

        if(isset($body['status']))
        {
            if($body['status'] == '500')
            {
                return ['error' => true, 'message'=> isset($body['exception']) ? $body['exception'] : '', 'data' => []];
            }
        }

        if(isset($body['hashid']) && isset($body['secret'])) {

            $this->_config->setConfig('RH_HASH',$body['hashid']);
            $this->_config->setConfig('RH_TOKEN',$body['secret']);
            $this->_config->setConfig('RH_DOMAIN',$this->_config->getBaseUrl());


            $types = array('config','reflection','db_ddl','eav','config_integration','config_integration_api','translate','config_webservice');
            foreach ($types as $type) {
                $this->_cacheTypeList->cleanType($type);
            }
            foreach ($this->_cacheFrontendPool as $cacheFrontend) {
                $cacheFrontend->getBackend()->clean();
            }
            return ['error' => false, 'data' => ['hashId' =>$body['hashid'], 'secret' => $body['secret'] ]];
        }

        return ['error' => true, 'data' => []];
    }
}

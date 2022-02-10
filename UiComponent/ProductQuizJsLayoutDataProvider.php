<?php
/**
 * Copyright © BoxLeafDigital 2021 All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Revenuehunt\ProductQuiz\UiComponent;

use Magento\Framework\App\Request\Http;
use Revenuehunt\ProductQuiz\Model\Config;
use Magento\Backend\Model\UrlInterface as UrlInterfaceBackend;
use Magento\Customer\CustomerData\JsLayoutDataProviderInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;

/**
 * Class ProductQuizJsLayoutDataProvider
 * @package Revenuehunt\ProductQuiz\UiComponent
 */
class ProductQuizJsLayoutDataProvider implements JsLayoutDataProviderInterface
{

    /**
     * @var Config
     */
    private  $_config;
    /**
     * @var UrlInterface
     */
    private  $_urlBuilder;
    /**
     * @var FormKey
     */
    private  $formKey;
    /**
     * @var Repository
     */
    private  $moduleAssetDir;
    /**
     * @var UrlInterfaceBackend
     */
    private  $urlBuilderBackend;
    /**
     * @var Http
     */
    private $request;

    public function __construct(Config  $config,
                                Http $request,
                                FormKey $formKey,
                                Repository $moduleAssetDir,
                                UrlInterfaceBackend $urlBuilderBackend,
                                UrlInterface $urlBuilder) {
        $this->_config = $config;
        $this->_urlBuilder = $urlBuilder;
        $this->formKey = $formKey;
        $this->request = $request;
        $this->moduleAssetDir = $moduleAssetDir;
        $this->urlBuilderBackend = $urlBuilderBackend;
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * @return array
     */
    public function getData()
    {

        $create =  $this->urlBuilderBackend->getRouteUrl('prqfw/index/create',
            [ 'key'=>$this->urlBuilderBackend->getSecretKey('prqfw','index','create'), 'store' => $this->request->getParam('store', 0)]);
        $auth  = $this->urlBuilderBackend->getRouteUrl('prqfw/index/auth',
            [ 'key'=>$this->urlBuilderBackend->getSecretKey('prqfw','index','auth'),  'store' => $this->request->getParam('store', 0)]);
        $config = [
            'image' => $this->moduleAssetDir->getUrl("Revenuehunt_ProductQuiz::images/revenuehunt-logo.png"),
            'has_ssl' => $this->hasSSL(),
            'form_key' => $this->getFormKey(),
            'has_migrated' => $this->hasMigrationWarning(),
            'ajax' => ['auth'=>$auth, 'create'=>$create],
            'storeId' => $this->_config->getStoreId(),
            'hashId' => (string)$this->_config->getHashId() ?? "",
            'secret' => (string)$this->_config->getSecret() ?? ""
        ];


        return [
            'components' => [
                'productQuiz' => [
                    'config' => $config
                ],
            ],
        ];
    }

    /**
     * @return bool
     */
    public function hasSSL(){
        $stream = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
        $read = fopen( $this->_config->getBaseUrl(), "rb", false, $stream);
        $cont = stream_context_get_params($read);
        return  (bool) isset($cont["options"]["ssl"]["capture_peer_cert"]) ? $cont["options"]["ssl"]["capture_peer_cert"] : false;
    }


    /**
     * @return bool
     * @throws NoSuchEntityException
     */
    public function hasMigrationWarning() {
        $domain =  $this->_config->getBaseUrl();
        $oldDomain =  $this->_config->getDomain();
        $hashId =  $this->_config->getHashId();
        return !($domain === $oldDomain)  && $hashId != '' ;
    }
}

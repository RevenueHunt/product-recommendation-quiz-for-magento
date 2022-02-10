<?php
/**
 * Copyright © BoxLeafDigital 2021 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Revenuehunt\ProductQuiz\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Integration\Model\AuthorizationService;
use Magento\Integration\Model\IntegrationFactory;
use Magento\Integration\Model\Oauth\Token;
use Magento\Integration\Model\OauthService;

/**
 * Class RecurringData
 * @package Revenuehunt\ProductQuiz\Setup
 */
class RecurringData implements InstallDataInterface
{
    /**
     * @var IntegrationFactory
     */
    private $_integrationFactory;
    /**
     * @var OauthService
     */
    private  $_oathservice;
    /**
     * @var AuthorizationService
     */
    private $_authService;
    /**
     * @var Token
     */
    private $_token;

    public function __construct(IntegrationFactory $integrationFactory, OauthService $oathservice, AuthorizationService $authService, Token $token)
    {
        $this->_integrationFactory = $integrationFactory;
        $this->_oathservice = $oathservice;
        $this->_authService = $authService;
        $this->_token = $token;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        $name = 'product recommendation quiz';
        $email = 'no-reply@revenuehunt.co.uk';

        $integrationFactory = $this->_integrationFactory->create();

        $integratrion = $integrationFactory->load($name, 'name');
        if(!$integratrion->getId()) {
            $integrationData = array(
                'name' => $name,
                'email' => $email,
                'status' => '1',
                'endpoint' => '',
                'setup_type' => '0'
            );

            try {
                $integration = $integrationFactory->setData($integrationData);
                $integration->save();
                $integrationId = $integration->getId();

                $consumerName = 'Integration' . $integrationId;

                $consumer = $this->_oathservice->createConsumer(['name' => $consumerName]);
                $consumerId = $consumer->getId();
                $integration->setConsumerId($consumer->getId());
                $integration->save();

                // Code to grant permission
                $this->_authService->grantAllPermissions($integrationId);


                $token = $this->_token;
                $uri = $token->createVerifierToken($consumerId);
                $token->setType('access');
                $token->save();
            }catch(\Exception $e) {

            }
        }

    }
}

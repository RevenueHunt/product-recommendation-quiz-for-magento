<?php

namespace Revenuehunt\ProductQuiz\Controller\Index;
/**
 * @author 1902 Software
 * @copyright Copyright (c) 2022
 * @package Revenuehunt_ProductQuiz
 */

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;


class AddAllQuizProductsTocart extends \Magento\Framework\App\Action\Action
{

   protected $formKey;   
   protected $cart;
   protected $product;

   public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Framework\Data\Form\FormKey $formKey,
       \Magento\Checkout\Model\Cart $cart,
       \Magento\Catalog\Model\ProductFactory $product,
       array $data = []
   ) {
       $this->formKey = $formKey;
       $this->cart = $cart;
       $this->product = $product;      
       parent::__construct($context);
   }

   public function execute()
   { 
      $selectedItems = $this->getRequest()->getParam('product_ids'); 
 
       $selectedItems = explode(",",$selectedItems);
       try{
       foreach ($selectedItems as $key => $selectedItem) {

           $params = array(
               'form_key' => $this->formKey->getFormKey(),
               'product_id' => $selectedItem, //product Id
               'qty'   =>1 //quantity of product                
           );
           $_product = $this->product->create()->load($selectedItem);       
           $this->cart->addProduct($_product, $params);
       }
           $this->cart->save();
           $status = 1;
       } catch (\Magento\Framework\Exception\LocalizedException $e) {

           $this->messageManager->addException($e,__('%1', $e->getMessage()));
           $status = 0;

           $result = array();
           $result['status'] = $status;
           $result['errormessage'] = $e->getMessage();
           $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
           $resultJson->setData($result);
           return $resultJson;

       } catch (\Exception $e) {
           $this->messageManager->addException($e, __('error.'));
           $status = 0;
           $result = array();
           $result['status'] = $status;
           $result['errormessage'] = $e->getMessage();
           $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
           $resultJson->setData($result);
       }
       $result = array();
       $result['status'] = $status;
       $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
       $resultJson->setData($result);
       return $resultJson;
    }
   }
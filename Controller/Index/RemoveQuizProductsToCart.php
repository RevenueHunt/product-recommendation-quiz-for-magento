<?php
namespace Revenuehunt\ProductQuiz\Controller\Index;
/**
 * @author 1902 Software
 * @copyright Copyright (c) 2022
 * @package Revenuehunt_ProductQuiz
 */


use Magento\Framework\Controller\ResultFactory;
use Magento\Quote\Api\CartRepositoryInterface;

class RemoveQuizProductsToCart extends \Magento\Framework\App\Action\Action
{
    /**
     * @var CartRepositoryInterface
     */
   protected $cartRepository;

   protected $formKey;   
   protected $cart;
   protected $product;

   public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Framework\Data\Form\FormKey $formKey,
       \Magento\Checkout\Model\Cart $cart,
       \Magento\Catalog\Model\ProductFactory $product,
       CartRepositoryInterface $cartRepository,
       array $data = []
   ) {
       $this->formKey = $formKey;
       $this->cart = $cart;
       $this->product = $product;    
       $this->cartRepository = $cartRepository;  
       parent::__construct($context);
   }

   public function execute()
   { 

    $product_id = $this->getRequest()->getParam('product_id'); 
    $qty = $this->getRequest()->getParam('qty'); 
     try{
         $params = array(
             'form_key' => $this->formKey->getFormKey(),
             'product_id' => $product_id, //product Id
             'qty'   =>$qty //quantity of product                
         );
       
         
         $quote = $this->cart->getQuote();
         $cart_id = 0;

        foreach ($quote->getAllVisibleItems() as $_item) {
            $cartproductid = $_item->getProductId();
            if($product_id==$cartproductid)
            {
               
                    $quote->deleteItem($_item);
                    $this->cartRepository->save($quote);
                
            }
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
       $result['message'] = __('Item removed from cart');
       $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
       $resultJson->setData($result);
       return $resultJson;
    }
       /**
     * @param array $params
     * @param int $itemId
     *
     * @return array
     */
    private function prepareParams($params, $itemId)
    {
        if (isset($params['qty'])) {
            $params['qty'] = (int)$params['qty'];
            $params['reset_count'] = true;
        }

        if (!isset($params['options'])) {
            $params['options'] = [];
        }

        $params['id'] = $itemId;

        return $params;
    }
   }
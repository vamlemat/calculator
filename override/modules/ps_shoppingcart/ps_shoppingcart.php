<?php
use PrestaShop\PrestaShop\Adapter\Cart\CartPresenter;
if (!defined('_PS_VERSION_')) {
    exit;
}
class Ps_ShoppingcartOverride extends Ps_Shoppingcart
{
    // Ducktoy 
    // public function renderModal(Cart $cart, $id_product, $id_product_attribute)
    public function renderModal( Cart $cart, $id_product, $id_product_attribute, $id_customization )
    {

        $data = (new CartPresenter)->present($cart);
        $product = null;
        foreach ($data['products'] as $p) {
            if ($p['id_product'] == $id_product && $p['id_product_attribute'] == $id_product_attribute) {
                $product = $p;
                break;
            }
        }

       if(Module::isEnabled('calculator')) 
       {
            require_once _PS_MODULE_DIR_ . 'calculator/calculator.php';
            $calculator = new Calculator();
            $accesories = $calculator->getProductAccesories($id_product);
            if($accesories)
            {
                $this->smarty->assign(array(
                    'product' => $product,
                    'accesories' => $calculator->productsForTemplate($accesories),
                    'cart' => $data,
                    'cart_url' => $this->getCartSummaryURL(),
                ));
    
                return $this->fetch('module:calculator/views/templates/override/ps_shoppingcart/modal.tpl');
            }
       }

        $this->smarty->assign(array(
            'product' => $product,
            'cart' => $data,
            'cart_url' => $this->getCartSummaryURL(),
        ));
        return $this->fetch('module:ps_shoppingcart/modal.tpl');        
   }

    private function getCartSummaryURL()
    {
        return $this->context->link->getPageLink(
            'cart',
            null,
            $this->context->language->id,
            array(
                'action' => 'show'
            ),
            false,
            null,
            true
        );
    }
}

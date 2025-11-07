<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\Presenter\Object\ObjectPresenter;
use PrestaShop\PrestaShop\Core\Product\ProductPresenter;

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;



class Calculator extends Module
{
    protected $config_form = false;
    protected $moduleTable = _DB_PREFIX_ . 'calculator';
    //protected $accesoriesTable = _DB_PREFIX_ . 'calculator_accesories';
    protected $accesoriesTable = _DB_PREFIX_ . 'accessory';

    protected $calc_template = 'views/templates/hook/calculator.tpl';
    protected $calc_js_template = 'views/templates/hook/productFooter.tpl';
    protected $objectPresenter;

    public function __construct()
    {
        $this->name = 'calculator';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = '8PECADOS';
        $this->need_instance = 0;
        $this->objectPresenter = new ObjectPresenter();

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Calculadora de precio');
        $this->description = $this->l('AÃ±ade una calculadora en los detalles del producto, se puede configurar por categorÃ­a');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include('sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayBackOfficeCategory') &&
            $this->registerHook('actionCategoryUpdate') &&
            $this->registerHook('displayProductAdditionalInfo') &&
            $this->registerHook('displayFooterProduct') &&
            $this->registerHook('actionFrontControllerSetVariables');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getContent()
    {
        if (((bool)Tools::isSubmit('submitCalculatorModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCalculatorModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Seleccione un atributo'),
                        'name' => 'CALCULATOR_ATTRIBUTE',
                        'label' => $this->l('Atributo'),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        return array(
            'CALCULATOR_ATTRIBUTE' => Configuration::get('CALCULATOR_ATTRIBUTE', 0)
        );
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookDisplayProductAdditionalInfo()
    {
        $id_product = Tools::getValue('id_product');
        $categoryIds = Product::getProductCategories($id_product);

        foreach ($categoryIds as $catId)
        {
            // TODO: Convert to ANY sql query... this was faster to write thought
            if($this->categoryHasCalculator($catId))
            {
                return $this->displayCalculator($catId, $id_product);
            }
        }
    }

   /* public function displayCalculator($id_category, $id_product)
    {
        $smarty_params = $this->getCategoryDataToDisplay($id_category);
        $packaging_variables = $this->getPackagingVariables($id_product);
        if(!$packaging_variables) { return; }
        $smarty_params = array_merge($smarty_params, $packaging_variables);
        $this->context->smarty->assign($smarty_params);
        return $this->display(__FILE__, $this->calc_template);
    }*/
    
    public function displayCalculator($id_category, $id_product)
{
    $smarty_params = $this->getCategoryDataToDisplay($id_category);
    $packaging_variables = $this->getPackagingVariables($id_product);
    if(!$packaging_variables) { return; }

    $smarty_params = array_merge($smarty_params, $packaging_variables);

    // ðŸ”¥ AquÃ­ aÃ±adimos el objeto Link y otros que necesita el tpl
    $smarty_params['link'] = $this->context->link;
    $smarty_params['static_token'] = Tools::getToken(false);
    $smarty_params['product'] = $this->getTemplateVarProduct(new Product($id_product));

    $this->context->smarty->assign($smarty_params);

    return $this->display(__FILE__, $this->calc_template);
}


    public function getPackagingVariables($id_product)
    {
        $packaging_id = Configuration::get('CALCULATOR_ATTRIBUTE', -1);
        $features = Product::getFrontFeaturesStatic($this->context->cart->id_lang, $id_product);
        $product = new Product($id_product);
        $packagePrice = $product->price;
        $packageArea = 0;

        foreach ($features as $f)
        {
            if($f["id_feature"] == $packaging_id)
            {
                $packageArea = $f["value"];
                break;
            }
        }

        // Ducktoy
        // Corregir en la medida de lo posible el formato de los nÃºmeros de la propiedad
        $packageArea = str_replace( ',', '.', $packageArea );
        $packageArea = is_numeric( $packageArea ) ? (float)$packageArea : 0;

        return array(
            'calculator_packaging' => $packageArea,
        );
    }

    public function hookActionCategoryUpdate()
    {
        $this->saveCategoryFormFromPost();
    }

    public function hookDisplayFooterProduct()
    {
        return $this->hookDisplayProductAdditionalInfo();
    }

    public function hookDisplayBackOfficeCategory()
    {
        $id_category = (int) Tools::getValue('id_category');
        if ($id_category <= 0) {
            // En pantalla de “Añadir categoría” todavía no hay ID → no renderizar nada
            return '';
        }
    
        $this->context->smarty->assign($this->getCategoryDataToDisplay($id_category));
        $output = $this->display(__FILE__, 'views/templates/admin/categoryForm.tpl');
        return $output;
    }


    public function hookActionFrontControllerSetVariables()
    {
        $id_product = Tools::getValue('id_product');
        return $this->getProductUnitPrice($id_product);
    }

    public function getProductUnitPrice($id_product)
    {
        $product = new Product($id_product);
        if(!$id_product) { return; }
        $id_category = $this->getCalculatorCategoryFromProduct($id_product);
        if(!$id_category) { return; }
        $variables = $this->getCategoryDataToDisplay($id_category);
        $variables = array_merge($variables, $this->getPackagingVariables($id_product));
        return $variables;
    }

    public function getCalculatorCategoryFromProduct($id_product)
    {
        $categoryIds = Product::getProductCategories($id_product);
        foreach ($categoryIds as $catId)
        {
            // TODO: Convert to ANY sql query... this was faster to write thought
            if($this->categoryHasCalculator($catId))
            {
                return $catId;
            }
        }
        return null;
    }

    private function getCategoryDataToDisplay($id_category)
    {
        $id = (int) $id_category;
        if ($id <= 0) {
            return array(
                'calculator_active' => 0,
                'calculator_unit'   => '',
            );
        }
    
        $categoryDataForm = $this->loadCategoryDatas($id);
        return array(
            'calculator_active' => (int) $categoryDataForm['activado'],
            'calculator_unit'   => isset($categoryDataForm['unidad']) ? $categoryDataForm['unidad'] : '',
        );
    }


    public function saveCategoryFormFromPost()
    {
        $id_category = (int) Tools::getValue('id_category');
        if ($id_category <= 0) {
            // En creación aún no hay ID; no hay nada que guardar
            return;
        }
    
        $activado = (int) Tools::getValue('calculator_active');
        $unidad   = pSQL((string) Tools::getValue('calculator_unit'));
    
        // Asumiendo que la tabla tiene PRIMARY KEY(id_category)
        $sql = 'INSERT INTO `'.$this->moduleTable.'` (`id_category`, `activado`, `unidad`)
                VALUES ('.$id_category.', '.$activado.", '".$unidad."')
                ON DUPLICATE KEY UPDATE
                  `activado` = VALUES(`activado`),
                  `unidad`   = VALUES(`unidad`)";
    
        Db::getInstance()->execute($sql);
    }


    public function categoryHasCalculator($id_category)
    {
        $id = (int) $id_category;
        if ($id <= 0) {
            return false;
        }
    
        $sql = 'SELECT `activado` FROM `'.$this->moduleTable.'`
                WHERE `id_category` = '.$id;
    
        $result = Db::getInstance()->getRow($sql);
        return ($result && (int)$result['activado'] === 1);
    }


    public function getProductAccesories($id_product)
    {
        if(!$id_product) { return; }
        $accsData = $this->loadProductAccesories($id_product);
        if(!$accsData) { return; }
        $accesories = [];
        foreach ($accsData as $accesory)
        {
            $id_accesory = $accesory['id_product_2'];
            $product = new Product($id_accesory);
            $product->unit_price = ($product->unit_price_ratio != 0 ? $product->price / $product->unit_price_ratio : 0);
            if($product->unit_price > 0)
            {
                $product->unit_price_full = $product->unit_price . ' ' . $this->context->currency->sign . '/' . $product->unity;
            }
            if($product->unity)
            {
                $packagingVars = $this->getProductUnitPrice($id_accesory);
                if($packagingVars)
                {
                    $product->packaging = $packagingVars['calculator_packaging'];
                }
                else
                {

                    $product->packaging = 0;
                }
            }

            array_push($accesories, $product);
        }

        return $accesories;
    }

    public function groupProductsByCategory($products)
    {
        $grouped = array();
        foreach($products as $p)
        {
            $categories = $p->getCategories();

            if(sizeof($categories) <= 0)
            {
                continue;
            }

            $categoryId = $categories[0];
            $grouped[$categoryId]['products'][] = $p;

            if(!isset($grouped[$categoryId]['category']))
            {
                $c = new Category($categoryId, $this->context->language->id);
                $grouped[$categoryId]['category']= $c;
            }
        }
        return $grouped;
    }

    // Check templates/catalog/partials/miniatures/product.tpl for reference
    public function productsForTemplate($products)
    {
        if (!$products || !is_array($products))
            return array();
        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();

        $retriever = new ImageRetriever($this->context->link);
        $presenter = new ProductListingPresenter(
            $retriever,
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        $accesories_for_template = array();

        if (!is_array($products)) { return array(); }
        $groupedProducts = $this->groupProductsByCategory($products);
        foreach ($groupedProducts as $catId => $acc) {
            $cat = $acc['category'];
            $accesories_for_template[$catId]['category'] = $this->objectPresenter->present($cat);
            $accesories_for_template[$catId]['category']['image'] = $retriever->getImage($cat, $cat->id_image);
            $accesories_for_template[$catId]['category']['description'] = Category::getDescriptionClean($cat->description);
            $rawProducts = $acc['products'];

            foreach($rawProducts as $p)
            {
                $product = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($p->getFieldsShop()),
                    $this->context->language
                );
                $packaging = $this->getPackagingVariables($p->id);

                if($packaging)
                {
                    $product['packaging'] = $packaging['calculator_packaging'];
                }

                $accesories_for_template[$catId]['products'][] = $product;
            }
        }
        return $accesories_for_template;
    }

    public function getTemplateVarProduct($product_to_present)
    {
        $product = $this->objectPresenter->present($product_to_present);

        $product['id_product'] = (int)$product_to_present->id;
        $product['out_of_stock'] = (int)$product_to_present->out_of_stock;
        $product['new'] = (int)$product_to_present->new;

        $product_full = Product::getProductProperties($this->context->language->id, $product, $this->context);
        $assembler = new ProductAssembler($this->context);
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();

        $presenter = new ProductPresenter(
            new ImageRetriever($this->context->link),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        return $presenter->present(
            $presentationSettings,
            $assembler->assembleProduct($product_full),
            $this->context->language
        );

    }

    private function loadProductAccesories($id_product)
    {
        /*$sql = "SELECT * from $this->accesoriesTable
            WHERE id_product = $id_product";*/
        $sql = "SELECT * from $this->accesoriesTable
            WHERE id_product_1 = $id_product";
        if($result = DB::getInstance()->executeS($sql))
        {
            return $result;
        }

        return null;
    }

    public function loadCategoryDatas($id_category)
    {
        $id = (int) $id_category;
        if ($id <= 0) {
            return array(
                'unidad'   => '',
                'activado' => 0,
            );
        }
    
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'calculator`
                WHERE `id_category` = '.$id;
    
        if ($result = Db::getInstance()->getRow($sql)) {
            return $result;
        }
    
        return array(
            'unidad'   => '',
            'activado' => 0,
        );
    }


}

<?php

$_['nitropackio'] = array(
    'version' => '3.4',
    'modified_file' => 'catalog/controller/extension/module/nitropack.php',
    'oc' => array(
        'setting_code' => 'module_nitropack',
        'field' => array(
            'status' => 'module_nitropack_status'
        ),
        'route' => array(
            'extension' => 'marketplace/extension',
            'modification' => 'marketplace/modification'
        )
    ),
    'route' => array(
        'event' => array(
            'category' => 'extension/module/nitropack_event_category',
            'information' => 'extension/module/nitropack_event_information',
            'manufacturer' => 'extension/module/nitropack_event_manufacturer',
            'product' => 'extension/module/nitropack_event_product',
            'review' => 'extension/module/nitropack_event_review'
        ),
        'module' => array(
            'nitropack' => 'extension/module/nitropack'
        )
    ),
    'model' => array(
        'event' => array(
            'category' => 'model_extension_module_nitropack_event_category',
            'information' => 'model_extension_module_nitropack_event_information',
            'manufacturer' => 'model_extension_module_nitropack_event_manufacturer',
            'product' => 'model_extension_module_nitropack_event_product',
            'review' => 'model_extension_module_nitropack_event_review'
        ),
        'module' => array(
            'nitropack' => 'model_extension_module_nitropack'
        )
    ),
    'event' => array(
        // Catalog Cart
        'catalog/controller/common/cart/before' => array('extension/module/nitropack/cartPlaceholder'),
        // Catalog Products
        'catalog/model/catalog/product/getProduct/after' => array('extension/module/nitropack/afterGetProduct'),
        'catalog/model/catalog/product/getProducts/after' => array('extension/module/nitropack/afterGetProducts'),
        // Catalog Categories
        'catalog/model/catalog/category/getCategory/after' => array('extension/module/nitropack/afterGetCategory'),
        'catalog/model/catalog/category/getCategories/after' => array('extension/module/nitropack/afterGetCategories'),
        // Catalog Manufacturers
        'catalog/model/catalog/manufacturer/getManufacturer/after' => array('extension/module/nitropack/afterGetManufacturer'),
        'catalog/model/catalog/manufacturer/getManufacturers/after' => array('extension/module/nitropack/afterGetManufacturers'),
        // Catalog Informations
        'catalog/model/catalog/information/getInformation/after' => array('extension/module/nitropack/afterGetInformation'),
        'catalog/model/catalog/information/getInformations/after' => array('extension/module/nitropack/afterGetInformations'),
        // Order Histories
        'catalog/model/checkout/order/addOrderHistory/before' => array('extension/module/nitropack/beforeAddOrderHistory'),
        'catalog/model/checkout/order/addOrderHistory/after' => array('extension/module/nitropack/afterAddOrderHistory'),
        // Admin Products
        'admin/model/catalog/product/addProduct/after' => array('extension/module/nitropack/productAfterAdd'),
        'admin/model/catalog/product/editProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product/editProduct/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/catalog/product/deleteProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/catalog/product/deleteProduct/after' => array('extension/module/nitropack/productAfterDelete'),
        'admin/model/catalog/product/copyProduct/after' => array('extension/module/nitropack/productAfterCopy'),
        // Quick Edit Product
        'admin/model/extension/module/product_quick_edit/quickEditProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/extension/module/product_quick_edit/quickEditProduct/after' => array('extension/module/nitropack/productAfterEdit'),
        // Product Import Suite
        'admin/model/importer/product/addProduct/after' => array('extension/module/nitropack/productAfterCopy'),
        'admin/model/importer/product/editProduct/before' => array('extension/module/nitropack/productBeforePersist'),
        'admin/model/importer/product/editProduct/after' => array('extension/module/nitropack/productAfterEdit'),
        'admin/model/importer/product/addManufacturer/after' => array('extension/module/nitropack/manufacturerAfterAdd'),
        'admin/model/importer/product/addCategory/after' => array('extension/module/nitropack/categoryAfterAdd'),
        // Admin Categories
        'admin/model/catalog/category/addCategory/after' => array('extension/module/nitropack/categoryAfterAdd'),
        'admin/model/catalog/category/editCategory/before' => array('extension/module/nitropack/categoryBeforePersist'),
        'admin/model/catalog/category/editCategory/after' => array('extension/module/nitropack/categoryAfterEdit'),
        'admin/model/catalog/category/deleteCategory/before' => array('extension/module/nitropack/categoryBeforePersist'),
        'admin/model/catalog/category/deleteCategory/after' => array('extension/module/nitropack/categoryAfterDelete'),
        // Admin Manufacturers
        'admin/model/catalog/manufacturer/addManufacturer/after' => array('extension/module/nitropack/manufacturerAfterAdd'),
        'admin/model/catalog/manufacturer/editManufacturer/before' => array('extension/module/nitropack/manufacturerBeforePersist'),
        'admin/model/catalog/manufacturer/editManufacturer/after' => array('extension/module/nitropack/manufacturerAfterEdit'),
        'admin/model/catalog/manufacturer/deleteManufacturer/before' => array('extension/module/nitropack/manufacturerBeforePersist'),
        'admin/model/catalog/manufacturer/deleteManufacturer/after' => array('extension/module/nitropack/manufacturerAfterDelete'),
        // Admin Informations
        'admin/model/catalog/information/addInformation/after' => array('extension/module/nitropack/informationAfterAdd'),
        'admin/model/catalog/information/editInformation/before' => array('extension/module/nitropack/informationBeforePersist'),
        'admin/model/catalog/information/editInformation/after' => array('extension/module/nitropack/informationAfterEdit'),
        'admin/model/catalog/information/deleteInformation/before' => array('extension/module/nitropack/informationBeforePersist'),
        'admin/model/catalog/information/deleteInformation/after' => array('extension/module/nitropack/informationAfterDelete'),
        // Admin Reviews
        'admin/model/catalog/review/addReview/after' => array('extension/module/nitropack/reviewAfterAdd'),
        'admin/model/catalog/review/editReview/before' => array('extension/module/nitropack/reviewBeforePersist'),
        'admin/model/catalog/review/editReview/after' => array('extension/module/nitropack/reviewAfterEdit'),
        'admin/model/catalog/review/deleteReview/before' => array('extension/module/nitropack/reviewBeforePersist'),
        'admin/model/catalog/review/deleteReview/after' => array('extension/module/nitropack/reviewAfterDelete'),
        // Admin Languages
        'admin/model/localisation/language/addLanguage/after' => array('extension/module/nitropack/updateActiveLanguages'),
        'admin/model/localisation/language/editLanguage/after' => array('extension/module/nitropack/updateActiveLanguages'),
        'admin/model/localisation/language/deleteLanguage/after' => array('extension/module/nitropack/updateActiveLanguages'),
        // Admin Settings
        'admin/model/setting/setting/editSetting/after' => array('extension/module/nitropack/updateDefaults'),
        // Admin Menu
        'admin/view/common/column_left/before' => array('extension/module/nitropack/menuItem'),
    )
);

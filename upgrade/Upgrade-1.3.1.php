<?php

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_1_3_1($object)
{
    return $object->unregisterHook('displayShoppingCartFooter') &&
        $object->registerHook('displayShoppingCart');
}

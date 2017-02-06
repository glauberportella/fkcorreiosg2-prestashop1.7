<?php

class ProductController extends ProductControllerCore {
	
	public function initContent() {
		parent::initContent();

        $this->context->smarty->assign(array(
            'HOOK_EXTRA_RIGHT' => Hook::exec('displayRightColumnProduct', array('product' => $this->product, 'category' => $this->category)),
        ));

	}
    
}
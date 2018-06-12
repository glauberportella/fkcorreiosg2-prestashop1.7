<?php

class OrderController extends OrderControllerCore {
	
	public function initContent() {
		parent::initContent();

        if ((int)$this->step == 2) {
            Hook::exec('displayBeforeCarrier');
            $this->setTemplate(_PS_THEME_DIR_.'order-carrier.tpl');
        }
	

	}
}
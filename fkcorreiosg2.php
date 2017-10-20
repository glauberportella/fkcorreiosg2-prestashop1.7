<?php

include_once(dirname(__FILE__).'/models/FKcorreiosg2Class.php');
include_once(dirname(__FILE__).'/models/FKcorreiosg2FreteClass.php');

class fkcorreiosg2 extends CarrierModule {

    // Contem o id do Carrier em execucao
    public $id_carrier;

    private $prazoEntrega = array();

    private $html = '';
    private $postErrors = array();
    private $tab_select = '';

    public function __construct() {

        $this->name     = 'fkcorreiosg2';
        $this->tab      = 'shipping_logistics';
        $this->version  = '1.3.1';
        $this->author   = 'módulosFK';

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('FKcorreios - PS 1.7');
        $this->description = $this->l('Oferece aos clientes diversos meios para envio dos produtos. Módulo FKCorreios G2 portado para Prestashop 1.7.');

        // Array com nome das classes do menu
        $this->_tabClassName['principal'] = array('className' => 'AdminFKcorreiosg2', 'name' => 'FKcorreios-G2');

        // URL/URI que variam conforme endereco do dominio
        Configuration::updateValue('FKCORREIOSG2_URL_IMG', Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/img/');
        Configuration::updateValue('FKCORREIOSG2_URL_FUNCOES', Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/funcoes.php');
        Configuration::updateValue('FKCORREIOSG2_URL_FUNCOES_RASTREIO', __PS_BASE_URI__.'modules/'.$this->name.'/funcoes.php');
        Configuration::updateValue('FKCORREIOSG2_URL_LOGO_PS', Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'img/s/');
        Configuration::updateValue('FKCORREIOSG2_URI_LOGO_PS', _PS_SHIP_IMG_DIR_);
        Configuration::updateValue('FKCORREIOSG2_URI_LOGO_PS_TMP', _PS_TMP_IMG_DIR_.'carrier_mini_');

        // Atualiza cookie do CEP
        if (Tools::getValue('origem') == 'adicCarrinho') {
            $this->context->cookie->fkcorreiosg2_cep_destino = Tools::getValue('cep');
        }else {
            if (Tools::isSubmit('btnSubmit')) {
                $this->context->cookie->fkcorreiosg2_cep_destino = Tools::getValue('fkcorreiosg2_cep');
            }
        }

    }

    public function install() {

        if (!parent::install()
            Or !$this->criaMenus()
            Or !$this->criaTabelas()
            Or !$this->registerHook('displayHeader')
            Or !$this->registerHook('actionCarrierUpdate')
            Or !$this->registerHook('displayBeforeCarrier')
            Or !$this->registerHook('displayRightColumnProduct')
            Or !$this->registerHook('displayProductButtons')
            Or !$this->registerHook('displayFooterProduct')
            Or !$this->registerHook('displayShoppingCart')
            //Or !$this->registerHook('displayShoppingCartFooter')
            Or !$this->registerHook('displayLeftColumn')
            Or !$this->registerHook('displayRightColumn')
            Or !$this->registerHook('displayFooter')
            Or !$this->registerHook('displayCustomerAccount')) {

            return false;
        }

        // Atualiza configuracoes se nao existit
        if (!Configuration::hasKey('FKCORREIOSG2_MEU_CEP')) {
            Configuration::updateValue('FKCORREIOSG2_MEU_CEP', '');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_CEP_CIDADE')) {
            Configuration::updateValue('FKCORREIOSG2_CEP_CIDADE', '');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_MAO_PROPRIA')) {
            Configuration::updateValue('FKCORREIOSG2_MAO_PROPRIA', '');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_VALOR_DECLARADO')) {
            Configuration::updateValue('FKCORREIOSG2_VALOR_DECLARADO', '');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_AVISO_RECEBIMENTO')) {
            Configuration::updateValue('FKCORREIOSG2_AVISO_RECEBIMENTO', '');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_TEMPO_PREPARACAO')) {
            Configuration::updateValue('FKCORREIOSG2_TEMPO_PREPARACAO', '0');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_EMBALAGEM')) {
            Configuration::updateValue('FKCORREIOSG2_EMBALAGEM', '2');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_OFFLINE')) {
            Configuration::updateValue('FKCORREIOSG2_OFFLINE', '');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_FRETE_GRATIS_DEMAIS_TRANSP')) {
            Configuration::updateValue('FKCORREIOSG2_FRETE_GRATIS_DEMAIS_TRANSP', 'on');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_BLOCO_PRODUTO')) {
            Configuration::updateValue('FKCORREIOSG2_BLOCO_PRODUTO', 'on');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_BLOCO_PRODUTO_POSICAO')) {
            Configuration::updateValue('FKCORREIOSG2_BLOCO_PRODUTO_POSICAO', '0');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_BLOCO_PRODUTO_LIGHTBOX')) {
            Configuration::updateValue('FKCORREIOSG2_BLOCO_PRODUTO_LIGHTBOX', '');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_BLOCO_CARRINHO')) {
            Configuration::updateValue('FKCORREIOSG2_BLOCO_CARRINHO', 'on');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_MSG_CORREIOS')) {
            Configuration::updateValue('FKCORREIOSG2_MSG_CORREIOS', 'on');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_BORDA')) {
            Configuration::updateValue('FKCORREIOSG2_BORDA', '1px solid #d6d4d4');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_RAIO_BORDA')) {
            Configuration::updateValue('FKCORREIOSG2_RAIO_BORDA', '5px');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_COR_FUNDO')) {
            Configuration::updateValue('FKCORREIOSG2_COR_FUNDO', '#ffffff');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_COR_FONTE_TITULO')) {
            Configuration::updateValue('FKCORREIOSG2_COR_FONTE_TITULO', '#7777777');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_COR_BOTAO')) {
            Configuration::updateValue('FKCORREIOSG2_COR_BOTAO', '#43b754');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_COR_FONTE_BOTAO')) {
            Configuration::updateValue('FKCORREIOSG2_COR_FONTE_BOTAO', '#ffffff');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_COR_FAIXA_MSG')) {
            Configuration::updateValue('FKCORREIOSG2_COR_FAIXA_MSG', '#43b754');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_COR_FONTE_MSG')) {
            Configuration::updateValue('FKCORREIOSG2_COR_FONTE_MSG', '#ffffff');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_LARGURA')) {
            Configuration::updateValue('FKCORREIOSG2_LARGURA', '50%');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_RASTREIO_LEFT')) {
            Configuration::updateValue('FKCORREIOSG2_RASTREIO_LEFT', '');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_RASTREIO_RIGHT')) {
            Configuration::updateValue('FKCORREIOSG2_RASTREIO_RIGHT', '');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_RASTREIO_FOOTER')) {
            Configuration::updateValue('FKCORREIOSG2_RASTREIO_FOOTER', '');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_RASTREIO_ACCOUNT')) {
            Configuration::updateValue('FKCORREIOSG2_RASTREIO_ACCOUNT', '');
        }

        if (!Configuration::hasKey('FKCORREIOSG2_EXCLUIR_CONFIG')) {
            Configuration::updateValue('FKCORREIOSG2_EXCLUIR_CONFIG', '');
        }

        Configuration::updateValue('FKCORREIOSG2_URL_WS_CORREIOS', 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL');
        Configuration::updateValue('FKCORREIOSG2_URL_RASTREIO_CORREIOS', 'http://websro.correios.com.br/sro_bin/txect01%24.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=@');

        // Processa atualizacao de versao
        if (!$this->atualizaVersaoModulo()) {
            $this->_errors[] = Tools::displayError('Erro durante atualização da versão do módulo.');
            return false;
        }

        return true;

    }

    public function uninstall() {

        // Desinstala Complementos
        $complementos = $this->recuperaComplementosInstalados();

        foreach ($complementos as $reg) {

            if (module::isInstalled($reg['modulo'])) {
                $modulo = module::getInstanceByName($reg['modulo']);
                $modulo->uninstall();
            }
        }

        // Recupera servicos
        $servicos = $this->recuperaServicosCorreios();

        // Instacia FKcorreiosClass
        $fkclass = new FKcorreiosg2Class();

        if (!parent::uninstall()
            Or !$this->excluiMenus()
            Or !$fkclass->desinstalaCarrier($servicos)
            Or !$this->unregisterHook('displayHeader')
            Or !$this->unregisterHook('actionCarrierUpdate')
            Or !$this->unregisterHook('displayBeforeCarrier')
            Or !$this->unregisterHook('displayRightColumnProduct')
            Or !$this->unregisterHook('displayProductButtons')
            Or !$this->unregisterHook('displayFooterProduct')
            Or !$this->unregisterHook('displayShoppingCart')
            //Or !$this->unregisterHook('displayShoppingCartFooter')
            Or !$this->unregisterHook('displayLeftColumn')
            Or !$this->unregisterHook('displayRightColumn')
            Or !$this->unregisterHook('displayFooter')
            Or !$this->unregisterHook('displayCustomerAccount')) {

            return false;
        }

        if (Configuration::get('FKCORREIOSG2_EXCLUIR_CONFIG') == 'on') {
            // Exclui tabelas
            $this->excluiTabelas();

            // Exclui dados de Configuração
            if (!Db::getInstance()->delete("configuration", "name LIKE 'FKCORREIOSG2_%'")) {
                return false;
            }

        }

        return true;

    }

    public function hookdisplayHeader($params) {
        // CSS
        $this->context->controller->registerStylesheet('fkcorreiosg2_front_css', $this->_path.'css/fkcorreiosg2_front.css');

        // JS
        $this->context->controller->registerJavascript('fkcorreiosg2_fancybox_js', _PS_JS_DIR_.'jquery/plugins/fancybox/jquery.fancybox.js');

        // Adiciona Fancybox caso QuickView esteja desativado
        if (!Configuration::get('PS_QUICK_VIEW')) {
            $this->context->controller->addjqueryPlugin('fancybox');
        }

        $this->context->controller->registerJavascript('fkcorreiosg2_fancybox_js', $this->_path.'js/fkcorreiosg2_fancybox.js');
        $this->context->controller->registerJavascript('fkcorreiosg2_front_js', $this->_path.'js/fkcorreiosg2_front.js');
        $this->context->controller->registerJavascript('fkcorreiosg2_maskedinput_js', $this->_path.'js/jquery.maskedinput.js');

    }

    public function hookdisplayBeforeCarrier($params) {

        if (!isset($this->context->smarty->tpl_vars['delivery_option_list'])) {
            return;
        }

        $delivery_option_list = $this->context->smarty->tpl_vars['delivery_option_list'];

        foreach ($delivery_option_list->value as $id_address) {

            foreach ($id_address as $key) {

                foreach ($key['carrier_list'] as $id_carrier) {

                    if (isset($this->prazoEntrega[$id_carrier['instance']->id])) {

                        if (is_numeric($this->prazoEntrega[$id_carrier['instance']->id])) {

                            if ($this->prazoEntrega[$id_carrier['instance']->id] == 0) {
                                $msg = $this->l('entrega no mesmo dia');
                            }else {
                                if ($this->prazoEntrega[$id_carrier['instance']->id] > 1) {
                                    $msg = 'entrega em até '.$this->prazoEntrega[$id_carrier['instance']->id].$this->l(' dias úteis');
                                }else {
                                    $msg = 'entrega em '.$this->prazoEntrega[$id_carrier['instance']->id].$this->l(' dia útil');
                                }
                            }
                        }else {
                            $msg = $this->prazoEntrega[$id_carrier['instance']->id];
                        }

                        $id_carrier['instance']->delay[$this->context->cart->id_lang] = $msg;
                    }
                }
            }
        }

    }

    public function hookactionCarrierUpdate($params) {

        $atualizado = false;

        // Recupera dados da tabela
        $sql = 'SELECT *
                FROM '._DB_PREFIX_.'fkcorreiosg2_servicos
                WHERE id_carrier = '.(int)$params['id_carrier'];

        $servicos = Db::getInstance()->getRow($sql);

        // Verifica se houve alteracao no id
        if ((int)$servicos['id_carrier'] != (int)$params['carrier']->id) {
            $novoId = $params['carrier']->id;
            $atualizado = true;
        }else {
            $novoId = $servicos['id_carrier'];
        }

        // Verifica se houve alteracao na grade
        if ((int)$servicos['grade'] != (int)$params['carrier']->grade) {
            $novaGrade = $params['carrier']->grade;
            $atualizado = true;
        }else {
            $novaGrade = $servicos['grade'];
        }

        // Verifica se houve alteracao no campo ativo
        if ($servicos['ativo'] != $params['carrier']->active) {
            $novoAtivo = $params['carrier']->active;
            $atualizado = true;
        }else {
            $novoAtivo = $servicos['ativo'];
        }

        if ($atualizado == true) {

            // Atualiza dados da tabela de servicos
            $dados = array(
                'id_carrier'    => $novoId,
                'grade'         => $novaGrade,
                'ativo'         => $novoAtivo
            );

            Db::getInstance()->update('fkcorreiosg2_servicos', $dados, 'id_carrier = '.(int)$servicos['id_carrier']);

            // Atualiza dados da tabela de frete gratis
            $dados = array(
                'id_carrier'    => $novoId,
            );

            Db::getInstance()->update('fkcorreiosg2_frete_gratis', $dados, 'id_carrier = '.(int)$servicos['id_carrier']);
        }

    }

    public function hookdisplayRightColumnProduct($params) {

        if (!$this->processaSimulador('produto', '0', $params)) {
            return false;
        }

        //return $this->display(__FILE__, 'views/front/simuladorAposDescResumida.tpl');
        return $this->context->smarty->fetch('module:fkcorreiosg2/views/front/simuladorAposDescResumida.tpl');
    }

    public function hookdisplayProductButtons($params) {

        if (!$this->processaSimulador('produto', '2', $params)) {
            return false;
        }
        //return $this->display(__FILE__, 'views/front/simuladorBoxAdicCarrinho.tpl');
        return $this->context->smarty->fetch('module:fkcorreiosg2/views/front/simuladorBoxAdicCarrinho.tpl');
    }

    public function hookdisplayFooterProduct($params) {

        if (!$this->processaSimulador('produto', '1', $params)) {
            return false;
        }

        return $this->context->smarty->fetch('module:fkcorreiosg2/views/front/simuladorAposDescDetalhada.tpl');

    }

    public function hookdisplayShoppingCart($params) {
        if (!$this->processaSimulador('carrinho', '', $params)) {
            return false;
        }

        return $this->context->smarty->fetch('module:fkcorreiosg2/views/front/simuladorCarrinho.tpl');
    }

    public function hookdisplayLeftColumn($params) {

        // Retorna se nao for para mostrar na coluna esquerda
        if (Configuration::get('FKCORREIOSG2_RASTREIO_LEFT') != 'on') {
            return false;
        }

        // Grava dados no smarty
        $this->gravaDadosSmartyRastreio();

        return $this->context->smarty->fetch('module:fkcorreiosg2/views/front/rastreioColLeft.tpl');
    }

    public function hookdisplayRightColumn($params) {

        // Retorna se nao for para mostrar na coluna direita
        if (Configuration::get('FKCORREIOSG2_RASTREIO_RIGHT') != 'on') {
            return false;
        }

        // Grava dados no smarty
        $this->gravaDadosSmartyRastreio();

        return $this->context->smarty->fetch('module:fkcorreiosg2/views/front/rastreioColRight.tpl');
    }

    public function hookdisplayFooter($params) {

        // Retorna se nao for para mostrar no footer
        if (Configuration::get('FKCORREIOSG2_RASTREIO_FOOTER') != 'on') {
            return false;
        }

        // Grava dados no smarty
        $this->gravaDadosSmartyRastreio();

        return $this->context->smarty->fetch('module:fkcorreiosg2/views/front/rastreioFooter.tpl');
    }

    public function hookdisplayCustomerAccount($params) {

        // Retorna se nao for para mostrar no account
        if (Configuration::get('FKCORREIOSG2_RASTREIO_ACCOUNT') != 'on') {
            return false;
        }

        // Grava dados no smarty
        $this->gravaDadosSmartyRastreio();

        return $this->context->smarty->fetch('module:fkcorreiosg2/views/front/rastreioAccount.tpl');
    }

    public function getContent() {

        if (!empty($_POST)) {

            $this->postValidation();

            if (sizeof($this->postErrors)) {
                foreach ($this->postErrors AS $err) {
                    $this->html .= $this->displayError($err);
                }
            }
        }

        $this->html .= $this->renderForm();

        return $this->html;

    }

    private function renderForm() {

        // CSS
        $this->context->controller->addCSS($this->_path.'css/fkcorreiosg2_admin.css');

        // JS
        $this->context->controller->addJS($this->_path.'js/fkcorreiosg2_admin.js');
        $this->context->controller->addJS($this->_path.'js/ajaxq.js');
        $this->context->controller->addJS($this->_path.'js/jquery.maskedinput.js');

        $this->configGeral();
        $this->cadastroCep();
        $this->cadastroEmbalagens();
        $this->especificacoesCorreios();
        $this->servicosCorreios();
        $this->freteGratis();
        $this->tabOffline();
        $this->compInstalados();
        $this->infConfiguracao();

        $this->context->smarty->assign(array(
            'fkcorreiosg2' => array(
                'pathInclude'   => _PS_MODULE_DIR_.$this->name.'/views/config/',
                'tabSelect'     => $this->tab_select,
            )

        ));

        return $this->display(__FILE__, 'views/config/mainConfig.tpl');
    }

    private function configGeral() {

        // TPL a ser utilizado
        $name_tpl ='configGeral.tpl';

        $this->smarty->assign(array(
            'tab_2' => array(
                'nameTpl'                                   => $name_tpl,
                'formAction'                                => Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']),
                'fkcorreiosg2_meu_cep'                      => Tools::getValue('fkcorreiosg2_meu_cep', Configuration::get('FKCORREIOSG2_MEU_CEP')),
                'fkcorreiosg2_cep_cidade'                   => Tools::getValue('fkcorreiosg2_cep_cidade', Configuration::get('FKCORREIOSG2_CEP_CIDADE')),
                'fkcorreiosg2_mao_propria'                  => Tools::getValue('fkcorreiosg2_mao_propria', Configuration::get('FKCORREIOSG2_MAO_PROPRIA')),
                'fkcorreiosg2_valor_declarado'              => Tools::getValue('fkcorreiosg2_valor_declarado', Configuration::get('FKCORREIOSG2_VALOR_DECLARADO')),
                'fkcorreiosg2_aviso_recebimento'            => Tools::getValue('fkcorreiosg2_aviso_recebimento', Configuration::get('FKCORREIOSG2_AVISO_RECEBIMENTO')),
                'fkcorreiosg2_tempo_preparacao'             => Tools::getValue('fkcorreiosg2_tempo_preparacao', Configuration::get('FKCORREIOSG2_TEMPO_PREPARACAO')),
                'fkcorreiosg2_embalagem'                    => Tools::getValue('fkcorreiosg2_embalagem', Configuration::get('FKCORREIOSG2_EMBALAGEM')),
                'fkcorreiosg2_offline'                      => Tools::getValue('fkcorreiosg2_offline', Configuration::get('FKCORREIOSG2_OFFLINE')),
                'fkcorreiosg2_frete_gratis_demais_transp'   => Tools::getValue('fkcorreiosg2_frete_gratis_demais_transp', Configuration::get('FKCORREIOSG2_FRETE_GRATIS_DEMAIS_TRANSP')),
                'fkcorreiosg2_bloco_produto'                => Tools::getValue('fkcorreiosg2_bloco_produto', Configuration::get('FKCORREIOSG2_BLOCO_PRODUTO')),
                'fkcorreiosg2_bloco_produto_posicao'        => Tools::getValue('fkcorreiosg2_bloco_produto_posicao', Configuration::get('FKCORREIOSG2_BLOCO_PRODUTO_POSICAO')),
                'fkcorreiosg2_bloco_produto_lightbox'       => Tools::getValue('fkcorreiosg2_bloco_produto_lightbox', Configuration::get('FKCORREIOSG2_BLOCO_PRODUTO_LIGHTBOX')),
                'fkcorreiosg2_bloco_carrinho'               => Tools::getValue('fkcorreiosg2_bloco_carrinho', Configuration::get('FKCORREIOSG2_BLOCO_CARRINHO')),
                'fkcorreiosg2_msg_correios'                 => Tools::getValue('fkcorreiosg2_msg_correios', Configuration::get('FKCORREIOSG2_MSG_CORREIOS')),
                'fkcorreiosg2_borda'                        => Tools::getValue('fkcorreiosg2_borda', Configuration::get('FKCORREIOSG2_BORDA')),
                'fkcorreiosg2_raio_borda'                   => Tools::getValue('fkcorreiosg2_raio_borda', Configuration::get('FKCORREIOSG2_RAIO_BORDA')),
                'fkcorreiosg2_cor_fundo'                    => Tools::getValue('fkcorreiosg2_cor_fundo', Configuration::get('FKCORREIOSG2_COR_FUNDO')),
                'fkcorreiosg2_cor_fonte_titulo'             => Tools::getValue('fkcorreiosg2_cor_fonte_titulo', Configuration::get('FKCORREIOSG2_COR_FONTE_TITULO')),
                'fkcorreiosg2_cor_botao'                    => Tools::getValue('fkcorreiosg2_cor_botao', Configuration::get('FKCORREIOSG2_COR_BOTAO')),
                'fkcorreiosg2_cor_fonte_botao'              => Tools::getValue('fkcorreiosg2_cor_fonte_botao', Configuration::get('FKCORREIOSG2_COR_FONTE_BOTAO')),
                'fkcorreiosg2_cor_faixa_msg'                => Tools::getValue('fkcorreiosg2_cor_faixa_msg', Configuration::get('FKCORREIOSG2_COR_FAIXA_MSG')),
                'fkcorreiosg2_cor_fonte_msg'                => Tools::getValue('fkcorreiosg2_cor_fonte_msg', Configuration::get('FKCORREIOSG2_COR_FONTE_MSG')),
                'fkcorreiosg2_largura'                      => Tools::getValue('fkcorreiosg2_largura', Configuration::get('FKCORREIOSG2_LARGURA')),
                'fkcorreiosg2_bloco_rastreio_left'          => Tools::getValue('fkcorreiosg2_bloco_rastreio_left', Configuration::get('FKCORREIOSG2_RASTREIO_LEFT')),
                'fkcorreiosg2_bloco_rastreio_right'         => Tools::getValue('fkcorreiosg2_bloco_rastreio_right', Configuration::get('FKCORREIOSG2_RASTREIO_RIGHT')),
                'fkcorreiosg2_bloco_rastreio_footer'        => Tools::getValue('fkcorreiosg2_bloco_rastreio_footer', Configuration::get('FKCORREIOSG2_RASTREIO_FOOTER')),
                'fkcorreiosg2_bloco_rastreio_account'       => Tools::getValue('fkcorreiosg2_bloco_rastreio_account', Configuration::get('FKCORREIOSG2_RASTREIO_ACCOUNT')),
                'fkcorreiosg2_excluir_config'               => Tools::getValue('fkcorreiosg2_excluir_config', Configuration::get('FKCORREIOSG2_EXCLUIR_CONFIG')),
            )
        ));

    }

    private function cadastroCep() {

        // TPL a ser utilizado
        $name_tpl ='cadastroCep.tpl';

        // Recupera dados da tabela
        $cadCep = $this->recuperaCadastroCep();

        // Inclui registros se nao existir
        if (!$cadCep) {
            $this->incluiCadastroCep();

            // Recupera dados incluidos na tabelas
            $cadCep = $this->recuperaCadastroCep();
        }

        $this->smarty->assign(array(
            'tab_3' => array(
                'nameTpl'       => $name_tpl,
                'formAction'    => Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']),
                'cadastro_cep'  => $cadCep,
            )
        ));

    }

    private function cadastroEmbalagens() {

        // TPL a ser utilizado
        $name_tpl ='cadastroEmbalagens.tpl';

        // Recupera dados da tabela
        $cadEmbalagens = $this->recuperaCadastroEmbalagens();

        if (!$cadEmbalagens) {
            $this->incluiEmbalagensIniciais();

            // Recupera dados da tabela
            $cadEmbalagens = $this->recuperaCadastroEmbalagens();
        }

        $this->smarty->assign(array(
            'tab_4' => array(
                'nameTpl'       => $name_tpl,
                'formAction'    => Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']),
                'cadastro_embalagens'  => $cadEmbalagens,
            )
        ));

    }

    private function especificacoesCorreios() {

        // TPL a ser utilizado
        $name_tpl ='especificacoesCorreios.tpl';

        // Recupera dados da tabela
        $espCorreios = $this->recuperaEspCorreios();

        // Inclui os registros se nao existir
        if (!$espCorreios) {
            $this->incluiEspecificacoesCorreios();

            // Recupera dados incluidos na tabela
            $espCorreios = $this->recuperaEspCorreios();
        }

        $this->smarty->assign(array(
            'tab_5' => array(
                'nameTpl'                   => $name_tpl,
                'formAction'                => Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']),
                'especificacoes_correios'   => $espCorreios,
            )
        ));

    }

    private function servicosCorreios() {

        // TPL a ser utilizado
        $name_tpl ='servicosCorreios.tpl';

        // Recupera dados da tabela
        $servicos = $this->recuperaServicosCorreios();

        // Inclui os registros se não existir
        if (!$servicos) {
            $this->incluiServicos();

            // Recupera dados incluidos na tabela
            $servicos = $this->recuperaServicosCorreios();
        }else {
            // Verifica e recupera carrier excluidos manualmente via opcao do Prestashop
            $fkClass = new FKcorreiosg2Class();
            $fkClass->recuperaCarrierExcluido($servicos);
        }

        // Instancia FKcorreiosClass
        $fkClass = new FKcorreiosg2Class();

        $this->smarty->assign(array(
            'tab_6' => array(
                'nameTpl'       => $name_tpl,
                'formAction'    => Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']),
                'servicos'      => $servicos,
                'arrayUF'       => $fkClass->criaArrayUF($servicos),
                'urlLogoPS'     => Configuration::get('FKCORREIOSG2_URL_LOGO_PS'),
                'uriLogoPS'     => Configuration::get('FKCORREIOSG2_URI_LOGO_PS'),
                'urlImg'        => Configuration::get('FKCORREIOSG2_URL_IMG'),
            )
        ));

    }

    private function freteGratis() {

        // TPL a ser utilizado
        $name_tpl ='freteGratis.tpl';

        // Recupera dados da tabela de Frete Gratis
        $regioes = $this->recuperaRegioesFreteGratis();

        // Recupera Servicos dos Correios e dos Complementos
        $transp = $this->recuperaTranspFreteGratis();

        // Instancia FKcorreiosg2Class
        $fkClass = new FKcorreiosg2Class();

        $this->smarty->assign(array(
            'tab_7' => array(
                'nameTpl'           => $name_tpl,
                'formAction'        => Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']),
                'regioes'           => $regioes,
                'arrayUF'           => $fkClass->criaArrayUF($regioes),
                'transportadoras'   => $transp,
            )
        ));

    }

    private function tabOffline() {

        // TPL a ser utilizado
        $name_tpl ='tabOffline.tpl';

        // Recupera dados da tabela de Especificacao dos Correios que devem gerar Tabelas Offline
        $espCorreios = $this->recuperaEspCorreiosTabOffline();

        // Recupera dados da tabela de Tabelas Offline - Estados
        $tabOfflineEstados = $this->recuperaTabOffline();

        // Inclui registros se nao existir
        if (!$tabOfflineEstados) {
            $this->incluiTabOffline();

            // Recupera dados da tabela de Tabelas Offline - Estados
            $tabOfflineEstados = $this->recuperaTabOffline();
        }

        // Recupera dados da tabela de Tabelas Offline - Minha Cidade
        $tabOfflineCidade = $this->recuperaTabOffline(true);

        $this->smarty->assign(array(
            'tab_8' => array(
                'nameTpl'                   => $name_tpl,
                'formAction'                => Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']),
                'urlImg'                    => Configuration::get('FKCORREIOSG2_URL_IMG'),
                'urlFuncoes'                => Configuration::get('FKCORREIOSG2_URL_FUNCOES'),
                'especificacoesCorreios'    => $espCorreios,
                'tabOfflineEstados'         => $tabOfflineEstados,
                'tabOfflineCidade'          => $tabOfflineCidade,
            )
        ));

    }

    private function compInstalados() {

        // TPL a ser utilizado
        $name_tpl ='compInstalados.tpl';

        // Recupera complementos instalados
        $complementos = $this->recuperaComplementosInstalados();

        $this->smarty->assign(array(
            'tab_9' => array(
                'nameTpl'       => $name_tpl,
                'complementos'  => $complementos,
            )
        ));

    }

    private function infConfiguracao() {

        // TPL a ser utilizado
        $name_tpl ='infConfiguracao.tpl';

        // Verifica SOAP
        $soap = true;
        $msgSoap = 'Habilite o SOAP em seu PHP';

        if (!extension_loaded('soap')) {
            $soap = false;
            $msgSoap = 'Habilite o SOAP em seu PHP';
        }

        // Verifica Modulos Nativos
        $modulosNativos = true;
        $msgModulosNativos = 'A execução de Módulos não Nativos está desabilitada. Habilite a execução de Módulos não Nativos.';

        if (Configuration::get('PS_DISABLE_NON_NATIVE_MODULE') == '1') {
            $modulosNativos = false;
        }

        // Verifica Overrides
        $overrides = true;
        $msgOverrides = 'A execução de Overrides está desabilitada. Habilite a execução de Overrides.';

        if (Configuration::get('PS_DISABLE_OVERRIDES') == '1') {
            $overrides = false;
        }

        // Verifica Custos de Envio
        $custosEnvio = true;
        $msgCustosEnvio = 'Existe Custo de Envio Adicional definido, caso não utilize coloque 0 (zero) no referido campo';

        if (Configuration::get('PS_SHIPPING_HANDLING') > 0) {
            $custosEnvio = false;
        }

        // Verifica Frete Gratis por valor
        $freteGratisValor = true;
        $msgFreteGratisValor = 'Existe Frete Grátis por Valor definido. Coloque 0 (zero) no referido campo';

        if (Configuration::get('PS_SHIPPING_FREE_PRICE') > 0) {
            $freteGratisValor = false;
        }

        // Verifica Frete Gratis por peso
        $freteGratisPeso = true;
        $msgFreteGratisPeso = 'Existe Frete Grátis por Peso definido. Coloque 0 (zero) no referido campo';

        if (Configuration::get('PS_SHIPPING_FREE_WEIGHT') > 0) {
            $freteGratisPeso = false;
        }

        // Verifica Regioes
        $regioes = true;
        $msgRegioes = 'Existem problemas no relacionamento das Regiões entre País, Estados e Transportadoras ou a Região em uso está inativa';

        $sql = "SELECT id_country, id_zone
                FROM "._DB_PREFIX_."country
                WHERE iso_code = 'BR' OR iso_code = 'br'";

        $pais = Db::getInstance()->getRow($sql);

        $sql = "SELECT id_state
                FROM "._DB_PREFIX_."state
                WHERE id_country = ".(int)$pais['id_country']." AND id_zone <> ".(int)$pais['id_zone'];

        $dados = Db::getInstance()->executeS($sql);

        if ($dados) {
            // Se os Estados estiverem com Regiao diferente do Pais
            $regioes = false;
        }else {
            // Verifica se existem Servicos Ativos
            $sql = "SELECT id
                    FROM "._DB_PREFIX_."fkcorreiosg2_servicos
                    WHERE ativo = 1";

            $dados = Db::getInstance()->executeS($sql);

            if ($dados) {

                $sql = "SELECT  "._DB_PREFIX_."carrier.id_carrier
                    FROM "._DB_PREFIX_."carrier
                        INNER JOIN "._DB_PREFIX_."carrier_zone
                            ON "._DB_PREFIX_."carrier.id_carrier = "._DB_PREFIX_."carrier_zone.id_carrier
                    WHERE "._DB_PREFIX_."carrier.active = 1 AND "._DB_PREFIX_."carrier.deleted = 0 AND "._DB_PREFIX_."carrier_zone.id_zone = ".(int)$pais['id_zone'];

                $dados = Db::getInstance()->executeS($sql);

                if (!$dados) {
                    // Se nao tiver transportadoras ativas definidas para a Regiao
                    $regioes = false;
                }else {
                    $sql = "SELECT id_zone
                        FROM "._DB_PREFIX_."zone
                        WHERE active = 1 AND id_zone = ".(int)$pais['id_zone'];

                    $dados = Db::getInstance()->executeS($sql);

                    if (!$dados) {
                        // Se a Regiao estiver inativa
                        $regioes = false;
                    }
                }
            }

        }

        // Verifica dimensoes e peso de produtos zerados
        $dimPesoZero = true;
        $msgDimPesoZero = 'Existem produtos cadastrados sem dimensões ou peso';

        $sql = "SELECT id_product
                FROM "._DB_PREFIX_."product
                WHERE width = 0 OR height = 0 OR depth = 0 OR weight = 0";

        $dados = Db::getInstance()->executeS($sql);

        if ($dados) {
            $dimPesoZero = false;
        }

        // Verifica dimensoes e peso acima do permitido
        $dimPesoMaior = true;
        $msgDimPesoMaior = 'Existem produtos com dimensões ou peso acima do permitido pelos Serviços dos Correios. Considere normal se utilizar outra transportadora para envio destes produtos';

        $sql = "SELECT
                  MAX("._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.comprimento_max) AS comprimento_max,
                  MAX("._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.largura_max) AS largura_max,
                  MAX("._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.altura_max) AS altura_max,
                  MAX("._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.peso_estadual_max) AS peso_max
                FROM "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios
                  INNER JOIN "._DB_PREFIX_."fkcorreiosg2_servicos
                    ON "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.id = "._DB_PREFIX_."fkcorreiosg2_servicos.id_especificacao
                WHERE "._DB_PREFIX_."fkcorreiosg2_servicos.ativo = 1";

        $dimPeso = Db::getInstance()->getRow($sql);

        if ($dimPeso['largura_max'] and $dimPeso['altura_max'] and $dimPeso['comprimento_max'] and $dimPeso['peso_max']) {

            $sql = "SELECT id_product
                    FROM "._DB_PREFIX_."product
                    WHERE width > ".$dimPeso['largura_max']." OR
                          height > ".$dimPeso['altura_max']." OR
                          depth > ".$dimPeso['comprimento_max']." OR
                          weight > ".$dimPeso['peso_max'];

            $dados = Db::getInstance()->executeS($sql);

            if ($dados) {
                $dimPesoMaior = false;
            }
        }

        // Verifica Gestao Avancada Estoque
        $gestaoAvancadaEstoque = true;
        $msgGestaoAvancadaEstoque = 'A opção Gestão Avançada de Estoque está ativada. Caso na Finalização da Compra não esteja sendo disponibilizada transportadoras, desative a opção ou configure corretamente';

        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $gestaoAvancadaEstoque = false;
        }

        // Verifica Meu CEP
        $meuCep = true;
        $msgMeuCep = 'Campo Meu Cep não preenchido';

        if (Configuration::get('FKCORREIOSG2_MEU_CEP') == '') {
            $meuCep = false;
        }

        // Verifica Minha Cidade
        $minhaCidade = true;
        $msgMinhaCidade = 'Campo Minha Cidade não preenchido';

        if (Configuration::get('FKCORREIOSG2_CEP_CIDADE') == '') {
            $minhaCidade = false;
        }

        // Verifica Servicos Ativos
        $servicos = true;
        $msgServicos = 'Todos os Serviços dos Correios estão inativos. Considere correto caso esteja utilizando Módulo Complemento de Outras Transportadoras e não deseje utilizar os Serviços dos Correios';

        $sql = "SELECT id
                FROM "._DB_PREFIX_."fkcorreiosg2_servicos
                WHERE ativo = 1";

        $dados = Db::getInstance()->executeS($sql);

        if (!$dados) {
            $servicos = false;
        }

        // Verifica Tabelas Offline
        $tabOffline = true;
        $msgTabOffline = 'Tabelas Offline não geradas';

        $sql = "SELECT id
                FROM "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios
                WHERE tabela_offline = 1";

        $espCorreios = Db::getInstance()->executeS($sql);

        if (!$espCorreios) {
            $tabOffline = false;
        }else {
            foreach ($espCorreios as $reg) {

                $sql = "SELECT id
                        FROM "._DB_PREFIX_."fkcorreiosg2_tabelas_offline
                        WHERE (tabela_cidade is not null OR tabela_capital is not null OR tabela_interior is not null) AND id_especificacao = ".(int)$reg['id'];

                $dados = Db::getInstance()->executeS($sql);

                if (!$dados) {
                    $tabOffline = false;
                    break;
                }
            }
        }

        $this->smarty->assign(array(
            'tab_10' => array(
                'nameTpl'                   => $name_tpl,
                'urlImg'                    => Configuration::get('FKCORREIOSG2_URL_IMG'),
                'soap'                      => $soap,
                'msgSoap'                   => $msgSoap,
                'modulosNativos'            => $modulosNativos,
                'msgModulosNativos'         => $msgModulosNativos,
                'overrides'                 => $overrides,
                'msgOverrides'              => $msgOverrides,
                'custosEnvio'               => $custosEnvio,
                'msgCustosEnvio'            => $msgCustosEnvio,
                'freteGratisValor'          => $freteGratisValor,
                'msgFreteGratisValor'       => $msgFreteGratisValor,
                'freteGratisPeso'           => $freteGratisPeso,
                'msgFreteGratisPeso'        => $msgFreteGratisPeso,
                'regioes'                   => $regioes,
                'msgRegioes'                => $msgRegioes,
                'dimPesoZero'               => $dimPesoZero,
                'msgDimPesoZero'            => $msgDimPesoZero,
                'dimPesoMaior'              => $dimPesoMaior,
                'msgDimPesoMaior'           => $msgDimPesoMaior,
                'gestaoAvancadaEstoque'     => $gestaoAvancadaEstoque,
                'msgGestaoAvancadaEstoque'  => $msgGestaoAvancadaEstoque,
                'meuCep'                    => $meuCep,
                'msgMeuCep'                 => $msgMeuCep,
                'minhaCidade'               => $minhaCidade,
                'msgMinhaCidade'            => $msgMinhaCidade,
                'servicos'                  => $servicos,
                'msgServicos'               => $msgServicos,
                'tabOffline'                => $tabOffline,
                'msgTabOffline'             => $msgTabOffline,

            )
        ));
    }

    private function postValidation() {

        $origem = Tools::getValue('origem');

        switch($origem) {

            case 'configGeral':

                // Tab selecionada
                $this->tab_select = '2';

                if (Trim(Tools::getValue('fkcorreiosg2_meu_cep')) == '') {
                    $this->postErrors[] = $this->l('Campo "Meu CEP" não preenchido.');
                }

                if (Trim(Tools::getValue('fkcorreiosg2_cep_cidade')) == '') {
                    $this->postErrors[] = $this->l('Campo "Minha Cidade" não preenchido.');
                }

                if (Trim(Tools::getValue('fkcorreiosg2_tempo_preparacao')) == '') {
                    $this->postErrors[] = $this->l('Campo "Preparação em dias" não preenchido');
                }else {
                    if (!is_numeric(Tools::getValue('fkcorreiosg2_tempo_preparacao'))) {
                        $this->postErrors[] = $this->l('O campo "Preparação em dias" não é numérico');
                    }else {
                        if (Tools::getValue('fkcorreiosg2_tempo_preparacao') < 0) {
                            $this->postErrors[] = $this->l('O campo "Preparação em dias" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (!$this->postErrors) {
                    $this->postProcess($origem);
                }

                break;

            case 'cadastroCep':

                // Tab selecionada
                $this->tab_select = '3';

                // Recupera dados da tabela
                $cadCep = $this->recuperaCadastroCep();

                foreach ($cadCep as $reg) {

                    if (trim(Tools::getValue('fkcorreiosg2_cep_estado_'.$reg['id'])) == '') {
                        $this->postErrors[] = $this->l('Intervalo de CEP dos Estados não preenchido. Estado').': '.$reg['estado'];
                    }else {
                        $intervalos = explode("/", Tools::getValue('fkcorreiosg2_cep_estado_'.$reg['id']));

                        foreach ($intervalos as $intervalo) {
                            if ($intervalo == ''){
                                continue;
                            }

                            if (strlen($intervalo) < 17) {
                                $this->postErrors[] = $this->l('"Intervalo de CEP dos Estados" com erro. Estado').': '.$reg['estado'];
                            }
                        }
                    }

                    if (trim(Tools::getValue('fkcorreiosg2_cep_capital_'.$reg['id'])) == '') {
                        $this->postErrors[] = $this->l('Intervalo de CEP das Capitais não preenchido. Estado').': '.$reg['estado'];
                    }else {
                        $intervalos = explode("/", Tools::getValue('fkcorreiosg2_cep_capital_'.$reg['id']));

                        foreach ($intervalos as $intervalo) {
                            if ($intervalo == ''){
                                continue;
                            }

                            if (strlen($intervalo) < 17) {
                                $this->postErrors[] = $this->l('"Intervalo de CEP das Capitais" com erro. Estado').': '.$reg['estado'];
                            }
                        }
                    }

                    if (trim(Tools::getValue('fkcorreiosg2_cep_base_capital_'.$reg['id'])) == '') {
                        $this->postErrors[] = $this->l('CEP base - Capital não preenchido. Estado').': '.$reg['estado'];
                    }else {
                        $valor = str_replace('-', '', Tools::getValue('fkcorreiosg2_cep_base_capital_'.$reg['id']));

                        if (!is_numeric($valor)) {
                            $this->postErrors[] = $this->l('O campo "CEP base - Capital é inválido. Estado').': '.$reg['estado'];
                        }
                    }

                    if (trim(Tools::getValue('fkcorreiosg2_cep_base_interior_'.$reg['id'])) == '') {
                        $this->postErrors[] = $this->l('CEP base - Interior não preenchido. Estado').': '.$reg['estado'];
                    }else {
                        $valor = str_replace('-', '', Tools::getValue('fkcorreiosg2_cep_base_interior_'.$reg['id']));

                        if (!is_numeric($valor)) {
                            $this->postErrors[] = $this->l('O campo "CEP base - Interior é inválido. Estado').': '.$reg['estado'];
                        }
                    }

                }

                if (!$this->postErrors) {
                    $this->postProcess($origem);
                }

                break;

            case 'cadastroEmbalagens':

                // Tab selecionada
                $this->tab_select = '4';

                if (Tools::isSubmit('btnAdd')) {
                    $this->incluiEmbalagem();
                    break;
                }

                if (Tools::isSubmit('btnDel')) {
                    // Recupera embalagens marcadas
                    $embalagens = Tools::getValue('fkcorreiosg2_emb_excluir');

                    $this->excluiEmbalagem($embalagens);
                    break;
                }

                // Recupera dados da tabela
                $embalagens = $this->recuperaCadastroEmbalagens();

                foreach ($embalagens as $reg) {

                    if (trim(Tools::getValue('fkcorreiosg2_emb_descricao_'.$reg['id'])) == '') {
                        $this->postErrors[] = $this->l('O campo "Descrição" não está preenchido');
                    }

                    if (trim(Tools::getValue('fkcorreiosg2_emb_comprimento_'.$reg['id'])) == '') {
                        $this->postErrors[] = $this->l('O campo "Comprimento" não está preenchido');
                    }else {
                        $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_emb_comprimento_'.$reg['id']));

                        if (!is_numeric($valor)) {
                            $this->postErrors[] = $this->l('O campo "Comprimento" não é numérico');
                        }else {
                            if ($valor <= 0) {
                                $this->postErrors[] = $this->l('O campo "Comprimento" não pode ser menor ou igual a 0 (zero)');
                            }
                        }
                    }

                    if (trim(Tools::getValue('fkcorreiosg2_emb_altura_'.$reg['id'])) == '') {
                        $this->postErrors[] = $this->l('O campo "Altura" não está preenchido');
                    }else {
                        $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_emb_altura_'.$reg['id']));

                        if (!is_numeric($valor)) {
                            $this->postErrors[] = $this->l('O campo "Altura" não é numérico');
                        }else {
                            if ($valor <= 0) {
                                $this->postErrors[] = $this->l('O campo "Altura" não pode ser menor ou igua a 0 (zero)');
                            }
                        }
                    }

                    if (trim(Tools::getValue('fkcorreiosg2_emb_largura_'.$reg['id'])) == '') {
                        $this->postErrors[] = $this->l('O campo "Largura" não está preenchido');
                    }else {
                        $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_emb_largura_'.$reg['id']));

                        if (!is_numeric($valor)) {
                            $this->postErrors[] = $this->l('O campo "Largura" não é numérico');
                        }else {
                            if ($valor <= 0) {
                                $this->postErrors[] = $this->l('O campo "Largura" não pode ser menor ou igual a 0 (zero)');
                            }
                        }
                    }

                    if (trim(Tools::getValue('fkcorreiosg2_emb_peso_'.$reg['id'])) == '') {
                        $this->postErrors[] = $this->l('O campo "Peso" não está preenchido');
                    }else {
                        $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_emb_peso_'.$reg['id']));

                        if (!is_numeric($valor)) {
                            $this->postErrors[] = $this->l('O campo "Peso" não é numérico');
                        }else {
                            if ($valor < 0) {
                                $this->postErrors[] = $this->l('O campo "Peso" não pode ser menor que 0 (zero)');
                            }
                        }
                    }

                    if (trim(Tools::getValue('fkcorreiosg2_emb_custo_'.$reg['id'])) == '') {
                        $this->postErrors[] = $this->l('O campo "Custo" não está preenchido');
                    }else {
                        $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_emb_custo_'.$reg['id']));

                        if (!is_numeric($valor)) {
                            $this->postErrors[] = $this->l('O campo "Custo" não é numérico');
                        }else {
                            if ($valor < 0) {
                                $this->postErrors[] = $this->l('O campo "Custo" não pode ser menor que 0 (zero)');
                            }
                        }
                    }
                }

                if (!$this->postErrors) {
                    $this->postProcess($origem);
                }

                break;

            case 'especificacoesCorreios':

                // Tab selecionada
                $this->tab_select = '5';

                // Recupera id das especificacoes dos Correios
                $id = Tools::getValue('id');

                if (trim(Tools::getValue('fkcorreiosg2_cod_servico_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Código serviço" não está preenchido');
                }

                if (trim(Tools::getValue('fkcorreiosg2_comprimento_min_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Comprimento mínimo" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_comprimento_min_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Comprimento mínimo" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Comprimento mínimo" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_comprimento_max_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Comprimento máximo" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_comprimento_max_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Comprimento máximo" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Comprimento máximo" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_largura_min_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Largura mínima" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_largura_min_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Largura mínima" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Largura mínima" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_largura_max_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Largura máxima" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_largura_max_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Largura máxima" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Largura máxima" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_altura_min_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Altura mínima" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_altura_min_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Altura mínima" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Altura mínima" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_altura_max_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Altura máxima" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_altura_max_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Altura máxima" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Altura máxima" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_somatoria_dimensoes_max_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Somatória dimensões" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_somatoria_dimensoes_max_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Somatória dimensões" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Somatória dimensões" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_peso_estadual_max_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Peso máximo - Estadual" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_peso_estadual_max_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Peso máximo - Estadual" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Peso máximo - Estadual" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_peso_nacional_max_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Peso máximo - Nacional" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_peso_nacional_max_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Peso máximo - Nacional" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Peso máximo - Nacional" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_intervalo_pesos_estadual_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Intervalo de pesos - Estadual" não está preenchido');
                }

                if (trim(Tools::getValue('fkcorreiosg2_intervalo_pesos_nacional_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Intervalo de pesos - Nacional" não está preenchido');
                }

                if (trim(Tools::getValue('fkcorreiosg2_cubagem_max_isenta_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Cubagem max isenta" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_cubagem_max_isenta_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Cubagem max isenta" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Cubagem max isenta" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_cubagem_base_calculo_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Cubagem base cálculo" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_cubagem_base_calculo_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Cubagem base cálculo" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Cubagem base cálculo" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_mao_propria_valor_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Valor Mão Própria" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_mao_propria_valor_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Valor mão própria" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Valor mão própria" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_aviso_recebimento_valor_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Valor Aviso Recebimento" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_aviso_recebimento_valor_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Valor Aviso Recebimento" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Valor Aviso Recebimento" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_valor_declarado_percentual_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Percentual Valor Declarado" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_valor_declarado_percentual_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Percentual Valor Declarado" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Percentual Valor Declarado" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_valor_declarado_max_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Máximo Valor Declarado" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_valor_declarado_max_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Máximo Valor Declarado" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Máximo Valor Declarado" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (trim(Tools::getValue('fkcorreiosg2_seguro_automatico_valor_'.$id)) == '') {
                    $this->postErrors[] = $this->l('O campo "Seguro automático" não está preenchido');
                }else {
                    $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_seguro_automatico_valor_'.$id));

                    if (!is_numeric($valor)) {
                        $this->postErrors[] = $this->l('O campo "Seguro automático" não é numérico');
                    }else {
                        if ($valor < 0) {
                            $this->postErrors[] = $this->l('O campo "Seguro automático" não pode ser menor que 0 (zero)');
                        }
                    }
                }

                if (!$this->postErrors) {
                    $this->postProcess($origem);
                }

                break;

            case 'servicosCorreios':

                // Tab selecionada
                $this->tab_select = '6';

                // Recupera id do servico
                $id = Tools::getValue('id');

                // Verifica se o servico esta ativo
                if (Tools::getValue('fkcorreiosg2_servicos_ativo_'.$id)) {

                    // Verifica o campo Grade
                    if (trim(Tools::getValue('fkcorreiosg2_servicos_grade_'.$id)) == '') {
                        $this->postErrors[] = $this->l('O campo "Grade" não está preenchido');
                    }else {
                        if (!is_numeric(Tools::getValue('fkcorreiosg2_servicos_grade_'.$id))) {
                            $this->postErrors[] = $this->l('O campo "Grade" não é numérico');
                        }else {
                            if (Tools::getValue('fkcorreiosg2_servicos_grade_'.$id) < 0) {
                                $this->postErrors[] = $this->l('O campo "Grade" não pode ser menor que 0 (zero)');
                            }
                        }
                    }

                    // Verifica os campo "Estados atendidos" e "Intervalo de CEPs atendidos"
                    if (!Tools::getValue('fkcorreiosg2_servicos_uf_'.$id) and !Tools::getValue('fkcorreiosg2_servicos_cep_'.$id)) {
                        $this->postErrors[] = $this->l('O campo "Estados atendidos" ou "Intervalo CEPs atendidos" devem ser preenchidos');
                    }

                    // Verifica o campo Percentual de Desconto
                    if (trim(Tools::getValue('fkcorreiosg2_servicos_percentual_desc_'.$id)) == '') {
                        $this->postErrors[] = $this->l('O campo "Percentual de Desconto" não está preenchido');
                    }else {
                        $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_servicos_percentual_desc_'.$id));

                        if (!is_numeric($valor)) {
                            $this->postErrors[] = $this->l('O campo "Percentual de Desconto" não é numérico');
                        }else {
                            if ($valor < 0) {
                                $this->postErrors[] = $this->l('O campo "Percentual de Desconto" não pode ser menor que 0 (zero)');
                            }
                        }
                    }

                    // Verifica o campo Valor do Pedido
                    if (trim(Tools::getValue('fkcorreiosg2_servicos_valor_pedido_desc_'.$id)) == '') {
                        $this->postErrors[] = $this->l('O campo "Valor do Pedido" não está preenchido');
                    }else {
                        $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_servicos_valor_pedido_desc_'.$id));

                        if (!is_numeric($valor)) {
                            $this->postErrors[] = $this->l('O campo "Valor do Pedido" não é numérico');
                        }else {
                            if ($valor < 0) {
                                $this->postErrors[] = $this->l('O campo "Valor do Pedido" não pode ser menor que 0 (zero)');
                            }
                        }
                    }

                }

                if (!$this->postErrors) {
                    $this->postProcess($origem);
                }

                break;

            case 'freteGratis':

                // Tab selecionada
                $this->tab_select = '7';

                // Recupera id da regiao
                $id = Tools::getValue('id');

                if (Tools::isSubmit('btnAdd')) {
                    $this->incluiFreteGratis();
                    break;
                }

                if (Tools::isSubmit('btnDel')) {
                    $this->excluiFreteGratis($id);
                    break;
                }

                // Verifica se regiao esta ativa
                if (Tools::getValue('fkcorreiosg2_frete_gratis_ativo_'.$id)) {

                    // Nome da regiao
                    if (trim(Tools::getValue('fkcorreiosg2_frete_gratis_nome_regiao_'.$id)) == '') {
                        $this->postErrors[] = $this->l('O campo "Nome da Região" não está preenchido');
                    }

                    // Campo "Estados atendidos" e "Intervalo de CEPs atendidos"
                    if (!Tools::getValue('fkcorreiosg2_frete_gratis_uf_'.$id) and !Tools::getValue('fkcorreiosg2_frete_gratis_cep_'.$id)) {
                        $this->postErrors[] = $this->l('O campo "Estados Atendidos" ou "Intervalo CEPs Atendidos" devem ser preenchidos');
                    }

                    // Valor do pedido
                    if (trim(Tools::getValue('fkcorreiosg2_frete_gratis_valor_pedido_'.$id)) == '') {
                        $this->postErrors[] = $this->l('O campo "Valor do Pedido" não está preenchido');
                    }else {
                        $valor = str_replace(',', '.', Tools::getValue('fkcorreiosg2_frete_gratis_valor_pedido_'.$id));

                        if (!is_numeric($valor)) {
                            $this->postErrors[] = $this->l('O campo "Valor do Pedido" não é numérico');
                        }else {
                            if ($valor < 0) {
                                $this->postErrors[] = $this->l('O campo "Valor do Pedido" não pode ser menor que 0 (zero)');
                            }
                        }
                    }

                    // Transportadoras
                    if (!Tools::getValue('fkcorreiosg2_frete_gratis_transp_'.$id)) {
                        $this->postErrors[] = $this->l('Transportadora não selecionada');
                    }

                }

                if (!$this->postErrors) {
                    $this->postProcess($origem);
                }

                break;

            case 'tabOffline':

                // Tab selecionada
                $this->tab_select = '8';

                // Recupera id das Especificacoes dos Correios
                $id = Tools::getValue('id');

                $sql = 'SELECT id, minha_cidade
                        FROM '._DB_PREFIX_.'fkcorreiosg2_tabelas_offline
                        WHERE id_especificacao = '.(int)$id;

                $tabOffline = Db::getInstance()->executeS($sql);

                foreach ($tabOffline as $reg) {

                    $msgTab = 'Existem Tabelas Offline não preenchidas. Favor verificar e regerar estas tabelas';
                    $msgPrazo = 'Existem Prazos de Entrega não preenchidos. Favor verificar e regerar as tabelas envolvidas';

                    if ($reg['minha_cidade'] == 1) {
                        if (trim(Tools::getValue('fkcorreiosg2_tab_offline_cidade_'.$reg['id'])) == '') {
                            $this->postErrors[] = $msgTab;
                            break;
                        }

                        if (trim(Tools::getValue('fkcorreiosg2_tab_offline_prazo_cidade_'.$reg['id'])) == '') {
                            $this->postErrors[] = $msgPrazo;
                            break;
                        }
                    }else {
                        if (trim(Tools::getValue('fkcorreiosg2_tab_offline_capital_'.$reg['id'])) == '' Or
                            trim(Tools::getValue('fkcorreiosg2_tab_offline_interior_'.$reg['id'])) == '') {
                            $this->postErrors[] = $msgTab;
                            break;
                        }

                        if (trim(Tools::getValue('fkcorreiosg2_tab_offline_prazo_capital_'.$reg['id'])) == '' Or
                            trim(Tools::getValue('fkcorreiosg2_tab_offline_prazo_interior_'.$reg['id'])) == '') {
                            $this->postErrors[] = $msgPrazo;
                            break;
                        }
                    }
                }

                if (!$this->postErrors) {
                    $this->postProcess($origem);
                }

                break;
        }
    }

    private function postProcess($origem) {

        // Exclui cache
        $this->excluiCache();

        switch ($origem) {

            case 'configGeral':

                // Salva as configurações
                Configuration::updateValue('FKCORREIOSG2_MEU_CEP', Trim(Tools::getValue('fkcorreiosg2_meu_cep')));
                Configuration::updateValue('FKCORREIOSG2_CEP_CIDADE', Trim(Tools::getValue('fkcorreiosg2_cep_cidade')));
                Configuration::updateValue('FKCORREIOSG2_MAO_PROPRIA', Trim(Tools::getValue('fkcorreiosg2_mao_propria')));
                Configuration::updateValue('FKCORREIOSG2_VALOR_DECLARADO', Trim(Tools::getValue('fkcorreiosg2_valor_declarado')));
                Configuration::updateValue('FKCORREIOSG2_AVISO_RECEBIMENTO', Trim(Tools::getValue('fkcorreiosg2_aviso_recebimento')));
                Configuration::updateValue('FKCORREIOSG2_TEMPO_PREPARACAO', Trim(Tools::getValue('fkcorreiosg2_tempo_preparacao')));
                Configuration::updateValue('FKCORREIOSG2_EMBALAGEM', Trim(Tools::getValue('fkcorreiosg2_embalagem')));
                Configuration::updateValue('FKCORREIOSG2_OFFLINE', Trim(Tools::getValue('fkcorreiosg2_offline')));
                Configuration::updateValue('FKCORREIOSG2_FRETE_GRATIS_DEMAIS_TRANSP', Trim(Tools::getValue('fkcorreiosg2_frete_gratis_demais_transp')));
                Configuration::updateValue('FKCORREIOSG2_BLOCO_PRODUTO', Trim(Tools::getValue('fkcorreiosg2_bloco_produto')));
                Configuration::updateValue('FKCORREIOSG2_BLOCO_PRODUTO_POSICAO', Trim(Tools::getValue('fkcorreiosg2_bloco_produto_posicao')));
                Configuration::updateValue('FKCORREIOSG2_BLOCO_PRODUTO_LIGHTBOX', Trim(Tools::getValue('fkcorreiosg2_bloco_produto_lightbox')));
                Configuration::updateValue('FKCORREIOSG2_BLOCO_CARRINHO', Trim(Tools::getValue('fkcorreiosg2_bloco_carrinho')));
                Configuration::updateValue('FKCORREIOSG2_MSG_CORREIOS', Trim(Tools::getValue('fkcorreiosg2_msg_correios')));
                Configuration::updateValue('FKCORREIOSG2_COR_FUNDO', Trim(Tools::getValue('fkcorreiosg2_cor_fundo')));
                Configuration::updateValue('FKCORREIOSG2_BORDA', Trim(Tools::getValue('fkcorreiosg2_borda')));
                Configuration::updateValue('FKCORREIOSG2_RAIO_BORDA', Trim(Tools::getValue('fkcorreiosg2_raio_borda')));
                Configuration::updateValue('FKCORREIOSG2_COR_FONTE_TITULO', Trim(Tools::getValue('fkcorreiosg2_cor_fonte_titulo')));
                Configuration::updateValue('FKCORREIOSG2_COR_BOTAO', Trim(Tools::getValue('fkcorreiosg2_cor_botao')));
                Configuration::updateValue('FKCORREIOSG2_COR_FONTE_BOTAO', Trim(Tools::getValue('fkcorreiosg2_cor_fonte_botao')));
                Configuration::updateValue('FKCORREIOSG2_COR_FAIXA_MSG', Trim(Tools::getValue('fkcorreiosg2_cor_faixa_msg')));
                Configuration::updateValue('FKCORREIOSG2_COR_FONTE_MSG', Trim(Tools::getValue('fkcorreiosg2_cor_fonte_msg')));
                Configuration::updateValue('FKCORREIOSG2_LARGURA', Trim(Tools::getValue('fkcorreiosg2_largura')));
                Configuration::updateValue('FKCORREIOSG2_RASTREIO_LEFT', Trim(Tools::getValue('fkcorreiosg2_bloco_rastreio_left')));
                Configuration::updateValue('FKCORREIOSG2_RASTREIO_RIGHT', Trim(Tools::getValue('fkcorreiosg2_bloco_rastreio_right')));
                Configuration::updateValue('FKCORREIOSG2_RASTREIO_FOOTER', Trim(Tools::getValue('fkcorreiosg2_bloco_rastreio_footer')));
                Configuration::updateValue('FKCORREIOSG2_RASTREIO_ACCOUNT', Trim(Tools::getValue('fkcorreiosg2_bloco_rastreio_account')));
                Configuration::updateValue('FKCORREIOSG2_EXCLUIR_CONFIG', Trim(Tools::getValue('fkcorreiosg2_excluir_config')));

                break;

            case 'cadastroCep':

                // Recupera dados da tabela
                $cadCep = $this->recuperaCadastroCep();

                foreach ($cadCep as $reg) {

                    $dados = array(
                        'cep_estado'        => Tools::getValue('fkcorreiosg2_cep_estado_'.$reg['id']),
                        'cep_capital'       => Tools::getValue('fkcorreiosg2_cep_capital_'.$reg['id']),
                        'cep_base_capital'  => Tools::getValue('fkcorreiosg2_cep_base_capital_'.$reg['id']),
                        'cep_base_interior' => Tools::getValue('fkcorreiosg2_cep_base_interior_'.$reg['id'])
                    );

                    Db::getInstance()->update('fkcorreiosg2_cadastro_cep', $dados, 'id = ' . (int)$reg['id']);
                }

                break;

            case 'cadastroEmbalagens':

                // Recupera dados da tabela
                $embalagens = $this->recuperaCadastroEmbalagens();

                // Array com as embalagens ativas
                $embalagens_ativas = Tools::getValue('fkcorreiosg2_emb_ativo');

                // Atualiza os dados das embalagens
                foreach ($embalagens as $reg) {

                    $comprimento = str_replace(',', '.', Tools::getValue('fkcorreiosg2_emb_comprimento_'.$reg['id']));
                    $altura = str_replace(',', '.', Tools::getValue('fkcorreiosg2_emb_altura_'.$reg['id']));
                    $largura = str_replace(',', '.', Tools::getValue('fkcorreiosg2_emb_largura_'.$reg['id']));
                    $peso = str_replace(',', '.', Tools::getValue('fkcorreiosg2_emb_peso_'.$reg['id']));
                    $custo = str_replace(',', '.', Tools::getValue('fkcorreiosg2_emb_custo_'.$reg['id']));

                    // Calcula cubagem da caixa
                    $cubagem = ($comprimento * $altura * $largura);

                    // Verifica se a embalagem esta ativa
                    $ativo = 0;

                    if ($embalagens_ativas) {
                        if (in_array($reg['id'], $embalagens_ativas)) {
                            $ativo = 1;
                        }
                    }

                    $dados = array(
                        'descricao'     => Tools::getValue('fkcorreiosg2_emb_descricao_'.$reg['id']),
                        'comprimento'   => $comprimento,
                        'altura'        => $altura,
                        'largura'       => $largura,
                        'peso'          => $peso,
                        'cubagem'       => $cubagem,
                        'custo'         => $custo,
                        'ativo'         => $ativo
                    );

                    Db::getInstance()->update('fkcorreiosg2_embalagens', $dados, 'id = ' . (int)$reg['id']);
                }

                break;

            case 'especificacoesCorreios':

                // Recupera id das especificacoes dos Correios
                $id = Tools::getValue('id');

                $dados = array(
                    'cod_servico'                   => Tools::getValue('fkcorreiosg2_cod_servico_'.$id),
                    'cod_administrativo'            => Tools::getValue('fkcorreiosg2_cod_administrativo_'.$id),
                    'senha'                         => Tools::getValue('fkcorreiosg2_senha_'.$id),
                    'comprimento_min'               => str_replace(',', '.', Tools::getValue('fkcorreiosg2_comprimento_min_'.$id)),
                    'comprimento_max'               => str_replace(',', '.', Tools::getValue('fkcorreiosg2_comprimento_max_'.$id)),
                    'largura_min'                   => str_replace(',', '.', Tools::getValue('fkcorreiosg2_largura_min_'.$id)),
                    'largura_max'                   => str_replace(',', '.', Tools::getValue('fkcorreiosg2_largura_max_'.$id)),
                    'altura_min'                    => str_replace(',', '.', Tools::getValue('fkcorreiosg2_altura_min_'.$id)),
                    'somatoria_dimensoes_max'       => str_replace(',', '.', Tools::getValue('fkcorreiosg2_somatoria_dimensoes_max_'.$id)),
                    'peso_estadual_max'             => str_replace(',', '.', Tools::getValue('fkcorreiosg2_peso_estadual_max_'.$id)),
                    'peso_nacional_max'             => str_replace(',', '.', Tools::getValue('fkcorreiosg2_peso_nacional_max_'.$id)),
                    'intervalo_pesos_estadual'      => Tools::getValue('fkcorreiosg2_intervalo_pesos_estadual_'.$id),
                    'intervalo_pesos_nacional'      => Tools::getValue('fkcorreiosg2_intervalo_pesos_nacional_'.$id),
                    'cubagem_max_isenta'            => str_replace(',', '.', Tools::getValue('fkcorreiosg2_cubagem_max_isenta_'.$id)),
                    'cubagem_base_calculo'          => str_replace(',', '.', Tools::getValue('fkcorreiosg2_cubagem_base_calculo_'.$id)),
                    'mao_propria_valor'             => str_replace(',', '.', Tools::getValue('fkcorreiosg2_mao_propria_valor_'.$id)),
                    'aviso_recebimento_valor'       => str_replace(',', '.', Tools::getValue('fkcorreiosg2_aviso_recebimento_valor_'.$id)),
                    'valor_declarado_percentual'    => str_replace(',', '.', Tools::getValue('fkcorreiosg2_valor_declarado_percentual_'.$id)),
                    'valor_declarado_max'           => str_replace(',', '.', Tools::getValue('fkcorreiosg2_valor_declarado_max_'.$id)),
                    'seguro_automatico_valor'       => str_replace(',', '.', Tools::getValue('fkcorreiosg2_seguro_automatico_valor_'.$id))
                );

                Db::getInstance()->update('fkcorreiosg2_especificacoes_correios', $dados, 'id = ' . (int)$id);

                break;

            case 'servicosCorreios':

                // Recupera id do servico
                $id = Tools::getValue('id');

                // Instancia FKcorreiosg2Class
                $fkClass = new FKcorreiosg2Class();

                // Formata UFs
                $regiaoUF = $fkClass->formataGravacaoUF(Tools::getValue('fkcorreiosg2_servicos_uf_'.$id));

                // Altera fkcorreiosg2_servicos
                $dados = array(
                    'filtro_regiao_uf'      => Tools::getValue('fkcorreiosg2_servicos_filtro_uf_'.$id),
                    'regiao_uf'             => $regiaoUF,
                    'regiao_cep'            => Tools::getValue('fkcorreiosg2_servicos_cep_'.$id),
                    'regiao_cep_excluido'   => Tools::getValue('fkcorreiosg2_servicos_cep_excluido_'.$id),
                    'grade'                 => Tools::getValue('fkcorreiosg2_servicos_grade_'.$id),
                    'percentual_desconto'   => str_replace(',', '.', Tools::getValue('fkcorreiosg2_servicos_percentual_desc_'.$id)),
                    'valor_pedido_desconto' => str_replace(',', '.', Tools::getValue('fkcorreiosg2_servicos_valor_pedido_desc_'.$id)),
                    'ativo'                 => (!Tools::getValue('fkcorreiosg2_servicos_ativo_'.$id) ? '0' : '1')
                );

                Db::getInstance()->update('fkcorreiosg2_servicos', $dados, 'id = '.(int)$id);

                // Altera Carrier
                $sql = 'SELECT *
                        FROM '._DB_PREFIX_.'fkcorreiosg2_servicos
                        WHERE id = '.$id;

                $servicos = Db::getInstance()->getRow($sql);

                $parm = array(
                    'nomeCarrier'   => '',
                    'idCarrier'     => $servicos['id_carrier'],
                    'ativo'         => $servicos['ativo'],
                    'grade'         => $servicos['grade'],
                    'arrayLogo'     => $_FILES,
                    'campoLogo'     => 'fkcorreiosg2_servicos_logo_'.$id,
                );

                if (!$fkClass->alteraCarrier($parm)) {
                    $this->postErrors[] = $fkClass->getMsgErro();
                }

                break;

            case 'freteGratis':

                // Recupera id da regiao
                $id = Tools::getValue('id');

                // Instancia FKcorreiosg2Class
                $fkClass = new FKcorreiosg2Class();

                // Formata UFs
                $regiaoUF = $fkClass->formataGravacaoUF(Tools::getValue('fkcorreiosg2_frete_gratis_uf_'.$id));

                // Altera fkcorreiosg2_frete_gratis
                $dados = array(
                    'id_carrier'            => Tools::getValue('fkcorreiosg2_frete_gratis_transp_'.$id),
                    'nome_regiao'           => Tools::getValue('fkcorreiosg2_frete_gratis_nome_regiao_'.$id),
                    'filtro_regiao_uf'      => Tools::getValue('fkcorreiosg2_frete_gratis_filtro_uf_'.$id),
                    'regiao_uf'             => $regiaoUF,
                    'regiao_cep'            => Tools::getValue('fkcorreiosg2_frete_gratis_cep_'.$id),
                    'regiao_cep_excluido'   => Tools::getValue('fkcorreiosg2_frete_gratis_cep_excluido_'.$id),
                    'valor_pedido'          => str_replace(',', '.', Tools::getValue('fkcorreiosg2_frete_gratis_valor_pedido_'.$id)),
                    'id_produtos'           => Tools::getValue('fkcorreiosg2_frete_gratis_produtos_'.$id),
                    'ativo'                 => (!Tools::getValue('fkcorreiosg2_frete_gratis_ativo_'.$id) ? '0' : '1'),
                );

                Db::getInstance()->update('fkcorreiosg2_frete_gratis', $dados, 'id = '.(int)$id);

                break;

            case 'tabOffline':

                // Recupera id das Especificacoes dos Correios
                $id = Tools::getValue('id');

                $sql = 'SELECT id, minha_cidade
                        FROM '._DB_PREFIX_.'fkcorreiosg2_tabelas_offline
                        WHERE id_especificacao = '.(int)$id;

                $tabOffline = Db::getInstance()->executeS($sql);

                foreach ($tabOffline as $reg) {

                    $prazoCidade = '';
                    $prazoCapital = '';
                    $prazoInterior = '';
                    $tabelaCidade = '';
                    $tabelaCapital = '';
                    $tabelaInterior = '';

                    if ($reg['minha_cidade']) {
                        $prazoCidade = Tools::getValue('fkcorreiosg2_tab_offline_prazo_cidade_'.$reg['id']);
                        $tabelaCidade = Tools::getValue('fkcorreiosg2_tab_offline_cidade_'.$reg['id']);
                    }else {
                        $prazoCapital = Tools::getValue('fkcorreiosg2_tab_offline_prazo_capital_'.$reg['id']);
                        $prazoInterior = Tools::getValue('fkcorreiosg2_tab_offline_prazo_interior_'.$reg['id']);
                        $tabelaCapital = Tools::getValue('fkcorreiosg2_tab_offline_capital_'.$reg['id']);
                        $tabelaInterior = Tools::getValue('fkcorreiosg2_tab_offline_interior_'.$reg['id']);
                    }

                    $dados = array(
                        'prazo_entrega_cidade'      => $prazoCidade,
                        'prazo_entrega_capital'     => $prazoCapital,
                        'prazo_entrega_interior'    => $prazoInterior,
                        'tabela_cidade'             => $tabelaCidade,
                        'tabela_capital'            => $tabelaCapital,
                        'tabela_interior'           => $tabelaInterior
                    );

                    Db::getInstance()->update('fkcorreiosg2_tabelas_offline', $dados, 'id = '.(int)$reg['id']);
                }

                break;
        }
    }

    public function getOrderShippingCost($params, $shipping_cost) {

        // Instacia FKcorreiosg2FreteClass
        $freteClass = new FKcorreiosg2FreteClass();

        // Ignora Carrier se frete nao calculado
        if (!$freteClass->calculaFretePS($params, $this->id_carrier)) {
            return false;
        }

        // Recupera dados do frete
        $frete = $freteClass->getFreteCarrier();

        // Grava array com o Prazo de entrega
        $this->prazoEntrega[$this->id_carrier] = $frete['prazoEntrega'];

        // Retorna Valor do Frete
        return (float)$frete['valorFrete'];
    }

    public function getOrderShippingCostExternal($params) {
        return $this->getOrderShippingCost($params, 0);
    }

    private function recuperaCadastroCep() {

        $sql = 'SELECT *
                FROM '._DB_PREFIX_.'fkcorreiosg2_cadastro_cep
                ORDER BY estado';

        return Db::getInstance()->executeS($sql);
    }

    private function incluiCadastroCep() {

        // Insere intervalo de cep dos estados e capitais
        $sql = "INSERT INTO `"._DB_PREFIX_."fkcorreiosg2_cadastro_cep` (`estado`, `capital`, `cep_estado`, `cep_capital`, `cep_base_capital`, `cep_base_interior`) VALUES
            ('AC', 'Rio Branco', 		'69900000:69999999', 						'69900001:69923999',                    '69900-001', '69985-000'),
            ('AL', 'Maceió', 			'57000000:57999999', 						'57000001:57099999',                    '57000-001', '57770-000'),
            ('AM', 'Manaus', 			'69000000:69299999/69400000:69899999', 		'69000001:69099999',                    '69000-001', '69158-000'),
            ('AP', 'Macapá', 			'68900000:68999999', 						'68900001:68911999',                    '68900-001', '68950-000'),
            ('BA', 'Salvador', 			'40000000:48999999', 						'40000001:42599999',                    '40000-001', '44500-000'),
            ('CE', 'Fortaleza', 		'60000000:63999999', 						'60000001:61599999',                    '60000-001', '62750-000'),
            ('DF', 'Brasília', 			'70000000:72799999/73000000:73699999', 		'70000001:72799999/73000001:73699999',  '70000-001', '70000-001'),
            ('ES', 'Vitória', 			'29000000:29999999', 						'29000001:29099999',                    '29000-001', '29700-001'),
            ('GO', 'Goiãnia', 			'72800000:72999999/73700000:76799999', 		'74000001:74899999',                    '74000-001', '75000-001'),
            ('MA', 'São Luiz', 			'65000000:65999999', 						'65000001:65109999',                    '65000-001', '65250-000'),
            ('MG', 'Belo Horizonte', 	'30000000:39999999', 						'30000001:31999999',                    '30000-001', '37130-000'),
            ('MS', 'Campo Grande', 		'79000000:79999999', 						'79000001:79124999',                    '79000-001', '79300-001'),
            ('MT', 'Cuiabá', 			'78000000:78899999', 						'78000001:78099999',                    '78000-001', '78200-000'),
            ('PA', 'Belém', 			'66000000:68899999', 						'66000001:66999999',                    '66000-001', '68370-001'),
            ('PB', 'João Pessoa', 		'58000000:58999999', 						'58000001:58099999',                    '58000-001', '58930-000'),
            ('PE', 'Recife', 			'50000000:56999999', 						'50000001:52999999',                    '50000-001', '53690-000'),
            ('PI', 'Teresina', 			'64000000:64999999', 						'64000001:64099999',                    '64000-001', '64235-000'),
            ('PR', 'Curitiba', 			'80000000:87999999', 						'80000001:82999999',                    '80000-001', '86800-001'),
            ('RJ', 'Rio de Janeiro', 	'20000000:28999999', 						'20000001:23799999',                    '20000-001', '27300-001'),
            ('RN', 'Natal', 			'59000000:59999999', 						'59000001:59139999',                    '59000-001', '59780-000'),
            ('RO', 'Porto Velho', 		'76800000:76999999', 						'76800001:76834999',                    '76800-001', '76870-001'),
            ('RR', 'Boa Vista', 		'69300000:69399999', 						'69300001:69339999',                    '69300-001', '69343-000'),
            ('RS', 'Porto Alegre', 		'90000000:99999999', 						'90000001:91999999',                    '90000-001', '97540-001'),
            ('SC', 'Florianópolis', 	'88000000:89999999', 						'88000001:88099999',                    '88000-001', '89245-000'),
            ('SE', 'Aracajú', 			'49000000:49999999', 						'49000001:49098999',                    '49000-001', '49500-000'),
            ('SP', 'São Paulo', 		'01000000:19999999', 						'01000001:05999999/08000000:08499999',  '01000-001', '17800-000'),
            ('TO', 'Palmas', 			'77000000:77999999', 						'77000001:77249999',                    '77000-001', '77645-000');";

        Db::getInstance()->execute($sql);

    }

    private function recuperaCadastroEmbalagens() {

        $sql = 'SELECT *
                FROM '._DB_PREFIX_.'fkcorreiosg2_embalagens
                WHERE id_shop = '.(int)$this->context->shop->id.'
                Order By cubagem';

        return Db::getInstance()->ExecuteS($sql);
    }

    private function incluiEmbalagensIniciais() {

        $sql = "INSERT INTO "._DB_PREFIX_."fkcorreiosg2_embalagens(id_shop, descricao, comprimento, altura, largura, peso, cubagem, custo, ativo) VALUES
            (".$this->context->shop->id.", 'Caixa 1', 16.00, 2.00,  11.00, 0.20, 352.000000,  0.00, 1),
            (".$this->context->shop->id.", 'Caixa 2', 16.00, 4.00,  11.00, 0.25, 704.000000,  0.00, 1),
            (".$this->context->shop->id.", 'Caixa 3', 16.00, 6.00,  11.00, 0.30, 1056.000000, 0.00, 1),
            (".$this->context->shop->id.", 'Caixa 4', 16.00, 8.00,  11.00, 0.35, 1408.000000, 0.00, 1),
            (".$this->context->shop->id.", 'Caixa 5', 16.00, 10.00, 11.00, 0.40, 1760.000000, 0.00, 1);";

        Db::getInstance()->execute($sql);
    }

    private function incluiEmbalagem() {

        $dados = array(
            'id_shop'		=> $this->context->shop->id,
            'descricao' 	=> 'Nova Caixa',
            'peso'          => '0',
            'custo'     	=> '0',
            'ativo' 		=> '1'
        );

        Db::getInstance()->insert('fkcorreiosg2_embalagens', $dados);
    }

    private function excluiEmbalagem($embalagens) {

        if ($embalagens) {
            foreach ($embalagens as $embalagem) {
                Db::getInstance()->delete('fkcorreiosg2_embalagens', 'id = '.(int)$embalagem);
            }
        }
    }

    private function recuperaEspCorreios() {

        $sql = 'SELECT *
                FROM '._DB_PREFIX_.'fkcorreiosg2_especificacoes_correios
                WHERE id_shop = '.(int)$this->context->shop->id;

        return Db::getInstance()->ExecuteS($sql);
    }

    private function incluiEspecificacoesCorreios() {

        $sql = "INSERT INTO `"._DB_PREFIX_."fkcorreiosg2_especificacoes_correios` (`id_shop`, `tabela_offline`, `servico`, `cod_servico`, `cod_administrativo`, `senha`, `comprimento_min`, `comprimento_max`, `largura_min`, `largura_max`, `altura_min`, `altura_max`, `somatoria_dimensoes_max`, `peso_estadual_max`, `peso_nacional_max`, `intervalo_pesos_estadual`, `intervalo_pesos_nacional`, `cubagem_max_isenta`, `cubagem_base_calculo`, `mao_propria_valor`, `aviso_recebimento_valor`, `valor_declarado_percentual`, `valor_declarado_max`, `seguro_automatico_valor`) VALUES
			('".$this->context->shop->id."', '0', 'E-SEDEX', 	'81019', '', '', '16', '105', '11', '105', '2', '105', '200', '15', '15', '0.3/1/2/3/4/5/6/7/8/9/10/11/12/13/14/15', 												'0.3/1/2/3/4/5/6/7/8/9/10/11/12/13/14/15', 												'60000',    '6000', 	'4.30', '3.20', '1.5', '10000', '50'),
			('".$this->context->shop->id."', '1', 'PAC', 		'41106', '', '', '16', '105', '11', '105', '2', '105', '200', '30', '30', '1/2/3/4/5/6/7/8/9/10/11/12/13/14/15/16/17/18/19/20/21/22/23/24/25/26/27/28/29/30', 	    '1/2/3/4/5/6/7/8/9/10/11/12/13/14/15/16/17/18/19/20/21/22/23/24/25/26/27/28/29/30',     '0',        '6000',		'4.30', '3.20', '1.5', '10000', '50'),
			('".$this->context->shop->id."', '0', 'PAC-GF',		'41300', '', '', '16', '150', '11', '150', '2', '150', '300', '30', '30', '1/2/3/4/5/6/7/8/9/10/11/12/13/14/15/16/17/18/19/20/21/22/23/24/25/26/27/28/29/30', 	    '1/2/3/4/5/6/7/8/9/10/11/12/13/14/15/16/17/18/19/20/21/22/23/24/25/26/27/28/29/30',     '0',        '6000',		'4.30', '3.20', '1.5', '10000', '50'),
			('".$this->context->shop->id."', '1', 'SEDEX', 		'40010', '', '', '16', '105', '11', '105', '2', '105', '200', '30', '30', '0.3/1/2/3/4/5/6/7/8/9/10/11/12/13/14/15/16/17/18/19/20/21/22/23/24/25/26/27/28/29/30', 	'0.3/1/2/3/4/5/6/7/8/9/10/11/12/13/14/15/16/17/18/19/20/21/22/23/24/25/26/27/28/29/30', '60000',    '6000', 	'4.30', '3.20', '1.5', '10000', '50'),
			('".$this->context->shop->id."', '0', 'SEDEX 10', 	'40215', '', '', '16', '105', '11', '105', '2', '105', '200', '10', '10', '0.3/1/2/3/4/5/6/7/8/9/10', 																'0.3/1/2/3/4/5/6/7/8/9/10', 															'60000',    '6000', 	'4.30', '3.20', '1.5', '10000', '75'),
			('".$this->context->shop->id."', '0', 'SEDEX 12', 	'40169', '', '', '16', '105', '11', '105', '2', '105', '200', '10', '10', '0.3/1/2/3/4/5/6/7/8/9/10', 																'0.3/1/2/3/4/5/6/7/8/9/10', 															'60000',    '6000', 	'4.30', '3.20', '1.5', '10000', '75'),
			('".$this->context->shop->id."', '0', 'SEDEX HOJE',	'40290', '', '', '16', '105', '11', '105', '2', '105', '200', '10', '10', '0.3/1/2/3/4/5/6/7/8/9/10', 																'0.3/1/2/3/4/5/6/7/8/9/10', 															'60000',    '6000', 	'4.30', '3.20', '1.5', '10000', '75');";

        Db::getInstance()->execute($sql);

    }

    private function recuperaServicosCorreios() {

        // Servicos dos Correios
        $sql = 'SELECT
                  '._DB_PREFIX_.'fkcorreiosg2_servicos.*,
                  '._DB_PREFIX_.'fkcorreiosg2_especificacoes_correios.servico
                FROM '._DB_PREFIX_.'fkcorreiosg2_servicos
                  INNER JOIN '._DB_PREFIX_.'fkcorreiosg2_especificacoes_correios
                    ON '._DB_PREFIX_.'fkcorreiosg2_servicos.id_especificacao = '._DB_PREFIX_.'fkcorreiosg2_especificacoes_correios.id
                WHERE '._DB_PREFIX_.'fkcorreiosg2_servicos.id_shop = '.(int)$this->context->shop->id;

        return Db::getInstance()->ExecuteS($sql);
    }

    private function incluiServicos() {

        // Instacia FKcorreiosClass
        $fkclass = new FKcorreiosg2Class();

        // Recupera dados da tabela de especificacoes dos Correios
        $sql = 'SELECT id, servico
                FROM '._DB_PREFIX_.'fkcorreiosg2_especificacoes_correios
                WHERE id_shop = '.(int)$this->context->shop->id;

        $espCorreios = Db::getInstance()->ExecuteS($sql);

        foreach ($espCorreios as $reg) {

            // Inclui Carrier no Prestashop
            $parm = array(
                'name' 					=> $reg['servico'],
                'id_tax_rules_group' 	=> 0,
                'active' 				=> false,
                'deleted' 				=> false,
                'shipping_handling' 	=> false,
                'range_behavior' 		=> true,
                'is_module' 			=> true,
                'shipping_external' 	=> true,
                'shipping_method' 		=> 0,
                'external_module_name' 	=> $this->name,
                'need_range' 			=> true,
                'url' 					=> Configuration::get('FKCORREIOSG2_URL_RASTREIO_CORREIOS'),
                'is_free' 				=> false,
                'grade' 				=> 0,
            );

            $idCarrier = $fkclass->instalaCarrier($parm);

            // Insere os registros na tabela de servicos
            $dados = array(
                'id_shop' 		        => $this->context->shop->id,
                'id_especificacao'      => $reg['id'],
                'id_carrier' 	        => $idCarrier,
                'filtro_regiao_uf'      => 1,
                'grade' 		        => 0,
                'ativo' 		        => 0,
                'percentual_desconto'   => 0,
                'valor_pedido_desconto' => 0
            );

            Db::getInstance()->insert('fkcorreiosg2_servicos', $dados);
        }

    }

    private function recuperaRegioesFreteGratis() {

        $sql = 'SELECT *
                FROM '._DB_PREFIX_.'fkcorreiosg2_frete_gratis
                WHERE id_shop = '.(int)$this->context->shop->id;

        return Db::getInstance()->ExecuteS($sql);
    }

    private function recuperaTranspFreteGratis() {

        $transportadoras = array();

        // Servicos dos Correios
        $sql = "SELECT
                    "._DB_PREFIX_."fkcorreiosg2_servicos.id_carrier,
                    "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.servico AS transportadora
                FROM "._DB_PREFIX_."fkcorreiosg2_servicos
                  INNER JOIN "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios
                    ON "._DB_PREFIX_."fkcorreiosg2_servicos.id_especificacao = "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.id
                WHERE "._DB_PREFIX_."fkcorreiosg2_servicos.id_shop = ".(int)$this->context->shop->id;

        $transpCorreios = Db::getInstance()->ExecuteS($sql);

        // Recupera transportadoras dos Complementos
        $transpComplementos = array();

        $complementos = $this->recuperaComplementosFrete();

        foreach ($complementos as $reg) {

            // Cria path da classe do Complemento
            $path = _PS_MODULE_DIR_.$reg['modulo'].'/models/'.strtoupper(substr($reg['modulo'],0,2)).substr($reg['modulo'],2).'FreteClass.php';

            // Verifica se a classe existe
            if (file_exists($path)) {

                // Include da classe
                include_once($path);

                // Instancia a classe de frete do complemento
                $funcao = strtoupper(substr($reg['modulo'],0,2)).substr($reg['modulo'],2).'FreteClass';
                $freteClass = new $funcao;

                $transp = $freteClass->recuperaTranspFreteGratis();

                // Merge dos array
                $transpComplementos = array_merge($transpComplementos, $transp);
            }
        }

        // Merge dos arrays dos Correios e dos Complementos
        $transportadoras = array_merge($transpCorreios, $transpComplementos);

        return $transportadoras;
    }

    private function incluiFreteGratis() {

        $dados = array(
            'id_shop'		    => $this->context->shop->id,
            'nome_regiao' 	    => 'Nova Região',
            'filtro_regiao_uf'  => '1',
            'valor_pedido'      => '0',
            'ativo' 		    => '0'
        );

        Db::getInstance()->insert('fkcorreiosg2_frete_gratis', $dados);
    }

    private function excluiFreteGratis($id) {
        Db::getInstance()->delete('fkcorreiosg2_frete_gratis', '`id` = '.(int)$id);
    }

    private function recuperaEspCorreiosTabOffline() {

        $sql = "SELECT id, servico
                FROM "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios
                WHERE tabela_offline = '1' AND id_shop = ".(int)$this->context->shop->id;

        return Db::getInstance()->ExecuteS($sql);
    }

    private function recuperaTabOffline($minhaCidade = false) {

        if ($minhaCidade) {
            $sql = "SELECT *
                    FROM "._DB_PREFIX_."fkcorreiosg2_tabelas_offline
                    WHERE minha_cidade = '1' AND id_shop = ".(int)$this->context->shop->id;
        }else {
            $sql = "SELECT
                        "._DB_PREFIX_."fkcorreiosg2_tabelas_offline.*,
                        "._DB_PREFIX_."fkcorreiosg2_cadastro_cep.estado,
                        "._DB_PREFIX_."fkcorreiosg2_cadastro_cep.capital
                    FROM "._DB_PREFIX_."fkcorreiosg2_tabelas_offline
                        INNER JOIN "._DB_PREFIX_."fkcorreiosg2_cadastro_cep
                            ON "._DB_PREFIX_."fkcorreiosg2_tabelas_offline.id_cadastro_cep = "._DB_PREFIX_."fkcorreiosg2_cadastro_cep.id
                    WHERE "._DB_PREFIX_."fkcorreiosg2_tabelas_offline.id_shop = ".(int)$this->context->shop->id;
        }

        return Db::getInstance()->ExecuteS($sql);
    }

    private function incluiTabOffline() {

        // Recupera dados das Especificacoes dos Correios para as Tabelas Offline
        $espCorreios = $this->recuperaEspCorreiosTabOffline();

        // Recupera dados da tabela de Cadastro de Cep
        $cadCep = $this->recuperaCadastroCep();

        foreach ($espCorreios as $especificacao) {

            // Grava tabela Minha Cidade
            $dados = array(
                'id_shop'           => $this->context->shop->id,
                'id_especificacao'  => $especificacao['id'],
                'id_cadastro_cep'   => '0',
                'minha_cidade'      => '1'
            );

            Db::getInstance()->insert('fkcorreiosg2_tabelas_offline', $dados);

            // Grava tabela por UF
            foreach ($cadCep as $estado) {

                $dados = array(
                    'id_shop'           => $this->context->shop->id,
                    'id_especificacao'  => $especificacao['id'],
                    'id_cadastro_cep'   => $estado['id'],
                    'minha_cidade'      => '0'
                );

                Db::getInstance()->insert('fkcorreiosg2_tabelas_offline', $dados);
            }
        }

    }

    private function recuperaComplementosInstalados() {

        $sql = "SELECT *
                FROM "._DB_PREFIX_."fkcorreiosg2_complementos
                WHERE id_shop = ".(int)$this->context->shop->id;

        return Db::getInstance()->executeS($sql);
    }

    private function recuperaComplementosFrete() {

        $sql = "SELECT *
                FROM "._DB_PREFIX_."fkcorreiosg2_complementos
                WHERE frete = 1 AND
                      id_shop =".(int)$this->context->shop->id;

        return Db::getInstance()->executeS($sql);
    }

    private function verificaModuloInstalado($modulo) {

        $sql = "SELECT *
                FROM "._DB_PREFIX_."module
                WHERE name = '".$modulo."'";

        $moduloInstalado = Db::getInstance()->getRow($sql);

        if ($moduloInstalado) {
            return true;
        }

        return false;
    }

    private function excluiCache() {
        Db::getInstance()->delete('fkcorreiosg2_cache');
    }

    private function gravaDadosSmartyFrete($msgStatus, $idProduto = null, $transportadoras, $lightBox) {

        // Verifica mensagem das transportadoras
        $msgTransp = '';
        foreach ($transportadoras as $transp) {

            if ($transp['mensagem'] != '') {
                $msgTransp = $transp['mensagem'];
                break;
            }

        }

        $this->context->smarty->assign(array(
            'fkcorreiosg2' => array(
                'borda'             => Configuration::get('FKCORREIOSG2_BORDA'),
                'raioBorda'         => Configuration::get('FKCORREIOSG2_RAIO_BORDA'),
                'corFundo'          => Configuration::get('FKCORREIOSG2_COR_FUNDO'),
                'corFonteTitulo'    => Configuration::get('FKCORREIOSG2_COR_FONTE_TITULO'),
                'corBotao'          => Configuration::get('FKCORREIOSG2_COR_BOTAO'),
                'corFonteBotao'     => Configuration::get('FKCORREIOSG2_COR_FONTE_BOTAO'),
                'corFaixaMsg'       => Configuration::get('FKCORREIOSG2_COR_FAIXA_MSG'),
                'corFonteMsg'       => Configuration::get('FKCORREIOSG2_COR_FONTE_MSG'),
                'largura'           => Configuration::get('FKCORREIOSG2_LARGURA'),
                'lightBox'          => $lightBox,
                'msgStatus'         => $msgStatus,
                'cepCookie'         => $this->context->cookie->fkcorreiosg2_cep_destino,
                'msgTransp'         => $msgTransp,
                'idProduto'         => $idProduto,
                'transportadoras'   => $transportadoras,
            )
        ));
    }

    private function gravaDadosSmartyRastreio() {

        $this->context->smarty->assign(array(
            'fkcorreiosg2_rastreio' => array(
                'urlFuncoesRastreio'   => Configuration::get('FKCORREIOSG2_URL_FUNCOES_RASTREIO'),
            )
        ));
    }

    private function processaSimulador($origem, $bloco, $params) {

        if ($origem == 'produto') {
            // Retorna se nao for para mostrar em produtos
            if (Configuration::get('FKCORREIOSG2_BLOCO_PRODUTO') != 'on') {
                return false;
            }

            // Retorna se o bloco e Apos Descricao Resumida e nao for para mostrar
            if ($bloco == '0' and Configuration::get('FKCORREIOSG2_BLOCO_PRODUTO_POSICAO') != '0') {
                return false;
            }elseif ($bloco == '1' and Configuration::get('FKCORREIOSG2_BLOCO_PRODUTO_POSICAO') != '1') {
                return false;
            }elseif ($bloco == '2' and Configuration::get('FKCORREIOSG2_BLOCO_PRODUTO_POSICAO') != '2') {
                return false;
            }

            // Retorna se $params nao contem dados do produto (override ainda nao executado)
            if ($bloco == '0' and !isset($params['product'])) {
                return false;
            }

            // Retorna se for produto virtual
            if (is_array($params['product'])) {
                if ($params['product']['is_virtual'] == 1) {
                    return false;
                }
            } else {
                if ($params['product']->is_virtual == 1) {
                    return false;
                }
            }
        }else {
            // Retorna se nao for para mostrar no carrinho
            if (Configuration::get('FKCORREIOSG2_BLOCO_CARRINHO') != 'on') {
                return false;
            }

            // Retorna se o carrinho estiver vazio
            $products = $params['cart']->getProducts();
            if (!count($products)) {
                return false;
            }

            // Retorna se carrinho contiver somente produtos virtuais
            $virtual = true;

            foreach ($this->context->cart->getProducts() as $prod) {
                if ($prod['is_virtual'] == 0) {
                    $virtual = false;
                }
            }

            if ($virtual == true) {
                return false;
            }
        }

        // Verifica se e para processar o Frete
        $lightBox = false;
        $msgStatus = 'Aguardando CEP';
        $transpCorreios = array();
        $transpComplementos = array();
        $transportadoras = array();

        if ($origem == 'produto' and (Tools::isSubmit('btnSubmit') or Tools::getValue('origem') == 'adicCarrinho') or
            $origem == 'carrinho' and (Tools::isSubmit('btnSubmit') or $this->context->customer->isLogged() or isset($this->context->cookie->fkcorreiosg2_cep_destino))) {

            // Verifica uso do lightBox
            if ($origem == 'produto') {
                if (Configuration::get('FKCORREIOSG2_BLOCO_PRODUTO_LIGHTBOX') == 'on') {
                    $lightBox = true;
                }
            }

            // Recupera dados basicos
            $dadosBasicos = $this->recuperaDadosBasicosSimulador($origem, $params);

            if (!$dadosBasicos['status']) {
                $msgStatus = $dadosBasicos['msgErro'];
            }else {
                // Instancia CalculoFreteClass do módulo FKcorreiosg2
                $freteClass = new FKcorreiosg2FreteClass();

                if ($freteClass->calculaFreteSimulador($origem, $dadosBasicos, $params)) {
                    $transpCorreios = $freteClass->getTransportadoras();
                }

                // Processa transportadoras dos Complementos
                $transpComplementos = $this->processaFreteComplementos($origem, $dadosBasicos, $params);

                // Merge dos arrays dos Correios e dos Complementos
                $transportadoras = array_merge($transpCorreios, $transpComplementos);

                // Verifica se foram selecionadas transportadoras
                if (count($transportadoras) > 0) {
                    // Classifica transportadoras por valor do Frete
                    usort($transportadoras, array($this, 'ordenaValor'));

                    $msgStatus = 'Frete Calculado';
                }else {
                    $msgStatus = $this->l('Não existem transportadoras disponíveis para o CEP de Destino. Favor entrar em contato com o Atendimento ao Cliente');
                }
            }
        }

        if ($origem == 'produto') {
            $id_product = is_array($params['product']) ? $params['product']['id'] : $params['product']->id;
            $this->gravaDadosSmartyFrete($msgStatus, $id_product, $transportadoras, $lightBox);
        }else {
            $this->gravaDadosSmartyFrete($msgStatus, null, $transportadoras, false);
        }

        return true;
    }

    private function recuperaDadosBasicosSimulador($origem, $params) {

        // Inicializa variaveis gerais
        $cepOrigem = trim(preg_replace("/[^0-9]/", "", Configuration::get('FKCORREIOSG2_MEU_CEP')));
        $ufOrigem = '';
        $cepDestino = '';
        $ufDestino = '';
        $valorPedido = 0;
        $freteGratisValor = false;
        $transpFreteGratisValor = 0;

        // Recupera CEP destino
        if (Tools::getValue('origem') == 'adicCarrinho') {
            $cepDestino = Tools::getValue('cep');
        }else {
            if (Tools::isSubmit('btnSubmit')){
                $cepDestino = Tools::getValue('fkcorreiosg2_cep');
            }else {
                if ($origem == 'carrinho') {
                    // Se o cliente esta logado
                    if ($this->context->customer->isLogged()) {
                        $delivery_address = new Address($params['cart']->id_address_delivery);
                        $cepDestino = $delivery_address->postcode;
                    }else {
                        // Recupera CEP do cookie
                        if ($this->context->cookie->fkcorreiosg2_cep_destino) {
                            $cepDestino = $this->context->cookie->fkcorreiosg2_cep_destino;
                        }
                    }
                }
            }
        }

        // Valida CEP destino
        $cepDestino = trim(preg_replace("/[^0-9]/", "", $cepDestino));

        // Retorna erro se o CEP for invalido
        if (strlen($cepDestino) <> 8) {
            return array(
                'status'    => false,
                'msgErro'   => 'CEP Destino inválido',
            );
        }

        // Instancia FKcorreiosg2Class
        $fkclass = new FKcorreiosg2Class();

        // Recupera UF destino
        $ufDestino = $fkclass->recuperaUF($cepDestino);

        // Retorna erro se nao localizada a UF
        if (!$ufDestino) {
            return array(
                'status'    => false,
                'msgErro'   => 'UF Destino não localizada',
            );
        }

        // Recupera UF origem
        $ufOrigem = $fkclass->recuperaUF($cepOrigem);

        // Retorna erro se nao localizada a UF
        if (!$ufOrigem) {
            return array(
                'status'    => false,
                'msgErro'   => 'UF Origem não localizada',
            );
        }

        // Recupera valor do Pedido
        if ($origem == 'produto') {
            // Calcula valor do pedido (como esta no Detalhes do Produto e o valor do produto)
            $preco = $params['product']['price'];
            $impostos = $params['product']['rate'];
            $valorPedido = $preco * (1 + ($impostos / 100));
        }else {
            // Recupera o valor do pedido
            $valorPedido = $this->context->cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
        }

        // Verifica frete gratis por valor
        $freteGratis = $fkclass->filtroFreteGratisValor($valorPedido, $cepDestino, $ufDestino);

        if ($freteGratis['status']) {
            $freteGratisValor = true;
            $transpFreteGratisValor = $freteGratis['idCarrier'];
        }

        return array(
            'status'                    => true,
            'cepOrigem'                 => $cepOrigem,
            'ufOrigem'                  => $ufOrigem,
            'cepDestino'                => $cepDestino,
            'ufDestino'                 => $ufDestino,
            'valorPedido'               => $valorPedido,
            'freteGratisValor'          => $freteGratisValor,
            'transpFreteGratisValor'    => $transpFreteGratisValor
        );

    }

    private function processaFreteComplementos($origem, $dadosBasicos, $params) {

        // Inicializa variaveis
        $transpComplementos = array();

        // Recupera complementos que processam frete
        $complementos = $this->recuperaComplementosFrete();

        foreach ($complementos as $reg) {

            if ($this->verificaModuloInstalado($reg['modulo'])) {

                // Cria path da classe do Complemento
                $path = _PS_MODULE_DIR_.$reg['modulo'].'/models/'.strtoupper(substr($reg['modulo'],0,2)).substr($reg['modulo'],2).'FreteClass.php';

                // Verifica se a classe existe
                if (file_exists($path)) {

                    // Include da classe
                    include_once($path);

                    // Instancia a classe de frete do complemento
                    $funcao = strtoupper(substr($reg['modulo'],0,2)).substr($reg['modulo'],2).'FreteClass';
                    $freteClass = new $funcao;

                    if ($freteClass->calculaFreteSimulador($origem, $dadosBasicos, $params)) {

                        $transp = $freteClass->getTransportadoras();

                        // Merge dos array
                        $transpComplementos = array_merge($transpComplementos, $transp);
                    }
                }
            }
        }

        return $transpComplementos;
    }

    private function atualizaVersaoModulo() {

        try {
            $db = Db::getInstance();

            // Retira campo volume_max utilizado nas versões anteriores a v1.2.2
            $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '"._DB_PREFIX_."fkcorreiosg2_especificacoes_correios' AND column_name = 'volume_max' AND table_schema = '"._DB_NAME_."'";
            $dados = $db->getRow($sql);

            if ($dados) {
                $sql = "ALTER TABLE "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios DROP COLUMN volume_max;";
                $db-> Execute($sql);
            }

        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    private function criaMenus() {

        // Cria tab principal
        $main_tab = new Tab();
        $main_tab->class_name = $this->_tabClassName['principal']['className'];

        $languages = Language::getLanguages();
        foreach ($languages as $language) {
            $main_tab->name[$language['id_lang']] = $this->_tabClassName['principal']['name'];
        }

        $main_tab->id_parent = 0;
        $main_tab->module = $this->name;
        $main_tab->active = false;
        $main_tab->add();

        return true;
    }

    private function excluiMenus() {

        $id_tab = Tab::getIdFromClassName($this->_tabClassName['principal']['className']);

        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }

        return true;
    }

    private function criaTabelas() {

        $db = Db::getInstance();

        // Cria a tabela de cadastro de cep
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fkcorreiosg2_cadastro_cep` (
            	`id` 			    int(10)     NOT NULL AUTO_INCREMENT,
            	`estado` 		    varchar(2),
            	`capital` 		    varchar(50),
            	`cep_estado` 	    varchar(150),
           	 	`cep_capital` 	    varchar(150),
           	 	`cep_base_capital` 	varchar(9),
           	 	`cep_base_interior`	varchar(9),
            	PRIMARY KEY  (`id`)
            	) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
        $db-> Execute($sql);

        // Cria tabela de cadastro de embalagens
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fkcorreiosg2_embalagens` (
	            `id` 			int(10) 		NOT NULL AUTO_INCREMENT,
				`id_shop`		int(10),
	            `descricao` 	varchar(50),
	            `comprimento` 	decimal(20,2),
	            `altura` 		decimal(20,2),
	            `largura` 		decimal(20,2),
	            `peso` 			decimal(20,2),
	            `cubagem` 		decimal(20,6),
	            `custo` 		decimal(20,2),
	            `ativo` 		tinyint(1),
	            PRIMARY KEY (`id`)
	            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
        $db-> Execute($sql);

        // Cria tabela com as Especificacoes dos Correios
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fkcorreiosg2_especificacoes_correios` (
	            `id` 							int(10) 		NOT NULL AUTO_INCREMENT,
	            `id_shop`		                int(10),
	            `tabela_offline`		        tinyint(1),
	            `servico` 						varchar(50),
				`cod_servico`					varchar(50),
				`cod_administrativo` 			varchar(50),
            	`senha` 						varchar(10),
	            `comprimento_min` 				decimal(20,2),
				`comprimento_max` 				decimal(20,2),
				`largura_min` 					decimal(20,2),
				`largura_max` 					decimal(20,2),
	            `altura_min` 					decimal(20,2),
				`altura_max` 					decimal(20,2),
				`somatoria_dimensoes_max` 		decimal(20,2),
	            `peso_estadual_max`				decimal(20,2),
				`peso_nacional_max`				decimal(20,2),
				`intervalo_pesos_estadual`		varchar(250),
				`intervalo_pesos_nacional`		varchar(250),
				`cubagem_max_isenta`			decimal(20,5),
				`cubagem_base_calculo`			decimal(20,5),
				`mao_propria_valor`				decimal(20,2),
				`aviso_recebimento_valor`		decimal(20,2),
				`valor_declarado_percentual`	decimal(20,2),
				`valor_declarado_max`			decimal(20,2),
				`seguro_automatico_valor`       decimal(20,2),
	            PRIMARY KEY (`id`)
	            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
        $db-> Execute($sql);

        // Cria tabela com os servicos dos correios
        $sql = 'CREATE TABLE IF NOT EXISTS `' ._DB_PREFIX_. 'fkcorreiosg2_servicos` (
            	`id` 				    int(10) 	NOT NULL AUTO_INCREMENT,
				`id_shop`			    int(10),
				`id_especificacao`      int(10),
            	`id_carrier` 		    int(10),
            	`filtro_regiao_uf`	    int(10),
				`regiao_uf`			    varchar(100),
				`regiao_cep`		    text,
				`regiao_cep_excluido`	text,
            	`grade` 			    int(10),
            	`percentual_desconto`   decimal(20,2),
            	`valor_pedido_desconto` decimal(20,2),
            	`ativo` 			    tinyint(1),
            	PRIMARY KEY (`id`),
				INDEX (`id_carrier`, `id_shop`)
            	) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
        $db-> Execute($sql);

        // Cria tabela com as configuracoes do frete gratis
        $sql = 'CREATE TABLE IF NOT EXISTS `' ._DB_PREFIX_. 'fkcorreiosg2_frete_gratis` (
            	`id` 					int(10) 		NOT NULL AUTO_INCREMENT,
            	`id_shop`			    int(10),
				`id_carrier`            int(10),
				`nome_regiao`  			varchar(100),
				`filtro_regiao_uf`	    int(10),
				`regiao_uf`				varchar(100),
				`regiao_cep`			text,
				`regiao_cep_excluido`	text,
				`valor_pedido`			decimal(20,2),
				`id_produtos`			text,
				`ativo` 			    tinyint(1),
				INDEX (`id_carrier`),
            	PRIMARY KEY (`id`)
            	) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
        $db-> Execute($sql);

        // Cria a tabela de precos offline dos correios
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fkcorreiosg2_tabelas_offline` (
                `id`                        int(10)     NOT NULL AUTO_INCREMENT,
                `id_shop`		            int(10),
                `id_especificacao`          int(10),
                `id_cadastro_cep`           int(10),
                `prazo_entrega_cidade`      int(10),
                `prazo_entrega_capital`     int(10),
                `prazo_entrega_interior`    int(10),
                `tabela_cidade`             text,
                `tabela_capital`            text,
                `tabela_interior`           text,
                `minha_cidade` 		        tinyint(1),
                INDEX (`id_especificacao`),
                PRIMARY KEY  (`id`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
        $db-> Execute($sql);

        // Cria tabela de controle de complementos
        $sql = 'CREATE TABLE IF NOT EXISTS `' ._DB_PREFIX_. 'fkcorreiosg2_complementos` (
            	`id` 					int(10) 		NOT NULL AUTO_INCREMENT,
            	`id_shop`			    int(10),
            	`modulo`                varchar(50),
            	`descricao`             varchar(100),
            	`frete`                 tinyint(1),
            	INDEX (`modulo`),
            	PRIMARY KEY (`id`)
            	) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
        $db-> Execute($sql);

        // Cria a tabela de cache
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fkcorreiosg2_cache` (
                `id`            int(10)     NOT NULL AUTO_INCREMENT,
                `hash`          varchar(32),
                `valor_frete`   decimal(20,2),
                `prazo_entrega` int(10),
                `msg_correios`  text,
                INDEX (`hash`),
                PRIMARY KEY  (`id`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
        $db-> Execute($sql);

        return true;

    }

    private function excluiTabelas() {

        // Exclui as tabelas
        $sql = "DROP TABLE IF EXISTS `"._DB_PREFIX_."fkcorreiosg2_cadastro_cep`;";
        Db::getInstance()->execute($sql);

        $sql = "DROP TABLE IF EXISTS `"._DB_PREFIX_."fkcorreiosg2_embalagens`;";
        Db::getInstance()->execute($sql);

        $sql = "DROP TABLE IF EXISTS `"._DB_PREFIX_."fkcorreiosg2_especificacoes_correios`;";
        Db::getInstance()->execute($sql);

        $sql = "DROP TABLE IF EXISTS `"._DB_PREFIX_."fkcorreiosg2_servicos`;";
        Db::getInstance()->execute($sql);

        $sql = "DROP TABLE IF EXISTS `"._DB_PREFIX_."fkcorreiosg2_frete_gratis`;";
        Db::getInstance()->execute($sql);

        $sql = "DROP TABLE IF EXISTS `"._DB_PREFIX_."fkcorreiosg2_tabelas_offline`;";
        Db::getInstance()->execute($sql);

        $sql = "DROP TABLE IF EXISTS `"._DB_PREFIX_."fkcorreiosg2_complementos`;";
        Db::getInstance()->execute($sql);

        $sql = "DROP TABLE IF EXISTS `"._DB_PREFIX_."fkcorreiosg2_cache`;";
        Db::getInstance()->execute($sql);

    }

    static function ordenaValor($a, $b) {

        if ($a['valorFrete'] == $b['valorFrete']) {
            return 0;
        }
        return ($a['valorFrete'] < $b['valorFrete']) ? -1 : 1;
    }

}

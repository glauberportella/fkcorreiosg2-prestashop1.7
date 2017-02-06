<?php

include_once('FKcorreiosg2Class.php');
include_once('CorreiosClass.php');

class FKcorreiosg2FreteClass {

    // Variaveis das funcoes de retorno
    private $transportadoras = array();
    private $freteCarrier = array();

    // Retorno utilizado no simulador de frete
    public function getTransportadoras() {
        return $this->transportadoras;
    }

    // Retorno utilizado no getOrderShippingCost
    public function getFreteCarrier() {
        return $this->freteCarrier;
    }

    public function __construct() {
        $this->context = Context::getContext();
    }

    public function calculaFreteSimulador($origem, $dadosBasicos, $params) {

        // Inicializa variaveis gerais
        $pesoPedido = 0;
        $freteGratisProdutos = false;
        $transpFreteGratisProdutos = 0;

        // Recupera dados gerais
        $cepOrigem = $dadosBasicos['cepOrigem'];
        $ufOrigem = $dadosBasicos['ufOrigem'];
        $cepDestino = $dadosBasicos['cepDestino'];
        $ufDestino = $dadosBasicos['ufDestino'];
        $valorPedido = $dadosBasicos['valorPedido'];
        $freteGratisValor = $dadosBasicos['freteGratisValor'];
        $transpFreteGratisValor = $dadosBasicos['transpFreteGratisValor'];

        // Instancia FKcorreiosg2Class
        $fkclass = new FKcorreiosg2Class();

        // Processa o frete
        $sql = "SELECT
                  "._DB_PREFIX_."fkcorreiosg2_servicos.*,
                  "._DB_PREFIX_."carrier.id_reference,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.tabela_offline,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.servico,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.cod_servico,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.cod_administrativo,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.senha,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.valor_declarado_max,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.cubagem_max_isenta,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.cubagem_base_calculo,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.mao_propria_valor,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.aviso_recebimento_valor,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.valor_declarado_percentual,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.seguro_automatico_valor
                FROM "._DB_PREFIX_."fkcorreiosg2_servicos
                  INNER JOIN "._DB_PREFIX_."carrier
                    ON "._DB_PREFIX_."fkcorreiosg2_servicos.id_carrier = "._DB_PREFIX_."carrier.id_carrier
                  INNER JOIN "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios
                    ON "._DB_PREFIX_."fkcorreiosg2_servicos.id_especificacao = "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.id
                WHERE "._DB_PREFIX_."fkcorreiosg2_servicos.ativo = 1 AND
                      "._DB_PREFIX_."fkcorreiosg2_servicos.id_shop = ".(int)$this->context->shop->id;

        $servicos = Db::getInstance()->executeS($sql);

        foreach ($servicos as $reg) {

            // Inicializa variaveis por servico
            $produtos = array();
            $embalagens = array();

            // Verifica se o servico atende a regiao
            if (!$fkclass->filtroRegiao($reg, $cepDestino, $ufDestino)) {
                continue;
            }

            // Filtro de grupo de clientes por transportadora
            if (!$fkclass->filtroClienteTransportadora($reg['id_carrier'])) {
                continue;
            }

            // Cria array de produtos
            if ($origem == 'produto') {
                // Calcula cubagem
                $cubagem = $params['product']->height * $params['product']->width * $params['product']->depth;

                // Calcula valor do produto
                $preco = $params['product']->price;
                $impostos = $params['product']->tax_rate;
                $valorProduto = $preco * (1 + ($impostos / 100));

                // Recupera o peso do pedido
                $pesoPedido = $params['product']->weight;

                $produtos[] = array(
                    'id'                            => $params['product']->id,
                    'altura'                        => $params['product']->height,
                    'largura'                       => $params['product']->width,
                    'comprimento'                   => $params['product']->depth,
                    'peso'                          => $params['product']->weight,
                    'cubagem'                       => $cubagem,
                    'valorProduto'                  => $valorProduto,
                    'adicionalEnvio'                => $params['product']->additional_shipping_cost,
                    'freteGratisProduto'            => false,
                );
            }else {
                foreach ($this->context->cart->getProducts() as $prod) {

                    // Ignora o produto se for virtual
                    if ($prod['is_virtual'] == 1) {
                        continue;
                    }

                    // Calcula cubagem
                    $cubagem = $prod['height'] * $prod['width'] * $prod['depth'];

                    for ($qty = 0; $qty < $prod['quantity']; $qty++) {

                        // Calcula o peso do pedido
                        $pesoPedido += $prod['weight'];

                        $produtos[] = array(
                            'id'                            => $prod['id_product'],
                            'altura'                        => $prod['height'],
                            'largura'                       => $prod['width'],
                            'comprimento'                   => $prod['depth'],
                            'peso'                          => $prod['weight'],
                            'cubagem'                       => $cubagem,
                            'valorProduto'                  => $prod['price_wt'],
                            'adicionalEnvio'                => $prod['additional_shipping_cost'],
                            'freteGratisProduto'            => false,
                        );
                    }
                }

            }

            // Processa os produtos
            foreach ($produtos as $key => $prod) {

                // Filtro de produto por transportadora
                if (!$fkclass->filtroProdutoTransportadora($prod['id'], $reg['id_reference'])) {
                    continue 2;
                }

                // Filtro por dimensoes e peso por transportadora
                if (!$fkclass->filtroDimensoesPesoTransportadora($prod['id'], $reg['id_carrier'], $pesoPedido)) {
                    continue 2;
                }

                // Filtro de frete gratis por produto - altera o array de produtos
                if ($fkclass->filtroFreteGratisProduto($prod['id'], $reg['id_carrier'], $cepDestino, $ufDestino)) {

                    $freteGratisProdutos = true;
                    $transpFreteGratisProdutos = $reg['id_carrier'];

                    // Altera array de produtos
                    $produtos[$key]['freteGratisProduto'] = true;
                    $produtos[$key]['adicionalEnvio'] = 0;
                }
            }

            // Processa embalagens
            switch(Configuration::get('FKCORREIOSG2_EMBALAGEM')) {

                case 0:
                    $embalagens = $this->processaEmbalagemIndividual($reg['id_especificacao'], $produtos, $ufOrigem, $ufDestino);
                    break;

                case 1:
                    $embalagens = $this->processaEmbalagemPadrao($reg['id_especificacao'], $produtos, $ufOrigem, $ufDestino);
                    break;

                case 2:
                    $embalagens = $this->processaPacote($reg['id_especificacao'], $produtos, $ufOrigem, $ufDestino);
                    break;

            }

            // Ignora transportadora se nao existirem embalagens (dimensoes fora do permitido)
            if (!$embalagens) {
                continue;
            }

            // Ignora transportadora se Frete Gratis por Valor e configurado para mostrar somente a transportadora de Frete Gratis
            if (Configuration::get('FKCORREIOSG2_FRETE_GRATIS_DEMAIS_TRANSP') != 'on' and $transpFreteGratisValor != $reg['id_carrier'] and $freteGratisValor or
                Configuration::get('FKCORREIOSG2_FRETE_GRATIS_DEMAIS_TRANSP') != 'on' and $transpFreteGratisProdutos != $reg['id_carrier'] and $freteGratisProdutos) {
                continue;
            }

            // Monta array com os dados necessarios para o calculo
            $parm = array(
                'embalagens'                => $embalagens,
                'cubagemMaxIsenta'          => $reg['cubagem_max_isenta'],
                'cubagemBaseCalculo'        => $reg['cubagem_base_calculo'],
                'maoPropriaValor'           => $reg['mao_propria_valor'],
                'avisoRecebimentoValor'     => $reg['aviso_recebimento_valor'],
                'valorDeclaradoPercentual'  => $reg['valor_declarado_percentual'],
                'seguroAutomaticoValor'     => $reg['seguro_automatico_valor'],
                'cepOrigem'                 => $cepOrigem,
                'cepDestino'                => $cepDestino,
                'ufDestino'                 => $ufDestino,
                'freteGratisValor'          => $freteGratisValor,
                'transpFreteGratisValor'    => $transpFreteGratisValor,
                'idEspecificacao'           => $reg['id_especificacao'],
                'idTranspAtual'             => $reg['id'],
                'idCarrierAtual'            => $reg['id_carrier'],
                'tempoPreparacao'           => Configuration::get('FKCORREIOSG2_TEMPO_PREPARACAO'),
                'codServico'                => $reg['cod_servico'],
                'codAdministrativo'         => $reg['cod_administrativo'],
                'senha'                     => $reg['senha'],
                'valorDeclaradoMax'         => $reg['valor_declarado_max'],
                'valorPedido'               => $valorPedido,
                'valorPedidoDescontoFrete'  => $reg['valor_pedido_desconto'],
                'percentualDescontoFrete'   => $reg['percentual_desconto'],
            );

            // Calcula valor do frete dos Correios
            if (Configuration::get('FKCORREIOSG2_OFFLINE') == 'on') {
                // ignora a transportadora se nao possui tabela offline
                if (!$reg['tabela_offline']) {
                    continue;
                }

                $retorno = $this->calculaValorOffline($parm);
            }else {
                $retorno = $this->calculaValorOnline($parm);
            }

            // Ignora transportadora se nao calculado o valor do frete
            if (!$retorno['status']) {
                continue;
            }

            $valorFrete = $retorno['valorFrete'];

            // Formata prazo de entrega
            if (is_numeric($retorno['prazoEntrega'])) {
                if ($retorno['prazoEntrega'] == 0) {
                    $prazoEntrega = 'Entrega no mesmo dia';
                }else {
                    if ($retorno['prazoEntrega'] > 1) {
                        $prazoEntrega = 'Entrega em até '.$retorno['prazoEntrega'].' dias úteis';
                    }else {
                        $prazoEntrega = 'Entrega em '.$retorno['prazoEntrega'].' dia útil';
                    }
                }
            }else {
                $prazoEntrega = $retorno['prazoEntrega'];
            }

            // Grava array com as transportadoras
            $this->transportadoras[] = array(
                'urlLogo'               => Configuration::get('FKCORREIOSG2_URL_LOGO_PS').$reg['id_carrier'].'.jpg',
                'nomeTransportadora'    => $reg['servico'],
                'prazoEntrega'          => $prazoEntrega,
                'mensagem'              => (Configuration::get('FKCORREIOSG2_MSG_CORREIOS') == 'on' ? $retorno['msgCorreios'] : ''),
                'valorFrete'            => $valorFrete,
            );
        }

        return true;
    }

    public function calculaFretePS($params, $idCarrier) {

        $cepOrigem = trim(preg_replace("/[^0-9]/", "", Configuration::get('FKCORREIOSG2_MEU_CEP')));
        $cepDestino = '';
        $pesoPedido = 0;
        $freteGratisValor = false;
        $transpFreteGratisValor = 0;
        $freteGratisProdutos = false;
        $transpFreteGratisProdutos = 0;
        $produtos = array();
        $embalagens = array();

        // Se o cliente esta logado
        if ($this->context->customer->isLogged()) {

            $address = new Address($params->id_address_delivery);

            // Recupera CEP destino
            if ($address->postcode) {
                $cepDestino = $address->postcode;
            }
        }else {
            // Recupera CEP do cookie
            if ($this->context->cookie->fkcorreiosg2_cep_destino) {
                $cepDestino = $this->context->cookie->fkcorreiosg2_cep_destino;
            }
        }

        // Pedidos efetuados via Admin
        if (!$cepDestino) {
            $address = new Address($params->id_address_delivery);

            // Ignora Carrier se não existir CEP
            if (!$address->postcode) {
                return false;
            }

            $cepDestino = $address->postcode;
        }

        // Valida CEP destino
        $cepDestino = trim(preg_replace("/[^0-9]/", "", $cepDestino));

        // Ignora Carrier se o CEP for invalido
        if (strlen($cepDestino) <> 8) {
            return false;
        }

        // Instancia FKcorreiosg2Class
        $fkclass = new FKcorreiosg2Class();

        // Recupera UF destino
        $ufDestino = $fkclass->recuperaUF($cepDestino);

        // Ignora Carrier se UF Destino nao localizada
        if (!$ufDestino) {
            return false;
        }

        // Recupera UF origem
        $ufOrigem = $fkclass->recuperaUF($cepOrigem);

        // Ignora Carrier se UF Origem nao localizada
        if (!$ufOrigem) {
            return false;
        }

        // Recupera dados
        $sql = "SELECT
                  "._DB_PREFIX_."fkcorreiosg2_servicos.*,
                  "._DB_PREFIX_."carrier.id_reference,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.tabela_offline,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.servico,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.cod_servico,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.cod_administrativo,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.senha,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.valor_declarado_max,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.cubagem_max_isenta,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.cubagem_base_calculo,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.mao_propria_valor,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.aviso_recebimento_valor,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.valor_declarado_percentual,
                  "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.seguro_automatico_valor
                FROM "._DB_PREFIX_."fkcorreiosg2_servicos
                  INNER JOIN "._DB_PREFIX_."carrier
                    ON "._DB_PREFIX_."fkcorreiosg2_servicos.id_carrier = "._DB_PREFIX_."carrier.id_carrier
                  INNER JOIN "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios
                    ON "._DB_PREFIX_."fkcorreiosg2_servicos.id_especificacao = "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.id
                WHERE "._DB_PREFIX_."fkcorreiosg2_servicos.ativo = 1 AND
                      "._DB_PREFIX_."fkcorreiosg2_servicos.id_shop = ".(int)$this->context->shop->id. " AND
                      "._DB_PREFIX_."fkcorreiosg2_servicos.id_carrier = ".(int)$idCarrier;

        $servico = Db::getInstance()->getRow($sql);

        // Ignora Carrier se nenhum dado foi selecionado
        if (!$servico) {
            return false;
        }

        // Ignora Carrier se nao atende a regiao
        if (!$fkclass->filtroRegiao($servico, $cepDestino, $ufDestino)) {
            return false;
        }

        // Ignora Carrier - Filtro de grupo de clientes por transportadora
        if (!$fkclass->filtroClienteTransportadora($servico['id_carrier'])) {
            return false;
        }

        // Recupera valor do pedido
        if (isset($this->context->cart)) {
            $valorPedido = $this->context->cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
        }else {
            // Para pedidos efetuados via Admin
            $cart = new cart($params->id);
            $valorPedido = $cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
        }

        // Verifica frete gratis por valor
        $freteGratis = $fkclass->filtroFreteGratisValor($valorPedido, $cepDestino, $ufDestino);

        if ($freteGratis['status']) {
            $freteGratisValor = true;
            $transpFreteGratisValor = $freteGratis['idCarrier'];
        }

        // Cria array de produtos
        foreach ($params->getProducts() as $prod) {

            // Ignora o produto se for virtual
            if ($prod['is_virtual'] == 1) {
                continue;
            }

            // Calcula cubagem
            $cubagem = $prod['height'] * $prod['width'] * $prod['depth'];

            for ($qty = 0; $qty < $prod['quantity']; $qty++) {

                // Calcula o peso do pedido
                $pesoPedido += $prod['weight'];

                $produtos[] = array(
                    'id'                            => $prod['id_product'],
                    'altura'                        => $prod['height'],
                    'largura'                       => $prod['width'],
                    'comprimento'                   => $prod['depth'],
                    'peso'                          => $prod['weight'],
                    'cubagem'                       => $cubagem,
                    'valorProduto'                  => $prod['price_wt'],
                    'adicionalEnvio'                => $prod['additional_shipping_cost'],
                    'freteGratisProduto'            => false,
                );
            }
        }

        // Processa os produtos
        foreach ($produtos as $key => $prod) {

            // Ignora Carrier - Filtro de produto por transportadora
            if (!$fkclass->filtroProdutoTransportadora($prod['id'], $servico['id_reference'])) {
                return false;
            }

            // Filtro por dimensoes e peso por transportadora
            if (!$fkclass->filtroDimensoesPesoTransportadora($prod['id'], $servico['id_carrier'], $pesoPedido)) {
                return false;
            }

            // Filtro de frete gratis por produto - altera o array de produtos
            if ($fkclass->filtroFreteGratisProduto($prod['id'], $servico['id_carrier'], $cepDestino, $ufDestino)) {

                $freteGratisProdutos = true;
                $transpFreteGratisProdutos = $servico['id_carrier'];

                // Altera array de produtos
                $produtos[$key]['freteGratisProduto'] = true;
                $produtos[$key]['adicionalEnvio'] = 0;
            }
        }

        // Processa embalagens
        switch(Configuration::get('FKCORREIOSG2_EMBALAGEM')) {

            case 0:
                $embalagens = $this->processaEmbalagemIndividual($servico['id_especificacao'], $produtos, $ufOrigem, $ufDestino);
                break;

            case 1:
                $embalagens = $this->processaEmbalagemPadrao($servico['id_especificacao'], $produtos, $ufOrigem, $ufDestino);
                break;

            case 2:
                $embalagens = $this->processaPacote($servico['id_especificacao'], $produtos, $ufOrigem, $ufDestino);
                break;

        }

        // Ignora Carrier se nao existirem embalagens (dimensoes fora do permitido)
        if (!$embalagens) {
            return false;
        }

        // Ignora Carrier se Frete Gratis por Valor e configurado para mostrar somente a transportadora de Frete Gratis
        if (Configuration::get('FKCORREIOSG2_FRETE_GRATIS_DEMAIS_TRANSP') != 'on' and $transpFreteGratisValor != $servico['id_carrier'] and $freteGratisValor or
            Configuration::get('FKCORREIOSG2_FRETE_GRATIS_DEMAIS_TRANSP') != 'on' and $transpFreteGratisProdutos != $servico['id_carrier'] and $freteGratisProdutos) {
            return false;
        }

        // Monta array com os dados necessarios para o calculo
        $parm = array(
            'embalagens'                => $embalagens,
            'cubagemMaxIsenta'          => $servico['cubagem_max_isenta'],
            'cubagemBaseCalculo'        => $servico['cubagem_base_calculo'],
            'maoPropriaValor'           => $servico['mao_propria_valor'],
            'avisoRecebimentoValor'     => $servico['aviso_recebimento_valor'],
            'valorDeclaradoPercentual'  => $servico['valor_declarado_percentual'],
            'seguroAutomaticoValor'     => $servico['seguro_automatico_valor'],
            'cepOrigem'                 => $cepOrigem,
            'cepDestino'                => $cepDestino,
            'ufDestino'                 => $ufDestino,
            'freteGratisValor'          => $freteGratisValor,
            'transpFreteGratisValor'    => $transpFreteGratisValor,
            'idEspecificacao'           => $servico['id_especificacao'],
            'idTranspAtual'             => $servico['id'],
            'idCarrierAtual'            => $servico['id_carrier'],
            'tempoPreparacao'           => Configuration::get('FKCORREIOSG2_TEMPO_PREPARACAO'),
            'codServico'                => $servico['cod_servico'],
            'codAdministrativo'         => $servico['cod_administrativo'],
            'senha'                     => $servico['senha'],
            'valorDeclaradoMax'         => $servico['valor_declarado_max'],
            'valorPedido'               => $valorPedido,
            'valorPedidoDescontoFrete'  => $servico['valor_pedido_desconto'],
            'percentualDescontoFrete'   => $servico['percentual_desconto'],
        );

        // Calcula valor do frete dos Correios
        if (Configuration::get('FKCORREIOSG2_OFFLINE') == 'on') {
            // Ignora Carrier se nao possui tabela offline
            if (!$servico['tabela_offline']) {
                return false;
            }

            $retorno = $this->calculaValorOffline($parm);
        }else {
            $retorno = $this->calculaValorOnline($parm);
        }

        // Ignora Carrier se nao calculado o valor do frete
        if (!$retorno['status']) {
            return false;
        }

        $valorFrete = $retorno['valorFrete'];
        $prazoEntrega = $retorno['prazoEntrega'];

        // Grava array com os dados de frete
        $this->freteCarrier = array(
            'prazoEntrega'  => $prazoEntrega,
            'valorFrete'    => $valorFrete,
        );

        return true;
    }

    private function processaEmbalagemIndividual($idEspecificacao, $produtos, $ufOrigem, $ufDestino) {

        $embalagens = array();

        // Recupera as dimensoes permitidas
        $dimPesoPermitidos = $this->recuperaDimensoes($idEspecificacao, $ufOrigem, $ufDestino);

        foreach ($produtos as $prod) {

            // Retorna vazio se as dimensoes e peso do produto estiverem fora do permitido
            if ($prod['altura'] > $dimPesoPermitidos['altura_max'] Or $prod['largura'] > $dimPesoPermitidos['largura_max'] Or $prod['comprimento'] > $dimPesoPermitidos['comprimento_max'] Or
                $prod['peso']  > $dimPesoPermitidos['peso_maximo'] Or
                $prod['altura'] + $prod['largura'] + $prod['comprimento'] > $dimPesoPermitidos['somatoria_dimensoes_max']) {

                return array();
            }

            $embalagens[] = array(
                'altura'                        => ($prod['altura'] < $dimPesoPermitidos['altura_min'] ? $dimPesoPermitidos['altura_min'] : $prod['altura']),
                'largura'                       => ($prod['largura'] < $dimPesoPermitidos['largura_min'] ? $dimPesoPermitidos['largura_min'] : $prod['largura']),
                'comprimento'                   => ($prod['comprimento'] < $dimPesoPermitidos['comprimento_min'] ? $dimPesoPermitidos['comprimento_min'] : $prod['comprimento']),
                'pesoEmbalagem'                 => '0',
                'custoEmbalagem'                => '0',
                'cubagem'                       => $prod['cubagem'],
                'pesoProdutos'                  => $prod['peso'],
                'valorProdutos'                 => $prod['valorProduto'],
                'adicionalEnvio'                => $prod['adicionalEnvio'],
                'freteGratisProduto'            => $prod['freteGratisProduto'],
            );
        }

        return $embalagens;

    }

    public function processaEmbalagemPadrao($idEspecificacao, $produtos, $ufOrigem, $ufDestino) {

        // Recupera as dimensoes permitidas
        $dimPesoPermitidos = $this->recuperaDimensoes($idEspecificacao, $ufOrigem, $ufDestino);

        // Seleciona as embalagens validas para os Correios
        $sql = "SELECT *
                FROM "._DB_PREFIX_."fkcorreiosg2_embalagens
                WHERE   ativo = 1 AND
                        id_shop = ".$this->context->shop->id." AND
                        comprimento >= ".$dimPesoPermitidos['comprimento_min']." AND
                        comprimento <= ".$dimPesoPermitidos['comprimento_max']." AND
                        altura >= ".$dimPesoPermitidos['altura_min']." AND
                        altura <= ".$dimPesoPermitidos['altura_max']." AND
                        largura >= ".$dimPesoPermitidos['largura_min']." AND
                        largura <= ".$dimPesoPermitidos['largura_max']." AND
                        comprimento + altura + largura <= ".$dimPesoPermitidos['somatoria_dimensoes_max']."
                ORDER BY cubagem";

        $caixas = Db::getInstance()->executeS($sql);

        // Classifica produtos por cubagem
        usort($produtos, array($this, 'ordenaCubagem'));

        // Inicializa variaveis das embalagens
        $embalagens = array();

        $alturaEmbalagem = 0;
        $larguraEmbalagem = 0;
        $comprimentoEmbalagem = 0;
        $pesoEmbalagem = 0;
        $custoEmbalagem = 0;
        $cubagemEmbalagem = 0;

        $pesoAcumuladoProdutos = 0;
        $valorAcumuladoProdutos = 0;
        $valorAcumuladoAdicionalEnvio = 0;
        $cubagemAcumuladaProdutos = 0;

        // Adiciona os produtos em suas embalagens
        foreach ($produtos as $prod) {

            // Se peso do produto for igual a zero assume valor minimo
            if ($prod['peso'] > 0) {
                $pesoProduto = $prod['peso'];
            }else {
                $pesoProduto = 0.01;
            }

            // Retorna vazio se as dimensoes e peso do produto estiverem fora do permitido
            if ($prod['altura'] > $dimPesoPermitidos['altura_max'] Or
                $prod['largura'] > $dimPesoPermitidos['largura_max'] Or
                $prod['comprimento'] > $dimPesoPermitidos['comprimento_max'] Or
                $pesoProduto  > $dimPesoPermitidos['peso_maximo'] Or
                $prod['altura'] + $prod['largura'] + $prod['comprimento'] > $dimPesoPermitidos['somatoria_dimensoes_max']) {

                return array();
            }

            // Seleciona embalagem
            $embalagemSelecionada = $this->selecionaEmbalagem($caixas, $prod['cubagem']);

            // Retorna vazio se o peso do produto + embalagem estiverem fora do permitido
            if ($embalagemSelecionada) {
                if (($pesoProduto + $embalagemSelecionada['peso']) > $dimPesoPermitidos['peso_maximo']) {
                    return array();
                }
            }

            // Grava embalagem se produto for frete gratis
            if ($prod['freteGratisProduto']) {

                // Grava dados considerando as dimensoes minimas
                $embalagens[] = array(
                    'altura'                => ($prod['altura'] < $dimPesoPermitidos['altura_min'] ? $dimPesoPermitidos['altura_min'] : $prod['altura']),
                    'largura'               => ($prod['largura'] < $dimPesoPermitidos['largura_min'] ? $dimPesoPermitidos['largura_min'] : $prod['largura']),
                    'comprimento'           => ($prod['comprimento'] < $dimPesoPermitidos['comprimento_min'] ? $dimPesoPermitidos['comprimento_min'] : $prod['comprimento']),
                    'pesoEmbalagem'         => 0,
                    'custoEmbalagem'        => 0,
                    'cubagem'               => $prod['cubagem'],
                    'pesoProdutos'          => $pesoProduto,
                    'valorProdutos'         => $prod['valorProduto'],
                    'adicionalEnvio'        => $prod['adicionalEnvio'],
                    'freteGratisProduto'    => true
                );

                continue;
            }

            // Grava embalagem se nao existe embalagem para o produto
            if (!$embalagemSelecionada) {

                $embalagens[] = array(
                    'altura'                => ($prod['altura'] < $dimPesoPermitidos['altura_min'] ? $dimPesoPermitidos['altura_min'] : $prod['altura']),
                    'largura'               => ($prod['largura'] < $dimPesoPermitidos['largura_min'] ? $dimPesoPermitidos['largura_min'] : $prod['largura']),
                    'comprimento'           => ($prod['comprimento'] < $dimPesoPermitidos['comprimento_min'] ? $dimPesoPermitidos['comprimento_min'] : $prod['comprimento']),
                    'pesoEmbalagem'         => 0,
                    'custoEmbalagem'        => 0,
                    'cubagem'               => $prod['cubagem'],
                    'pesoProdutos'          => $pesoProduto,
                    'valorProdutos'         => $prod['valorProduto'],
                    'adicionalEnvio'        => $prod['adicionalEnvio'],
                    'freteGratisProduto'    => false
                );

                continue;
            }

            // Verifica se existe caixa para a cubagem acumulada somada a cubagem do produto atual
            $embalagemSelecionada = $this->selecionaEmbalagem($caixas, ($prod['cubagem'] + $cubagemAcumuladaProdutos));

            // Se embalagem nao localizada
            if (!$embalagemSelecionada Or (($pesoAcumuladoProdutos + $pesoProduto + $pesoEmbalagem) > $dimPesoPermitidos['peso_maximo'] And $dimPesoPermitidos['peso_maximo'] > 0)) {

                // Grava dados acumulados
                $embalagens[] = array(
                    'altura'                => $alturaEmbalagem,
                    'largura'               => $larguraEmbalagem,
                    'comprimento'           => $comprimentoEmbalagem,
                    'pesoEmbalagem'         => $pesoEmbalagem,
                    'custoEmbalagem'        => $custoEmbalagem,
                    'cubagem'               => $cubagemEmbalagem,
                    'pesoProdutos'          => $pesoAcumuladoProdutos,
                    'valorProdutos'         => $valorAcumuladoProdutos,
                    'adicionalEnvio'        => $valorAcumuladoAdicionalEnvio,
                    'freteGratisProduto'    => false
                );

                // Seleciona embalagem para o produto
                $embalagemSelecionada = $this->selecionaEmbalagem($caixas, $prod['cubagem']);

                // Inicializa variaveis
                $pesoAcumuladoProdutos = 0;
                $valorAcumuladoProdutos = 0;
                $valorAcumuladoAdicionalEnvio = 0;
                $cubagemAcumuladaProdutos = 0;
            }

            // Guarda os campos da embalagem
            $alturaEmbalagem = $embalagemSelecionada['altura'];
            $larguraEmbalagem = $embalagemSelecionada['largura'];
            $comprimentoEmbalagem = $embalagemSelecionada['comprimento'];
            $pesoEmbalagem = $embalagemSelecionada['peso'];
            $custoEmbalagem = $embalagemSelecionada['custo'];
            $cubagemEmbalagem = $embalagemSelecionada['cubagem'];

            // Acumula valores
            $pesoAcumuladoProdutos += $pesoProduto;
            $valorAcumuladoProdutos += $prod['valorProduto'];
            $valorAcumuladoAdicionalEnvio += $prod['adicionalEnvio'];
            $cubagemAcumuladaProdutos += $prod['cubagem'];
        }

        // Grava a ultima embalagem
        if ($pesoAcumuladoProdutos > 0) {

            $embalagens[] = array(
                'altura'                => $alturaEmbalagem,
                'largura'               => $larguraEmbalagem,
                'comprimento'           => $comprimentoEmbalagem,
                'pesoEmbalagem'         => $pesoEmbalagem,
                'custoEmbalagem'        => $custoEmbalagem,
                'cubagem'               => $cubagemEmbalagem,
                'pesoProdutos'          => $pesoAcumuladoProdutos,
                'valorProdutos'         => $valorAcumuladoProdutos,
                'adicionalEnvio'        => $valorAcumuladoAdicionalEnvio,
                'freteGratisProduto'    => false
            );
        }

        return $embalagens;
    }

    public function processaPacote($idEspecificacao, $produtos, $ufOrigem, $ufDestino) {

        // Recupera as dimensoes permitidas
        $dimPesoPermitidos = $this->recuperaDimensoes($idEspecificacao, $ufOrigem, $ufDestino);

        // Classifica produtos por cubagem
        usort($produtos, array($this, 'ordenaCubagem'));

        // Inicializa variaveis
        $embalagens = array();
        $alturaPacote = $dimPesoPermitidos['altura_min'];
        $larguraPacote = $dimPesoPermitidos['largura_min'];
        $comprimentoPacote = $dimPesoPermitidos['comprimento_min'];
        $valorAcumuladoProdutos = 0;
        $valorAcumuladoAdicionalEnvio = 0;
        $pesoAcumuladoProdutos = 0;
        $volumeAcumuladoProdutos = 0;

        $inicializarPacote = true;

        // Adiciona os produtos em embalagens virtuais
        foreach ($produtos as $prod) {

            // Se peso do produto for igual a zero assume valor minimo
            if ($prod['peso'] > 0) {
                $pesoProduto = $prod['peso'];
            }else {
                $pesoProduto = 0.01;
            }

            // Retorna vazio se as dimensoes e peso estiverem fora do permitido
            if ($prod['altura'] > $dimPesoPermitidos['altura_max'] Or
                $prod['largura'] > $dimPesoPermitidos['largura_max'] Or
                $prod['comprimento'] > $dimPesoPermitidos['comprimento_max'] Or
                $pesoProduto  > $dimPesoPermitidos['peso_maximo'] Or
                $prod['altura'] + $prod['largura'] + $prod['comprimento'] > $dimPesoPermitidos['somatoria_dimensoes_max']) {

                return array();
            }

            // Grava pacote se produto for frete gratis
            if ($prod['freteGratisProduto']) {

                // Grava dados considerando as dimensoes minimas
                $alturaTmp = ($prod['altura'] < $dimPesoPermitidos['altura_min'] ? $dimPesoPermitidos['altura_min'] : $prod['altura']);
                $larguraTmp = ($prod['largura'] < $dimPesoPermitidos['largura_min'] ? $dimPesoPermitidos['largura_min'] : $prod['largura']);
                $comprimentoTmp = ($prod['comprimento'] < $dimPesoPermitidos['comprimento_min'] ? $dimPesoPermitidos['comprimento_min'] : $prod['comprimento']);
                $volumeTmp = $alturaTmp * $larguraTmp * $comprimentoTmp;

                $embalagens[] = array(
                    'altura'                => $alturaTmp,
                    'largura'               => $larguraTmp,
                    'comprimento'           => $comprimentoTmp,
                    'pesoEmbalagem'         => 0,
                    'custoEmbalagem'        => 0,
                    'cubagem'               => $volumeTmp,
                    'pesoProdutos'          => $pesoProduto,
                    'valorProdutos'         => $prod['valorProduto'],
                    'adicionalEnvio'        => $prod['adicionalEnvio'],
                    'freteGratisProduto'    => true
                );

                continue;
            }

            // Inicializa o pacote se for o primeiro produto
            if ($inicializarPacote) {
                $inicializarPacote = false;

                $alturaPacote = $prod['altura'];
                $larguraPacote = $prod['largura'];
                $comprimentoPacote = $prod['comprimento'];
                $volumePacote = $alturaPacote * $larguraPacote * $comprimentoPacote;
            }

            // Verifica se o produto cabe no pacote atual
            if ($volumePacote >= ($volumeAcumuladoProdutos + $prod['cubagem'])) {
                // Acumula os dados
                $valorAcumuladoProdutos += $prod['valorProduto'];
                $valorAcumuladoAdicionalEnvio += $prod['adicionalEnvio'];
                $pesoAcumuladoProdutos += $pesoProduto;
                $volumeAcumuladoProdutos += $prod['cubagem'];

                // Vai para o proximo produto
                continue;
            }

            // Aumenta o pacote ate o caber o produto dentro dos limites dos Correios ou grava o pacote
            $gravarPacote = false;
            $alturaPacoteTmp = $alturaPacote;
            $larguraPacoteTmp = $larguraPacote;
            $comprimentoPacoteTmp = $comprimentoPacote;
            $volumePacoteTmp = $volumePacote;

            while ($volumePacoteTmp < ($volumeAcumuladoProdutos + $prod['cubagem'])){

                // Soma 1 na altura do pacote
                if ($alturaPacoteTmp < $dimPesoPermitidos['altura_max']) {
                    $alturaPacoteTmp++;

                    // Calcula volume do pacote atual
                    $volumePacoteTmp = $alturaPacoteTmp * $larguraPacoteTmp * $comprimentoPacoteTmp;
                }

                // Verifica se esta dentro das especificacoes dos Correios
                if ($pesoAcumuladoProdutos + $pesoProduto > $dimPesoPermitidos['peso_maximo'] Or
                    $alturaPacoteTmp + $larguraPacoteTmp + $comprimentoPacoteTmp > $dimPesoPermitidos['somatoria_dimensoes_max']) {

                    $gravarPacote = true;
                    break;
                }

                // Soma 1 na largura do pacote
                if ($larguraPacoteTmp < $dimPesoPermitidos['largura_max']) {
                    $larguraPacoteTmp++;

                    // Calcula volume do pacote atual
                    $volumePacoteTmp = $alturaPacoteTmp * $larguraPacoteTmp * $comprimentoPacoteTmp;
                }

                // Verifica se esta dentro das especificacoes dos Correios
                if ($pesoAcumuladoProdutos + $pesoProduto > $dimPesoPermitidos['peso_maximo'] Or
                    $alturaPacoteTmp + $larguraPacoteTmp + $comprimentoPacoteTmp > $dimPesoPermitidos['somatoria_dimensoes_max']) {

                    $gravarPacote = true;
                    break;
                }

                // Soma 1 no comprimento do pacote
                if ($comprimentoPacoteTmp < $dimPesoPermitidos['comprimento_max']) {
                    $comprimentoPacoteTmp++;

                    // Calcula volume do pacote atual
                    $volumePacoteTmp = $alturaPacoteTmp * $larguraPacoteTmp * $comprimentoPacoteTmp;
                }

                // Verifica se esta dentro das especificacoes dos Correios
                if ($pesoAcumuladoProdutos + $pesoProduto > $dimPesoPermitidos['peso_maximo'] Or
                    $alturaPacoteTmp + $larguraPacoteTmp + $comprimentoPacoteTmp > $dimPesoPermitidos['somatoria_dimensoes_max']) {

                    $gravarPacote = true;
                    break;
                }

            }

            // Grava pacote
            if ($gravarPacote) {

                $alturaTmp = ($alturaPacote < $dimPesoPermitidos['altura_min'] ? $dimPesoPermitidos['altura_min'] : $alturaPacote);
                $larguraTmp = ($larguraPacote < $dimPesoPermitidos['largura_min'] ? $dimPesoPermitidos['largura_min'] : $larguraPacote);
                $comprimentoTmp = ($comprimentoPacote < $dimPesoPermitidos['comprimento_min'] ? $dimPesoPermitidos['comprimento_min'] : $comprimentoPacote);
                $volumeTmp = $alturaTmp * $larguraTmp * $comprimentoTmp;

                $embalagens[] = array(
                    'altura'                => $alturaTmp,
                    'largura'               => $larguraTmp,
                    'comprimento'           => $comprimentoTmp,
                    'pesoEmbalagem'         => 0,
                    'custoEmbalagem'        => 0,
                    'cubagem'               => $volumeTmp,
                    'pesoProdutos'          => $pesoAcumuladoProdutos,
                    'valorProdutos'         => $valorAcumuladoProdutos,
                    'adicionalEnvio'        => $valorAcumuladoAdicionalEnvio,
                    'freteGratisProduto'    => false
                );

                // Reinicializa variaveis
                $alturaPacote = $prod['altura'];
                $larguraPacote = $prod['largura'];
                $comprimentoPacote = $prod['comprimento'];
                $volumePacote = $alturaPacote * $larguraPacote * $comprimentoPacote;
                $valorAcumuladoProdutos = $prod['valorProduto'];
                $valorAcumuladoAdicionalEnvio = $prod['adicionalEnvio'];
                $pesoAcumuladoProdutos = $pesoProduto;
                $volumeAcumuladoProdutos = $prod['cubagem'];

            }else {
                // Acumula os dados
                $alturaPacote = $alturaPacoteTmp;
                $larguraPacote = $larguraPacoteTmp;
                $comprimentoPacote = $comprimentoPacoteTmp;
                $volumePacote = $alturaPacote * $larguraPacote * $comprimentoPacote;
                $valorAcumuladoProdutos += $prod['valorProduto'];
                $valorAcumuladoAdicionalEnvio += $prod['adicionalEnvio'];
                $pesoAcumuladoProdutos += $pesoProduto;
                $volumeAcumuladoProdutos += $prod['cubagem'];
            }

        }

        // Grava a ultima pacote
        if ($pesoAcumuladoProdutos > 0) {

            $alturaTmp = ($alturaPacote < $dimPesoPermitidos['altura_min'] ? $dimPesoPermitidos['altura_min'] : $alturaPacote);
            $larguraTmp = ($larguraPacote < $dimPesoPermitidos['largura_min'] ? $dimPesoPermitidos['largura_min'] : $larguraPacote);
            $comprimentoTmp = ($comprimentoPacote < $dimPesoPermitidos['comprimento_min'] ? $dimPesoPermitidos['comprimento_min'] : $comprimentoPacote);
            $volumeTmp = $alturaTmp * $larguraTmp * $comprimentoTmp;

            $embalagens[] = array(
                'altura'                => $alturaTmp,
                'largura'               => $larguraTmp,
                'comprimento'           => $comprimentoTmp,
                'pesoEmbalagem'         => 0,
                'custoEmbalagem'        => 0,
                'cubagem'               => $volumeTmp,
                'pesoProdutos'          => $pesoAcumuladoProdutos,
                'valorProdutos'         => $valorAcumuladoProdutos,
                'adicionalEnvio'        => $valorAcumuladoAdicionalEnvio,
                'freteGratisProduto'    => false
            );

        }

        return $embalagens;
    }

    private function calculaValorOnline($parm) {

        // Inicializa variaveis
        $prazoEntrega = 0;
        $msgCorreios = '';
        $totalFrete = 0;

        // Instancia CorreiosClass
        $correiosClass = new CorreiosClass();

        foreach ($parm['embalagens'] as $embalagem) {

            // Verifica se existe no cache
            $hash = $this->criaHash($parm['idTranspAtual'], $parm['cepOrigem'], $parm['cepDestino'], $embalagem);
            $cache = $this->recuperaCache($hash);

            if ($cache['status']) {
                // Retorna se valor o servico não atende
                if ($cache['valorFrete'] <= 0) {
                    return array('status' => false, 'valorFrete' => '', 'prazoEntrega' => '', 'msgCorreios' => '');
                }

                // Recupera valores e continua
                $valorFrete = $cache['valorFrete'];
                $prazoEntrega = $cache['prazoEntrega'];
                $msgCorreios = $cache['msgCorreios'];
            }else {
                // Verifica Servicos Adicionais
                $maoPropria = 'N';
                if (Configuration::get('FKCORREIOSG2_MAO_PROPRIA') == 'on') {
                    $maoPropria = 'S';
                }

                $avisoRecebimento = 'N';
                if (Configuration::get('FKCORREIOSG2_AVISO_RECEBIMENTO') == 'on') {
                    $avisoRecebimento = 'S';
                }

                $valorDeclarado = '0';
                if (Configuration::get('FKCORREIOSG2_VALOR_DECLARADO') == 'on') {
                    if ($embalagem['valorProdutos'] <= $parm['valorDeclaradoMax']) {
                        $valorDeclarado = $embalagem['valorProdutos'];
                    }else {
                        $valorDeclarado = $parm['valorDeclaradoMax'];
                    }
                }

                // Consome web services dos Correios
                $correiosClass->setEmpresa($parm['codAdministrativo']);
                $correiosClass->setSenha($parm['senha']);
                $correiosClass->setCodServico($parm['codServico']);
                $correiosClass->setCepOrigem($parm['cepOrigem']);
                $correiosClass->setCepDestino($parm['cepDestino']);
                $correiosClass->setPeso($embalagem['pesoProdutos'] + $embalagem['pesoEmbalagem']);
                $correiosClass->setFormato('1');
                $correiosClass->setComprimento($embalagem['comprimento']);
                $correiosClass->setAltura($embalagem['altura']);
                $correiosClass->setLargura($embalagem['largura']);
                $correiosClass->setDiametro('0');
                $correiosClass->setCubagem($embalagem['cubagem']);
                $correiosClass->setMaoPropria($maoPropria);
                $correiosClass->setValorDeclarado($valorDeclarado);
                $correiosClass->setAvisoRecebimento($avisoRecebimento);

                // Consulta webservice dos Correios
                if ($correiosClass->calculaPrecoPrazo()) {

                    // Recupera dados retornados pelos Correios
                    $valorFrete = $correiosClass->getValorFrete();
                    $prazoEntrega = $correiosClass->getPrazoEntrega();
                    $msgCorreios = $correiosClass->getMsgRetorno();

                    // Grava cache
                    $this->gravaCache($correiosClass->getRetornoCorreios(), $parm['cepOrigem'], $parm['cepDestino'], $embalagem);
                }else {
                    // Grava cache
                    $this->gravaCache($correiosClass->getRetornoCorreios(), $parm['cepOrigem'], $parm['cepDestino'], $embalagem);

                    // Verifica o erro
                    $trataErro = $correiosClass->trataErro($correiosClass->getCodRetorno(), $correiosClass->getMsgRetorno());

                    if (!$trataErro['calculoOffline']) {
                        return array('status' => false, 'valorFrete' => '', 'prazoEntrega' => '', 'msgCorreios' => '');
                    }

                    // Calculo Offline
                    $offline = $this->calculaValorOffline($parm);

                    // Retorna se nao calculado o valor do frete offline
                    if (!$offline['status']) {
                        return array('status' => false, 'valorFrete' => '', 'prazoEntrega' => '', 'msgCorreios' => '');
                    }

                    // Recupera dados retornados pelo calculo offline
                    $valorFrete = $offline['valorFrete'];
                    $prazoEntrega = $offline['prazoEntrega'];
                    $msgCorreios = '';
                }

            }

            // Adiciona Tempo de Preparacao
            $prazoEntrega += (int)$parm['tempoPreparacao'];

            // Retorna se Frete Gratis por Valor for verdadeiro e a transportadora for a definida para o frete gratis
            if ($parm['freteGratisValor'] and $parm['transpFreteGratisValor'] == $parm['idCarrierAtual']) {
                return array('status' => true, 'valorFrete' => 'Grátis', 'prazoEntrega' => $prazoEntrega, 'msgCorreios' => $msgCorreios);
            }

            // Nao acumula se o produto e Frete Gratis
            if ($embalagem['freteGratisProduto']) {
                continue;
            }

            // Soma o Adicional de Envio ao frete (cadastro de produtos)
            $valorFrete += (float)$embalagem['adicionalEnvio'];

            // Acumula Valor do Frete
            $totalFrete += $valorFrete + $embalagem['custoEmbalagem'];
        }

        if ($totalFrete > 0) {

            // Verifica se o Custo de Envio deve ser adicionado ao valor do frete da transportadora
            if (Configuration::get('PS_SHIPPING_HANDLING') > 0) {
                $carrier = new Carrier($parm['idCarrierAtual']);

                if ($carrier->shipping_handling) {
                    $totalFrete += (float)Configuration::get('PS_SHIPPING_HANDLING');
                }
            }

            // Desconto no frete
            if ($parm['percentualDescontoFrete'] > 0 and $parm['valorPedido'] >= $parm['valorPedidoDescontoFrete']) {
                $totalFrete *= (1 - ($parm['percentualDescontoFrete'] / 100));
            }
        }else {
            $totalFrete = 'Grátis';
        }

        return array('status' => true, 'valorFrete' => $totalFrete, 'prazoEntrega' => $prazoEntrega, 'msgCorreios' => $msgCorreios);
    }

    private function calculaValorOffline($parm) {

        // Inicializa variaveis
        $prazoEntrega = 0;
        $totalFrete = 0;
        $destino = false;
        $tabelaPreco = '';

        // Verifica o destino da entrega e Minha Cidade
        $minhaCidade = explode('/', Configuration::get('FKCORREIOSG2_CEP_CIDADE'));

        foreach ($minhaCidade as $intervaloCep) {

            if ($intervaloCep == '') {
                continue;
            }

            if ($parm['cepDestino'] >= substr($intervaloCep, 0, 8) And $parm['cepDestino'] <= substr($intervaloCep, 9, 8)) {
                $destino = 'cidade';
                break;
            }
        }

        // Inicializa FKcorreiosg2Class
        $fkclass = new FKcorreiosg2Class();

        // Caso o CEP destino nao seja da Minha Cidade, verifica se e Capital ou Interior
        if (!$destino) {

            if ($fkclass->verificaSeCapital($parm['cepDestino'])) {
                $destino = 'capital';
            }else {
                $destino = 'interior';
            }
        }

        // Recupera os dados das tabelas offline
        if ($destino == 'cidade') {

            $sql = "SELECT *
                    FROM "._DB_PREFIX_."fkcorreiosg2_tabelas_offline
                    WHERE minha_cidade = 1 AND
                          id_shop = ".(int)$this->context->shop->id." AND
                          id_especificacao = ".(int)$parm['idEspecificacao'];
        }else {
            $sql = "SELECT
                      "._DB_PREFIX_."fkcorreiosg2_tabelas_offline.*
                    FROM "._DB_PREFIX_."fkcorreiosg2_tabelas_offline
                      INNER JOIN "._DB_PREFIX_."fkcorreiosg2_cadastro_cep
                        ON "._DB_PREFIX_."fkcorreiosg2_tabelas_offline.id_cadastro_cep = "._DB_PREFIX_."fkcorreiosg2_cadastro_cep.id
                    WHERE "._DB_PREFIX_."fkcorreiosg2_tabelas_offline.minha_cidade = 0 AND
                          "._DB_PREFIX_."fkcorreiosg2_tabelas_offline.id_shop = ".(int)$this->context->shop->id." AND
                          "._DB_PREFIX_."fkcorreiosg2_tabelas_offline.id_especificacao = ".(int)$parm['idEspecificacao']." AND
                          "._DB_PREFIX_."fkcorreiosg2_cadastro_cep.estado = '".$parm['ufDestino']."'";
        }

        $tabelasOffline = Db::getInstance()->getRow($sql);

        // Ignora transportadora se não localizada a tabela offline
        if (!$tabelasOffline){
            return array('status' => false, 'valorFrete' => '', 'prazoEntrega' => '', 'msgCorreios' => '');
        }

        // Recupera a tabela a ser utilizada e o prazo de entrega
        switch ($destino) {
            case 'cidade':
                $tabelaPreco = $tabelasOffline['tabela_cidade'];
                $prazoEntrega = $tabelasOffline['prazo_entrega_cidade'];
                break;

            case 'capital':
                $tabelaPreco = $tabelasOffline['tabela_capital'];
                $prazoEntrega = $tabelasOffline['prazo_entrega_capital'];
                break;

            case 'interior':
                $tabelaPreco = $tabelasOffline['tabela_interior'];
                $prazoEntrega = $tabelasOffline['prazo_entrega_interior'];
                break;
        }

        // Adiciona Tempo de Preparacao
        if (is_numeric($prazoEntrega)) {
            $prazoEntrega += (int)$parm['tempoPreparacao'];
        }

        // Cria array da tabela de preços
        $arrayTabela = explode('/', $tabelaPreco);

        // Calcula o frete
        foreach ($parm['embalagens'] as $embalagem) {

            $valorFrete = 0;

            // Verifica se deve considerar o Peso Cubico ou Peso Real
            $pesoProdutos = $embalagem['pesoProdutos'] + $embalagem['pesoEmbalagem'];

            if ($embalagem['cubagem'] > $parm['cubagemMaxIsenta']) {

                $pesoCubico = $embalagem['cubagem'] / $parm['cubagemBaseCalculo'];

                if ($pesoCubico > $pesoProdutos) {
                    $pesoProdutos = $pesoCubico;
                }
            }

            // Recupera o valor do frete
            foreach ($arrayTabela as $itemTabela) {

                if ($itemTabela == '') {
                    continue;
                }

                // Verifica posicao do delimitador entre Peso e Valor
                $pos = strpos($itemTabela, ':');

                // Ignora a transportadora (tabela está configurada errada)
                if ($pos === false) {
                    return array('status' => false, 'valorFrete' => '', 'prazoEntrega' => '', 'msgCorreios' => '');
                }

                $pesoTabela = substr($itemTabela, 0, $pos);

                if ($pesoProdutos <= $pesoTabela) {
                    $valorFrete = substr($itemTabela, $pos + 1);
                    break;
                }
            }

            // Ignora transportadora caso não tenho localizado o valor a ser cobrado
            if ($valorFrete == 0) {
                return array('status' => false, 'valorFrete' => '', 'prazoEntrega' => '', 'msgCorreios' => '');
            }

            // Retorna se Frete Gratis por Valor for verdadeiro e a transportadora for a definida para o frete gratis
            if ($parm['freteGratisValor'] and $parm['transpFreteGratisValor'] == $parm['idCarrierAtual']) {
                return array('status' => true, 'valorFrete' => 'Grátis', 'prazoEntrega' => $prazoEntrega, 'msgCorreios' => '');
            }

            // Nao acumula se o produto e Frete Gratis
            if ($embalagem['freteGratisProduto']) {
                continue;
            }

            // Verifica Mao Propria
            if (Configuration::get('FKCORREIOSG2_MAO_PROPRIA') == 'on') {
                $valorFrete += $parm['maoPropriaValor'];
            }

            // Verifica Valor Declarado
            if (Configuration::get('FKCORREIOSG2_VALOR_DECLARADO') == 'on') {

                if ($embalagem['valorProdutos'] > $parm['seguroAutomaticoValor']) {

                    if ($embalagem['valorProdutos'] <= $parm['valorDeclaradoMax']) {
                        $valorDeclarado = $embalagem['valorProdutos'];
                    }else {
                        $valorDeclarado = $parm['valorDeclaradoMax'];
                    }

                    $valorFrete += ($valorDeclarado - $parm['seguroAutomaticoValor']) * $parm['valorDeclaradoPercentual'] / 100;
                }
            }

            // Verifica Aviso de Recebimento
            if (Configuration::get('FKCORREIOSG2_AVISO_RECEBIMENTO') == 'on') {
                $valorFrete += $parm['avisoRecebimentoValor'];
            }

            // Soma o Adicional de Envio ao frete (cadastro de produtos)
            $valorFrete += (float)$embalagem['adicionalEnvio'];

            // Acumula Valor do Frete
            $totalFrete += $valorFrete + $embalagem['custoEmbalagem'];
        }

        if ($totalFrete > 0) {

            // Verifica se o Custo de Envio deve ser adicionado ao valor do frete da transportadora
            if (Configuration::get('PS_SHIPPING_HANDLING') > 0) {
                $carrier = new Carrier($parm['idCarrierAtual']);

                if ($carrier->shipping_handling) {
                    $totalFrete += (float)Configuration::get('PS_SHIPPING_HANDLING');
                }
            }

            // Desconto no frete
            if ($parm['percentualDescontoFrete'] > 0 and $parm['valorPedido'] >= $parm['valorPedidoDescontoFrete']) {
                $totalFrete *= (1 - ($parm['percentualDescontoFrete'] / 100));
            }
        }else {
            $totalFrete = 'Grátis';
        }

        return array('status' => true, 'valorFrete' => $totalFrete, 'prazoEntrega' => $prazoEntrega, 'msgCorreios' => '');
    }

    private function recuperaDimensoes($idEspecificacao, $ufOrigem, $ufDestino) {

        // Recupera as dimensoes mínimas/maximas e pesos permitidos para os Correios
        $sql = "SELECT  *
                FROM "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios
                WHERE id_shop = ".$this->context->shop->id." AND
                      id = ".(int)$idEspecificacao;

        $espCorreios = Db::getInstance()->getRow($sql);

        if ($ufOrigem == $ufDestino) {
            $pesoMaximo = $espCorreios['peso_estadual_max'];
        }else {
            $pesoMaximo = $espCorreios['peso_nacional_max'];
        }

        return array(
            'comprimento_min'           => $espCorreios['comprimento_min'],
            'comprimento_max'           => $espCorreios['comprimento_max'],
            'largura_min'               => $espCorreios['largura_min'],
            'largura_max'               => $espCorreios['largura_max'],
            'altura_min'                => $espCorreios['altura_min'],
            'altura_max'                => $espCorreios['altura_max'],
            'somatoria_dimensoes_max'   => $espCorreios['somatoria_dimensoes_max'],
            'peso_maximo'               => $pesoMaximo
        );

    }

    private function selecionaEmbalagem($caixas, $cubagemProduto) {

        foreach ($caixas as $reg) {

            if ($cubagemProduto <= $reg['cubagem']) {

                return array(
                    'altura'        => $reg['altura'],
                    'largura'       => $reg['largura'],
                    'comprimento'   => $reg['comprimento'],
                    'peso'          => $reg['peso'],
                    'custo'         => $reg['custo'],
                    'cubagem'       => $reg['cubagem']
                );
            }
        }

        return array();
    }

    private function criaHash($idTranspAtual, $cepOrigem, $cepDestino, $embalagem) {

        $hash = $this->context->shop->id.':'.
                $this->context->cart->id.':'.
                $idTranspAtual.':'.
                $cepOrigem.':'.
                $cepDestino.':'.
                Configuration::get('FKCORREIOSG2_MAO_PROPRIA').':'.
                Configuration::get('FKCORREIOSG2_VALOR_DECLARADO').':'.
                Configuration::get('FKCORREIOSG2_AVISO_RECEBIMENTO').':'.
                $embalagem['altura'].':'.
                $embalagem['largura'].':'.
                $embalagem['comprimento'].':'.
                $embalagem['cubagem'].':'.
                number_format($embalagem['valorProdutos'], 2).':'.
                $embalagem['pesoProdutos'];

        return md5($hash);
    }

    private function gravaCache($retornoCorreios, $cepOrigem, $cepDestino, $embalagem) {

        foreach ($retornoCorreios as $retorno) {

            $hash = $this->criaHash($retorno['idTranspAtual'], $cepOrigem, $cepDestino, $embalagem);

            // Verifica se ja existe
            $cache = $this->recuperaCache($hash);

            if (!$cache['status']) {

                $dados = array(
                    'hash'          => $hash,
                    'valor_frete'   => $retorno['valorFrete'],
                    'prazo_entrega' => $retorno['prazoEntrega'],
                    'msg_correios'  => $retorno['msgRetorno'],
                );

                Db::getInstance()->insert('fkcorreiosg2_cache', $dados);
            }

        }

    }

    private function recuperaCache($hash) {

        $sql = "SELECT *
                FROM "._DB_PREFIX_."fkcorreiosg2_cache
                WHERE hash = '".$hash."'";

        $cache = Db::getInstance()->getRow($sql);

        if ($cache) {
            return array('status' => true, 'valorFrete' => $cache['valor_frete'], 'prazoEntrega' => $cache['prazo_entrega'], 'msgCorreios' => $cache['msg_correios']);
        }else {
            return array('status' => false, 'valorFrete' => '', 'prazoEntrega' => '', 'msgCorreios' => '');
        }
    }

    static function ordenaCubagem($a, $b) {

        if ($a['cubagem'] == $b['cubagem']) {
            return 0;
        }
        return ($a['cubagem'] < $b['cubagem']) ? -1 : 1;
    }


}
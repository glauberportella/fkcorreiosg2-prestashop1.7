<?php

class FKcorreiosg2Class {

    private $msgErro;

    public function getMsgErro() {
        return $this->msgErro;
    }

    public function __construct() {
        $this->context = Context::getContext();
    }

    public function recuperaServicosCorreiosAtivos() {

        $sql = "SELECT
                    "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.cod_servico,
                    "._DB_PREFIX_."fkcorreiosg2_servicos.id
                FROM "._DB_PREFIX_."fkcorreiosg2_servicos
                    INNER JOIN "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios
                        ON "._DB_PREFIX_."fkcorreiosg2_servicos.id_especificacao = "._DB_PREFIX_."fkcorreiosg2_especificacoes_correios.id
                WHERE "._DB_PREFIX_."fkcorreiosg2_servicos.id_shop = ".(int)$this->context->shop->id." AND
                      "._DB_PREFIX_."fkcorreiosg2_servicos.ativo = 1";

        return Db::getInstance()->executeS($sql);
    }

    public function recuperaUF($cep) {

        $cep = preg_replace("/[^0-9]/", "", $cep);

        // Recupera dados do Cadastro de Cep
        $cadCep = $this->recuperaCadastroCep();

        $localizado = false;

        foreach ($cadCep as $reg) {

            $cepArray = explode('/', $reg['cep_estado']);

            foreach ($cepArray as $intervaloCep) {

                if ($intervaloCep == '') {
                    continue;
                }

                if ($cep >= substr($intervaloCep, 0, 8) And $cep <= substr($intervaloCep, 9, 8)) {
                    $localizado = true;
                    break;
                }
            }

            if ($localizado == true){
                return $reg['estado'];
                break;
            }
        }

        return false;
    }

    public function criaArrayUF($dados) {

        $arrayUF = array();

        foreach ($dados as $reg) {

            // Recupera dados do Cadastro de Cep
            $cadCep = $this->recuperaCadastroCep();

            $arrayTmp = array();

            foreach ($cadCep as $estado) {
                $ativo = false;

                // Pesquisa se a UF está ativa em servicos
                if (isset($reg['regiao_uf'])) {
                    if (strpos($reg['regiao_uf'], $estado['estado']) === false) {
                    }else {
                        $ativo = true;
                    }
                }

                $arrayTmp[] = array('id_servico' => $reg['id'], 'uf' => $estado['estado'], 'ativo' => $ativo);
            }

            $arrayUF[$reg['id']] = $arrayTmp;
        }

        return $arrayUF;

    }

    public function formataGravacaoUF($uf_selecionadas) {

        $regiao_uf = '';

        if ($uf_selecionadas) {
            foreach ($uf_selecionadas as $uf) {
                $regiao_uf .= $uf.'/';
            }
        }

        return $regiao_uf;
    }

    public function instalaCarrier($parm) {

        $carrier = new Carrier();
        $carrier->name 					= $parm['name'];
        $carrier->id_tax_rules_group 	= $parm['id_tax_rules_group'];
        $carrier->active 				= $parm['active'];
        $carrier->deleted 				= $parm['deleted'];
        $carrier->shipping_handling 	= $parm['shipping_handling'];
        $carrier->range_behavior 		= $parm['range_behavior'];
        $carrier->is_module 			= $parm['is_module'];
        $carrier->shipping_external 	= $parm['shipping_external'];
        $carrier->shipping_method 		= $parm['shipping_method'];
        $carrier->external_module_name 	= $parm['external_module_name'];
        $carrier->need_range 			= $parm['need_range'];
        $carrier->url 					= $parm['url'];
        $carrier->is_free 				= $parm['is_free'];
        $carrier->grade 				= $parm['grade'];

        $languages = Language::getLanguages(true);
        foreach ($languages as $language) {
            $carrier->delay[(int)$language['id_lang']] = 'Prazo de Entrega';
        }

        if ($carrier->add()) {

            // Liga carrier ao shop
            $carrier->associateTo($this->context->shop->id);

            // Liga carrier aos grupos de clientes
            $grupos_clientes = array();
            $grupos = Group::getGroups(true);

            foreach ($grupos as $grupo) {
                $grupos_clientes[] = $grupo['id_group'];
            }

            $carrier->setGroups($grupos_clientes);

            // Define intervalo de precos
            $intervalo_preco = new RangePrice();

            if (!$intervalo_preco->rangeExist($carrier->id, '0', '100000')) {
                $intervalo_preco->id_carrier = $carrier->id;
                $intervalo_preco->delimiter1 = '0';
                $intervalo_preco->delimiter2 = '100000';
                $intervalo_preco->add();
            }

            // Define intervalo de pesos
            $intervalo_peso = new RangeWeight();

            if (!$intervalo_peso->rangeExist($carrier->id, '0', '10000')) {
                $intervalo_peso->id_carrier = $carrier->id;
                $intervalo_peso->delimiter1 = '0';
                $intervalo_peso->delimiter2 = '10000';
                $intervalo_peso->add();;
            }

            // Liga carrier as regioes
            $regioes = Zone::getZones(true);
            foreach ($regioes as $regiao) {

                if (!$carrier->checkCarrierZone($carrier->id, $regiao['id_zone'])) {
                    $carrier->addZone($regiao['id_zone']);
                }

            }

            // Retorna o ID Carrier
            return $carrier->id;
        }

        return false;
    }

    public function alteraCarrier($parm) {

        //Recupera array $_FILES
        $files = $parm['arrayLogo'];

        //Recupera nome do campo
        $campoLogo = $parm['campoLogo'];

        // Instancia e altera carrier
        $carrier = new Carrier($parm['idCarrier']);

        if ($parm['nomeCarrier']) {
            $carrier->name = $parm['nomeCarrier'];
        }

        $carrier->active 	= $parm['ativo'];
        $carrier->grade		= $parm['grade'];
        $carrier->update();

        // Copia logo
        $extensoes_permitidas = array('0' => 'jpg');

        if(!empty($files[$campoLogo]['name'])) {

            // Verifica se houve algum erro com o upload
            if ($files[$campoLogo]['error'] != 0) {
                $this->msgErro = 'Erro durante upload da imagem.';
                return false;
            }

            // Verifica extensão do arquivo
            $array = explode('.', $files[$campoLogo]['name']);
            $extensao = end($array);
            $extensao = strtolower($extensao);

            if (array_search($extensao, $extensoes_permitidas) === false) {
                $this->msgErro = 'Permitido somente arquivos com extensões jpg.';
                return false;
            }

            // Exclui logo da pasta tmp
            if (file_exists(Configuration::get('FKCORREIOSG2_URI_LOGO_PS_TMP').$parm['idCarrier'].'_'.$this->context->shop->id.'.jpg')) {
                unlink(Configuration::get('FKCORREIOSG2_URI_LOGO_PS_TMP').$parm['idCarrier'].'_'.$this->context->shop->id.'.jpg');
            }

            // Move o logo para a pasta upload dando rename
            if (!move_uploaded_file($files[$campoLogo]['tmp_name'], _PS_SHIP_IMG_DIR_.$parm['idCarrier'].'.jpg')) {
                $this->msgErro = 'Não foi possível gravar o Logo na pasta img.';
                return false;
            }
        }

        return true;
    }

    public function recuperaCarrierExcluido($transp) {

        foreach ($transp as $reg) {

            $carrier = new Carrier($reg['id_carrier']);

            if ($carrier->id) {
                if ($carrier->deleted == true) {
                    $carrier->deleted = false;
                    $carrier->update();
                }
            }
        }

        return true;
    }

    public function desinstalaCarrier($transp) {

        foreach ($transp as $reg) {

            // Marca como excluido o carrier do Prestashop
            $carrier = new Carrier($reg['id_carrier']);

            if ($carrier->id) {
                $carrier->deleted = true;
                $carrier->update();
            }
        }

        return true;
    }

    public function excluiCarrier($idCarrier) {

        // Exclui logo
        if (file_exists(_PS_SHIP_IMG_DIR_.'/'.$idCarrier.'.jpg')) {
            unlink(_PS_SHIP_IMG_DIR_.'/'.$idCarrier.'.jpg');
        }

        // Marca como excluido o carrier do Prestashop
        $carrier = new Carrier($idCarrier);

        if ($carrier->id) {
            $carrier->deleted = true;
            $carrier->update();
        }

        return true;
    }

    public function filtroRegiao($transportadora, $cepDestino, $ufDestino) {

        // Verifica se o CEP esta contido no intervalo a ser excluido da Regiao
        $cepExcluido = explode('/', $transportadora['regiao_cep_excluido']);

        foreach ($cepExcluido as $intervaloExcluido) {

            if ($intervaloExcluido == '') {
                continue;
            }

            if ($cepDestino >= substr($intervaloExcluido, 0, 8) And $cepDestino <= substr($intervaloExcluido, 9, 8)) {
                return false;
            }
        }

        // Verifica se o CEP esta contido no intervalo a ser atendido da Regiao
        $cepIncluido = explode('/', $transportadora['regiao_cep']);

        foreach ($cepIncluido as $intervaloIncluido) {

            if ($intervaloIncluido == '') {
                continue;
            }

            if ($cepDestino >= substr($intervaloIncluido, 0, 8) And $cepDestino <= substr($intervaloIncluido, 9, 8)) {
                return true;
            }
        }

        // Verifica os Estados atendidos pela regiao
        if ($transportadora['regiao_uf']) {

            // Retorna se nao localizar o Estado
            if (strpos($transportadora['regiao_uf'], $ufDestino) === false) {
                return false;
            }

            if ($transportadora['filtro_regiao_uf'] == '1') {
                return true;
            }else {
                // Verifica se o CEP e da Capital
                $capital = $this->verificaSeCapital($cepDestino);

                // Retorna se o CEP for da Capital
                if ($transportadora['filtro_regiao_uf'] == '2' and $capital) {
                    return true;
                }else {
                    // Retorna se o CEP for do Interior
                    if ($transportadora['filtro_regiao_uf'] == '3' and !$capital) {
                        return true;
                    }
                }
            }

        }

        return false;

    }

    public function filtroProdutoTransportadora($idProduto, $idCarrierReference) {

        $sql = "SELECT
                    "._DB_PREFIX_."product_carrier.id_carrier_reference
				FROM "._DB_PREFIX_."product_carrier
                    INNER JOIN  "._DB_PREFIX_."carrier
                        ON  "._DB_PREFIX_."product_carrier.id_carrier_reference =  "._DB_PREFIX_."carrier.id_reference
				WHERE "._DB_PREFIX_."carrier.deleted = 0 AND
                      "._DB_PREFIX_."product_carrier.id_product = ".(int)$idProduto." AND
					  "._DB_PREFIX_."product_carrier.id_shop = ".(int)$this->context->shop->id;

        $dados = Db::getInstance()->executeS($sql);

        if (!$dados) {
            return true;
        }else {
            $retorno = false;

            foreach ($dados as $reg) {

                if ($reg['id_carrier_reference'] == $idCarrierReference) {
                    $retorno = true;
                    break;
                }
            }
        }

        return $retorno;

    }

    public function filtroClienteTransportadora($idCarrier) {

        if ($this->context->customer->logged) {
            $sql = "SELECT *
                    FROM "._DB_PREFIX_."customer_group
                        INNER JOIN "._DB_PREFIX_."carrier_group
                            ON "._DB_PREFIX_."customer_group.id_group = "._DB_PREFIX_."carrier_group.id_group
                    WHERE "._DB_PREFIX_."customer_group.id_customer = ".(int)$this->context->customer->id." AND
                          "._DB_PREFIX_."carrier_group.id_carrier = ".(int)$idCarrier;
        }else {
            $grupoClinte = Configuration::get('PS_UNIDENTIFIED_GROUP');

            $sql = "SELECT *
                    FROM "._DB_PREFIX_."carrier_group
                    WHERE "._DB_PREFIX_."carrier_group.id_carrier = ".(int)$idCarrier." AND
                          "._DB_PREFIX_."carrier_group.id_group = ".(int)$grupoClinte;
        }

        $dados = Db::getInstance()->executeS($sql);

        if (!$dados) {
            return false;
        }

        return true;
    }

    public function filtroDimensoesPesoTransportadora($idProduto, $idCarrier, $pesoPedido) {

        $produto = new product($idProduto);

        if ($produto->width > 0 or $produto->height > 0 or $produto->depth > 0 or $produto->weight > 0) {

            $carrier = new Carrier($idCarrier);

            if (($carrier->max_width > 0 and $carrier->max_width < $produto->width) or
                ($carrier->max_height > 0 and $carrier->max_height < $produto->height) or
                ($carrier->max_depth > 0 and $carrier->max_depth < $produto->depth) or
                ($carrier->max_weight > 0 && $carrier->max_weight < $pesoPedido)) {

                return false;
            }
        }

        return true;
    }

    public function filtroFreteGratisProduto($idProduto, $idCarrier, $cepDestino, $ufDestino) {

        $sql = "SELECT *
                FROM "._DB_PREFIX_."fkcorreiosg2_frete_gratis
                WHERE ativo = 1 AND
                      id_produtos IS NOT NULL AND
                      id_shop = ".$this->context->shop->id." AND
                      id_carrier = ".(int)$idCarrier;

        $freteGratis = Db::getInstance()->executeS($sql);

        // Retorna se nao existe frete gratis para o produto nas regioes atendida pela transportadora
        if (!$freteGratis) {
            return false;
        }

        // Verifica se o produto esta contido em alguma Regiao Frete Gratis
        foreach ($freteGratis as $reg) {

            if ($this->filtroRegiao($reg, $cepDestino, $ufDestino)) {

                // Coloca barra inicial no campo da tabela que contem os produtos
                $produtosFreteGratis = '/'.$reg['id_produtos'];

                if (strpos($produtosFreteGratis, '/'.$idProduto.'/') === false) {
                }else {
                    return true;
                }

            }
        }

        return false;
    }

    public function filtroFreteGratisValor($valorPedido, $cepDestino, $ufDestino){

        $sql = "SELECT *
                FROM "._DB_PREFIX_."fkcorreiosg2_frete_gratis
                WHERE ativo = 1 AND
                      valor_pedido > 0 AND
                      valor_pedido <= ".$valorPedido." AND
                      id_shop = ".$this->context->shop->id;

        $freteGratis = Db::getInstance()->executeS($sql);

        // Verifica se alguma Regiao Frete Gratis por valor atende ao CEP
        if ($freteGratis) {

            foreach ($freteGratis as $reg) {

                if ($this->filtroRegiao($reg, $cepDestino, $ufDestino)) {
                    return array('status' => true, 'idCarrier' => $reg['id_carrier']);
                }
            }
        }

        return array('status' => false, 'idCarrier' => 0);
    }

    public function verificaSeCapital($cep) {

        $cep = preg_replace("/[^0-9]/", "", $cep);

        // Recupera os dados do Cadastro de CEP
        $cadCep = $this->recuperaCadastroCep();

        foreach ($cadCep as $reg) {
            // Retorna se o CEP for Capital
            if (strpos($reg['cep_capital'], $cep) === false) {
            }else {
                return true;
            }
        }

        return false;
    }

    public function recuperaRastreio($codigo) {

        $urlRastreio = str_replace('@', $codigo, Configuration::get('FKCORREIOSG2_URL_RASTREIO_CORREIOS'));
        $htmlOriginal = utf8_encode(file_get_contents($urlRastreio));

        // Verifica se o objeto ainda não foi postado
        if (strstr($htmlOriginal, '<table') === false){

            // Se o objeto nao foi localizado
            $htmlRetorno = '<p style="text-align: center; font-weight: bold;">Objeto ainda não foi adicionando no sistema</p>';
            return $htmlRetorno;
        }

        // Recupera as linhas que contem os dados de rastreamento
        if (preg_match_all('@<tr>(.*)</tr>@', $htmlOriginal, $htmlFiltrado, PREG_SET_ORDER)){

            // Inicializa variaveis
            $rastreamento = array();
            $encaminhamento = null;

            // Formata os dados
            foreach($htmlFiltrado as $item) {

                if (preg_match("@<td rowspan=[12]>(.*)</td><td>(.*)</td><td><FONT COLOR=\"[0-9A-F]{6}\">(.*)</font></td>@", $item[0], $itemFiltrado)){

                    // Cria uma linha de rastreamento
                    $tmp = array(
                        'data'      => $itemFiltrado[1],
                        'local'     => $itemFiltrado[2],
                        'acao'      => $itemFiltrado[3],
                        'detalhes'  => ''
                    );

                    // Se tiver um encaminhamento armazenado
                    if ($encaminhamento){
                        $tmp['detalhes'] = $encaminhamento;
                        $encaminhamento = null;
                    }

                    // Adiciona o item na lista de rastreamento
                    $rastreamento[] = $tmp;

                }elseif (preg_match("@<td colspan=2>(.*)</td>@", $item[0], $itemFiltrado)) {
                    // Se for um encaminhamento, armazena para o proximo item
                    $encaminhamento = $itemFiltrado[1];
                }
            }

            // Monta html de retorno
            $htmlRetorno = '<table>';
            $htmlRetorno .= '    <tr style="background-color: #DEDEDE;">';
            $htmlRetorno .= '        <th style="width: 130px;">Data</th>';
            $htmlRetorno .= '        <th>Local</th>';
            $htmlRetorno .= '        <th>Detalhes</th>';
            $htmlRetorno .= '    </tr>';

            foreach($rastreamento as $item) {
                $htmlRetorno .= '<tr style="height: 75px; border-bottom: 1px solid #DEDEDE">';
                $htmlRetorno .= '   <td>'.$item['data'].'</td>';
                $htmlRetorno .= '   <td>'.$item['local'].'</td>';
                $htmlRetorno .= '   <td>'.$item['acao'].'<br>'.$item['detalhes'].'</td>';
                $htmlRetorno .= '</tr>';
            }

            $htmlRetorno .= '</table>';

            return $htmlRetorno;
        }

        // Se houve erro
        $htmlRetorno = '<p style="text-align: center; font-weight: bold;">Falha de Comunicação com os correios</p>';
        return $htmlRetorno;

    }

    private function recuperaCadastroCep() {

        $sql = 'SELECT *
                FROM '._DB_PREFIX_.'fkcorreiosg2_cadastro_cep
                ORDER BY estado';

        return  Db::getInstance()->executeS($sql);
    }

}
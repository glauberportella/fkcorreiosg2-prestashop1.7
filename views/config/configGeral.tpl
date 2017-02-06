
<form id="configuration_form" class="defaultForm  form-horizontal" action="{$tab_2['formAction']}&origem=configGeral" method="post">

    <div class="fkcorreiosg2-panel" style="border-top-left-radius: 0">

        <div class="fkcorreiosg2-panel-heading">
            {l s="Configuração geral" mod="fkcorreiosg2"}
        </div>

        <div class="fkcorreiosg2-panel-header">
            <button type="button" value="1" name="btnAjuda" class="fkcorreiosg2-button fkcorreiosg2-float-right" onClick="window.open('http://www.fkmodulos.com.br/modulosfk/ajuda/fkcorreiosg2_v1_0_0.pdf','Janela','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=900,height=500,left=500,top=150'); return false;">
                <i class="process-icon-help"></i>
                {l s="Ajuda" mod="fkcorreiosg2"}
            </button>
        </div>

        <div class="fkcorreiosg2-panel">

            <div class="fkcorreiosg2-panel-heading">
                {l s="CEP" mod="fkcorreiosg2"}
            </div>

            <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-40 fkcorreiosg2-sub-panel">

                <div class="fkcorreiosg2-panel-heading">
                    {l s="Meu CEP" mod="fkcorreiosg2"}
                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                        <input class="fkcorreiosg2-mask-cep" type="text" name="fkcorreiosg2_meu_cep" id="fkcorreiosg2_meu_cep" value="{$tab_2['fkcorreiosg2_meu_cep']}">
                    </div>
                </div>
            </div>

            <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-40 fkcorreiosg2-sub-panel">

                <div class="fkcorreiosg2-panel-heading">
                    {l s="Minha Cidade" mod="fkcorreiosg2"}
                </div>

                <div class="fkcorreiosg2-form">

                    <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                        <input class="fkcorreiosg2-mask-cep" type="text" name="fkcorreiosg2_cidade_cep1" id="fkcorreiosg2_cidade_cep1" value="">
                    </div>

                    <div class="fkcorreiosg2-float-left">
                        <span id="fkcorreiosg2_span_cidade">a</span>
                    </div>

                    <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                        <input class="fkcorreiosg2-mask-cep" type="text" name="fkcorreiosg2_cidade_cep2" id="fkcorreiosg2_cidade_cep2" value="">
                    </div>

                    <div class="fkcorreiosg2-float-left" id="fkcorreiosg2_button_cidade">
                        <input class="fkcorreiosg2-button" name="button" type="button" value="{l s="Incluir" mod="fkcorreiosg2"}" onclick="fkcorreiosg2IncluirCepCidade();">
                    </div>

                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-col-lg-70 fkcorreiosg2-float-left">
                        <textarea id="fkcorreiosg2_cep_cidade" name="fkcorreiosg2_cep_cidade">{$tab_2['fkcorreiosg2_cep_cidade']}</textarea>
                    </div>
                </div>

            </div>

        </div>

        <div class="fkcorreiosg2-panel">

            <div class="fkcorreiosg2-panel-heading">
                {l s="Serviços" mod="fkcorreiosg2"}
            </div>

            <div class="fkcorreiosg2-form">
                <label for="fkcorreiosg2_mao_propria" class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                <div class="fkcorreiosg2-float-left">
                    <input type="checkbox" name="fkcorreiosg2_mao_propria" value="on" {if isset($tab_2['fkcorreiosg2_mao_propria']) and $tab_2['fkcorreiosg2_mao_propria'] == 'on'}checked="checked"{/if}>
                </div>
                <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                    {l s="Mão Própria" mod="fkcorreiosg2"}
                </label>
            </div>

            <div class="fkcorreiosg2-form">
                <label for="fkcorreiosg2_valor_declarado" class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                <div class="fkcorreiosg2-float-left">
                    <input type="checkbox" name="fkcorreiosg2_valor_declarado" value="on" {if isset($tab_2['fkcorreiosg2_valor_declarado']) and $tab_2['fkcorreiosg2_valor_declarado'] == 'on'}checked="checked"{/if}>
                </div>
                <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                    {l s="Valor Declarado" mod="fkcorreiosg2"}
                </label>
            </div>

            <div class="fkcorreiosg2-form">
                <label for="fkcorreiosg2_aviso_recebimento" class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                <div class="fkcorreiosg2-float-left">
                    <input type="checkbox" name="fkcorreiosg2_aviso_recebimento" value="on" {if isset($tab_2['fkcorreiosg2_aviso_recebimento']) and $tab_2['fkcorreiosg2_aviso_recebimento'] == 'on'}checked="checked"{/if}>
                </div>
                <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                    {l s="Aviso de Recebimento" mod="fkcorreiosg2"}
                </label>
            </div>

        </div>

        <div class="fkcorreiosg2-panel">

            <div class="fkcorreiosg2-panel-heading">
                {l s="Frete e Envio" mod="fkcorreiosg2"}
            </div>

            <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-40 fkcorreiosg2-sub-panel">

                <div class="fkcorreiosg2-panel-heading">
                    {l s="Preparação em Dias" mod="fkcorreiosg2"}
                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-col-lg-20">
                        <input type="text" name="fkcorreiosg2_tempo_preparacao" id="fkcorreiosg2_tempo_preparacao" value="{$tab_2['fkcorreiosg2_tempo_preparacao']}">
                    </div>
                    <p class="help-block">
                        O valor deste campo será somado ao Prazo de Entrega
                    </p>
                </div>

            </div>

            <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-40 fkcorreiosg2-sub-panel">

                <div class="fkcorreiosg2-panel-heading">
                    {l s="Unidades de Envio" mod="fkcorreiosg2"}
                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-float-left">
                        <input type="radio" name="fkcorreiosg2_embalagem" value="2" {if isset($tab_2['fkcorreiosg2_embalagem']) and $tab_2['fkcorreiosg2_embalagem'] == '2'}checked="checked"{/if}>
                    </div>
                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                        {l s="Pacote" mod="fkcorreiosg2"}
                    </label>
                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-float-left">
                        <input type="radio" name="fkcorreiosg2_embalagem" value="1" {if isset($tab_2['fkcorreiosg2_embalagem']) and $tab_2['fkcorreiosg2_embalagem'] == '1'}checked="checked"{/if}>
                    </div>
                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                        {l s="Embalagens padrão" mod="fkcorreiosg2"}
                    </label>
                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-float-left">
                        <input type="radio" name="fkcorreiosg2_embalagem" value="0" {if isset($tab_2['fkcorreiosg2_embalagem']) and $tab_2['fkcorreiosg2_embalagem'] == '0'}checked="checked"{/if}>
                    </div>
                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                        {l s="Embalagem individual" mod="fkcorreiosg2"}
                    </label>
                    <p class="help-block fkcorreiosg2-clear">
                        Estas opções são utilizadas somente nos Serviços dos Correios
                    </p>
                </div>

            </div>

            <div class="fkcorreiosg2-form">
                <label for="fkcorreiosg2_offline" class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                <div class="fkcorreiosg2-float-left">
                    <input type="checkbox" name="fkcorreiosg2_offline" value="on" {if isset($tab_2['fkcorreiosg2_offline']) and $tab_2['fkcorreiosg2_offline'] == 'on'}checked="checked"{/if}>
                </div>
                <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                    {l s="Cálculo com base somente nas tabelas offline" mod="fkcorreiosg2"}
                </label>
            </div>

            <div class="fkcorreiosg2-form">
                <label for="fkcorreiosg2_frete_gratis_demais_transp" class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                <div class="fkcorreiosg2-float-left">
                    <input type="checkbox" name="fkcorreiosg2_frete_gratis_demais_transp" value="on" {if isset($tab_2['fkcorreiosg2_frete_gratis_demais_transp']) and $tab_2['fkcorreiosg2_frete_gratis_demais_transp'] == 'on'}checked="checked"{/if}>
                </div>
                <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                    {l s="Quando frete grátis, disponibilizar demais transportadoras com valores" mod="fkcorreiosg2"}
                </label>
            </div>

        </div>

        <div class="fkcorreiosg2-panel">

            <div class="fkcorreiosg2-panel-heading">
                {l s="Bloco de Simulação do Frete" mod="fkcorreiosg2"}
            </div>

            <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-40 fkcorreiosg2-sub-panel">

                <div class="fkcorreiosg2-panel-heading">
                    {l s="Detalhes do Produto" mod="fkcorreiosg2"}
                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-float-left">
                        <input type="checkbox" name="fkcorreiosg2_bloco_produto" value="on" {if isset($tab_2['fkcorreiosg2_bloco_produto']) and $tab_2['fkcorreiosg2_bloco_produto'] == 'on'}checked="checked"{/if}>
                    </div>
                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                        {l s="Ativo" mod="fkcorreiosg2"}
                    </label>
                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-float-left fkcorreiosg2-margin">
                        <input type="radio" name="fkcorreiosg2_bloco_produto_posicao" value="0" {if isset($tab_2['fkcorreiosg2_bloco_produto_posicao']) and $tab_2['fkcorreiosg2_bloco_produto_posicao'] == '0'}checked="checked"{/if}>
                    </div>
                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                        {l s="Após Descrição Resumida" mod="fkcorreiosg2"}
                    </label>
                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-float-left fkcorreiosg2-margin">
                        <input type="radio" name="fkcorreiosg2_bloco_produto_posicao" value="2" {if isset($tab_2['fkcorreiosg2_bloco_produto_posicao']) and $tab_2['fkcorreiosg2_bloco_produto_posicao'] == '2'}checked="checked"{/if}>
                    </div>
                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                        {l s="Box Adicionar ao Carrinho" mod="fkcorreiosg2"}
                    </label>
                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-float-left fkcorreiosg2-margin">
                        <input type="radio" name="fkcorreiosg2_bloco_produto_posicao" value="1" {if isset($tab_2['fkcorreiosg2_bloco_produto_posicao']) and $tab_2['fkcorreiosg2_bloco_produto_posicao'] == '1'}checked="checked"{/if}>
                    </div>
                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                        {l s="Após Descrição Detalhada" mod="fkcorreiosg2"}
                    </label>
                </div>

                <div class="fkcorreiosg2-form fkcorreiosg2-margin">
                    <div class="fkcorreiosg2-float-left">
                        <input type="checkbox" name="fkcorreiosg2_bloco_produto_lightbox" value="on" {if isset($tab_2['fkcorreiosg2_bloco_produto_lightbox']) and $tab_2['fkcorreiosg2_bloco_produto_lightbox'] == 'on'}checked="checked"{/if}>
                    </div>
                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                        {l s="LightBox" mod="fkcorreiosg2"}
                    </label>
                </div>

            </div>

            <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-40 fkcorreiosg2-sub-panel">

                <div class="fkcorreiosg2-panel-heading">
                    {l s="Carrinho de Compras" mod="fkcorreiosg2"}
                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-float-left">
                        <input type="checkbox" name="fkcorreiosg2_bloco_carrinho" value="on" {if isset($tab_2['fkcorreiosg2_bloco_carrinho']) and $tab_2['fkcorreiosg2_bloco_carrinho'] == 'on'}checked="checked"{/if}>
                    </div>
                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                        {l s="Ativo" mod="fkcorreiosg2"}
                    </label>
                </div>

            </div>

            <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-40 fkcorreiosg2-sub-panel">

                <div class="fkcorreiosg2-panel-heading">
                    {l s="Opções Diversas" mod="fkcorreiosg2"}
                </div>

                <div class="fkcorreiosg2-form">
                    <div class="fkcorreiosg2-float-left">
                        <input type="checkbox" name="fkcorreiosg2_msg_correios" value="on" {if isset($tab_2['fkcorreiosg2_msg_correios']) and $tab_2['fkcorreiosg2_msg_correios'] == 'on'}checked="checked"{/if}>
                    </div>
                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                        {l s="Mostrar mensagens informativas enviadas pelos Correios" mod="fkcorreiosg2"}
                    </label>
                </div>

                <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-90">

                    <div class="fkcorreiosg2-panel-heading">
                        {l s="Tema" mod="fkcorreiosg2"}
                    </div>

                    <div class="fkcorreiosg2-form">
                        <div class="fkcorreiosg2-col-lg-30 fkcorreiosg2-float-left">
                            <input type="text" name="fkcorreiosg2_borda" id="fkcorreiosg2_borda" value="{$tab_2['fkcorreiosg2_borda']}">
                        </div>
                        <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                            {l s="Borda" mod="fkcorreiosg2"}
                        </label>
                    </div>
                    <div class="fkcorreiosg2-form">
                        <div class="fkcorreiosg2-col-lg-30 fkcorreiosg2-float-left">
                            <input type="text" name="fkcorreiosg2_raio_borda" id="fkcorreiosg2_raio_borda" value="{$tab_2['fkcorreiosg2_raio_borda']}">
                        </div>
                        <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                            {l s="Raio da Borda" mod="fkcorreiosg2"}
                        </label>
                    </div>
                    <div class="fkcorreiosg2-form">
                        <div class="fkcorreiosg2-col-lg-30 fkcorreiosg2-float-left">
                            <input type="text" name="fkcorreiosg2_cor_fundo" id="fkcorreiosg2_cor_fundo" value="{$tab_2['fkcorreiosg2_cor_fundo']}">
                        </div>
                        <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                            {l s="Cor de Fundo" mod="fkcorreiosg2"}
                        </label>
                    </div>
                    <div class="fkcorreiosg2-form">
                        <div class="fkcorreiosg2-col-lg-30 fkcorreiosg2-float-left">
                            <input type="text" name="fkcorreiosg2_cor_fonte_titulo" id="fkcorreiosg2_cor_fonte_titulo" value="{$tab_2['fkcorreiosg2_cor_fonte_titulo']}">
                        </div>
                        <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                            {l s="Cor da Fonte do Título" mod="fkcorreiosg2"}
                        </label>
                    </div>
                    <div class="fkcorreiosg2-form">
                        <div class="fkcorreiosg2-col-lg-30 fkcorreiosg2-float-left">
                            <input type="text" name="fkcorreiosg2_cor_botao" id="fkcorreiosg2_cor_botao" value="{$tab_2['fkcorreiosg2_cor_botao']}">
                        </div>
                        <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                            {l s="Cor do Botão" mod="fkcorreiosg2"}
                        </label>
                    </div>
                    <div class="fkcorreiosg2-form">
                        <div class="fkcorreiosg2-col-lg-30 fkcorreiosg2-float-left">
                            <input type="text" name="fkcorreiosg2_cor_fonte_botao" id="fkcorreiosg2_cor_fonte_botao" value="{$tab_2['fkcorreiosg2_cor_fonte_botao']}">
                        </div>
                        <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                            {l s="Cor da Fonte do Botão" mod="fkcorreiosg2"}
                        </label>
                    </div>
                    <div class="fkcorreiosg2-form">
                        <div class="fkcorreiosg2-col-lg-30 fkcorreiosg2-float-left">
                            <input type="text" name="fkcorreiosg2_cor_faixa_msg" id="fkcorreiosg2_cor_faixa_msg" value="{$tab_2['fkcorreiosg2_cor_faixa_msg']}">
                        </div>
                        <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                            {l s="Cor da Faixa de Mensagem" mod="fkcorreiosg2"}
                        </label>
                    </div>
                    <div class="fkcorreiosg2-form">
                        <div class="fkcorreiosg2-col-lg-30 fkcorreiosg2-float-left">
                            <input type="text" name="fkcorreiosg2_cor_fonte_msg" id="fkcorreiosg2_cor_fonte_msg" value="{$tab_2['fkcorreiosg2_cor_fonte_msg']}">
                        </div>
                        <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                            {l s="Cor da Fonte da Faixa de Mensagem" mod="fkcorreiosg2"}
                        </label>
                    </div>

                    <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-90">

                        <div class="fkcorreiosg2-panel-heading">
                            {l s="Descrição Detalhada e Carrinho" mod="fkcorreiosg2"}
                        </div>

                        <div class="fkcorreiosg2-form">
                            <div class="fkcorreiosg2-col-lg-30 fkcorreiosg2-float-left">
                                <input type="text" name="fkcorreiosg2_largura" id="fkcorreiosg2_largura" value="{$tab_2['fkcorreiosg2_largura']}">
                            </div>
                            <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                {l s="Largura" mod="fkcorreiosg2"}
                            </label>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="fkcorreiosg2-panel">

            <div class="fkcorreiosg2-panel-heading">
                {l s="Bloco de Rastreio de Encomendas" mod="fkcorreiosg2"}
            </div>

            <div class="fkcorreiosg2-form">
                <label for="fkcorreiosg2_bloco_rastreio_left" class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                <div class="fkcorreiosg2-float-left">
                    <input type="checkbox" name="fkcorreiosg2_bloco_rastreio_left" value="on" {if isset($tab_2['fkcorreiosg2_bloco_rastreio_left']) and $tab_2['fkcorreiosg2_bloco_rastreio_left'] == 'on'}checked="checked"{/if}>
                </div>
                <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                    {l s="Coluna Esquerda" mod="fkcorreiosg2"}
                </label>
            </div>

            <div class="fkcorreiosg2-form">
                <label for="fkcorreiosg2_bloco_rastreio_right" class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                <div class="fkcorreiosg2-float-left">
                    <input type="checkbox" name="fkcorreiosg2_bloco_rastreio_right" value="on" {if isset($tab_2['fkcorreiosg2_bloco_rastreio_right']) and $tab_2['fkcorreiosg2_bloco_rastreio_right'] == 'on'}checked="checked"{/if}>
                </div>
                <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                    {l s="Coluna Direita" mod="fkcorreiosg2"}
                </label>
            </div>

            <div class="fkcorreiosg2-form">
                <label for="fkcorreiosg2_bloco_rastreio_footer" class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                <div class="fkcorreiosg2-float-left">
                    <input type="checkbox" name="fkcorreiosg2_bloco_rastreio_footer" value="on" {if isset($tab_2['fkcorreiosg2_bloco_rastreio_footer']) and $tab_2['fkcorreiosg2_bloco_rastreio_footer'] == 'on'}checked="checked"{/if}>
                </div>
                <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                    {l s="Rodapé" mod="fkcorreiosg2"}
                </label>
            </div>

            <div class="fkcorreiosg2-form">
                <label for="fkcorreiosg2_bloco_rastreio_account" class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                <div class="fkcorreiosg2-float-left">
                    <input type="checkbox" name="fkcorreiosg2_bloco_rastreio_account" value="on" {if isset($tab_2['fkcorreiosg2_bloco_rastreio_account']) and $tab_2['fkcorreiosg2_bloco_rastreio_account'] == 'on'}checked="checked"{/if}>
                </div>
                <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                    {l s="Conta do Cliente" mod="fkcorreiosg2"}
                </label>
            </div>

        </div>

        <div class="fkcorreiosg2-panel">

            <div class="fkcorreiosg2-panel-heading">
                {l s="Diversos" mod="fkcorreiosg2"}
            </div>

            <div class="fkcorreiosg2-form">
                <label for="fkcorreiosg2_excluir_config" class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                <div class="fkcorreiosg2-float-left">
                    <input type="checkbox" name="fkcorreiosg2_excluir_config" id="fkcorreiosg2_excluir_config" value="on" onclick="fkcorreiosg2ExcluirConf('Atenção: Você marcou para excluir a configuração do módulo na desinstalação. Confirma?','fkcorreiosg2_excluir_config')" {if isset($tab_2['fkcorreiosg2_excluir_config']) and $tab_2['fkcorreiosg2_excluir_config'] == 'on'}checked="checked"{/if}>
                </div>
                <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                    {l s="Excluir Configuração do Módulo na desinstalação" mod="fkcorreiosg2"}
                </label>
            </div>

        </div>

        <div class="fkcorreiosg2-panel-footer">
            <button type="submit" value="1" name="btnSubmit" class="fkcorreiosg2-button fkcorreiosg2-float-right">
                <i class="process-icon-save"></i>
                {l s="Salvar" mod="fkcorreiosg2"}
            </button>
        </div>

    </div>

</form>
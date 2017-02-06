
<div class="fkcorreiosg2-panel">

    <div class="fkcorreiosg2-panel-heading">
        {l s="Frete Grátis" mod="fkcorreiosg2"}
    </div>

    <div class="fkcorreiosg2-panel-header">
        <form id="configuration_form" class="defaultForm  form-horizontal" action="{$tab_7['formAction']}&origem=freteGratis" method="post">
            <button type="submit" value="1" name="btnAdd" class="fkcorreiosg2-button fkcorreiosg2-float-left">
                <i class="process-icon-new"></i>
                {l s="Incluir Região" mod="fkcorreiosg2"}
            </button>
        </form>

        <button type="button" value="1" name="btnAjuda" class="fkcorreiosg2-button fkcorreiosg2-float-right" onClick="window.open('http://www.modulosfk.com.br/modulosfk/ajuda/fkcorreiosg2_v1_0_0.pdf','Janela','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=900,height=500,left=500,top=150'); return false;">
            <i class="process-icon-help"></i>
            {l s="Ajuda" mod="fkcorreiosg2"}
        </button>
    </div>

    {if isset($tab_7['regioes'])}
        {foreach $tab_7['regioes'] as $reg}

            <form id="configuration_form" class="defaultForm form-horizontal" action="{$tab_7['formAction']}&origem=freteGratis&id={$reg['id']}" method="post">

                {*** Campo hidden para controle de POST - mostra o servico aberto/fechado ***}
                <input type="hidden" name="fkcorreiosg2_frete_gratis_post_{$reg['id']}">

                <div class="fkcorreiosg2-panel">

                    <div class="fkcorreiosg2-panel-heading {if isset($reg['ativo']) and $reg['ativo'] == '1'}fkcorreiosg2-toggle{else}fkcorreiosg2-toggle-inativo{/if}" onclick="fkcorreiosg2Toggle('fkcorreiosg2_toggle_item_frete_gratis_' + {$reg['id']})">
                        <i class="icon-resize-full"></i>
                        {$reg['nome_regiao']}
                    </div>

                    {assign var="temp" value="fkcorreiosg2_frete_gratis_post_`$reg['id']`"}
                    {if isset($smarty.post.$temp)}
                        {assign var="classToggleItem" value="fkcorreiosg2-toggle-item-open"}
                    {else}
                        {assign var="classToggleItem" value="fkcorreiosg2-toggle-item-close"}
                    {/if}

                    <div class="{$classToggleItem}" id="fkcorreiosg2_toggle_item_frete_gratis_{$reg['id']}">

                        <div class="fkcorreiosg2-form">
                            <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                            <div class="fkcorreiosg2-float-left">
                                {assign var="temp" value="fkcorreiosg2_frete_gratis_ativo_`$reg['id']`"}
                                <input type="checkbox" name="fkcorreiosg2_frete_gratis_ativo_{$reg['id']}" value="on" {if isset($smarty.post.$temp) and $smarty.post.$temp == 'on'}checked="checked"{else}{if isset($reg['ativo']) and $reg['ativo'] == '1'}checked="checked"{/if}{/if}>
                            </div>
                            <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                {l s="Ativo" mod="fkcorreiosg2"}
                            </label>
                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-regioes">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Nome da Região" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">
                                <div class="fkcorreiosg2-col-lg-70 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_frete_gratis_nome_regiao_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_frete_gratis_nome_regiao_{$reg['id']}" id="fkcorreiosg2_frete_gratis_nome_regiao_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['nome_regiao']}{/if}">
                                </div>
                            </div>

                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-regioes">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Estados Atendidos" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-panel">

                                <div class="fkcorreiosg2-panel-heading">
                                    {l s="Filtro" mod="fkcorreiosg2"}
                                </div>

                                <div class="fkcorreiosg2-form">
                                    <div class="fkcorreiosg2-float-left">
                                        {assign var="temp" value="fkcorreiosg2_frete_gratis_filtro_uf_`$reg['id']`"}
                                        <input type="radio" name="fkcorreiosg2_frete_gratis_filtro_uf_{$reg['id']}" value="1" {if isset($smarty.post.$temp) and $smarty.post.$temp == 1}checked="checked"{else}{if isset($reg['filtro_regiao_uf']) and $reg['filtro_regiao_uf'] == '1'}checked="checked"{/if}{/if}>
                                    </div>
                                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                        {l s="Todo o Estado" mod="fkcorreiosg2"}
                                    </label>

                                    <div class="fkcorreiosg2-float-left fkcorreiosg2-margin">
                                        {assign var="temp" value="fkcorreiosg2_frete_gratis_filtro_uf_`$reg['id']`"}
                                        <input type="radio" name="fkcorreiosg2_frete_gratis_filtro_uf_{$reg['id']}" value="2" {if isset($smarty.post.$temp) and $smarty.post.$temp == 2}checked="checked"{else}{if isset($reg['filtro_regiao_uf']) and $reg['filtro_regiao_uf'] == '2'}checked="checked"{/if}{/if}>
                                    </div>
                                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                        {l s="Somente Capital" mod="fkcorreiosg2"}
                                    </label>

                                    <div class="fkcorreiosg2-float-left fkcorreiosg2-margin">
                                        {assign var="temp" value="fkcorreiosg2_frete_gratis_filtro_uf_`$reg['id']`"}
                                        <input type="radio" name="fkcorreiosg2_frete_gratis_filtro_uf_{$reg['id']}" value="3" {if isset($smarty.post.$temp) and $smarty.post.$temp == 3}checked="checked"{else}{if isset($reg['filtro_regiao_uf']) and $reg['filtro_regiao_uf'] == '3'}checked="checked"{/if}{/if}>
                                    </div>
                                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                        {l s="Somente Interior" mod="fkcorreiosg2"}
                                    </label>
                                </div>

                            </div>

                            {*** Variavel de controle de UFs por linha ***}
                            {assign var="totEstados" value=1}
                            {assign var="maxEstados" value=10}

                            <div class="fkcorreiosg2-form">
                                {foreach $tab_7['arrayUF'][$reg['id']] as $uf}

                                    {if $totEstados > $maxEstados}
                                        {assign var="totEstados" value=1}
                                    {/if}

                                    <div class="fkcorreiosg2-float-left">
                                        {assign var="temp" value="fkcorreiosg2_frete_gratis_uf_`$reg['id']`"}
                                        <input class="fkcorreiosg2_frete_gratis_uf_{$reg['id']}" type="checkbox" name="fkcorreiosg2_frete_gratis_uf_{$reg['id']}[]" value="{$uf['uf']}" {if isset($smarty.post.$temp) and $smarty.post.$temp == $uf['uf']}checked="checked"{else}{if isset($uf['ativo']) and $uf['ativo'] == '1'}checked="checked"{/if}{/if}>
                                    </div>
                                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-estados">
                                        {$uf['uf']}
                                    </label>

                                    {assign var="totEstados" value=$totEstados+1}

                                    {if $totEstados > $maxEstados}
                                        <div class="fkcorreiosg2-clear">
                                            <br>
                                        </div>
                                    {/if}

                                {/foreach}
                            </div>

                            <div class="fkcorreiosg2-panel-footer">
                                <button type="button" value="1" name="btnMarcar" class="fkcorreiosg2-button fkcorreiosg2-float-left" onclick="fkcorreiosg2Marcar('fkcorreiosg2_frete_gratis_uf_' + {$reg['id']})">
                                    <i class="process-icon-ok"></i>
                                    {l s="Marcar Todos" mod="fkcorreiosg2"}
                                </button>

                                <button type="button" value="1" name="btnDesmarcar" class="fkcorreiosg2-button fkcorreiosg2-float-right" onclick="fkcorreiosg2Desmarcar('fkcorreiosg2_frete_gratis_uf_' + {$reg['id']})">
                                    <i class="process-icon-cancel"></i>
                                    {l s="Desmarcar Todos" mod="fkcorreiosg2"}
                                </button>
                            </div>
                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-regioes">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Intervalo de CEP Atendidos" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">

                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    <input class="fkcorreiosg2-mask-cep" type="text" name="fkcorreiosg2_frete_gratis_cep1_{$reg['id']}" id="fkcorreiosg2_frete_gratis_cep1_{$reg['id']}" value="">
                                </div>

                                <div class="fkcorreiosg2-float-left">
                                    <span id="fkcorreiosg2_span_servicos">a</span>
                                </div>

                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    <input class="fkcorreiosg2-mask-cep" type="text" name="fkcorreiosg2_frete_gratis_cep2_{$reg['id']}" id="fkcorreiosg2_frete_gratis_cep2_{$reg['id']}" value="">
                                </div>

                                <div class="fkcorreiosg2-float-left" id="fkcorreiosg2_button_frete_gratis">
                                    <input class="fkcorreiosg2-button" name="button" type="button" value="{l s="Incluir" mod="fkcorreiosg2"}" onclick="fkcorreiosg2IncluirCepFreteGratis({$reg['id']});">
                                </div>

                            </div>

                            <div class="fkcorreiosg2-form">
                                <div class="fkcorreiosg2-col-lg-90">
                                    {assign var="temp" value="fkcorreiosg2_frete_gratis_cep_`$reg['id']`"}
                                    <textarea name="fkcorreiosg2_frete_gratis_cep_{$reg['id']}" id="fkcorreiosg2_frete_gratis_cep_{$reg['id']}">{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['regiao_cep']}{/if}</textarea>
                                </div>
                                <p class="help-block">
                                    Os intervalos de CEP aqui relacionados serão atendidos por esta Região independentemente dos Estados selecionados
                                </p>
                            </div>

                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-regioes">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Intervalo de CEP Excluídos" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">

                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    <input class="fkcorreiosg2-mask-cep" type="text" name="fkcorreiosg2_frete_gratis_cep1_excluido_{$reg['id']}" id="fkcorreiosg2_frete_gratis_cep1_excluido_{$reg['id']}" value="">
                                </div>

                                <div class="fkcorreiosg2-float-left">
                                    <span id="fkcorreiosg2_span_servicos">a</span>
                                </div>

                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    <input class="fkcorreiosg2-mask-cep" type="text" name="fkcorreiosg2_frete_gratis_cep2_excluido_{$reg['id']}" id="fkcorreiosg2_frete_gratis_cep2_excluido_{$reg['id']}" value="">
                                </div>

                                <div class="fkcorreiosg2-float-left" id="fkcorreiosg2_button_servicos">
                                    <input class="fkcorreiosg2-button" name="button" type="button" value="{l s="Incluir" mod="fkcorreiosg2"}" onclick="fkcorreiosg2IncluirCepFreteGratisExcluido({$reg['id']});">
                                </div>

                            </div>

                            <div class="fkcorreiosg2-form">
                                <div class="fkcorreiosg2-col-lg-90">
                                    {assign var="temp" value="fkcorreiosg2_frete_gratis_cep_excluido_`$reg['id']`"}
                                    <textarea name="fkcorreiosg2_frete_gratis_cep_excluido_{$reg['id']}" id="fkcorreiosg2_frete_gratis_cep_excluido_{$reg['id']}">{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['regiao_cep_excluido']}{/if}</textarea>
                                </div>
                                <p class="help-block">
                                    Os intervalos de CEP aqui relacionados não serão atendidos por esta Região
                                </p>
                            </div>

                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-regioes">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Valor do Pedido" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">
                                <div class="fkcorreiosg2-col-lg-30">
                                    {assign var="temp" value="fkcorreiosg2_frete_gratis_valor_pedido_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_frete_gratis_valor_pedido_{$reg['id']}" id="fkcorreiosg2_frete_gratis_valor_pedido_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['valor_pedido']}{/if}">
                                </div>
                                <p class="help-block">
                                    Valor 0 (zero) indica que a região não será selecionada de acordo com o valor do pedido
                                </p>
                            </div>

                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-regioes">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Produtos com Frete Grátis" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">

                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    <input type="text" name="fkcorreiosg2_frete_gratis_produto_{$reg['id']}" id="fkcorreiosg2_frete_gratis_produto_{$reg['id']}" value="">
                                </div>

                                <div class="fkcorreiosg2-float-left" id="fkcorreiosg2_button_frete_gratis">
                                    <input class="fkcorreiosg2-button" name="button" type="button" value="{l s="Incluir" mod="fkcorreiosg2"}" onclick="fkcorreiosg2IncluirProdutosFreteGratis({$reg['id']});">
                                </div>

                            </div>

                            <div class="fkcorreiosg2-form">
                                <div class="fkcorreiosg2-col-lg-90 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_frete_gratis_produtos_`$reg['id']`"}
                                    <textarea name="fkcorreiosg2_frete_gratis_produtos_{$reg['id']}" id="fkcorreiosg2_frete_gratis_produtos_{$reg['id']}">{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['id_produtos']}{/if}</textarea>
                                </div>
                            </div>
                            <p class="help-block">
                                Os produtos aqui informados terão Frete Grátis independentemente do Valor do Pedido
                            </p>

                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-regioes">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Transportadora Definida para o Frete Grátis" mod="fkcorreiosg2"}
                            </div>

                            {if isset($tab_7['transportadoras'])}
                                {foreach $tab_7['transportadoras'] as $transp}

                                    <div class="fkcorreiosg2-form">
                                        <div class="fkcorreiosg2-float-left">
                                            {assign var="temp" value="fkcorreiosg2_frete_gratis_transp_`$reg['id']`"}
                                            {assign var="transpAtual" value="`$reg['id_carrier']`"}
                                            <input type="radio" name="fkcorreiosg2_frete_gratis_transp_{$reg['id']}" value="{$transp['id_carrier']}" {if isset($smarty.post.$temp) and $smarty.post.$temp == {$transp['id_carrier']}}checked="checked"{else}{if $transpAtual == $transp['id_carrier']}checked="checked"{/if}{/if}>
                                        </div>
                                        <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                            {$transp['transportadora']}
                                        </label>
                                    </div>

                                {/foreach}
                            {/if}

                        </div>

                        <div class="fkcorreiosg2-panel-footer">
                            <button type="submit" value="1" name="btnDel" id="fkcorreiosg2_frete_gratis_button_del" class="fkcorreiosg2-button fkcorreiosg2-float-left" onclick="return fkcorreiosg2Excluir('{l s="Confirma a exclusão da Região?" mod="fkcorreiosg2"}');">
                                <i class="process-icon-delete"></i>
                                {l s="Excluir Região" mod="fkcorreiosg2"}
                            </button>

                            <button type="submit" value="1" name="btnSubmit" class="fkcorreiosg2-button fkcorreiosg2-float-right">
                                <i class="process-icon-save"></i>
                                {l s="Salvar" mod="fkcorreiosg2"}
                            </button>
                        </div>

                    </div>

                </div>

            </form>

        {/foreach}

    {/if}

</div>
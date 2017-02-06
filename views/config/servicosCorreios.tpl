
<div class="fkcorreiosg2-panel">

    <div class="fkcorreiosg2-panel-heading">
        {l s="Servicos" mod="fkcorreiosg2"}
    </div>

    <div class="fkcorreiosg2-panel-header">
        <button type="button" value="1" name="btnAjuda" class="fkcorreiosg2-button fkcorreiosg2-float-right" onClick="window.open('http://www.modulosfk.com.br/modulosfk/ajuda/fkcorreiosg2_v1_0_0.pdf','Janela','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=900,height=500,left=500,top=150'); return false;">
            <i class="process-icon-help"></i>
            {l s="Ajuda" mod="fkcorreiosg2"}
        </button>
    </div>

    {if isset($tab_6['servicos'])}
        {foreach $tab_6['servicos'] as $reg}

            <form id="configuration_form" class="defaultForm form-horizontal" action="{$tab_6['formAction']}&origem=servicosCorreios&id={$reg['id']}" method="post" enctype="multipart/form-data">

                {*** Campo hidden para controle de POST - mostra o servico aberto/fechado ***}
                <input type="hidden" name="fkcorreiosg2_servicos_post_{$reg['id']}">

                <div class="fkcorreiosg2-panel">

                    <div class="fkcorreiosg2-panel-heading {if isset($reg['ativo']) and $reg['ativo'] == '1'}fkcorreiosg2-toggle{else}fkcorreiosg2-toggle-inativo{/if}" onclick="fkcorreiosg2Toggle('fkcorreiosg2_toggle_item_servicos_' + {$reg['id']})">
                        <i class="icon-resize-full"></i>
                        {$reg['servico']}
                    </div>

                    {assign var="temp" value="fkcorreiosg2_servicos_post_`$reg['id']`"}
                    {if isset($smarty.post.$temp)}
                        {assign var="classToggleItem" value="fkcorreiosg2-toggle-item-open"}
                    {else}
                        {assign var="classToggleItem" value="fkcorreiosg2-toggle-item-close"}
                    {/if}

                    <div class="{$classToggleItem}" id="fkcorreiosg2_toggle_item_servicos_{$reg['id']}">

                        <div class="fkcorreiosg2-form">
                            <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-15"></label>
                            <div class="fkcorreiosg2-float-left">
                                {assign var="temp" value="fkcorreiosg2_servicos_ativo_`$reg['id']`"}
                                <input type="checkbox" name="fkcorreiosg2_servicos_ativo_{$reg['id']}" value="on" {if isset($smarty.post.$temp) and $smarty.post.$temp == 'on'}checked="checked"{else}{if isset($reg['ativo']) and $reg['ativo'] == '1'}checked="checked"{/if}{/if}>
                            </div>
                            <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                {l s="Ativo" mod="fkcorreiosg2"}
                            </label>
                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-regioes">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Grade de Velocidade" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_servicos_grade_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_servicos_grade_{$reg['id']}" id="fkcorreiosg2_servicos_grade_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['grade']}{/if}">
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
                                        {assign var="temp" value="fkcorreiosg2_servicos_filtro_uf_`$reg['id']`"}
                                        <input type="radio" name="fkcorreiosg2_servicos_filtro_uf_{$reg['id']}" value="1" {if isset($smarty.post.$temp) and $smarty.post.$temp == 1}checked="checked"{else}{if isset($reg['filtro_regiao_uf']) and $reg['filtro_regiao_uf'] == '1'}checked="checked"{/if}{/if}>
                                    </div>
                                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                        {l s="Todo o Estado" mod="fkcorreiosg2"}
                                    </label>

                                    <div class="fkcorreiosg2-float-left fkcorreiosg2-margin">
                                        {assign var="temp" value="fkcorreiosg2_servicos_filtro_uf_`$reg['id']`"}
                                        <input type="radio" name="fkcorreiosg2_servicos_filtro_uf_{$reg['id']}" value="2" {if isset($smarty.post.$temp) and $smarty.post.$temp == 2}checked="checked"{else}{if isset($reg['filtro_regiao_uf']) and $reg['filtro_regiao_uf'] == '2'}checked="checked"{/if}{/if}>
                                    </div>
                                    <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                        {l s="Somente Capital" mod="fkcorreiosg2"}
                                    </label>

                                    <div class="fkcorreiosg2-float-left fkcorreiosg2-margin">
                                        {assign var="temp" value="fkcorreiosg2_servicos_filtro_uf_`$reg['id']`"}
                                        <input type="radio" name="fkcorreiosg2_servicos_filtro_uf_{$reg['id']}" value="3" {if isset($smarty.post.$temp) and $smarty.post.$temp == 3}checked="checked"{else}{if isset($reg['filtro_regiao_uf']) and $reg['filtro_regiao_uf'] == '3'}checked="checked"{/if}{/if}>
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
                                {foreach $tab_6['arrayUF'][$reg['id']] as $uf}

                                    {if $totEstados > $maxEstados}
                                        {assign var="totEstados" value=1}
                                    {/if}

                                    <div class="fkcorreiosg2-float-left">
                                        {assign var="temp" value="fkcorreiosg2_servicos_uf_`$reg['id']`"}
                                        <input class="fkcorreiosg2_servicos_uf_{$reg['id']}" type="checkbox" name="fkcorreiosg2_servicos_uf_{$reg['id']}[]" value="{$uf['uf']}" {if isset($smarty.post.$temp) and $smarty.post.$temp == $uf['uf']}checked="checked"{else}{if isset($uf['ativo']) and $uf['ativo'] == '1'}checked="checked"{/if}{/if}>
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
                                <button type="button" value="1" name="btnMarcar" class="fkcorreiosg2-button fkcorreiosg2-float-left" onclick="fkcorreiosg2Marcar('fkcorreiosg2_servicos_uf_' + {$reg['id']})">
                                    <i class="process-icon-ok"></i>
                                    {l s="Marcar Todos" mod="fkcorreiosg2"}
                                </button>

                                <button type="button" value="1" name="btnDesmarcar" class="fkcorreiosg2-button fkcorreiosg2-float-right" onclick="fkcorreiosg2Desmarcar('fkcorreiosg2_servicos_uf_' + {$reg['id']})">
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
                                    <input class="fkcorreiosg2-mask-cep" type="text" name="fkcorreiosg2_servicos_cep1_{$reg['id']}" id="fkcorreiosg2_servicos_cep1_{$reg['id']}" value="">
                                </div>

                                <div class="fkcorreiosg2-float-left">
                                    <span id="fkcorreiosg2_span_servicos">a</span>
                                </div>

                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    <input class="fkcorreiosg2-mask-cep" type="text" name="fkcorreiosg2_servicos_cep2_{$reg['id']}" id="fkcorreiosg2_servicos_cep2_{$reg['id']}" value="">
                                </div>

                                <div class="fkcorreiosg2-float-left" id="fkcorreiosg2_button_servicos">
                                    <input class="fkcorreiosg2-button" name="button" type="button" value="{l s="Incluir" mod="fkcorreiosg2"}" onclick="fkcorreiosg2IncluirCepServicos({$reg['id']});">
                                </div>

                            </div>

                            <div class="fkcorreiosg2-form">
                                <div class="fkcorreiosg2-col-lg-90">
                                    {assign var="temp" value="fkcorreiosg2_servicos_cep_`$reg['id']`"}
                                    <textarea name="fkcorreiosg2_servicos_cep_{$reg['id']}" id="fkcorreiosg2_servicos_cep_{$reg['id']}">{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['regiao_cep']}{/if}</textarea>
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
                                    <input class="fkcorreiosg2-mask-cep" type="text" name="fkcorreiosg2_servicos_cep1_excluido_{$reg['id']}" id="fkcorreiosg2_servicos_cep1_excluido_{$reg['id']}" value="">
                                </div>

                                <div class="fkcorreiosg2-float-left">
                                    <span id="fkcorreiosg2_span_servicos">a</span>
                                </div>

                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    <input class="fkcorreiosg2-mask-cep" type="text" name="fkcorreiosg2_servicos_cep2_excluido_{$reg['id']}" id="fkcorreiosg2_servicos_cep2_excluido_{$reg['id']}" value="">
                                </div>

                                <div class="fkcorreiosg2-float-left" id="fkcorreiosg2_button_servicos">
                                    <input class="fkcorreiosg2-button" name="button" type="button" value="{l s="Incluir" mod="fkcorreiosg2"}" onclick="fkcorreiosg2IncluirCepServicosExcluido({$reg['id']});">
                                </div>

                            </div>

                            <div class="fkcorreiosg2-form">
                                <div class="fkcorreiosg2-col-lg-90">
                                    {assign var="temp" value="fkcorreiosg2_servicos_cep_excluido_`$reg['id']`"}
                                    <textarea name="fkcorreiosg2_servicos_cep_excluido_{$reg['id']}" id="fkcorreiosg2_servicos_cep_excluido_{$reg['id']}">{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['regiao_cep_excluido']}{/if}</textarea>
                                </div>
                                <p class="help-block">
                                    Os intervalos de CEP aqui relacionados não serão atendidos por esta Região
                                </p>
                            </div>

                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-regioes">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Desconto no Frete" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Percentual de Desconto:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_servicos_percentual_desc_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_servicos_percentual_desc_{$reg['id']}" id="fkcorreiosg2_servicos_percentual_desc_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['percentual_desconto']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Valor do Pedido:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_servicos_valor_pedido_desc_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_servicos_valor_pedido_desc_{$reg['id']}" id="fkcorreiosg2_servicos_valor_pedido_desc_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['valor_pedido_desconto']}{/if}">
                                </div>
                            </div>

                            <p class="help-block">
                                Informe o valor 0 (zero) nos campos "Percentual de Desconto" e "Valor do Pedido" para não aplicar desconto ao frete
                            </p>

                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-regioes">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Logo do Serviço" mod="fkcorreiosg2"}
                            </div>

                            {assign var="urlLogoTransp" value="`$tab_6['urlLogoPS']``$reg['id_carrier']`.jpg"}
                            {assign var="uriLogoTransp" value="`$tab_6['uriLogoPS']``$reg['id_carrier']`.jpg"}
                            {assign var="urlNoImage" value="`$tab_6['urlImg']`no_image.jpg"}

                            <div class="fkcorreiosg2-form">
                                {if file_exists({$uriLogoTransp})}
                                    <img id="fkcorreiosg2_logo_servicos_{$reg['id']}" alt="Logo serviço" src="{$urlLogoTransp}">
                                {else}
                                    <img id="fkcorreiosg2_logo_servicos_{$reg['id']}" alt="Logo serviço" src="{$urlNoImage}">
                                {/if}
                            </div>

                            <div class="fkcorreiosg2-form">
                                <input class="btn btn-default" type="file" name="fkcorreiosg2_servicos_logo_{$reg['id']}">
                            </div>
                            <p class="help-block">
                                Formato jpg
                                <br>
                                Tamanho máximo do arquivo 8 MB
                            </p>

                            {if file_exists({$uriLogoTransp})}
                                <script type="text/javascript">
                                    d = new Date();
                                    idLogo = '#fkcorreiosg2_logo_servicos_' + {$reg['id']};
                                    $(idLogo).attr("src", "{$urlLogoTransp}?" + d.getTime());
                                </script>
                            {/if}

                        </div>

                        <div class="fkcorreiosg2-panel-footer">
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
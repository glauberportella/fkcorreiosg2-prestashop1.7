
<div class="fkcorreiosg2-panel">

    <div class="fkcorreiosg2-panel-heading">
        {l s="Especificações Correios" mod="fkcorreiosg2"}
    </div>

    <div class="fkcorreiosg2-panel-header">
        <button type="button" value="1" name="btnAjuda" class="fkcorreiosg2-button fkcorreiosg2-float-right" onClick="window.open('http://www.modulosfk.com.br/modulosfk/ajuda/fkcorreiosg2_v1_0_0.pdf','Janela','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=900,height=500,left=500,top=150'); return false;">
            <i class="process-icon-help"></i>
            {l s="Ajuda" mod="fkcorreiosg2"}
        </button>
    </div>

    {if isset($tab_5['especificacoes_correios'])}
        {foreach $tab_5['especificacoes_correios'] as $reg}

            <form id="configuration_form" class="defaultForm  form-horizontal" action="{$tab_5['formAction']}&origem=especificacoesCorreios&id={$reg['id']}" method="post">

                {*** Campo hidden para controle de POST - mostra o servico aberto/fechado ***}
                <input type="hidden" name="fkcorreiosg2_espec_post_{$reg['id']}">

                <div class="fkcorreiosg2-panel">

                    <div class="fkcorreiosg2-panel-heading fkcorreiosg2-toggle" onclick="fkcorreiosg2Toggle('fkcorreiosg2_toggle_item_espec_' + {$reg['id']})">
                        <i class="icon-resize-full"></i>
                        {$reg['servico']}
                    </div>

                    {assign var="temp" value="fkcorreiosg2_espec_post_`$reg['id']`"}
                    {if isset($smarty.post.$temp)}
                        {assign var="classToggleItem" value="fkcorreiosg2-toggle-item-open"}
                    {else}
                        {assign var="classToggleItem" value="fkcorreiosg2-toggle-item-close"}
                    {/if}

                    <div class="{$classToggleItem}" id="fkcorreiosg2_toggle_item_espec_{$reg['id']}">

                        <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-50 fkcorreiosg2-sub-panel">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Código de Serviço e Dados Contratuais" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Código de Serviço:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_cod_servico_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_cod_servico_{$reg['id']}" id="fkcorreiosg2_cod_servico_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['cod_servico']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Código Administrativo:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_cod_administrativo_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_cod_administrativo_{$reg['id']}" id="fkcorreiosg2_cod_administrativo_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['cod_administrativo']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Senha:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_senha_`$reg['id']`"}
                                    <input type="password" name="fkcorreiosg2_senha_{$reg['id']}" id="fkcorreiosg2_senha_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['senha']}{/if}">
                                </div>
                            </div>

                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-50 fkcorreiosg2-sub-panel">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Dimensões e Pesos" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Comprimento Mínimo:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_comprimento_min_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_comprimento_min_{$reg['id']}" id="fkcorreiosg2_comprimento_min_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['comprimento_min']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Comprimento Máximo:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_comprimento_max_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_comprimento_max_{$reg['id']}" id="fkcorreiosg2_comprimento_max_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['comprimento_max']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Largura Mínima:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_largura_min_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_largura_min_{$reg['id']}" id="fkcorreiosg2_largura_min_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['largura_min']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Largura Máxima:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_largura_max_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_largura_max_{$reg['id']}" id="fkcorreiosg2_largura_max_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['largura_max']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Altura Mínima:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_altura_min_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_altura_min_{$reg['id']}" id="fkcorreiosg2_altura_min_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['altura_min']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Altura Máxima:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_altura_max_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_altura_max_{$reg['id']}" id="fkcorreiosg2_altura_max_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['altura_max']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Somatória Máx. Dimensões:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_somatoria_dimensoes_max_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_somatoria_dimensoes_max_{$reg['id']}" id="fkcorreiosg2_somatoria_dimensoes_max_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['somatoria_dimensoes_max']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Peso máximo - Estadual:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_peso_estadual_max_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_peso_estadual_max_{$reg['id']}" id="fkcorreiosg2_peso_estadual_max_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['peso_estadual_max']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Peso máximo - Nacional:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_peso_nacional_max_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_peso_nacional_max_{$reg['id']}" id="fkcorreiosg2_peso_nacional_max_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['peso_nacional_max']}{/if}">
                                </div>
                            </div>

                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-50 fkcorreiosg2-sub-panel">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Intervalo de pesos - Estadual" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">
                                {assign var="temp" value="fkcorreiosg2_intervalo_pesos_estadual_`$reg['id']`"}
                                <textarea name="fkcorreiosg2_intervalo_pesos_estadual_{$reg['id']}" id="fkcorreiosg2_intervalo_pesos_estadual_{$reg['id']}">{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['intervalo_pesos_estadual']}{/if}</textarea>
                            </div>

                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-50 fkcorreiosg2-sub-panel">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Intervalo de pesos - Nacional" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">
                                {assign var="temp" value="fkcorreiosg2_intervalo_pesos_nacional_`$reg['id']`"}
                                <textarea name="fkcorreiosg2_intervalo_pesos_nacional_{$reg['id']}" id="fkcorreiosg2_intervalo_pesos_nacional_{$reg['id']}">{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['intervalo_pesos_nacional']}{/if}</textarea>
                            </div>

                        </div>

                        <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-50 fkcorreiosg2-sub-panel">

                            <div class="fkcorreiosg2-panel-heading">
                                {l s="Valores Base Cálculo - Offline" mod="fkcorreiosg2"}
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Cubagem Máxima Isenta:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_cubagem_max_isenta_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_cubagem_max_isenta_{$reg['id']}" id="fkcorreiosg2_cubagem_max_isenta_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['cubagem_max_isenta']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Cubagem Base Cálculo:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_cubagem_base_calculo_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_cubagem_base_calculo_{$reg['id']}" id="fkcorreiosg2_cubagem_base_calculo_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['cubagem_base_calculo']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Valor Mão Própria:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_mao_propria_valor_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_mao_propria_valor_{$reg['id']}" id="fkcorreiosg2_mao_propria_valor_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['mao_propria_valor']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Valor Aviso Recebimento:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_aviso_recebimento_valor_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_aviso_recebimento_valor_{$reg['id']}" id="fkcorreiosg2_aviso_recebimento_valor_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['aviso_recebimento_valor']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Percentual Valor Declarado:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_valor_declarado_percentual_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_valor_declarado_percentual_{$reg['id']}" id="fkcorreiosg2_valor_declarado_percentual_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['valor_declarado_percentual']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Valor Declarado Máximo:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_valor_declarado_max_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_valor_declarado_max_{$reg['id']}" id="fkcorreiosg2_valor_declarado_max_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['valor_declarado_max']}{/if}">
                                </div>
                            </div>

                            <div class="fkcorreiosg2-form">
                                <label class="fkcorreiosg2-label fkcorreiosg2-col-lg-40">
                                    {l s="Seguro Automático:" mod="fkcorreiosg2"}
                                </label>
                                <div class="fkcorreiosg2-col-lg-20 fkcorreiosg2-float-left">
                                    {assign var="temp" value="fkcorreiosg2_seguro_automatico_valor_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_seguro_automatico_valor_{$reg['id']}" id="fkcorreiosg2_seguro_automatico_valor_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['seguro_automatico_valor']}{/if}">
                                </div>
                            </div>

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





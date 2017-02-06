
<div class="fkcorreiosg2-panel">

    <div class="fkcorreiosg2-panel-heading">
        {l s="Tabelas Offline" mod="fkcorreiosg2"}
    </div>

    <div class="fkcorreiosg2-panel-header">
        <button type="button" value="1" name="btnAjuda" class="fkcorreiosg2-button fkcorreiosg2-float-right" onClick="window.open('http://www.modulosfk.com.br/modulosfk/ajuda/fkcorreiosg2_v1_0_0.pdf','Janela','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=900,height=500,left=500,top=150'); return false;">
            <i class="process-icon-help"></i>
            {l s="Ajuda" mod="fkcorreiosg2"}
        </button>
    </div>

    {if isset($tab_8['especificacoesCorreios'])}
        {foreach $tab_8['especificacoesCorreios'] as $espCorreios}

            <form id="configuration_form" class="defaultForm  form-horizontal" action="{$tab_8['formAction']}&origem=tabOffline&id={$espCorreios['id']}" method="post">

                {*** Campo hidden para controle de POST - mostra o servico aberto/fechado ***}
                <input type="hidden" name="fkcorreiosg2_tab_offline_post_{$espCorreios['id']}">

                <div class="fkcorreiosg2-panel">

                    {*** Verifica se a tabela esta gerada ou nao ***}
                    {assign var="tabGerada" value="1"}

                    {if isset($tab_8['tabOfflineCidade'])}
                        {foreach $tab_8['tabOfflineCidade'] as $tabOffCidade}
                            {if $tabOffCidade['id_especificacao'] == $espCorreios['id']}
                                {if !$tabOffCidade['tabela_cidade']}
                                    {assign var="tabGerada" value="0"}
                                {/if}
                            {/if}
                        {/foreach}
                    {/if}

                    <div class="fkcorreiosg2-panel-heading {if $tabGerada == '1'}fkcorreiosg2-toggle{else}fkcorreiosg2-toggle-inativo{/if}" onclick="fkcorreiosg2Toggle('fkcorreiosg2_toggle_item_tab_offline_' + {$espCorreios['id']})">
                        <i class="icon-resize-full"></i>
                        {$espCorreios['servico']}
                    </div>

                    {assign var="temp" value="fkcorreiosg2_tab_offline_post_`$espCorreios['id']`"}
                    {if isset($smarty.post.$temp)}
                        {assign var="classToggleItem" value="fkcorreiosg2-toggle-item-open"}
                    {else}
                        {assign var="classToggleItem" value="fkcorreiosg2-toggle-item-close"}
                    {/if}

                    <div class="{$classToggleItem}" id="fkcorreiosg2_toggle_item_tab_offline_{$espCorreios['id']}">

                        <div id="fkcorreiosg2_button_tab_offline_todos">
                            <input class="fkcorreiosg2-button" name="button" type="button" value="{l s="Processar Todos" mod="fkcorreiosg2"}" onclick="fkcorreiosg2TabOffGeral('{$tab_8['urlImg']}', '{$tab_8['urlFuncoes']}', '{$espCorreios['id']}')">
                        </div>

                        <div class="fkcorreiosg2-tab-offline-status fkcorreiosg2-col-lg-50" id="fkcorreiosg2_tab_offline_status_{$espCorreios['id']}"></div>

                        {if isset($tab_8['tabOfflineCidade'])}
                            {foreach $tab_8['tabOfflineCidade'] as $tabOffCidade}
                                {if $tabOffCidade['id_especificacao'] == $espCorreios['id']}

                                    <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-col-lg-50">

                                        <div class="fkcorreiosg2-panel-heading">
                                            {l s="Minha Cidade" mod="fkcorreiosg2"}
                                        </div>

                                        <div class="fkcorreiosg2-form">
                                            <img class="fkcorreiosg2-img-tab-offline" id="fkcorreiosg2_tab_offline_img_cidade_{$tabOffCidade['id']}" src="" alt="" width="32" height="32"/>
                                        </div>
                                        <div class="fkcorreiosg2-form" id="fkcorreiosg2_button_tab_offline_tabela">
                                            <input class="fkcorreiosg2-button" name="button" type="button" value="{l s="Processar esta Tabela" mod="fkcorreiosg2"}" onclick="fkcorreiosg2TabOffEspecifica('fkcorreiosg2_tab_offline_cidade_{$tabOffCidade['id']}', '{$tab_8['urlImg']}', '{$tab_8['urlFuncoes']}', '{$espCorreios['id']}')">
                                        </div>
                                        <div class="fkcorreiosg2-form">
                                            <div class="fkcorreiosg2-col-lg-90">
                                                {assign var="temp" value="fkcorreiosg2_tab_offline_cidade_`$tabOffCidade['id']`"}
                                                <textarea class="fkcorreiosg2_tab_offline_{$espCorreios['id']}" name="fkcorreiosg2_tab_offline_cidade_{$tabOffCidade['id']}" id="fkcorreiosg2_tab_offline_cidade_{$tabOffCidade['id']}">{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$tabOffCidade['tabela_cidade']}{/if}</textarea>
                                            </div>
                                        </div>
                                        <div class="fkcorreiosg2-form">
                                            <div class="fkcorreiosg2-col-lg-10 fkcorreiosg2-float-left">
                                                {assign var="temp" value="fkcorreiosg2_tab_offline_prazo_cidade_`$tabOffCidade['id']`"}
                                                <input type="text" name="fkcorreiosg2_tab_offline_prazo_cidade_{$tabOffCidade['id']}" id="fkcorreiosg2_tab_offline_prazo_cidade_{$tabOffCidade['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$tabOffCidade['prazo_entrega_cidade']}{/if}">
                                            </div>
                                            <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                                {l s="Prazo de Entrega (em dias)" mod="fkcorreiosg2"}
                                            </label>
                                        </div>

                                    </div>

                                {/if}
                            {/foreach}

                        {/if}

                        {if isset($tab_8['tabOfflineEstados'])}
                            {foreach $tab_8['tabOfflineEstados'] as $tabOffEstados}
                                {if $tabOffEstados['id_especificacao'] == $espCorreios['id']}

                                    <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-col-lg-50">

                                        <div class="fkcorreiosg2-panel-heading">
                                            {$tabOffEstados['estado']}

                                            <div class="fkcorreiosg2-float-right">
                                                {$tabOffEstados['capital']}
                                            </div>
                                        </div>

                                        <div class="fkcorreiosg2-form">
                                            <img class="fkcorreiosg2-img-tab-offline" id="fkcorreiosg2_tab_offline_img_capital_{$tabOffEstados['id']}" src="" alt="" width="32" height="32"/>
                                        </div>
                                        <div class="fkcorreiosg2-form" id="fkcorreiosg2_button_tab_offline_tabela">
                                            <input class="fkcorreiosg2-button" name="button" type="button" value="{l s="Processar esta Tabela" mod="fkcorreiosg2"}" onclick="fkcorreiosg2TabOffEspecifica('fkcorreiosg2_tab_offline_capital_{$tabOffEstados['id']}', '{$tab_8['urlImg']}', '{$tab_8['urlFuncoes']}', '{$espCorreios['id']}')">
                                        </div>
                                        <div class="fkcorreiosg2-form">
                                            <div class="fkcorreiosg2-col-lg-90">
                                                {assign var="temp" value="fkcorreiosg2_tab_offline_capital_`$tabOffEstados['id']`"}
                                                <textarea class="fkcorreiosg2_tab_offline_{$espCorreios['id']}" name="fkcorreiosg2_tab_offline_capital_{$tabOffEstados['id']}" id="fkcorreiosg2_tab_offline_capital_{$tabOffEstados['id']}">{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$tabOffEstados['tabela_capital']}{/if}</textarea>
                                            </div>
                                        </div>
                                        <div class="fkcorreiosg2-form">
                                            <div class="fkcorreiosg2-col-lg-10 fkcorreiosg2-float-left">
                                                {assign var="temp" value="fkcorreiosg2_tab_offline_prazo_capital_`$tabOffEstados['id']`"}
                                                <input type="text" name="fkcorreiosg2_tab_offline_prazo_capital_{$tabOffEstados['id']}" id="fkcorreiosg2_tab_offline_prazo_capital_{$tabOffEstados['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$tabOffEstados['prazo_entrega_capital']}{/if}">
                                            </div>
                                            <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                                {l s="Prazo de Entrega (em dias)" mod="fkcorreiosg2"}
                                            </label>
                                        </div>

                                    </div>

                                    <div class="fkcorreiosg2-panel fkcorreiosg2-sub-panel fkcorreiosg2-col-lg-50">

                                        <div class="fkcorreiosg2-panel-heading">
                                            {$tabOffEstados['estado']}

                                            <div class="fkcorreiosg2-float-right">
                                                Interior
                                            </div>
                                        </div>

                                        <div class="fkcorreiosg2-form">
                                            <img class="fkcorreiosg2-img-tab-offline" id="fkcorreiosg2_tab_offline_img_interior_{$tabOffEstados['id']}" src="" alt="" width="32" height="32"/>
                                        </div>
                                        <div class="fkcorreiosg2-form" id="fkcorreiosg2_button_tab_offline_tabela">
                                            <input class="fkcorreiosg2-button" name="button" type="button" value="{l s="Processar esta Tabela" mod="fkcorreiosg2"}" onclick="fkcorreiosg2TabOffEspecifica('fkcorreiosg2_tab_offline_interior_{$tabOffEstados['id']}', '{$tab_8['urlImg']}', '{$tab_8['urlFuncoes']}', '{$espCorreios['id']}')">
                                        </div>
                                        <div class="fkcorreiosg2-form">
                                            <div class="fkcorreiosg2-col-lg-90">
                                                {assign var="temp" value="fkcorreiosg2_tab_offline_interior_`$tabOffEstados['id']`"}
                                                <textarea class="fkcorreiosg2_tab_offline_{$espCorreios['id']}" name="fkcorreiosg2_tab_offline_interior_{$tabOffEstados['id']}" id="fkcorreiosg2_tab_offline_interior_{$tabOffEstados['id']}">{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$tabOffEstados['tabela_interior']}{/if}</textarea>
                                            </div>
                                        </div>
                                        <div class="fkcorreiosg2-form">
                                            <div class="fkcorreiosg2-col-lg-10 fkcorreiosg2-float-left">
                                                {assign var="temp" value="fkcorreiosg2_tab_offline_prazo_interior_`$tabOffEstados['id']`"}
                                                <input type="text" name="fkcorreiosg2_tab_offline_prazo_interior_{$tabOffEstados['id']}" id="fkcorreiosg2_tab_offline_prazo_interior_{$tabOffEstados['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$tabOffEstados['prazo_entrega_interior']}{/if}">
                                            </div>
                                            <label class="fkcorreiosg2-label-right fkcorreiosg2-col-lg-auto">
                                                {l s="Prazo de Entrega (em dias)" mod="fkcorreiosg2"}
                                            </label>
                                        </div>

                                    </div>

                                {/if}
                                
                            {/foreach}
                        {/if}

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
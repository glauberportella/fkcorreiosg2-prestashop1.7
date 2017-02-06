
<form id="configuration_form" class="defaultForm  form-horizontal" action="{$tab_3['formAction']}&origem=cadastroCep" method="post">

    <div class="fkcorreiosg2-panel">

        <div class="fkcorreiosg2-panel-heading">
            {l s="Cadastro de CEP" mod="fkcorreiosg2"}
        </div>

        <div class="fkcorreiosg2-panel-header">
            <button type="button" value="1" name="btnAjuda" class="fkcorreiosg2-button fkcorreiosg2-float-right" onClick="window.open('http://www.modulosfk.com.br/modulosfk/ajuda/fkcorreiosg2_v1_0_0.pdf','Janela','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=900,height=500,left=500,top=150'); return false;">
                <i class="process-icon-help"></i>
                {l s="Ajuda" mod="fkcorreiosg2"}
            </button>
        </div>

        {if isset($tab_3['cadastro_cep'])}
            <div class="fkcorreiosg2-panel">

                <div class="fkcorreiosg2-panel-heading">
                    {l s="Cadastro" mod="fkcorreiosg2"}
                </div>

                <div class="fkcorreiosg2-form">
                    <table>
                        <tr>
                            <th>{l s="Estado" mod="fkcorreiosg2"}</th>
                            <th>{l s="Intervalo de CEP dos Estados" mod="fkcorreiosg2"}</th>
                            <th>{l s="Intervalo de CEP das Capitais" mod="fkcorreiosg2"}</th>
                            <th>{l s="CEP base - Capital" mod="fkcorreiosg2"}</th>
                            <th>{l s="CEP base - Interior" mod="fkcorreiosg2"}</th>
                        </tr>

                        {foreach $tab_3['cadastro_cep'] as $reg}

                            <tr>
                                <td id="fkcorreiosg2_estado">{$reg['estado']}</td>
                                <td id="fkcorreiosg2_cep_estado">
                                    {assign var="temp" value="fkcorreiosg2_cep_estado_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_cep_estado_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['cep_estado']}{/if}">
                                </td>
                                <td id="fkcorreiosg2_cep_capital">
                                    <p>{$reg['capital']}</p>

                                    {assign var="temp" value="fkcorreiosg2_cep_capital_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_cep_capital_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['cep_capital']}{/if}">
                                </td>
                                <td id="fkcorreiosg2_cep_base_capital">
                                    {assign var="temp" value="fkcorreiosg2_cep_base_capital_`$reg['id']`"}
                                    <input class="fkcorreiosg2-mask-cep fkcorreiosg2-col-lg-50" type="text" name="fkcorreiosg2_cep_base_capital_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['cep_base_capital']}{/if}">
                                </td>
                                <td id="fkcorreiosg2_cep_base_interior">
                                    {assign var="temp" value="fkcorreiosg2_cep_base_interior_`$reg['id']`"}
                                    <input class="fkcorreiosg2-mask-cep fkcorreiosg2-col-lg-50" type="text" name="fkcorreiosg2_cep_base_interior_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['cep_base_interior']}{/if}">
                                </td>
                            </tr>

                        {/foreach}

                    </table>

                </div>

            </div>

            <div class="fkcorreiosg2-panel-footer">
                <button type="submit" value="1" name="btnSubmit" class="fkcorreiosg2-button fkcorreiosg2-float-right">
                    <i class="process-icon-save"></i>
                    {l s="Salvar" mod="fkcorreiosg2"}
                </button>
            </div>
        {/if}

    </div>

</form>
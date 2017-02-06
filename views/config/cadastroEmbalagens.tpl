
<form id="configuration_form" class="defaultForm  form-horizontal" action="{$tab_4['formAction']}&origem=cadastroEmbalagens" method="post">

    <div class="fkcorreiosg2-panel">

        <div class="fkcorreiosg2-panel-heading">
            {l s="Cadastro de Embalagens" mod="fkcorreiosg2"}
        </div>

        <div class="fkcorreiosg2-panel-header">
            <button type="submit" value="1" name="btnAdd" class="fkcorreiosg2-button fkcorreiosg2-float-left">
                <i class="process-icon-new"></i>
                {l s="Incluir Embalagem" mod="fkcorreiosg2"}
            </button>

            <button type="submit" value="1" name="btnDel" id="fkcorreiosg2_emb_button_del" class="fkcorreiosg2-button fkcorreiosg2-float-left" onclick="return fkcorreiosg2Excluir('{l s="Confirma a exclusão das embalagens?" mod="fkcorreiosg2"}');">
                <i class="process-icon-delete"></i>
                {l s="Excluir Embalagem Selecionada" mod="fkcorreiosg2"}
            </button>

            <button type="button" value="1" name="btnAjuda" class="fkcorreiosg2-button fkcorreiosg2-float-right" onClick="window.open('http://www.modulosfk.com.br/modulosfk/ajuda/fkcorreiosg2_v1_0_0.pdf','Janela','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=900,height=500,left=500,top=150'); return false;">
                <i class="process-icon-help"></i>
                {l s="Ajuda" mod="fkcorreiosg2"}
            </button>
        </div>

        <div class="fkcorreiosg2-panel">

            <div class="fkcorreiosg2-panel-heading">
                {l s="Cadastro" mod="fkcorreiosg2"}
            </div>

            {if isset($tab_4['cadastro_embalagens'])}
                <div class="fkcorreiosg2-form">
                    <table>
                        <tr>
                            <th>{l s="Descrição" mod="fkcorreiosg2"}</th>
                            <th>{l s="Largura (cm)" mod="fkcorreiosg2"}</th>
                            <th>{l s="Comprimento (cm)" mod="fkcorreiosg2"}</th>
                            <th>{l s="Altura (cm)" mod="fkcorreiosg2"}</th>
                            <th>{l s="Peso (kg)" mod="fkcorreiosg2"}</th>
                            <th>{l s="Preço de Custo" mod="fkcorreiosg2"}</th>
                            <th>{l s="Ativo" mod="fkcorreiosg2"}</th>
                            <th>{l s="Excluir" mod="fkcorreiosg2"}</th>
                        </tr>

                        {foreach $tab_4['cadastro_embalagens'] as $reg}
                            <tr>
                                <td class="fkcorreiosg2-col-lg-25">
                                    {assign var="temp" value="fkcorreiosg2_emb_descricao_`$reg['id']`"}
                                    <input type="text" name="fkcorreiosg2_emb_descricao_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['descricao']}{/if}">
                                </td>
                                <td>
                                    {assign var="temp" value="fkcorreiosg2_emb_largura_`$reg['id']`"}
                                    <input class="fkcorreiosg2-col-lg-60" type="text" name="fkcorreiosg2_emb_largura_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['largura']}{/if}">
                                </td>
                                <td>
                                    {assign var="temp" value="fkcorreiosg2_emb_comprimento_`$reg['id']`"}
                                    <input class="fkcorreiosg2-col-lg-60" type="text" name="fkcorreiosg2_emb_comprimento_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['comprimento']}{/if}">
                                </td>
                                <td>
                                    {assign var="temp" value="fkcorreiosg2_emb_altura_`$reg['id']`"}
                                    <input class="fkcorreiosg2-col-lg-60" type="text" name="fkcorreiosg2_emb_altura_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['altura']}{/if}">
                                </td>
                                <td>
                                    {assign var="temp" value="fkcorreiosg2_emb_peso_`$reg['id']`"}
                                    <input class="fkcorreiosg2-col-lg-60" type="text" name="fkcorreiosg2_emb_peso_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['peso']}{/if}">
                                </td>
                                <td>
                                    {assign var="temp" value="fkcorreiosg2_emb_custo_`$reg['id']`"}
                                    <input class="fkcorreiosg2-col-lg-60" type="text" name="fkcorreiosg2_emb_custo_{$reg['id']}" value="{if isset($smarty.post.$temp)}{$smarty.post.$temp}{else}{$reg['custo']}{/if}">
                                </td>
                                <td>
                                    {assign var="temp" value="fkcorreiosg2_emb_ativo_`$reg['id']`"}
                                    <input type="checkbox" name="fkcorreiosg2_emb_ativo[]" value="{$reg['id']}" {if isset($smarty.post.$temp) and $smarty.post.$temp == 1}checked="checked"{else}{if $reg['ativo'] == 1}checked="checked"{/if}{/if}>
                                </td>
                                <td>
                                    <input type="checkbox" name="fkcorreiosg2_emb_excluir[]" value="{$reg['id']}">
                                </td>
                            </tr>

                        {/foreach}

                    </table>
                </div>

            {/if}


        </div>

        <div class="fkcorreiosg2-panel-footer">
            <button type="submit" value="1" name="btnSubmit" class="fkcorreiosg2-button fkcorreiosg2-float-right">
                <i class="process-icon-save"></i>
                {l s="Salvar" mod="fkcorreiosg2"}
            </button>
        </div>

    </div>

</form>
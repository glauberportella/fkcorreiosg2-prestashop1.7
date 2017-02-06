
<div class="fkcorreiosg2-panel">

    <div class="fkcorreiosg2-panel-heading">
        {l s="Complementos" mod="fkcorreiosg2"}
    </div>

    <div class="fkcorreiosg2-panel-header">
        <button type="button" value="1" name="btnAjuda" class="fkcorreiosg2-button fkcorreiosg2-float-right" onClick="window.open('http://www.modulosfk.com.br/modulosfk/ajuda/fkcorreiosg2_v1_0_0.pdf','Janela','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=900,height=500,left=500,top=150'); return false;">
            <i class="process-icon-help"></i>
            {l s="Ajuda" mod="fkcorreiosg2"}
        </button>
    </div>

    <div class="fkcorreiosg2-panel fkcorreiosg2-col-lg-40 fkcorreiosg2-sub-panel" id="fkcorreiosg2_complementos">

        <div class="fkcorreiosg2-panel-heading">
            {l s="Complementos Instalados" mod="fkcorreiosg2"}
        </div>

        {if isset($tab_9['complementos'])}

            <div class="fkcorreiosg2-form">
                <table>
                    <tr>
                        <th>{l s="Módulo" mod="fkcorreiosg2"}</th>
                        <th>{l s="Descrição" mod="fkcorreiosg2"}</th>
                    </tr>

                    {foreach $tab_9['complementos'] as $reg}
                        <tr>
                            <td>
                                {$reg['modulo']}
                            </td>
                            <td>
                                {$reg['descricao']}
                            </td>
                        </tr>
                    {/foreach}
                </table>
            </div>

        {/if}

    </div>

</div>
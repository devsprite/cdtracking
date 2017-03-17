<div class="row">
    <div id="chartProspectsByEmployees" class="panel">
        <div class="panel-heading">
            <h4><i class="icon-chevron-right" title="chartProspectsByEmployees"></i> Prospects par employés et tracers</h4>
        </div>
        <div class="panel-body">
            <div>
                <a class="btn btn-primary" href="{$linkFile}&export_csv=2">CSV</a>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th></th>
                    {foreach item=item from=$employees}
                    <th class="text-center" colspan="4">{$item['lastname']}</th>
                    {/foreach}
                </tr>
                <tr>
                    <th>Tracer</th>
                    {foreach item=item from=$employees}
                    <th class="text-center adapteWidth">Nbr Pros.</th>
                    <th class="text-right adapteWidth">      Rép.</th>
                    <th class="text-center adapteWidth">Nbr. Vent.</th>
                    <th class="text-right adapteWidth">      Taux</th>
                    {/foreach}
                </tr>
                </thead>
                <tbody>
                    {foreach item=item key=key from=$results}
                    <tr>
                        <td>{$key}</td>
                        {foreach item=value from=$item}
                        <td class="text-center">{if $value['nbrProspects'] != 0}{$value['nbrProspects']}{/if}</td>
                        <td class="text-right">{if $value['nbrProspects'] != 0}{$value['repartition']} %{/if}</td>
                        <td class="text-center">{if $value['nbrVentes'] != 0}{$value['nbrVentes']}{/if}</td>
                        <td class="text-right">{if $value['nbrVentes'] != 0}{$value['tauxTransfo']} %{/if}</td>
                        {/foreach}
                    </tr>
                    {/foreach}
                </tbody>
                <tfoot>
                <tr>
                    <th>Total</th>
                    {foreach item=item from=$employees}
                    <td class="text-center">{$item['totalProspects']}</td>
                    <td colspan="3"></td>
                    {/foreach}
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
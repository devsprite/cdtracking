<div class="row">
    <div id="ChartCustomersByAge" class="panel panel-primary col-xs-12">
        <div class="panel-heading">
            <h4><i class="icon-chevron-down" data-canvas="chartAgeCustomers" title="ChartCustomersByAge"></i> Clients par age du {$dateBetween['debut']|date_format:'%d/%m/%Y'} au
                {$dateBetween['fin']|date_format:'%d/%m/%Y'}<a class="btn btn-primary pull-right" href="{$LinkFile}&export_csv=6"><i class="icon-file-text"></i></a></h4>
        </div>
        <div class="panel-body">
            <table class="table table-striped tableChart">
                <thead class="thead">
                <tr>
                    <th class="text-center">Tracer</th>
                    <th class="text-center" colspan="2">< 18</th>
                    <th class="text-center" colspan="2">18-24</th>
                    <th class="text-center" colspan="2">25-34</th>
                    <th class="text-center" colspan="2">35-44</th>
                    <th class="text-center" colspan="2">45-54</th>
                    <th class="text-center" colspan="2">55-64</th>
                    <th class="text-center" colspan="2">> 64</th>
                    <th class="text-center" colspan="2">Inconnu</th>
                    <th class="text-center" colspan="2">Total</th>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center">Nbr</td>
                    <td class="text-center">%</td>
                    <td class="text-center">Nbr</td>
                    <td class="text-center">%</td>
                    <td class="text-center">Nbr</td>
                    <td class="text-center">%</td>
                    <td class="text-center">Nbr</td>
                    <td class="text-center">%</td>
                    <td class="text-center">Nbr</td>
                    <td class="text-center">%</td>
                    <td class="text-center">Nbr</td>
                    <td class="text-center">%</td>
                    <td class="text-center">Nbr</td>
                    <td class="text-center">%</td>
                    <td class="text-center">Nbr</td>
                    <td class="text-center">%</td>
                    <td class="text-center">Nbr</td>
                    <td class="text-center">%</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                {foreach item=item key=key from=$tableAgeCustomersValues}
                <tr>
                    <td>{$key}</td>
                    {foreach item=value from=$item}
                    <td class="text-center">{if $value['orders'] != 0}{$value['orders']}{/if}</td>
                    <td class="text-center">{if $value['orders'] !=0}{(($value['orders']/$value['prospects'])*100)|string_format:"%.1f"} %{/if}</td>
                    {/foreach}
                </tr>
                {/foreach}
                <tr class="text-left"><td colspan="4">Total : <strong>{$tableAgeCustomersValuesTotal}</strong></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
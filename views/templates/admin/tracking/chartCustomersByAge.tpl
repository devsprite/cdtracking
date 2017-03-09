<script>
    var chartAgeCustomersHeaders = {$chartAgeCustomersHeaders};
    var chartAgeCustomersValues = {$chartAgeCustomersValues};
</script>
    <div id="ChartCustomersByAge" class="panel panel-primary col-xs-6">
        <div class="panel-heading">
            <h4><i class="icon-chevron-down toggleChart" data-canvas="chartAgeCustomers" title="ChartCustomersByAge"></i> Clients par age du {$dateBetween['debut']|date_format:'%d/%m/%Y'} au
                {$dateBetween['fin']|date_format:'%d/%m/%Y'}</h4>
        </div>
        <div class="panel-body">
            <table class="table table-striped tableChart">
                <thead class="thead">
                <tr>
                    {foreach item=item from=$tableAgeCustomersHeaders}
                    <th class="text-center">{$item}</th>
                    {/foreach}
                </tr>

                </thead>
                <tbody>
                <tr>
                    {foreach item=item from=$tableAgeCustomersValues}
                    <td class="text-center">{((100 / $tableAgeCustomersValuesTotal) * $item)|string_format:"%.2f"} %</td>
                    {/foreach}
                </tr>
                <tr class="text-left"><td colspan="4">Total : <strong>{$tableAgeCustomersValuesTotal}</strong></td></tr>
                </tbody>
            </table>
            <div class="canvasChart">
                <canvas id="chartAgeCustomers" height="250"></canvas>
            </div>
        </div>
    </div>
</div>
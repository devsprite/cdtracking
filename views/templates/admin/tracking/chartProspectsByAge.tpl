<script>
    var chartAgeProspectsHeaders = {$chartAgeProspectsHeaders};
    var chartAgeProspectsValues = {$chartAgeProspectsValues};
</script>
<div class="row">
    <div id="ChartProspectsByAge" class="panel panel-primary col-xs-5 chart">
        <div class="panel-heading">
            <h4>Prospects par age du {$dateBetween['debut']|date_format:'%d/%m/%Y'} au
                {$dateBetween['fin']|date_format:'%d/%m/%Y'}</h4>
        </div>
        <div class="panel-body">
            <table class="table table-striped tableChart">
                <thead class="thead">
                <tr>
                    {foreach item=item from=$tableAgeProspectsHeaders}
                    <th class="text-center">{$item}</th>
                    {/foreach}
                </tr>

                </thead>
                <tbody>
                <tr>
                    {foreach item=item from=$tableAgeProspectsValues}
                    <td class="text-center">{((100 / $tableAgeProspectsValuesTotal) * $item)|string_format:"%.2f"} %</td>
                    {/foreach}
                </tr>
                <tr class="text-left"><td colspan="4"><strong>Total : {$tableAgeProspectsValuesTotal}</strong></td></tr>
                </tbody>
            </table>
            <div class="">
                <canvas id="chartAgeProspects" height="250"></canvas>
            </div>
        </div>
    </div>
</div>
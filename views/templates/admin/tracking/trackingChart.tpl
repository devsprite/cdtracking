<script>
    var countTrackingBetweenDateJsonHeader = {$countTrackingBetweenDateJsonHeader};
    var countTrackingBetweenDateJsonValue = {$countTrackingBetweenDateJsonValue};
</script>
<div class="row">
    <div class="panel panel-primary col-xs-4 chart">
        <div class="panel-heading">
            <h4>Nombre de clients par numéro de tracking du {$dateBetween['debut']|date_format:'%d/%m/%Y'} au {$dateBetween['fin']|date_format:'%d/%m/%Y'}</h4>
        </div>
        <div class="panel-body">
            <table class="table table-striped tableChart">
                <thead class="thead">
                <tr>
                    {foreach item=item from=$countTrackingBetweenDate}
                    <th class="text-center">Tracer n° {$item['tracer']}</th>
                    {/foreach}
                </tr>
                </thead>
                <tbody>
                <tr>
                    {foreach item=item from=$countTrackingBetweenDate}
                    <td class="text-center">{$item['total']}</td>
                    {/foreach}
                </tr>
                <tr>
                    <td colspan="3">Total Clients : {$countTrackingNbrProspects}</td>
                </tr>
                </tbody>
            </table>
            <div class="">
                <canvas id="countTrackingBetweenDate" height="250"></canvas>
            </div>
        </div>
    </div>
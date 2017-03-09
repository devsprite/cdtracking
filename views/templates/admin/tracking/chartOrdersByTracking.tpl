<script>
    var trackingProspectsHeader = {$trackingProspectsHeader};
    var trackingProspectsValues = {$trackingProspectsValues};
</script>

<div  id="chartOrdersByTracking" class="panel panel-primary col-xs-6 chart">
    <div class="panel-heading">
        <h4><i class="icon-chevron-right" title="chartOrdersByTracking"></i> Nbr de Commandes par numéro de tracking {$dateBetween['debut']|date_format:'%d/%m/%Y'} au {$dateBetween['fin']|date_format:'%d/%m/%Y'}</h4>
    </div>
    <div class="panel-body">
        <table class="table table-striped tableChart">
            <thead>
            <tr>
                {foreach item=item key=key from=$trackingProspects}
                <th class="text-center">{$key}</th>
                {/foreach}
            </tr>
            </thead>
            <tbody>
            <tr>
                {foreach item=item key=key from=$trackingProspects}
                <td class="text-center">{((100/$totalTrackingProspects) * $item)|string_format:"%.2f"} %</td>
                {/foreach}
            </tr>
            <tr>
                <td colspan="3">Total : <strong>{$totalTrackingProspects}</strong></td>
            </tr>
            </tbody>
        </table>
        <div class="">
            <canvas id="trackingProspects" height="250"></canvas>
        </div>
    </div>
</div>

</div> <!-- EndRow -->

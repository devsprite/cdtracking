<?php

class ChartTrackingProspects extends AdminController
{
    protected $smarty;
    protected $path_tpl;

    public function __construct()
    {
        parent::__construct();
        $this->smarty = $this->context->smarty;
        $this->path_tpl = _PS_MODULE_DIR_ . 'cdtracking/views/templates/admin/tracking/';
    }

    public function displayChartCountTrackingBetweenDate(CustomerTrackingClass $prospects, $dateRange)
    {
        $countTrackingBetweenDate = $prospects->countTrackingBetweenDate($dateRange);
        $nbrProspects = $prospects->readNbrTotalProspects($dateRange);
        $trackingJsonValues = array();
        $trackingJsonHeader = array();
        foreach ($countTrackingBetweenDate as $key => $value) {
            $repartition = round(100 / (int)$nbrProspects * (int)$value['total'], 2);
            $trackingJsonValues[] = $repartition;
            $trackingJsonHeader[] = "% " . (int)$value['tracer'];
            $countTrackingBetweenDate[$key]['repartition'] = $repartition;
        }

        $this->smarty->assign(array(
            "countTrackingBetweenDate" => $countTrackingBetweenDate,
            "countTrackingNbrProspects" => $nbrProspects,
            "countTrackingBetweenDateJsonValue" => Tools::jsonEncode($trackingJsonValues),
            "countTrackingBetweenDateJsonHeader" => Tools::jsonEncode($trackingJsonHeader),
            'LinkFile' => Tools::safeOutput($_SERVER['REQUEST_URI'])
        ));
        return $this->smarty->fetch($this->path_tpl . "chartTrackingProspects.tpl");
    }

    public function exportCsv(CustomerTrackingClass $prospects, $dateEmployee)
    {
        $countTracking = $prospects->countTrackingBetweenDate($dateEmployee);
        $nbrProspects = $prospects->readNbrTotalProspects($dateEmployee);

        foreach ($countTracking as $key => $value) {
            $countTracking[$key]['repartition'] = round(100 / (int)$nbrProspects * (int)$value['total'], 2);
        }

        $titles = array(
            "Tracer", "Nombre", "Répartition"
        );

        $csv = new ExportCsvClass();
        $csv->exportCsv($titles, $countTracking, 'Nombre de prospects');
        var_dump($countTracking);
        die();
    }
}
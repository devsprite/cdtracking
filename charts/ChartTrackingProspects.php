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

    public function displayChartCountTrackingBetweenDate($countTrackingBetweenDate, CustomerTrackingClass $prospects, $dateRange)
    {
        $nbrProspects = $prospects->readNbrTotalProspects($dateRange);
        $trackingJsonValues = array();
        $trackingJsonHeader = array();
        foreach ($countTrackingBetweenDate as $key => $value) {
            $trackingJsonValues[] = round(100 / (int)$nbrProspects * (int)$value['total'], 2);
            $trackingJsonHeader[] = "% " . (int)$value['tracer'];
        }

        $this->smarty->assign(array(
            "countTrackingBetweenDate" => $countTrackingBetweenDate,
            "countTrackingNbrProspects" => $nbrProspects,
            "countTrackingBetweenDateJsonValue" => Tools::jsonEncode($trackingJsonValues),
            "countTrackingBetweenDateJsonHeader" => Tools::jsonEncode($trackingJsonHeader),
        ));
        return $this->smarty->fetch($this->path_tpl . "chartTrackingProspects.tpl");
    }
}
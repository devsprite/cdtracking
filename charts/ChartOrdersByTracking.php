<?php

class ChartOrdersByTracking extends AdminController
{
    protected $smarty;
    protected $path_tpl;

    public function __construct()
    {
        parent::__construct();
        $this->smarty = $this->context->smarty;
        $this->path_tpl = _PS_MODULE_DIR_ . 'cdtracking/views/templates/admin/tracking/';
    }

    public function displayChartOrdersByTracking($countTrackingBetweenDate, CustomerTrackingClass $prospect)
    {
        $prospects = $prospect->getProspectsByDate($countTrackingBetweenDate);
        $arrayNumberTracking = $this->readNumberTracking($prospects);
        $arrayTrackingProspects = $this->trackingProspects($prospects, $arrayNumberTracking);

        $arrayTrackingProspectsValues = array();
        $arrayTrackingProspectsHeader = array();
        foreach ($arrayTrackingProspects as $key => $value) {
            $arrayTrackingProspectsHeader[] = $key;
            $arrayTrackingProspectsValues[] = $value;
        }

        $this->smarty->assign(array(
            "trackingProspects" => $arrayTrackingProspects,
            "totalTrackingProspects" => array_sum($arrayTrackingProspects),
            "trackingProspectsHeader" => Tools::jsonEncode($arrayTrackingProspectsHeader),
            "trackingProspectsValues" => Tools::jsonEncode($arrayTrackingProspectsValues),
        ));

        return $this->smarty->fetch($this->path_tpl . "chartOrdersByTracking.tpl");
    }

    private function readNumberTracking($prospects)
    {
        $arrayTracking = array();
        foreach ($prospects as $prospect => $value) {
            if ($value['tracer'] != 0 || !empty($value['tracer']))
                $arrayTracking[(int)$value['tracer']] = (int)$value['tracer'];
        }

        return $arrayTracking;
    }

    private function trackingProspects($prospects, $arrayNumberTracking)
    {
        $arrayTracking = array();
        foreach ($arrayNumberTracking as $numberTracking) {
            foreach ($prospects as $prospect) {
                if ($numberTracking == (int)$prospect['tracer']) {
                    $arrayTracking[$numberTracking] += 1;
                }
            }
        }

        return $arrayTracking;
    }
}
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

    public function displayChartOrdersByTracking($dateRange, CustomerTrackingClass $prospect)
    {
        $prospects = $prospect->getCustomersByDate($dateRange);
        $arrayNumberTracking = $this->readNumberTracking($prospects);
        $arrayTrackingProspects = $this->trackingProspects($prospects, $arrayNumberTracking);
        $totalTrackingProspects = array_sum($arrayTrackingProspects);
        $arrayTrackingProspectsValues = array();
        $arrayTrackingProspectsHeader = array();
        $trackingProspects = array();
        $totalByTracer = $this->totalByTracer($prospect, $dateRange);

        foreach ($arrayTrackingProspects as $key => $value) {
            $p['tracer'] = $key;
            $p['nombre'] = $value;
            $p['taux'] = round((($value/$totalByTracer[$key])*100),2);
            $trackingProspects[] = $p;

            $arrayTrackingProspectsHeader[] = $key;
            $arrayTrackingProspectsValues[] = $value;
        }

        $this->smarty->assign(array(
            "trackingProspects" => $trackingProspects,
            "totalTrackingProspects" => $totalTrackingProspects,
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

    public function exportCsv(CustomerTrackingClass $prospect, $countTrackingBetweenDate)
    {
        $prospects = $prospect->getCustomersByDate($countTrackingBetweenDate);
        $arrayNumberTracking = $this->readNumberTracking($prospects);
        $arrayTrackingProspects = $this->trackingProspects($prospects, $arrayNumberTracking);
        $totalTrackingProspects = array_sum($arrayTrackingProspects);

        $trackingProspects = array();
        foreach ($arrayTrackingProspects as $key => $value) {
            $p['tracer'] = $key;
            $p['nombre'] = $value;
//            $p['taux'] = round((($value*100)/$totalTrackingProspects),2);
            $p['taux'] = round((($value/$totalTrackingProspects)*100),2);
            $trackingProspects[] = $p;
        }
        $titles = array(
            'Tracer', 'Nombre de vente', 'Taux de transformation'
        );
        $csv = new ExportCsvClass();
        $csv->exportCsv($titles, $trackingProspects, 'Nombre de vente');
    }

    private function totalByTracer(CustomerTrackingClass $prospect, $dateRange)
    {
        $req = $countTrackingBetweenDate = $prospect->countTrackingBetweenDate($dateRange);
        $results = array();
        foreach ($req as $key => $value) {
            $results[$value['tracer']] = $value['total'];
        }

        return $results;
    }
}
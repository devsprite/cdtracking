<?php

class ChartProspectsByAge extends AdminController
{
    protected $smarty;
    protected $path_tpl;

    public function __construct()
    {
        parent::__construct();
        $this->smarty = $this->context->smarty;
        $this->path_tpl = _PS_MODULE_DIR_ . 'cdtracking/views/templates/admin/tracking/';
    }


    public function displayChartAgeProspects($getDateBetweenFromEmployee, CustomerTrackingClass $p)
    {
        $tracers = TracerClass::getAllTracer();
        $results = array();
        foreach ($tracers as $tracer) {
            $prospects = $p->getProspectsByDate($getDateBetweenFromEmployee, $tracer);
            $results[$tracer] = $this->getRepartitionProspects($prospects);
        }

        $tableAgeProspectsValuesTotal = array_reduce($results, function($a, $b) {
            $a += isset($b['total']) ? $b['total'] : 0;
            return $a;
        });

        $this->smarty->assign(array(
            'tableAgeProspectsValues' => $results,
            'tableAgeProspectsValuesTotal' => $tableAgeProspectsValuesTotal,
        ));

        return $this->smarty->fetch($this->path_tpl . "chartProspectsByAge.tpl");
    }

    /**
     * Calcule la répartition des prospects par age
     * @param $prospects
     * @return array
     */
    public function getRepartitionProspects($prospects)
    {
        $repartitionProspectsHeaders = array(
            '< 18' => 0,
            '18-24' => 0,
            '25-34' => 0,
            '35-44' => 0,
            '45-54' => 0,
            '55-65' => 0,
            '> 65' => 0,
            'Inconnu' => 0,
            'total' => 0
        );

        foreach ($prospects as $prospect) {
            switch ($prospect['age']) {
                case ($prospect['age'] < 18):
                    $repartitionProspectsHeaders['< 18']++;
                    break;
                case ($prospect['age'] >= 18 && $prospect['age'] < 25):
                    $repartitionProspectsHeaders['18-24']++;
                    break;
                case ($prospect['age'] >= 25 && $prospect['age'] < 34):
                    $repartitionProspectsHeaders['25-34']++;
                    break;
                case ($prospect['age'] >= 35 && $prospect['age'] < 44):
                    $repartitionProspectsHeaders['35-44']++;
                    break;
                case ($prospect['age'] >= 45 && $prospect['age'] < 54):
                    $repartitionProspectsHeaders['45-54']++;
                    break;
                case ($prospect['age'] >= 55 && $prospect['age'] < 64):
                    $repartitionProspectsHeaders['55-65']++;
                    break;
                case ($prospect['age'] > 65):
                    $repartitionProspectsHeaders['> 65']++;
                    break;
                default:
                    $repartitionProspectsHeaders['Inconnu']++;
            }
        }

        $sum = 0;
        foreach ($repartitionProspectsHeaders as $key => $value) {
            $sum += (int)$value;
        }
        $repartitionProspectsHeaders['total'] = $sum;

        return $repartitionProspectsHeaders;
    }

    public function exportCsv(CustomerTrackingClass $p, $dateEmployee)
    {
        $tracers = TracerClass::getAllTracer();
        $results = array();
        foreach ($tracers as $tracer) {
            $prospects = $p->getProspectsByDate($dateEmployee, $tracer);
            $results[$tracer] = $this->getRepartitionProspects($prospects);
        }

        $titles = array(
            '< 18',
            '18-24',
            '25-34',
            '35-44',
            '45-54',
            '55-65',
            '> 65',
            'Inconnu',
            'total'
        );

        $csv = new ExportCsvClass();

        $csv->exportCsv($titles, $results, "prospectsParAge", "Tracer");
    }
}
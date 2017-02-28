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


    public function displayChartAgeProspects($getDateBetweenFromEmployee, CustomerTrackingClass $prospects)
    {
        $prospects = $prospects->getProspectsByDate($getDateBetweenFromEmployee);
        $repartitionProspectsHeaders = array(
            '< 18',
            '18-24',
            '25-34',
            '35-44',
            '45-54',
            '55-65',
            '> 65',
            'Inconnu'
        );

        $repartitionProspectsValues = array(
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0
        );

        foreach ($prospects as $prospect) {
            switch ($prospect['age']) {
                case ($prospect['age'] < 18):
                    $repartitionProspectsValues[0]++;
                    break;
                case ($prospect['age'] >= 18 && $prospect['age'] < 25):
                    $repartitionProspectsValues[1]++;
                    break;
                case ($prospect['age'] >= 25 && $prospect['age'] < 34):
                    $repartitionProspectsValues[2]++;
                    break;
                case ($prospect['age'] >= 35 && $prospect['age'] < 44):
                    $repartitionProspectsValues[3]++;
                    break;
                case ($prospect['age'] >= 45 && $prospect['age'] < 54):
                    $repartitionProspectsValues[4]++;
                    break;
                case ($prospect['age'] >= 55 && $prospect['age'] < 64):
                    $repartitionProspectsValues[5]++;
                    break;
                case ($prospect['age'] > 65):
                    $repartitionProspectsValues[6]++;
                    break;
                default:
                    $repartitionProspectsValues[7]++;
            }
        }

        $tableAgeProspectsValuesTotal = array_sum($repartitionProspectsValues);

        $this->smarty->assign(array(
            'tableAgeProspectsHeaders' => $repartitionProspectsHeaders,
            'tableAgeProspectsValues' => $repartitionProspectsValues,
            'tableAgeProspectsValuesTotal' => $tableAgeProspectsValuesTotal,
            'chartAgeProspectsHeaders' => json_encode($repartitionProspectsHeaders),
            'chartAgeProspectsValues' => json_encode($repartitionProspectsValues),
        ));

        return $this->smarty->fetch($this->path_tpl . "ChartProspectsByAge.tpl");
    }
}
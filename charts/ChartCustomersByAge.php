<?php

class ChartCustomersByAge extends AdminController
{
    protected $smarty;
    protected $path_tpl;

    public function __construct()
    {
        parent::__construct();
        $this->smarty = $this->context->smarty;
        $this->path_tpl = _PS_MODULE_DIR_ . 'cdtracking/views/templates/admin/tracking/';
    }


    public function displayChartAgeCustomers($getDateBetweenFromEmployee, CustomerTrackingClass $customers)
    {
        $customers = $customers->getCustomersByDate($getDateBetweenFromEmployee);
        $repartitionCustomersHeaders = array(
            '< 18',
            '18-24',
            '25-34',
            '35-44',
            '45-54',
            '55-65',
            '> 65',
            'Inconnu'
        );

        $repartitionCustomersValues = array(
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0
        );

        foreach ($customers as $customer) {
            switch ($customer['age']) {
                case ($customer['age'] < 18):
                    $repartitionCustomersValues[0]++;
                    break;
                case ($customer['age'] >= 18 && $customer['age'] < 25):
                    $repartitionCustomersValues[1]++;
                    break;
                case ($customer['age'] >= 25 && $customer['age'] < 34):
                    $repartitionCustomersValues[2]++;
                    break;
                case ($customer['age'] >= 35 && $customer['age'] < 44):
                    $repartitionCustomersValues[3]++;
                    break;
                case ($customer['age'] >= 45 && $customer['age'] < 54):
                    $repartitionCustomersValues[4]++;
                    break;
                case ($customer['age'] >= 55 && $customer['age'] < 64):
                    $repartitionCustomersValues[5]++;
                    break;
                case ($customer['age'] > 65):
                    $repartitionCustomersValues[6]++;
                    break;
                default:
                    $repartitionCustomersValues[7]++;
            }
        }

        $tableAgeCustomersValuesTotal = array_sum($repartitionCustomersValues);

        $this->smarty->assign(array(
            'tableAgeCustomersHeaders' => $repartitionCustomersHeaders,
            'tableAgeCustomersValues' => $repartitionCustomersValues,
            'tableAgeCustomersValuesTotal' => $tableAgeCustomersValuesTotal,
            'chartAgeCustomersHeaders' => json_encode($repartitionCustomersHeaders),
            'chartAgeCustomersValues' => json_encode($repartitionCustomersValues),
        ));

        return $this->smarty->fetch($this->path_tpl . "ChartCustomersByAge.tpl");
    }
}
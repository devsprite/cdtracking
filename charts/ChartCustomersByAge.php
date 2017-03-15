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


    public function displayChartAgeCustomers($getDateBetweenFromEmployee, CustomerTrackingClass $c)
    {
        $tracers = TracerClass::getAllTracer();
        $results = array();
        foreach ($tracers as $tracer) {
            $customers = $c->getCustomersByTracer($getDateBetweenFromEmployee, $tracer);
            $prospects = $c->getProspectsByTracer($getDateBetweenFromEmployee, $tracer);

            $totalProspects = $this->getRepartitionCustomers($prospects, $tracer);
            $totalCustomers = $this->getRepartitionCustomers($customers, $tracer);
            foreach ($totalCustomers as $key => $value) {
                $results[$tracer][$key] = array(
                    'orders' => $value,
                    'prospects' => $totalProspects[$key]
                );
            }

        }

        $tableAgeCustomersValuesTotal = array_reduce($results, function ($a, $b) {
            $a += array_reduce($b, function($c, $d) {
               $c += isset($d['orders']) ?  $d['orders'] : 0 ;
               return $c;
           });
            return $a;
        });

        $this->smarty->assign(array(
            'tableAgeCustomersValues' => $results,
            'tableAgeCustomersValuesTotal' => $tableAgeCustomersValuesTotal / 2,
        ));

        return $this->smarty->fetch($this->path_tpl . "chartCustomersByAge.tpl");
    }

    /**
     * @param CustomerTrackingClass $customers
     * @param $tracer
     * @return array
     */
    public function getRepartitionCustomers($customers, $tracer)
    {
        $repartitionCustomersHeaders = array(
            '< 18' => 0,
            '18-24' => 0,
            '25-34' => 0,
            '35-44' => 0,
            '45-54' => 0,
            '55-64' => 0,
            '> 64' => 0,
            'Inconnu' => 0,
            'total' => 0
        );

        foreach ($customers as $customer) {
            switch ($customer['age']) {
                case ($customer['age'] < 18):
                    $repartitionCustomersHeaders['< 18']++;
                    break;
                case ($customer['age'] >= 18 && $customer['age'] <= 24):
                    $repartitionCustomersHeaders['18-24']++;
                    break;
                case ($customer['age'] >= 25 && $customer['age'] <= 34):
                    $repartitionCustomersHeaders['25-34']++;
                    break;
                case ($customer['age'] >= 35 && $customer['age'] <= 44):
                    $repartitionCustomersHeaders['35-44']++;
                    break;
                case ($customer['age'] >= 45 && $customer['age'] <= 54):
                    $repartitionCustomersHeaders['45-54']++;
                    break;
                case ($customer['age'] >= 55 && $customer['age'] <= 64):
                    $repartitionCustomersHeaders['55-64']++;
                    break;
                case ($customer['age'] >= 65):
                    $repartitionCustomersHeaders['> 64']++;
                    break;
                default:
                    $repartitionCustomersHeaders['Inconnu']++;
            }
        }

        $sum = 0;
        foreach ($repartitionCustomersHeaders as $key => $value) {
            $sum += $value;
        }
        $repartitionCustomersHeaders['total'] = $sum;

        return $repartitionCustomersHeaders;
    }

    public function exportCsv(CustomerTrackingClass $c, $dateEmployee)
    {
        $tracers = TracerClass::getAllTracer();
        $results = array();
        foreach ($tracers as $tracer) {
            $customers = $c->getCustomersByTracer($dateEmployee, $tracer);
            $results[$tracer] = $this->getRepartitionCustomers($customers, $tracer);
        }

        $titles = array(
            '< 18',
            '18-24',
            '25-34',
            '35-44',
            '45-54',
            '55-64',
            '> 64',
            'Inconnu',
            'total'
        );

        $csv = new ExportCsvClass();
        $csv->exportCsv($titles, $results, "clientsParAge", "Tracer");
    }
}
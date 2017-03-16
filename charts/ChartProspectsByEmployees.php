<?php

require_once(dirname(__FILE__) . "/../classes/EmployeesClass.php");
require_once(dirname(__FILE__) . "/../classes/TracerClass.php");
require_once(dirname(__FILE__) . "/../classes/ExportCsvClass.php");

class ChartProspectsByEmployees extends AdminController
{
    private $smarty;
    private $path_tpl;

    public function __construct()
    {
        parent::__construct();
        $this->smarty = $this->context->smarty;
        $this->path_tpl = _PS_MODULE_DIR_ . 'cdtracking/views/templates/admin/tracking/';
    }

    public function displayChartProspectsByEmployees($dateRange)
    {
        $employees = array();
        $employeesTotal = EmployeesClass::getEmployees();
        $customerTracking = new CustomerTrackingClass();
        foreach ($employeesTotal as $employee) {
            $count = $customerTracking->getCustomersByEmployee($employee['id_employee'], $dateRange);
            if ($count > 0) {
                $employees[] = $employee;
            }
        }

        $tracers = TracerClass::getAllTracer();
        $results = array();
        foreach ($tracers as $tracer) {
            foreach ($employees as $key => $employe) {
                $employees[$key]['totalProspects'] =
                    $this->getTotalProspectsByTracerAndEmployee($employe['id_employee'], $dateRange);
                $results[$tracer][$employe['id_employee']] = $employe;
                $results[$tracer][$employe['id_employee']]['nbrProspects'] =
                    TracerClass::getProspectsByEmployeeAndTracer($employe['id_employee'], $tracer, $dateRange);
                $results[$tracer][$employe['id_employee']]['repartition'] =
                    round((($results[$tracer][$employe['id_employee']]['nbrProspects'] / $employees[$key]['totalProspects'])*100),2);
                $results[$tracer][$employe['id_employee']]['nbrVentes'] = $this->getNbrVentes($employe['id_employee'], $tracer, $dateRange);
                $results[$tracer][$employe['id_employee']]['tauxTransfo'] = round(
                    (($results[$tracer][$employe['id_employee']]['nbrVentes']/$results[$tracer][$employe['id_employee']]['nbrProspects'])*100)
                ,2);
            }
        }

        $this->smarty->assign(array(
            'tracers' => $tracers,
            'employees' => $employees,
            'results' => $results,
            'linkFile' => Tools::safeOutput($_SERVER['REQUEST_URI'])
        ));

        return $this->smarty->fetch($this->path_tpl . "chartProspectsByEmployees.tpl");
    }

    public function exportCsv($dateRange)
    {
        $employees = array();
        $employeesTotal = EmployeesClass::getEmployees();
        $customerTracking = new CustomerTrackingClass();
        foreach ($employeesTotal as $employee) {
            $count = $customerTracking->getCustomersByEmployee($employee['id_employee'], $dateRange);
            if ($count > 0) {
                $employees[] = $employee;
            }
        }

        $tracers = TracerClass::getAllTracer();
        $results = array();
        foreach ($tracers as $tracer) {
            foreach ($employees as $key => $employe) {
                $employees[$key]['totalProspects'] =
                    $this->getTotalProspectsByTracerAndEmployee($employe['id_employee'], $dateRange);
                $results[$tracer][$employe['id_employee']] = $employe;
                $results[$tracer][$employe['id_employee']]['nbrProspects'] =
                    TracerClass::getProspectsByEmployeeAndTracer($employe['id_employee'], $tracer, $dateRange);
                $results[$tracer][$employe['id_employee']]['repartition'] =
                    round((($results[$tracer][$employe['id_employee']]['nbrProspects'] / $employees[$key]['totalProspects'])*100),2);
                $results[$tracer][$employe['id_employee']]['nbrVentes'] = $this->getNbrVentes($employe['id_employee'], $tracer, $dateRange);
                $results[$tracer][$employe['id_employee']]['tauxTransfo'] = round(
                    (($results[$tracer][$employe['id_employee']]['nbrVentes']/$results[$tracer][$employe['id_employee']]['nbrProspects'])*100)
                    ,2);
            }
        }

        $csv = new ExportCsvClass();
        $csv->csvExportProspectsByEmployees($employees, $results, "prospectsParEmployes");
    }

    private function getNbrVentes($id_employee, $tracer, $dateRange)
    {
        $customer = new CustomerTrackingClass();
        return $customer->getCustomersByEmployeeAndTracer($id_employee, $tracer, $dateRange);
    }

    private function getTotalProspectsByTracerAndEmployee($id_employee, $dateRange)
    {
        return TracerClass::getTotalCountTracersByEmployees($id_employee, $dateRange);
    }
}

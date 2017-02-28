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
        $employees = EmployeesClass::getEmployees();
        $tracers = TracerClass::getAllTracer();
        foreach ($employees as $key => $employe) {
            $employees[$key]['tracersByGroups'] = TracerClass::getArrayTracersByEmployees($employe['id_employee'], $dateRange);
            $employees[$key]['TotalCountTracersByGroups'] = TracerClass::getTotalCountTracersByEmployees($employe['id_employee'], $dateRange);
        }

        $this->smarty->assign(array(
            'tracers' => $tracers,
            'employees' => $employees,
            'LinkFile' => Tools::safeOutput($_SERVER['REQUEST_URI'])
        ));

        return $this->smarty->fetch($this->path_tpl . "chartProspectsByEmployees.tpl");
    }

    public function exportCsv($dateRange)
    {
        $employees = EmployeesClass::getEmployees();
        $tracers = TracerClass::getAllTracer();
        array_unshift($tracers, 'firstname');
        array_unshift($tracers, 'lastname');
        $tracersFill = array_fill_keys($tracers, "");
        $tracers[] = "total";
        foreach ($employees as $key => $employe) {
            $employees[$key]['tracersByGroups'] = TracerClass::getArrayTracersByEmployees($employe['id_employee'], $dateRange);
            $employees[$key]['TotalCountTracersByGroups'] = TracerClass::getTotalCountTracersByEmployees($employe['id_employee'], $dateRange);
        }
        $arrayEmployees = array();
        foreach ($employees as $employee) {
            $arrayTracers = array_replace($tracersFill, $employee['tracersByGroups']);
            $arrayTracers['lastname'] = $employee['lastname'];
            $arrayTracers['firstname'] = $employee['firstname'];
            $arrayTracers['total'] = ($employee['TotalCountTracersByGroups']) ? $employee['TotalCountTracersByGroups'] : "";
            $arrayEmployees[$employee['id_employee']] = $arrayTracers;
        }

        $csv = new ExportCsvClass();
        $csv->csvExportProspectsByEmployees($tracers, $arrayEmployees, "prospectsParEmployes");
    }
}

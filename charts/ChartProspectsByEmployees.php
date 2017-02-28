<?php

require_once(dirname(__FILE__) . "/../classes/EmployeesClass.php");
require_once(dirname(__FILE__) . "/../classes/TracerClass.php");

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
            'employees' => $employees
        ));

        return $this->smarty->fetch($this->path_tpl . "chartProspectsByEmployees.tpl");
    }
}

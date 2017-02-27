<?php
require_once(dirname(__FILE__) . "/../classes/GroupClass.php");
require_once(dirname(__FILE__) . "/../classes/TracerClass.php");
require_once(dirname(__FILE__) . "/../classes/ExportCsvClass.php");


class ChartGroupProspects extends AdminController
{

    protected $smarty;
    protected $path_tpl;

    public function __construct()
    {
        parent::__construct();
        $this->smarty = $this->context->smarty;
        $this->path_tpl = _PS_MODULE_DIR_ . 'cdtracking/views/templates/admin/tracking/';
    }

    public function displayChartGroupProspects($dateEmployee)
    {
        $lang = Language::getLanguages(true);
        $groups = Group::getGroups($lang[0]['id_lang']);
        $chartsGroups = array();
        $tracers = $this->getAllTracers();

        foreach ($groups as $group) {
             $groups = $this->getProspectsByGroupId($group['id_group'], $lang[0]['id_lang'], $dateEmployee);
            foreach ($tracers as $tracer => $value) {
                $chartsGroups[$group['name']][] = array( '0' => $tracer , '1' => $groups[$tracer]);
            }
        }

//        $csv = new ExportCsvClass();
//        $csv->csvExport($tracers, $chartsGroups, 'prospects_par_groupe');


        $this->smarty->assign(array(
            'prospectsByGroups' => $chartsGroups,
            'tracers' => $tracers
        ));

        return $this->smarty->fetch($this->path_tpl . "chartGroupProspects.tpl");
    }

    protected function getProspectsByGroupId($id_group, $lang, $dateRange)
    {
        $group = new GroupClass($id_group, $lang);
        $groups = $group->getCustomersByGroup($dateRange, false);
        $result = array();

        foreach ($groups as $group) {
            $result[$group['tracer']] = $group['total_tracer'];
        }

        return $result;
    }

    private function getAllTracers()
    {
        $tracers = TracerClass::getAllTracer();
        $result = array();
        foreach ($tracers as $tracer) {
            if ($tracer === '') {
                $tracer = 'null';
            }
            $result[$tracer] = 0;
        }

        return $result;
    }

}
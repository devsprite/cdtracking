<?php
/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author  Dominique <dominique@chez-dominique.fr>
 * @copyright   2007-2016 Chez-dominique
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__) . "/../../classes/CustomerTrackingClass.php");

class AdminTrackingProspectsController extends ModuleAdminController
{
    public $smarty;
    private $path_tpl;
    private $html = "";

    public function __construct()
    {
        $this->module = 'cdtracking';
        $this->lang = true;
        $this->bootstrap = true;
        $this->context = Context::getContext();
        $this->smarty = $this->context->smarty;
        $this->path_tpl = _PS_MODULE_DIR_ . 'cdtracking/views/templates/admin/tracking/';

        parent::__construct();
    }

    public function initContent()
    {
        $this->context->controller->addJquery();
        $this->context->controller->addJS(_PS_MODULE_DIR_ . 'cdtracking/views/js/Chart.min.js');
        $this->context->controller->addJS(_PS_MODULE_DIR_ . 'cdtracking/views/js/script.js');
        $this->context->controller->addCSS(_PS_MODULE_DIR_ . 'cdtracking/views/css/style.css');

        $this->setDefaultValues();
        $this->html .= $this->displayCalendar();

        $prospects = new CustomerTrackingClass();
        $countTrackingBetweenDate = $prospects->countTrackingBetweenDate($this->getDateBetweenFromEmployee());

        $this->html .= $this->displayChartCountTrackingBetweenDate($countTrackingBetweenDate, $prospects);
        $this->html .= $this->displayChartEmployeeByTracking($this->getDateBetweenFromEmployee(), $prospects);

        $this->content = $this->html;
        parent::initContent();
    }

    private function getDateBetweenFromEmployee()
    {
        $dateBetween = array();
        $dateBetween['debut'] = $this->context->employee->stats_date_from;
        $dateBetween['fin'] = $this->context->employee->stats_date_to;
        $this->smarty->assign(array(
            "dateBetween" => $dateBetween
        ));

        return $dateBetween;
    }

    public function postProcess()
    {
        $this->processDateRange();

        return parent::postProcess();
    }

    public function displayCalendar()
    {
        return AdminTrackingProspectsController::displayCalendarForm(array(
            'Calendar' => $this->l('Calendrier', 'AdminTrackingProspects'),
            'Day' => $this->l('Jour', 'AdminTrackingProspects'),
            'Month' => $this->l('Mois', 'AdminTrackingProspects'),
            'Year' => $this->l('Année', 'AdminTrackingProspects'),
            'From' => $this->l('Du', 'AdminTrackingProspects'),
            'To' => $this->l('Au', 'AdminTrackingProspects'),
            'Save' => $this->l('Enregistrer', 'AdminTrackingProspects')
        ), $this->token);
    }

    public function displayCalendarForm($translations, $token, $action = null, $table = null, $identifier = null, $id = null)
    {
        $context = $this->context;
        $context->controller->addJqueryUI('ui.datepicker');
        if ($identifier === null && Tools::getValue('module')) {
            $identifier = 'module';
            $id = Tools::getValue('module');
        }

        $action = Context::getContext()->link->getAdminLink('AdminTrackingProspects');
        $action .= ($action && $table ? '&' . Tools::safeOutput($action) : '');
        $action .= ($identifier && $id ? '&' . Tools::safeOutput($identifier) . '=' . (int)$id : '');
        $module = Tools::getValue('module');
        $action .= ($module ? '&module=' . Tools::safeOutput($module) : '');
        $action .= (($id_product = Tools::getValue('id_product')) ? '&id_product=' . Tools::safeOutput($id_product) : '');
        $this->smarty->assign(array(
            'current' => self::$currentIndex,
            'token' => $token,
            'action' => $action,
            'table' => $table,
            'identifier' => $identifier,
            'id' => $id,
            'translations' => $translations,
            'datepickerFrom' => Tools::getValue('datepickerFrom', $context->employee->stats_date_from),
            'datepickerTo' => Tools::getValue('datepickerTo', $context->employee->stats_date_to)
        ));

        $tpl = $this->smarty->fetch($this->path_tpl . 'calendar/form_date_range_picker.tpl');
        return $tpl;
    }

    public function processDateRange()
    {
        if (Tools::isSubmit('submitDatePicker')) {
            if ((!Validate::isDate($from = Tools::getValue('datepickerFrom')) ||
                    !Validate::isDate($to = Tools::getValue('datepickerTo'))) ||
                (strtotime($from) > strtotime($to))
            ) {
                $this->errors[] = Tools::displayError('The specified date is invalid.');
            }
        }
        if (Tools::isSubmit('submitDateDay')) {
            $from = date('Y-m-d');
            $to = date('Y-m-d');
        }
        if (Tools::isSubmit('submitDateDayPrev')) {
            $yesterday = time() - 60 * 60 * 24;
            $from = date('Y-m-d', $yesterday);
            $to = date('Y-m-d', $yesterday);
        }
        if (Tools::isSubmit('submitDateMonth')) {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
        }
        if (Tools::isSubmit('submitDateMonthPrev')) {
            $m = (date('m') == 1 ? 12 : date('m') - 1);
            $y = ($m == 12 ? date('Y') - 1 : date('Y'));
            $from = $y . '-' . $m . '-01';
            $to = $y . '-' . $m . date('-t', mktime(12, 0, 0, $m, 15, $y));
        }
        if (Tools::isSubmit('submitDateYear')) {
            $from = date('Y-01-01');
            $to = date('Y-12-31');
        }
        if (Tools::isSubmit('submitDateYearPrev')) {
            $from = (date('Y') - 1) . date('-01-01');
            $to = (date('Y') - 1) . date('-12-31');
        }
        if (isset($from) && isset($to) && !count($this->errors)) {
            $this->context->employee->stats_date_from = $from;
            $this->context->employee->stats_date_to = $to;
            $this->context->employee->update();
            if (!$this->isXmlHttpRequest()) {
                Tools::redirectAdmin($_SERVER['REQUEST_URI']);
            }
        }
    }

    private function setDefaultValues()
    {
        if (empty($_COOKIE['stats_date'])) {
            $from = date('Y-m-01');
            $to = date('Y-m-t');
            $this->context->employee->stats_date_from = $from;
            $this->context->employee->stats_date_to = $to;
            $this->context->employee->update();
            setcookie('stats_date', 'on', time() + 3600);
        }
    }

    private function displayChartCountTrackingBetweenDate($countTrackingBetweenDate, CustomerTrackingClass $prospects)
    {
        $nbrProspects = $prospects->readNbrTotalProspects($this->getDateBetweenFromEmployee());
        $trackingJsonValues = array();
        $trackingJsonHeader = array();
        foreach ($countTrackingBetweenDate as $key => $value) {
            $trackingJsonValues[] = round(100 / intval($nbrProspects) * intval($value['total']), 2);
            $trackingJsonHeader[] = "% " . intval($value['tracer']);
        }

        $this->smarty->assign(array(
            "countTrackingBetweenDate" => $countTrackingBetweenDate,
            "countTrackingNbrProspects" => $nbrProspects,
            "countTrackingBetweenDateJsonValue" => json_encode($trackingJsonValues),
            "countTrackingBetweenDateJsonHeader" => json_encode($trackingJsonHeader),
        ));
        return $this->smarty->fetch($this->path_tpl . "trackingChart.tpl");
    }

    private function displayChartEmployeeByTracking($countTrackingBetweenDate, CustomerTrackingClass $prospect)
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
            "totalTrackingProspects" => $this->totalTrackingProspects($arrayTrackingProspects),
            "trackingProspectsHeader" => json_encode($arrayTrackingProspectsHeader),
            "trackingProspectsValues" => json_encode($arrayTrackingProspectsValues),
        ));

        return $this->smarty->fetch($this->path_tpl . "prospectsChart.tpl");
    }

    private function readNumberTracking($prospects)
    {
        $arrayTracking = array();
        foreach ($prospects as $prospect => $value) {
            if ($value['tracer'] != 0 || !empty($value['tracer']))
            $arrayTracking[intval($value['tracer'])] = intval($value['tracer']);
        }

        return $arrayTracking;
    }

    private function trackingProspects($prospects, $arrayNumberTracking)
    {
        $arrayTracking = array();
        foreach ($arrayNumberTracking as $numberTracking) {
            foreach ($prospects as $prospect) {
                if ($numberTracking == intval($prospect['tracer'])) {
                    $arrayTracking[$numberTracking] += 1;
                }
            }
        }

        return $arrayTracking;
    }

    private function totalTrackingProspects($arrayTrackingProspects)
    {
        $total = 0;
        return array_sum($arrayTrackingProspects);
    }
}
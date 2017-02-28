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
require_once(dirname(__FILE__) . "/../../charts/ChartCustomersByAge.php");
require_once(dirname(__FILE__) . "/../../charts/ChartProspectsByAge.php");
require_once(dirname(__FILE__) . "/../../charts/ChartTrackingProspects.php");
require_once(dirname(__FILE__) . "/../../charts/ChartOrdersByTracking.php");
require_once(dirname(__FILE__) . "/../../charts/ChartGroupProspects.php");
require_once(dirname(__FILE__) . "/../../charts/ChartProspectsByEmployees.php");
require_once(dirname(__FILE__) . "/../../utils/DateRange.php");


class AdminTrackingProspectsController extends ModuleAdminController
{
    private $smarty;
    private $path_tpl;
    private $html = "";
    private $dateRange;
    private $dateEmployee;

    public function __construct()
    {
        $this->module = 'cdtracking';
        $this->lang = true;
        $this->bootstrap = true;
        $this->context = Context::getContext();
        $this->smarty = $this->context->smarty;
        $this->path_tpl = _PS_MODULE_DIR_ . 'cdtracking/views/templates/admin/tracking/';
        $this->dateRange = new DateRange();
        $this->dateEmployee = $this->dateRange->getDateBetweenFromEmployee();

        parent::__construct();
    }

    public function initContent()
    {
        $this->context->controller->addJquery();
        $this->context->controller->addJS(_PS_MODULE_DIR_ . 'cdtracking/views/js/Chart.min.js');
        $this->context->controller->addJS(_PS_MODULE_DIR_ . 'cdtracking/views/js/script.js');
        $this->context->controller->addCSS(_PS_MODULE_DIR_ . 'cdtracking/views/css/style.css');
        $this->setDefaultValues();

        $chartAgeCustomers = new ChartCustomersByAge();
        $chartAgeProspects = new ChartProspectsByAge();
        $trackingProspects = new ChartTrackingProspects();
        $orderByTracking = new ChartOrdersByTracking();
        $prospects = new CustomerTrackingClass();
        $chartGroupProspects = new ChartGroupProspects();
        $chartProspectsByEmployees = new ChartProspectsByEmployees();

        $countTrackingBetweenDate = $prospects->countTrackingBetweenDate($this->dateEmployee);

        $this->html .= $this->dateRange->displayCalendar();
        $this->html .= $trackingProspects->displayChartCountTrackingBetweenDate($countTrackingBetweenDate, $prospects, $this->dateEmployee);
        $this->html .= $orderByTracking->displayChartOrdersByTracking($this->dateEmployee, $prospects);
        $this->html .= $chartAgeProspects->displayChartAgeProspects($this->dateEmployee, $prospects);
        $this->html .= $chartAgeCustomers->displayChartAgeCustomers($this->dateEmployee, $prospects);
        $this->html .= $chartGroupProspects->displayChartGroupProspects($this->dateEmployee);
        $this->html .= $chartProspectsByEmployees->displayChartProspectsByEmployees($this->dateEmployee);

        $this->content = $this->html;
        parent::initContent();
    }


    public function postProcess()
    {
        if (Tools::isSubmit('export_csv')){
            $export = Tools::getValue('export_csv');
            if ( $export == '1') {
                $chartGroupProspects = new ChartGroupProspects();
                $chartGroupProspects->exportCsv($this->dateEmployee);
            }
        }
        $this->dateRange->processDateRange();

        return parent::postProcess();
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
}
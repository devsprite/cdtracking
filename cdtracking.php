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
 * @author    Dominique <dominique@chez-dominique.fr>
 * @copyright 2007-2016 Chez-Dominique
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit();
}

class Cdtracking extends Module
{
    public function __construct()
    {
        $this->name = 'cdtracking';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.0';
        $this->author = 'Dominique';
        $this->need_instance = 0;
        $this->table_name = $this->name;
        $this->table_charset = 'utf8';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Stats Tracking');
        $this->description = $this->l('Affichage des informations de tracking.');
        $this->confirmUninstall = $this->l('Are you sure you want to delete this module?');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function installModule()
    {
        if (!parent::install() ||
            !$this->_createSingleTab('AdminTrackingProspects', 'Tracking', Language::getLanguages())
        )
            return false;
        return true;
    }

    public function uninstallModule()
    {
        if (!parent::uninstall() ||
            !$this->_eraseTabs()
        )
            return false;
        return true;
    }

    private function _createSingleTab($class, $name, $all_langs)
    {
        $tab = new Tab();
        $tab->active = 1;
        foreach ($all_langs as $language){
            $tab->name[$language['id_lang']] = $name;
        }
        $tab->class_name = $class;
        $tab->module = $this->name;
        $tab->id_parent = Tab::getIdFromClassName('AdminParentStats');
        if($tab->add()){
            return $tab->id;
        }
        else return false;
    }

    private function _eraseTabs()
    {
        // Base Tab
        $id_tabm = (int)Tab::getIdFromClassName('AdminTrackingProspects');
        if($id_tabm)
        {
            $tabm = new Tab($id_tabm);
            $tabm->delete();
        }

        return true;
    }
}
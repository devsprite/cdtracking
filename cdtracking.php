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
    public $name;
    public $tabName;

    public function __construct()
    {
        $this->name = 'cdtracking';
        $this->tab = 'analytics_stats';
        $this->tabName = 'Tracking';
        $this->version = '1.0.0';
        $this->author = 'Dominique';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->table_name = $this->name;
        $this->displayName = $this->l('Stats Tracking');
        $this->description = $this->l('Affichage des informations de tracking.');
        $this->confirmUninstall = $this->l('Are you sure you want to delete this module?');
    }

    public function install()
    {
        if (!parent::install() ||
            !$this->createSingleTab()
        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !$this->_eraseTabs()
        ){
            return false;
        }
        return true;
    }

    private function createSingleTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $languages = Language::getLanguages(false);
        if (is_array($languages)) {
            foreach ($languages as $language) {
                $tab->name[$language['id_lang']] = $this->tabName;
            }
        }
        $tab->class_name = 'AdminTracking';
        $tab->module = 'cdtracking';
        $tab->id_parent = Tab::getIdFromClassName("AdminParentStats");

        return (bool)$tab->add();
    }

    private function _eraseTabs()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminTracking');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }
        return true;
    }
}
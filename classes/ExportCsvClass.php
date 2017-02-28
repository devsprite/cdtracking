<?php

class ExportCsvClass extends AdminController
{
    private $_csv;

    public function __construct()
    {
        parent::__construct();
    }

    public function csvExport($tracers, $datas, $nameFile)
    {
        $this->_csv = chr(239) . chr(187) . chr(191) ;
        if (count($tracers)) {
            $this->_csv .= "Groupes;";
            foreach ($tracers as $column => $value) {
                $this->_csv .= $column . ';';
            }
            $this->_csv = rtrim($this->_csv, ';') . "\n";

            foreach ($datas as $group => $values) {
                $this->_csv .= $group . ';';
                foreach ($values as $column) {
                    $this->_csv .= $column[1] . ';';
                }
                $this->_csv = rtrim(str_replace('.', ',', $this->_csv), ';') . "\n";
            }
        }
        $this->_displayCsv($nameFile);
    }

    public function csvExportProspectsByEmployees($tracers, $datas, $nameFile)
    {
        $this->_csv = chr(239) . chr(187) . chr(191) ;
        if (count($tracers)) {
            $this->_csv .= "Id;";
            foreach ($tracers as $column => $value) {
                $this->_csv .= $value . ';';
            }
            $this->_csv = rtrim($this->_csv, ';') . "\n";

            foreach ($datas as $group => $values) {
                $this->_csv .= $group . ';';
                foreach ($values as $column) {
                    $this->_csv .= $column . ';';
                }
                $this->_csv = rtrim(str_replace('.', ',', $this->_csv), ';') . "\n";
            }
        }
        $this->_displayCsv($nameFile);
    }

    protected function _displayCsv($nameFile)
    {
        if (ob_get_level() && ob_get_length() > 0) {
            ob_end_clean();
        }
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $nameFile . '.csv"');
        echo $this->_csv;
        exit;
    }
}
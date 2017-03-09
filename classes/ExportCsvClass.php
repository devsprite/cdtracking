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

    /**
     * @param $titles
     * array (size=3)
     *     0 => string 'Tracer' (length=6)
     *     1 => string 'Nombre' (length=6)
     *     2 => string 'Répartition' (length=12)
     *
     * @param $datas
     * array (size=7)
     *      0 =>
     *          array (size=3)
     *              'tracer' => string '5001' (length=4)
     *              'total' => string '235' (length=3)
     *              'repartition' => float 54.4
     *      1 =>
     *          array (size=3)
     *              'tracer' => string '21' (length=2)
     *              'total' => string '79' (length=2)
     *              'repartition' => float 18.29
     *      2 =>
     *          array (size=3)
     *              'tracer' => string '5301' (length=4)
     *              'total' => string '58' (length=2)
     *              'repartition' => float 13.43
     * @param $nameFile
     */
    public function exportCsv($titles, $datas, $nameFile)
    {
        $this->_csv = chr(239) . chr(187) . chr(191) ;
        if (count($titles)) {
            foreach ($titles as $column) {
                $this->_csv .= $column . ';';
            }
            $this->_csv = rtrim($this->_csv, ';') . "\n";

            foreach ($datas as $group => $values) {
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
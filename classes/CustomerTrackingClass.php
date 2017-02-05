<?php

interface ICustomerTrackingClass
{
    public function countTrackingBetweenDate($dateBetween);
}

class CustomerTrackingClass implements ICustomerTrackingClass
{
    private $tracer;

    private $birthday;

    private $id_gender;

    public function __construct()
    {
    }

    public function countTrackingBetweenDate($dateBetween)
    {
        $sql = 'SELECT tracer, COUNT(id_customer) as total 
                FROM `' . _DB_PREFIX_ . 'customer`
                WHERE date_add BETWEEN "' . $dateBetween["debut"] . ' 00:00:00" AND "' . $dateBetween["fin"] . ' 23:59:59"
                GROUP BY tracer
                ORDER BY total DESC';
        $results = Db::getInstance()->executeS($sql);

        return $this->trimArray($results);
    }


    private function trimArray($results)
    {
        $retour = array();
        foreach ($results as $key => $value) {
            foreach ($value as $k => $i) {
                $retour[$key][$k] = trim($i);
            }
        }

        return $retour;
    }

    public function readNbrTotalProspects($getDateBetweenFromEmployee)
    {
        $sql = 'SELECT COUNT(id_customer) FROM `' . _DB_PREFIX_ . 'customer`
                WHERE date_add BETWEEN "' . $getDateBetweenFromEmployee["debut"] . ' 00:00:00" 
                AND "' . $getDateBetweenFromEmployee["fin"] . ' 23:59:59"';
        $query = Db::getInstance()->getValue($sql);

        return $query;
    }

    public function getProspectsByDate($countTrackingBetweenDate)
    {
        $sql = 'SELECT ROUND(o.`total_products` - o.`total_discounts_tax_excl`,2) AS total,
                o.`id_order`, c.`tracer`, o.`id_employee`, c.`id_customer`, o.`date_add`,
                IF((SELECT so.`id_order` FROM `' . _DB_PREFIX_ . 'orders` so 
                WHERE so.`id_customer` = o.`id_customer` 
                AND so.`id_order` < o.`id_order` LIMIT 1) > 0, 0, 1) as new
				FROM `' . _DB_PREFIX_ . 'orders` AS o, `'._DB_PREFIX_.'customer` AS c
				WHERE `valid` = 1
				AND o.`id_customer` = c.`id_customer`
				AND c.`tracer` != ""
				AND o.`date_add` BETWEEN "'.$countTrackingBetweenDate['debut'].' 00:00:00"
				AND "'.$countTrackingBetweenDate['fin'].' 23:59:59"
				';
        $query = Db::getInstance()->executeS($sql);

        return $this->trimArray($query);
    }

}
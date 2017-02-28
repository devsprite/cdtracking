<?php


class CustomerTrackingClass
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
                WHERE date_add BETWEEN "' . $dateBetween["debut"] . '" AND "' . $dateBetween["fin"] . '"
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
                WHERE `tracer` != ""
                AND date_add BETWEEN "' . $getDateBetweenFromEmployee["debut"] . '" 
                AND "' . $getDateBetweenFromEmployee["fin"] . '"';
        $query = Db::getInstance()->getValue($sql);

        return $query;
    }

    public function getCustomersByDate($countTrackingBetweenDate)
    {
        $sql = 'SELECT ROUND(o.`total_products` - o.`total_discounts_tax_excl`,2) AS total,
                o.`id_order`, c.`tracer`, o.`id_employee`, c.`id_customer`, o.`date_add`, 
                floor(datediff(current_date(), birthday)/ 365) AS age,
                birthday,
                IF((SELECT so.`id_order` FROM `' . _DB_PREFIX_ . 'orders` so 
                WHERE so.`id_customer` = o.`id_customer` 
                AND so.`id_order` < o.`id_order` LIMIT 1) > 0, 0, 1) as new
				FROM `' . _DB_PREFIX_ . 'orders` AS o, `'._DB_PREFIX_.'customer` AS c
				WHERE `valid` = 1
				AND o.`id_customer` = c.`id_customer`
				AND c.`tracer` != ""
				AND o.`date_add` BETWEEN "'.$countTrackingBetweenDate['debut'].'"
				AND "'.$countTrackingBetweenDate['fin'].'"
				AND `id_code_action` = 2
				';
        $query = Db::getInstance()->executeS($sql);

        return $this->trimArray($query);
    }

    public function getProspectsByDate($countTrackingBetweenDate)
    {
        $sql = 'SELECT c.`tracer`, c.`id_customer`, c.`date_add`, 
                floor(datediff(current_date(), birthday)/ 365) AS age,
                c.birthday
				FROM `'._DB_PREFIX_.'customer` AS c
				WHERE c.`tracer` != ""
				AND c.`date_add` BETWEEN "'.$countTrackingBetweenDate['debut'].'"
				AND "'.$countTrackingBetweenDate['fin'].'"
				';
        $query = Db::getInstance()->executeS($sql);

        return $this->trimArray($query);
    }
}

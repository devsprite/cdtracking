<?php

class GroupClass extends Group
{
    public function getCustomersByGroup($dateRange, $start = 0, $limit = 0, $shop_filtering = false)
    {
        return Db::getInstance()->executeS('
		SELECT 
		REPLACE(REPLACE(c.`tracer`, " ", ""), "\n", "") as tracer,
		COUNT(c.`tracer`) AS total_tracer 
		FROM `' . _DB_PREFIX_ . 'customer_group` cg
		LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (cg.`id_customer` = c.`id_customer`)
		WHERE cg.`id_group` = ' . (int)$this->id . '
		AND c.`date_add` BETWEEN "' . $dateRange["debut"] . '" AND "' . $dateRange["fin"] . '"
		AND c.`deleted` != 1
		GROUP BY c.`tracer`
		' . ($shop_filtering ? Shop::addSqlRestriction(Shop::SHARE_CUSTOMER) : '') . '
		' . ($limit > 0 ? 'LIMIT ' . (int)$start . ', ' . (int)$limit : ''));
    }
}

<?php

class EmployeesClass
{
    public static function getEmployees()
    {
        return Db::getInstance()->executeS('
			SELECT `id_employee`, `firstname`, `lastname`
			FROM `'._DB_PREFIX_.'employee`
			ORDER BY `id_employee` ASC
		');
    }

}
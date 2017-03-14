<?php

class TracerClass extends AdminController
{
    public static function getAllTracer()
    {
        $sql = 'SELECT DISTINCT REPLACE(REPLACE(REPLACE(`tracer`, " ", ""), "\n", ""), "\r", "") as tracer FROM `ps_customer`';
        $req = Db::getInstance()->executeS($sql);
        $result = array();

        foreach ($req as $r) {
            $result[] = $r['tracer'];
        }
        sort($result);

        return $result;
    }

    public static function getTracersByEmployees($id_employee, $dateRange)
    {
        $sql = 'SELECT REPLACE(REPLACE(REPLACE(c.`tracer`, " ", ""), "\n", ""), "\r", "") as tracer,
                COUNT(*) as total
                FROM ps_prospect_attribue as pa
                LEFT JOIN ps_prospect as p on pa.id_prospect_attribue = p.id_prospect_attribue
                LEFT JOIN ps_customer as c ON p.id_customer = c.id_customer
                WHERE pa.id_employee = "' . (int)$id_employee . '"
                AND pa.date_debut BETWEEN "' . $dateRange['debut'] . '" AND "' . $dateRange['fin'] . '"
                GROUP BY c.tracer';
        $req = Db::getInstance()->executeS($sql);

        return $req;
    }

    public static function getTotalCountTracersByEmployees($id_employee, $dateRange)
    {
        $sql = 'SELECT COUNT(*) as total
                FROM ps_prospect_attribue as pa
                LEFT JOIN ps_prospect as p on pa.id_prospect_attribue = p.id_prospect_attribue
                LEFT JOIN ps_customer as c ON p.id_customer = c.id_customer
                WHERE pa.id_employee = ' . (int)$id_employee . '
                AND pa.date_debut BETWEEN "' . $dateRange['debut'] . '" AND "' . $dateRange['fin'] . '"';
        $req = Db::getInstance()->getValue($sql);

        return $req;
    }

    public static function getArrayTracersByEmployees($id_employee, $dateRange)
    {
        $tracers = TracerClass::getTracersByEmployees($id_employee, $dateRange);
        $arrayTracers = array();
        foreach ($tracers as $tracer => $value) {
            $arrayTracers[$value['tracer']] = $value['total'];
        }

        return $arrayTracers;
    }

    public static function getNbrProspectsByTracer($tracer, $dateRange)
    {
        $sql = 'SELECT COUNT(id_customer) from ps_customer 
                WHERE tracer = "' . $tracer . '"
                AND date_add BETWEEN "' . $dateRange['debut'] . '" AND "' . $dateRange['fin'] . '"';
        $req = Db::getInstance()->getValue($sql);

        return $req;

    }

}

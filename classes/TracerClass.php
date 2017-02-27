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
}

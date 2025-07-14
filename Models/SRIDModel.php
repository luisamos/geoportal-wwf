<?php
class SRIDModel extends PostgreSQL
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerSRID($proj4)
    {
        $sql = "SELECT srid FROM spatial_ref_sys WHERE proj4text LIKE('%{$proj4}%') LIMIT 1;";
        $request = $this->select($sql);
        if(empty($request)) return 0;
        else return trim($request['srid']);
    }
}
?>
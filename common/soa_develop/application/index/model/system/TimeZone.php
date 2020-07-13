<?php

namespace app\index\model\system;
use app\common\help\Help;
use think\Model;

use think\config;
use think\Db;
class TimeZone extends Model{
    protected $table = 'time_zone';

    public function initialize()
    {
        parent::initialize();
    }

    public function getTimeZoneList(){

        $result = $this->table("time_zone")
            ->field(['time_zone.*'])
            ->select();

        return $result;
    }

}
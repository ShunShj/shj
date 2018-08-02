<?php
/**
 * Created by PhpStorm.
 * User: Shj
 * Date: 2018/8/2
 * Time: 17:16
 */
namespace app\model;
use common\lib\Model;

class userModel extends Model{
    public $table = 'user';

    public function all(){
        return $this->select($this->table,'*');
    }
}
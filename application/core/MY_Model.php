<?php
/**
 * Created by PhpStorm.
 * User: fengchu
 * Date: 17/9/27
 * Time: 下午1:42
 */
class MY_Model extends CI_Model {

    public $msg = '';
    public $status = false;
    public $dbname = "fengchu";//数据库名称,可以为空,如果是空使用连接对象中的默认值
    public $dbconfigkey="SNOB";//数据库连接对象配置key
    //public $dbconfigkey="default";//数据库连接对象配置key
    public $dbtable="";//表名称,在执行sql之前不可以为空
    public $db = "";//数据库主库连接对象
    public $read_db ="";//数据库从库连接对象
    protected $columnAlowd_R = 1;//可读
    protected $columnAlowd_W = 2;//可写
    function __construct()
    {
        parent::__construct();
        //$this->load->library("Redis_db");//实例化redis对象
        //$this->redis_db->connect();//连接redis对象
        $this->setdb();
    }

    public function checkColumn($data,$column){
        if(empty($data) || empty($column)){//??
            return [];
        }
        $result = [];
        foreach ($data as $k => $v){
            foreach ($column as $item){
                if($k == $item[0] && $item[3]&$this->columnAlowd_W){
                    $result[$k] = $v;
                }
            }
        }
        return $result;
    }

    /*
     * 获取配置文件选择数据库连接值
     * */
    function setdb($dbconfigkey="",$isreturn=FALSE,$dataname="")
    {
        if ($dbconfigkey=="")
        {
            $dbconfigkey = $this->dbconfigkey;

        }
        if ($dbconfigkey)//如果存在则进行数据库连接
        {
            $this->config->load("database");
            $database = $this->config->item('database');
            //print_r($database);die;
            if (isset($database[$this->dbconfigkey]))
            {
                $Master = "";
                $readdb = "";
                foreach($database[$this->dbconfigkey] as $val)
                {
                    if ($this->dbname != "")
                    {
                        $val['database']=$this->dbname;
                    }
                    if ($dataname)
                    {
                        $val['database'] = $dataname;
                    }
                    if ($val['isMaster']){
                        unset($val['isMaster']);
                        $Master = $val;
                    }else{
                        unset($val['isMaster']);
                        $readdb[] = $val;
                    }
                }
                //连接主库获得主库连接
                $mdb = $this->load->database($Master,true);
                if($readdb)
                {
                    $sdb = $this->load->database($readdb[rand(0,(count($readdb)-1))],true);
                    //print_r($sdb);die;
                }else{//当没有从库的时候,从库用主库的连接对象
                    $sdb = $mdb;
                }
                if ($isreturn)
                {
                    $dbarray = array('mdb' => $mdb,'sdb' => $sdb);
                    return $dbarray;
                }else{
                    $this->db = $mdb;
                    $this->read_db = $sdb;
                }
            }else{
                show_error('对不起没有找到可用的连接');
            }
        }
    }




}
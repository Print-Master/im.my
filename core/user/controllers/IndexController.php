<?php
namespace core\user\controllers;

use core\admin\models\Model;
use core\base\controllers\BaseController;
use core\base\models\Crypt;

class IndexController extends BaseUser
{

    protected $name;

    protected function inputData()
    {
    
        parent::inputData();
        
        $years = $this->wordsForCounters(22);
        
        $a = 43;
        
        
    }
}
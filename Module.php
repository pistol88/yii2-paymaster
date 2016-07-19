<?php
namespace pistol88\paymaster;

use yii;

class Module extends \yii\base\Module
{
    public $adminRoles = ['admin', 'superadmin'];
    public $thanksUrl = '/page/spasibo-za-zakaz';
    public $failUrl = '/page/problema-s-oplatoy';
    public $currency = 'RUB';
    public $secret = '';
    public $merchantId = '';

    public function init()
    {
        parent::init();
    }
}

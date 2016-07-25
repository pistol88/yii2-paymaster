<?php
namespace pistol88\paymaster\widgets;

use yii;

class PaymentForm extends \yii\base\Widget
{
    public $description = '';
    public $orderModel;
    public $autoSend = false;

    public function init()
    {
        return parent::init();
    }

    public function run()
    {
        if(empty($this->orderModel)) {
            return false;
        }
        
        return $this->render('paymentForm', [
            'orderModel' => $this->orderModel,
            'module' => yii::$app->getModule('paymaster'),
            'description' => $this->description,
            'autoSend' => $this->autoSend,
        ]);
    }
}

<?php
namespace pistol88\paymaster\controllers;

use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PaymasterController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }
    
    function actionResult()
    {
        if(!yii::$app->request->post('LMI_PAYMENT_NO')) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
		
        $check = yii::$app->request->post('LMI_MERCHANT_ID').';'.
                yii::$app->request->post('LMI_PAYMENT_NO').';'.
                yii::$app->request->post('LMI_SYS_PAYMENT_ID').';'.
                yii::$app->request->post('LMI_SYS_PAYMENT_DATE').';'.
                yii::$app->request->post('LMI_PAYMENT_AMOUNT').';'.
                yii::$app->request->post('LMI_CURRENCY').';'.
                yii::$app->request->post('LMI_PAID_AMOUNT').';'.
                yii::$app->request->post('LMI_PAID_CURRENCY').';'.
                yii::$app->request->post('LMI_PAYMENT_SYSTEM').';'.
                yii::$app->request->post('LMI_SIM_MODE').';'.
                $this->module->secret;

        $hash = base64_encode(md5($check, true));
        
        if(yii::$app->request->post('LMI_HASH') != $hash) {
            return 'NO';
        }

        $response = base64_decode(yii::$app->request->post('LMI_HASH'));

        $pmMerchantId = yii::$app->request->post('LMI_MERCHANT_ID');
        $pmOrderId    = (int)yii::$app->request->post('LMI_PAYMENT_NO');

        $orderModel = $this->module->orderModel;
        $orderModel = $orderModel::findOne($pmOrderId);

        if(!$orderModel) {
            throw new NotFoundHttpException('The requested order does not exist.');
        }

        $pm_amount      = yii::$app->request->post('LMI_PAYMENT_AMOUNT');
        $pm_currency    = yii::$app->request->post('LMI_CURRENCY');

        $order_total = number_format($orderModel->getCost(), 2, '.', '');

        if($response == '' | $pm_amount < $order_total) {
            return 'NO';
        } else {
            $orderModel->setPaymentStatus('yes');
            $orderModel->save(false);
            
            return 'YES';
        }
    }
}

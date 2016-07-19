<?php
namespace pistol88\paymaster\controllers;

use yii;
use yii\web\Controller;

class PaymasterController extends Controller
{
    function actionResult()
	{
        if(!isset(yii::$app->request->post('LMI_PAYMENT_NO'))) {
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
            die('NO');
        }

        $response = base64_decode(yii::$app->request->post('LMI_HASH'));

        $pm_merchant_id = yii::$app->request->post('LMI_MERCHANT_ID');
        $pm_order_id    = (int)yii::$app->request->post('LMI_PAYMENT_NO');

        $orderModel = yii::$app->orderModel;
        $orderModel = $orderModel::findOne($pm_order_id);

        if(!$orderModel) {
            throw new NotFoundHttpException('The requested order does not exist.');
        }

        $pm_amount      = yii::$app->request->post('LMI_PAYMENT_AMOUNT');
        $pm_currency    = yii::$app->request->post('LMI_CURRENCY');

        $order_total = number_format($orderModel->getCost(), 2, '.', '');

        if($response == '' || $pm_amount < $order_total) {
            echo 'NO';
        }
        else {
            $orderModel->setPaymentStatus('yes');
            $orderModel->save(false);
            echo 'YES';
        }
    }
}
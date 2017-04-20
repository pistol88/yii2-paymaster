Yii2-paymaster
==========
В составе модуля содержится виджет оплаты заказа через paymaster.ru. В виджет передается модель заказа, которая должна имплементировать интерфейс interfaces/Order.

Модуль написан в основном для [pistol88/yii2-order](https://github.com/pistol88/yii2-order), но подойдет для любого сайта, где есть модель заказа.

Установка
---------------------------------
Выполнить команду

```
php composer require pistol88/yii2-paymaster "*"
```

Или добавить в composer.json

```
"pistol88/yii2-paymaster": "*",
```

И выполнить

```
php composer update
```

Подключение и настройка
---------------------------------
В конфигурационный файл приложения добавить модуль paymaster

```php
    'modules' => [
        'paymaster' => [
            'class' => 'pistol88\paymaster\Module',
            'merchantId' => 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX', //Идентификатор мерчанта, выдается автоматически в личном кабинете
            'secret' => 'XXXXXX', //Секретный ключ, задается вручную в настройках магазина
            'thanksUrl' => '/page/spasibo-za-zakaz', //Страница, куда попадает пользователь после оплаты. Туда ГЕТ параметром будет передан также номер заказа.
            'failUrl' => '/page/problema-s-oplatoy', //Страница, куда попадает пользовать в случае неудачной оплаты.
            'currency' => 'RUB', //Яснопонятно
            'orderModel' => 'pistol88\order\models\Order', //Модель заказа. Эта модель должна имплементировать интерфейс pistol88\paymaster\interfaces\Order. В момент списания денег будет вызываться $model->setPaymentStatus('yes').
        ],
        //...
    ],
```

Чтобы срабатывал редирект обратно и оповещение сайта о списании денег, не забудьте поставить галочку "Разрешена замена URL" в настройках магазина в кабинете Паймастера.

Виджеты
---------------------------------
За вывод формы оплаты отвечает виджет pistol88\paymaster\widgets\PaymentForm.

Скорее всего, самое уместное место для виджета - страница "спасибо за заказ.

```php
<?=\pistol88\paymaster\widgets\PaymentForm::widget([
    'autoSend' => false,
    'orderModel' => $model,
    'description' => 'Оплата заказа'
]);?>
```

* autoSend - нужно ли автоматически отправлять форму заказа
* orderModel - экземпляр модели заказа, имплементирующий interfaces/Order
* description - описание платежа

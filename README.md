Yii2-paymaster
==========
В составе модуля содержится виджет оплаты заказа через paymaster.ru. В виджет передается модель заказа, которая должна имплементировать интерфейс interfaces/Order.

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

Далее, мигрируем базу:

```
php yii migrate --migrationPath=vendor/pistol88/yii2-paymaster/migrations
```

Подключение и настройка
---------------------------------
В конфигурационный файл приложения добавить модуль review

```php
    'modules' => [
        'paymaster' => [
            'class' => 'pistol88\paymaster\Module',
            'merchantId' => 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX', //Идентификатор мерчанта, выдается автоматически в личном кабинете
            'secret' => 'XXXXXX', //Секретный ключ, задается вручную в настройках магазина
            'thanksUrl' => '/page/spasibo-za-zakaz', //Страница, куда попадает пользователь после оплаты. Туда ГЕТ параметром будет передан также номер заказа.
            'failUrl' => '/page/problema-s-oplatoy', //Страница, куда попадает пользовать в случае неудачной оплаты.
            'currency' => 'RUB',
        ],
        ///
    ],
```

Чтобы срабатывал редирект обратно и опопвещение сайта, не забудьте поставить галочку "Разрешена замена URL" в настройках магазина в кабинете Паймастера.

Виджеты
---------------------------------
За вывод формы заказа отвечает виджет pistol88\paymaster\widgets\PaymentForm. Скорее всего, самое уместное место для виджета - страница "спасибо за заказ.

```php
<?=\pistol88\paymaster\widgets\PaymentForm::widget([
    'autoSend' => false,
    'orderModel' => $model,
    'description' => 'Оплата заказа'
]);?>
```

*autoSend - нужно ли автоматически отправлять форму заказа
*orderModel - экземпляр модели заказа, имплементирующий interfaces/Order
*description - описание платежа
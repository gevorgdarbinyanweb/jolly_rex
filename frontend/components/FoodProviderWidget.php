<?php
namespace frontend\components;

use yii\base\Widget;
use common\models\Orders;
use yii\helpers\Html;

class FoodProviderWidget extends Widget {
    public $content;
    public $userData;
    public $userTypeData;
    public $userSearchModel;
    public $dataProvider;
    
    public function init() {
        parent::init();
        $this->content = Html::beginTag('div', ['class' => 'row']);
        $this->content .= Html::beginTag('div', ['class' => 'col-lg-10 col-md-10 col-sm-12 col-xs-12 col-lg-offset-1 col-md-offset-1']);
        $this->content .= Html::beginTag('div', ['class' => 'food-dashboard-internal']);
        $this->content .= $this->drawHeader($this->userData);
        $this->content .= $this->drawContent($this->userData);
        $this->content .= Html::endTag('div');
        $this->content .= Html::endTag('div');
        $this->content .= Html::endTag('div');
    }

    public function run(){
        return $this->content;
    }
    
    public function drawHeader($userData) {
        $str = Html::beginTag('div', ['class' => 'row']);
        $str .= Html::beginTag('div', ['class' => 'col-lg-4 col-md-4 col-sm-12 col-xs-12']);
        $str .= $this->getProductPhoto($userData->id);
        $str .= Html::endTag('div');
        $str .= Html::beginTag('div', ['class' => 'col-lg-8 col-md-8 col-sm-12 col-xs-12']);
        $str .= $this->getProductTitle($userData);
        $str .= Html::endTag('div');
        $str .= Html::endTag('div');

        return $str;
    }
    
    public function getProductPhoto($foodID) {
//        $entertainerPhotoData = UserPhotos::find()
//                ->where(['user_id' => $foodID, 'type' => 'main'])
//                ->one();
//        $userPhoto = Html::img(Yii::getAlias('@root') . '/common/uploads/' . $entertainerPhotoData['id'] . '/main/' . $entertainerPhotoData['photo'], []);
        $userPhoto = Html::img('@web/images/foodLayer.jpg', ['class' => 'food-dashboard-photo']);
        return $userPhoto;
    }
    
    public function getProductTitle($userData) {
        $str = Html::beginTag('h1', ['class' => 'food-dashboard-title']);
        $str .= 'Welcome, ';
        $str .= $userData->email;
        $str .= Html::endTag('h1');

        return $str;
    }
    
    public function drawContent($userData) {
        $str = Html::beginTag('div', ['class' => 'row']);
        $str .= Html::beginTag('div', ['class' => 'col-lg-4 col-md-4 col-sm-12 col-xs-12']);
//            $str .= 
//            yii2fullcalendar::widget(array(
//                'clientOptions' => [
//                    'header' => ['right' => ''],
//                    'dayClick' => new \yii\web\JsExpression('
//                        function (date, jsEvent, view) {
//                            var dateValue = date.format();
//                            
//                            var dateExpression = new Date(dateValue);
//                            var toDateString = dateExpression.toDateString();
//
//
//                            //console.log(typeof dateValue);
//                            //console.log("Clicked on: " + date.format());
//                            // console.log("Coordinates: " + jsEvent.pageX + "," + jsEvent.pageY);
//                            // console.log("Current view: " + view.name);
//
//                            $(this).css("background-color", "red");
//                            $(".selected-date").html(toDateString);
//                            $(".selected-date-class").val(dateValue);
//                            $("#time-container").show();
//                            var entertainerID = '.Yii::$app->user->identity->id.';
//                            $("#modal-schedule-content").modal("show");
//                            $.ajax({
//                                url: App.base_path + "entertainers/get-busy-schedule",
//                                type: "POST",
//                                    data: { date: dateValue, entertainer_id:entertainerID},
//                                    success: function(data) {
//                                    $(".busy-schedule-table tbody").html(data);
//                                    }
//                            });
//                            
//                        }'
//                    ),
//                    'dayRender' => new \yii\web\JsExpression('
//                        function (date, cell) {
//                            var check = $.fullCalendar.formatDate(date,"YYYY-MM-DD");
//                            console.log(check);
//                            if (moment().diff(date,"days") > 0){
//                                //cell.css("background-color","red");
//                            }else{
//                                //cell.css("background-color", "green");
//                            }
//                        }'
//                    ),
//                ],
//            ));

        $str .= Html::endTag('div');
        $str .= Html::beginTag('div', ['class' => 'col-lg-8 col-md-8 col-sm-12 col-xs-12']);
        //$str .= $this->drawOrders($userData->id);
        $str .= 'Orders';
        $str .= Html::endTag('div');
        $str .= Html::endTag('div');

        return $str;
    }
    
    public function drawOrders($foodID) {
        $foodOrders = Orders::find()
                ->select([
                    'tbl_orders.id AS order_id',
                    'tbl_orders.customer_id AS customer_id',
                    'tbl_orders.entertainer_id AS entertainer_id',
                    'tbl_orders.entertainer_package_id AS entertainer_package_id',
                    'tbl_orders.food_id AS food_id',
                    'tbl_orders.event_date AS event_date',
                    'tbl_orders.start_time AS start_time',
                    'tbl_orders.end_time AS end_time',
                    'tbl_orders.special_request AS special_request',
                    'tbl_orders.status AS status',
                    'tbl_orders.price AS price',
                    'tbl_orders.price_type AS price_type',
                    'tbl_orders.venue_address',
                    'tbl_food.name AS food_name',
                    'tbl_food.description AS food_description',
                    'tbl_food.rating AS food_rating',
                    'tbl_food_items.price AS food_price'
                ])
                ->leftJoin('tbl_food_items', 'tbl_food_items.id = tbl_orders.food_id')
                ->leftJoin('tbl_food', 'tbl_food.id = tbl_food_items.food_id')
                ->where(['tbl_food.user_id' => $foodID])
                ->asArray()
                ->all();
        

        $str = '';

        foreach ($foodOrders as $order) {
            $str .= Html::beginTag('div', ['style' => 'margin-bottom: 25px;']);
            $str .= Html::beginTag('h4', ['style' => 'margin-bottom: 25px;', 'class' => 'food-dashboard-order-title']);
            $str .= $order['event_date'] . ' | ' . $order['start_time'] . '-' . $order['end_time'];
            $str .= Html::endTag('h4');
//            if(!empty($order['venue_address'])) {
//                $str .= Html::beginTag('h4',['style'=>'font-weight:bold;']);
//                    $str .= $order['venue_address'];
//                $str .= Html::endTag('h4');
//            }
            
            if ($order['special_request']) {
                $str .= Html::beginTag('h4', ['class' => 'food-dashboard-order-title']);
                $str .= 'Special requests';
                $str .= Html::endTag('h4');
                $str .= Html::beginTag('p', ['style' => 'margin-bottom: 35px;']);
                $str .= $order['special_request'];
                $str .= Html::endTag('p');
            }

            $orderID = $order['order_id'];
            $str .= Html::beginTag('h4', ['class' => 'food-dashboard-order-title', 'style' => 'margin-top: 30px;']);
                $str .= (isset($order['service_name'])) ? $order['service_name'] : '';
            $str .= Html::endTag('h4');
            $str .= Html::beginTag('h4', ['style' => 'margin-bottom: 25px;']);
                $str .= 'Guests:';
            $str .= (!empty($order['count_of_guests'])) ? $order['count_of_guests'] : '';
            $str .= Html::endTag('h4');

            if (isset($order['food_price'])) {
                $str .= Html::beginTag('h4');
                $str .= 'Price: ';
                $str .= (isset($order['food_price'])) ? '?? ' . $order['food_price'] : '';
                $str .= Html::endTag('h4');
            }
            
//            $time1 = strtotime($order['start_time']);
//            $time2 = strtotime($order['end_time']);
//            $orderDuration = round(abs($time2 - $time1) / 3600, 2);
//            $totalPrice = $orderDuration * $order['food_price'];
            
            $str .= Html::beginTag('hr', ['class' => 'solid-line']);
            $str .= Html::beginTag('h4');
            $str .= 'Total price: ' . $order['food_price'] . ' ??';

            $str .= Html::endTag('h4');
            if ($order['status'] == 'pending') {
                $str .= Html::beginTag('div', ['class' => 'row']);
                    $str .= Html::beginTag('div', ['class' => 'col-lg-2 col-md-2 col-sm-12 col-xs-12']);
                        $str .= Html::beginTag('div', ['class' => 'form-group']);
                        $str .= Html::button('Accept', ['class' => 'btn btn-primary food_accept_btn', 'order_id' => $orderID]);
                        $str .= Html::endTag('div');
                        $str .= Html::endTag('div');
                        $str .= Html::beginTag('div', ['class' => 'col-lg-2 col-md-2 col-sm-12 col-xs-12']);
                        $str .= Html::beginTag('div', ['class' => 'form-group']);
                        $str .= Html::button('Decline', ['class' => 'btn btn-danger food_decline_btn', 'order_id' => $orderID]);
                        $str .= Html::endTag('div');
                    $str .= Html::endTag('div');
                $str .= Html::endTag('div');
            } elseif ($order['status'] == 'Canceled') {
                $str .= Html::beginTag('div', ['class' => 'row']);
                $str .= Html::beginTag('div', ['class' => 'col-lg-2 col-md-2 col-sm-12 col-xs-12']);
                $str .= 'canceled';
                $str .= Html::endTag('div');
                $str .= Html::endTag('div');
            } elseif ($order['status'] == 'Approved') {
                $str .= Html::beginTag('div', ['class' => 'row']);
                $str .= Html::beginTag('div', ['class' => 'col-lg-2 col-md-2 col-sm-12 col-xs-12']);
                $str .= 'approved';
                $str .= Html::endTag('div');
                $str .= Html::endTag('div');
            }
            $str .= Html::endTag('div');
        }
        return $str;
    }
}
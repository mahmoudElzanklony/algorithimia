<?php

use PHPUnit\Framework\TestCase;

require_once 'User.php';
require_once 'Address.php';
require_once 'Shipping.php';
require_once 'Products.php';
require_once 'Shipping.php';
require_once 'Order.php';


class OrderTest extends TestCase
{
    public function testOrderHaveReceipt(){
        $address = new Address('cairo','el tahrir','egypt');


        $shipping = new Shipping('aramex',10,13);

        $product = new Products('t-shirt',30,'men',['size' => 'small','color' => 'red'],50);


        $user = new User('ahmed mohamed',$address);


        $order = new Order([$product],$user,$shipping,'','pending',0.02,$product->get_price(),0);

        $order->change_status('delivering');
        $print_receipt = $order->print_receipt();
        $this->assertEquals('total price : 83.16 #|# user name : ahmed mohamed #|# product name : t-shirt category : men price : 60 #|# size small #|# color red',$print_receipt);

        /*
         * i'm sorry for not complete the task because now days is delivery of system to a client working on it more than one year so i dont have much time to
         * end this task as well as possible and the next task of laravel i already this before if you see my github you will see what you want implemented in many projects
         * and maybe i will not accepted because of these reasons but make sure i did what i can
         * thank you :"))
         * */
    }
}

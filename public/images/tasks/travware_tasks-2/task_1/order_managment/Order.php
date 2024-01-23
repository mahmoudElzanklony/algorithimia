<?php

class Order
{
    private $products;
    private $user;
    private $shipping;
    private $inv_num;
    private $status;
    private $tax;
    private $price;
    private $is_done;

    public function __construct(Products $products , User $user , Shipping $shipping , $inv_num  , $status , $tax , $price , $is_done)
    {
        $this->products = $products;
        $this->user = $user;
        $this->shipping = $shipping;
        $this->inv_num = $inv_num;
        $this->status = $status;
        $this->tax = $tax;
        $this->price = $price;
        $this->is_done = $is_done;
    }

    public function change_status($status,$extra = false){
        if ($status == 'pending') {
            $this->o_status = 'pending';
        }
        elseif ($status == 'accepted') {
            $this->o_status = 'accepted';
            $this->o_user->notify("your order accepted");
            $t = 0;
            foreach ($this->p_products as $product){
                $t = $product->calculate_price();
            }
            if ($extra){
                $tax = $this->s_tax * 2;
                $tax = $tax + ($this->o_price * $tax);
                if ($tax == 0){
                    $_tax = 1;
                    $this->o_price *= $_tax;
                }
            }
            $this->o_price = $t;
        }
        elseif ($status == 'processing') {
            $this->o_status = 'processing';
            $this->o_user->notify("your order is processing");
        }
        elseif ($status == 'delivering') {
            $this->o_status = 'delivering';
            $this->o_user->notify("your order is delivering");
            $this->o_inv_num = rand(1,10);
            $this->p_shipping->notify('we have order, we need to delivery it');
            $t = 0;
            foreach ($this->p_products as $product){
                $t = $product->calculate_price();
            }
            $this->o_price = $t;
            $tax = $this->s_tax + $this->p_shipping->calculate_tax($this->o_user->address);
            $this->o_price += $tax + $this->p_shipping->s_cost ;
        }
        elseif ($status == 'received') {
            $this->o_status = 'pending';
            $this->o_is_done = true;
        }
        elseif ($status == 'rejected') {
            $this->o_status = 'pending';
            $this->o_user->notify("your order is rejected");
        }
        else {
            throw new exception("internal error ");
        }
    }

    public function print_receipt(){
        $receipt = '';
        if ($this->o_status == 'delivering'){
            $receipt .= "total price : " . $this->o_price
                     . ' #|# user name : ' . $this->o_user->name;
            foreach ($this->p_products as $product){
                $receipt .= ' #|# product name : ' . $product->_name
                         . ' category : ' . $product->_category
                         . ' price : ' . $product->_price;
                foreach ($product->_attributes as $key => $_attribute){
                    $receipt .= ' #|# ' . $key . ' ' . $_attribute;
                }
            }
            return $receipt;
        }
        else{
            throw new Exception("internal error ");
        }
    }
}

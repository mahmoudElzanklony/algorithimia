<?php

use interfaces\RequiredActions;
use traits\Exceptions;
class Shipping implements RequiredActions
{
    use Exceptions;
    private $name;
    private $cost;
    private $tax;

    public function __construct($name,$cost,$tax)
    {
        $this->name = $name;
        $this->cost = $cost;
        $this->tax = $tax;
    }

    // ------------------getters----------------------
    public function get_name()
    {
        return $this->name;
    }
    public function get_cost()
    {
        return $this->cost;
    }
    public function get_tax()
    {
        return $this->tax;
    }

    public function calculate_tax($address){
        try {
            $tax = $this->make_actions()[$this->get_name()][$address->get_country()]['tax'];
            $this->set_tax($tax);
        }catch (Exception $e){
            return $this->handleError($e->getMessage());
        }
        /*if ($this->get_name() == 'aramex'){
            if ($address->o_country == 'egypt'){
                return $this->s_tax + .14;
            }
            elseif ($address->o_country == 'kuwait'){
                return $this->s_tax + .1;
            }
        }
        elseif($this->get_name() == 'fedex'){
            if ($address->o_country == 'egypt'){
                return $this->s_tax + .20;
            }
            elseif ($address->o_country == 'kuwait'){
                return $this->s_tax + .13;
            }
        }*/
    }

    public function make_actions()
    {
        // actions data and this should be stored at table database
        $actions = [
            'aramex'=>[
                'egypt'=>['tax'=>.14],
                'kuwait'=>['tax'=>.1],
            ],
            'fedex'=>[
                'egypt'=>['price'=>.20],
                'kuwait'=>['price'=>.13],
            ],
        ];
        return $actions;
    }

    public function notify($message){
        /**
         * TODO
         * we need to add channel to send notification to shipping company but not now
         */
    }


}

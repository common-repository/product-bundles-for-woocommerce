<?php

if (!defined('ABSPATH'))
    exit;


if (!class_exists('OCPBW_comman') ) {
    class OCPBW_comman {
        protected static $instance;
       
        function init() {
            global $ocpbw_comman;
            $optionget = array(
                'position_of_product'=>'above_add_to_cart',
                'show_thumbilee'=>'yes',
                'show_quntity'=>'yes',
                'show_description'=>'yes',
                'show_price'=>'yes',
                'show_title'=>'yes',
            );
           
            foreach ($optionget as $key_optionget => $value_optionget) {
               $ocpbw_comman[$key_optionget] = get_option( $key_optionget,$value_optionget );
            }
        }
        

       	public static function instance() {

            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
            return self::$instance;

        }


    }
    
    OCPBW_comman::instance();
}

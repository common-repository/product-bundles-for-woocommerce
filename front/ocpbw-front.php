<?php

if (!defined('ABSPATH'))
    exit;

if (!class_exists('OCPBW_front')) {

    class OCPBW_front {

        protected static $instance;

        function ocpbw_before_add_to_cart_btn(){ 

            global $ocpbw_comman;
            $productId = get_the_ID();
            $product = wc_get_product( $productId );
            /*echo "<pre>";
            print_r($product->get_type());
            echo "</pre>";*/
            if ($product->get_type() == 'ocpbw') {
                $product = get_post_meta( get_the_ID(), 'ocpbw_select2', true );
                $ocpbw_discunt = get_post_meta( get_the_ID(), 'ocpbw_per_qunty', true );
                $ocpbw_discunt_type = get_post_meta( get_the_ID(), 'ocpbw_discount_type', true );
                if(empty($product)) {
                  return;
                }
                $count  = 0;
                $badge ='';
                $product_details = '';
                $total= 0;
                $images = '';
                foreach ($product as $productId) {
                    // print_r($productId);
                    $product = wc_get_product( $productId );
                    $current_product_link =  $product->get_permalink();
                    $current_product_image = $product->get_image();
                    $current_product_title = $product->get_title();
                    $current_product_price = $product->get_price();
                    $current_product_id = $product->get_id();
                    $current_product_is_variation   = $product->is_type( 'variation' );
                    $dis_type = get_post_meta( get_the_ID(), 'ocpbw_discount_type' );
                    $Quntity_product = get_post_meta( get_the_ID(), 'ocpbw_per_qunty' );
                    $images .= '<td class="ocpbw_img sss" image_pro_id="'.$current_product_id.'"><div class="ocpbw_img_div"><a href="' . $current_product_link . '">' . $current_product_image . '</a>'.$badge.'</div></td>';
                    ob_start();
                    ?>
                    <div class="ocpbw_each_item <?php if($count == 0) { echo 'ocpbw_each_curprod'; } ?>">

                        <?php  if($ocpbw_comman['show_thumbilee'] == "yes"){?>
                            <div class="ocpbw_product_image">
                                <?php echo $product->get_image(); ?>
                            </div>
                        <?php }?>
                        <?php  if($ocpbw_comman['show_title'] == "yes"){?>
                            <div class="ocpbw_product_title">
                                <span>
                                    <?php
                                        if($ocpbw_comman['show_quntity'] == "yes"){
                                            echo ''.$Quntity_product[0][$current_product_id].' x <a href="'.$current_product_link.'">'.$current_product_title.'</a>';
                                        }else{
                                            echo ' <a href="'.$current_product_link.'">'.$current_product_title.'</a>';
                                        }

                                    ?>
                                </span>
                                <?php
                                    if( $current_product_is_variation ) {
                                        $attributes = $product->get_variation_attributes();
                                        $variations = array();

                                        foreach( $attributes as $key => $attribute ) {
                                            $variations[] = $attribute;
                                        }

                                        if( ! empty( $variations ) )
                                        echo '<span class="product-attributes"> &ndash; ' . implode( ', ', $variations ) . '</span>';
                                    }
                                ?>
                            </div>
                       <?php }?>

                        <?php  if($ocpbw_comman['show_price'] == "yes"){?>
                            <div class="ocpbw_product_price">
                                <?php 
                                    if(!empty($product->get_price())) { 
                                        $price = wc_price($product->get_price()); 
                                    }else { 
                                        $price = wc_price(0);
                                    }
                                    echo '<span class="ocpbw_price_old">' . $price . '</span>';
                                ?>
                            </div>
                        <?php }?>
                         <?php  if($ocpbw_comman['show_description'] == "yes"){?>
                            <div class="ocpbw_product_discription">
                                <p><?php echo $product->get_short_description(); ?></p>
                               
                            </div>
                        <?php }?>
                    </div>
                    <?php
                    $product_details .= ob_get_clean();

                }?>
                <input type="hidden" name="formate" value="<?php echo get_woocommerce_currency_symbol(); ?>" class="formate">
              
                <div class="ocpbw_main layout1">
                    <?php if (!empty(get_post_meta( get_the_ID(), 'above_text', true ))){ ?>
                        <p class="top_bottom_text"><?php echo get_post_meta( get_the_ID(), 'above_text', true ); ?></p>
                    <?php } ?>
                    <div class="ocpbw_div">
                        <?php echo $product_details; ?>
                    </div>
                    <?php if (!empty(get_post_meta( get_the_ID(), 'bottom_text', true ))){ ?>
                        <p class="top_bottom_text"><?php echo get_post_meta( get_the_ID(), 'bottom_text', true ); ?></p>
                    <?php } ?>
                    
                </div>
                <?php
            }
        }


        function ocpbw_add_to_cart_form() {

            wc_get_template( 'single-product/add-to-cart/simple.php' );
        }

        function ocpbw_get_price_html( $price, $product ) {


            $product_id = $product->get_id();
           

            if ( $product->is_type( 'ocpbw' ) && ( $items = $product->get_items() ) ) {

                 $discount_custom_amount = stripslashes( get_post_meta( $product_id, 'discount_price', true ) );
                if ( ! empty($discount_custom_amount) ) {
                    $discount_amount     = $product->get_discount_amount();
                    $ocpbw_discount_type = $product->get_discount_type();
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();

                        if ( !empty($discount_amount )) {
                            if($ocpbw_discount_type == "fixed"){
                                if ($sale_price == "") {
                                    $price_sale = $regular_price - $discount_amount;
                                }elseif ($sale_price != "") {
                                    $price_sale = $sale_price - $discount_amount;
                                }
                            }else{
                                if ($sale_price == "") {
                                    $price_sale = round( $regular_price * ( 100 - $discount_amount ) / 100, wc_get_price_decimals() );
                                }elseif ($sale_price != "") {
                                    $price_sale = round( $sale_price * ( 100 - $discount_amount ) / 100, wc_get_price_decimals() );
                                }                                
                            }

                        }

                        if ( !empty($price_sale )) {

                            return wc_format_sale_price( $price, wc_price( $price_sale ) );

                        }

                        return wc_price( $price );
                 }
            }

            return $price;
        }

        
        function ocpbw_add_cart_item_data( $cart_item_data, $product_id ) {
            // $_product = wc_get_product( $product_id );
            $ocpbw_drag_product = get_post_meta( $product_id, 'ocpbw_select2', true );

            if(!empty($ocpbw_drag_product)) {

                $ocpbw_combo_ids =  $this->ocpbw_recursive_sanitize_text_field($ocpbw_drag_product);

                if ( ! empty( $ocpbw_combo_ids ) ) {

              
                    $cart_item_data['combo_ids'] = $ocpbw_combo_ids;

                
                }
            }

    
            return $cart_item_data;
        }


        function ocpbw_recursive_sanitize_text_field($array) {
         
            foreach ( $array as $key => &$value ) {
                if ( is_array( $value ) ) {
                    $value = $this->ocpbw_recursive_sanitize_text_field($value);
                }else{
                    $value = sanitize_text_field( $value );
                }
            }
            return $array;
        }


        function ocpbw_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation,$cart_item_data ) {


            $product = wc_get_product( $product_id );
           

            if ( $product->is_type( 'ocpbw' ) && ( $items = $product->get_items() ) ) {

                $ocpbw_add_current_product_discunt = get_post_meta( $product_id, 'ocpbw_per_qunty', true );
               
                WC()->cart->cart_contents[ $cart_item_key ]['ocpbw_key'] = $cart_item_key;


                $get_discount_amount  = WC()->cart->cart_contents[ $cart_item_key ]['data']->get_discount_amount();
                $get_discount_type   = WC()->cart->cart_contents[ $cart_item_key ]['data']->get_discount_type();
               
             
                if ( isset( $cart_item_data['combo_ids'] ) && ( $cart_item_data['combo_ids'] !== '' ) ) {
               

                    $fbtfwitems = $cart_item_data['combo_ids'];

                   remove_action( 'woocommerce_add_to_cart', array( $this, 'ocpbw_add_to_cart' ), 10, 6 );
                        $_data = array(
                            'fbtfw_parent_id' =>  $product_id,
                            'fbtfw_parent_key' => $cart_item_key,
                            'get_discount_amount'=> $get_discount_amount,
                            'get_discount_type'     => $get_discount_type
                        );

                    foreach ($fbtfwitems as $keya => $valuea) {
                        $occp_product = wc_get_product( $valuea );
                        if ( $occp_product) {
                            $cart_item_keya = WC()->cart->add_to_cart( $valuea, $ocpbw_add_current_product_discunt[$valuea], 0, array(), $_data);
                      
                            if ( $cart_item_keya ) {
                                WC()->cart->cart_contents[ $cart_item_key ]['fbtfw_child_keys'][] = $cart_item_keya;
                            }
                        }
                    }
                }
            }
        }



        function ocpbw_add_custom_price( $cart_object ) { 
            
            $cart_contents = WC()->cart->get_cart();

            foreach (  $cart_contents as $key => $value ) {

                if( isset( $value["combo_ids"])) {

                    $product = wc_get_product($value["product_id"]);
                    if ($product->get_type() == 'ocpbw') {
                        $regular_price = $product->get_regular_price();
                        $discount_amount     = $product->get_discount_amount();
                        $ocpbw_discount_type = $product->get_discount_type();
                        $sale_price = $product->get_sale_price();

                        if($ocpbw_discount_type == "fixed"){

                            if(!empty($discount_amount )){

                                if ($sale_price == "") {
                                    $price_sale = $regular_price - $discount_amount;
                                }elseif ($sale_price != "") {
                                    $price_sale = $sale_price - $discount_amount;
                                }

                            }

                        }else{

                            if(!empty($discount_amount )){

                                if ($sale_price == "") {
                                    $price_sale = round( $regular_price * ( 100 -  $discount_amount ) / 100, wc_get_price_decimals() );
                                }elseif ($sale_price != "") {
                                    $price_sale = round( $sale_price * ( 100 -  $discount_amount ) / 100, wc_get_price_decimals() );
                                }
                            }
                
                        }
                        if(!empty($price_sale)){

                            $value['data']->set_price($price_sale);  

                        }
                    }
                             
                }
                     
                  

                if( isset( $value["fbtfw_parent_id"] ) ) {

                    $value['data']->set_price( 0 );
                }
            
            }
            
        }

        
        function ocpbw_cart_item_name( $name, $cart_item ) {
            if ( isset( $cart_item['fbtfw_parent_id'] ) && ! empty( $cart_item['fbtfw_parent_id'] )  ) {
                if ( ( strpos( $name, '</a>' ) !== false ) ) {
                    return '<a href="' . get_permalink( $cart_item['fbtfw_parent_id'] ) . '">' . get_the_title( $cart_item['fbtfw_parent_id'] ) . '</a> &rarr; ' . $name;
                }

                return get_the_title( $cart_item['fbtfw_parent_id'] ) . ' &rarr; ' . strip_tags( $name );
            }

            return $name;
        }

        function ocpbw_cart_item_quantity( $quantity, $cart_item_key, $cart_item ) {
            // add qty as text - not input
            if ( isset( $cart_item['fbtfw_parent_id'] ) ) {
                return $cart_item['quantity'];
            }

            return $quantity;
        }

        function ocpbw_cart_item_remove_link( $link, $cart_item_key ) {
            if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['fbtfw_parent_key'] ) ) {
                $parent_key = WC()->cart->cart_contents[ $cart_item_key ]['fbtfw_parent_key'];

                if ( isset( WC()->cart->cart_contents[ $parent_key ] ) || array_search( $parent_key, array_column( WC()->cart->cart_contents, 'fbtfw_child_keys' ) ) !== false ) {
                    return '';
                }
            }

            return $link;
        }

        

        function ocpbw_cart_item_removed( $cart_item_key, $cart ) {
            if ( isset( $cart->removed_cart_contents[ $cart_item_key ]['fbtfw_child_keys'] ) ) {
                $keys = $cart->removed_cart_contents[ $cart_item_key ]['fbtfw_child_keys'];

                foreach ( $keys as $key ) {
                    $cart->remove_cart_item( $key );

                    if ( ( $new_key = array_search( $key, array_column( $cart->cart_contents, 'fbtfw_child_keys', 'key' ) ) ) !== false ) {
                        $cart->remove_cart_item( $new_key );
                    }
                }
            }
        }

        public function ocpbw_woocommerce_cart_item_price( $price, $cart_item, $cart_item_key ) {
          
            if ( isset( $cart_item['fbtfw_parent_id'] ) ) {
                 
                if ( isset( $cart_item['fbtfw_parent_key']  ) ) {
                    return '';
                }
            }

            return $price;
        }


        public function ocpbw_bundles_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {
            if ( isset( $cart_item['fbtfw_parent_id'] ) ) {
           
                if ( isset( $cart_item['fbtfw_parent_key']  ) ) {
                    return '';
                }
            }

            if ( isset( $cart_item['bundled_items'] ) ) {
                if ( $cart_item['data']->get_price() == 0 ) {
                    return '';
                }
            }
           
            return $subtotal;
        }

        
    
        function init() {
            
            global $ocpbw_comman;

            if($ocpbw_comman['position_of_product'] == "above_add_to_cart"){

                 add_action( 'woocommerce_before_add_to_cart_button', array($this , 'ocpbw_before_add_to_cart_btn') ); 

            }else if($ocpbw_comman['position_of_product'] == "under_add_to_cart"){

                 add_action( 'woocommerce_after_add_to_cart_button', array($this , 'ocpbw_before_add_to_cart_btn') ); 


            }else if($ocpbw_comman['position_of_product'] == "above_title"){

                add_action( 'woocommerce_single_product_summary', array($this , 'ocpbw_before_add_to_cart_btn'), 1  );
                
            }

            add_action( 'woocommerce_ocpbw_add_to_cart', array( $this, 'ocpbw_add_to_cart_form' ) );  
            add_filter( 'woocommerce_add_cart_item_data', array( $this, 'ocpbw_add_cart_item_data' ), 10, 3 );
            add_action( 'woocommerce_add_to_cart', array( $this, 'ocpbw_add_to_cart' ), 10, 6 );
            add_action( 'woocommerce_before_calculate_totals', array($this, 'ocpbw_add_custom_price' ));
            add_filter( 'woocommerce_get_price_html', array( $this, 'ocpbw_get_price_html' ), 99, 2 );
            add_filter( 'woocommerce_cart_item_name', array( $this, 'ocpbw_cart_item_name' ), 10, 2 );
            add_filter( 'woocommerce_cart_item_quantity', array( $this, 'ocpbw_cart_item_quantity' ), 10, 3 );
            add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'ocpbw_cart_item_remove_link' ), 10, 2 );
            add_action( 'woocommerce_cart_item_removed', array( $this, 'ocpbw_cart_item_removed' ), 10, 2 );
            add_filter( 'woocommerce_cart_item_price', array( $this, 'ocpbw_woocommerce_cart_item_price' ), 99, 3 );
            add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'ocpbw_bundles_item_subtotal' ), 99, 3 );
            add_filter( 'woocommerce_checkout_item_subtotal', array( $this, 'ocpbw_bundles_item_subtotal' ), 10, 3 );
           
        
        }
  
        public static function instance() {

            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
            return self::$instance;
        }

    }

    OCPBW_front::instance();
}


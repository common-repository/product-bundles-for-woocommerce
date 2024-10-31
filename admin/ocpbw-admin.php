<?php

if (!defined('ABSPATH'))
    exit;


if (!class_exists('OCPBW_menu') && class_exists( 'WC_Product' )) {
    
    class OCPBW_menu {
        
        protected static $instance;

        //create menu page here
        function ocpbw_create_menu() {
            add_menu_page('Woocommerce Bundle Product', 'Woo Bundle Product', 'manage_options', 'ocpbw_bundle_product', array($this, 'ocpbw_admin_menu_content'));
        }

        //bundle product menu page option 
        function ocpbw_admin_menu_content() {
            global $ocpbw_comman;?>

            <div class="ocpbw_main">

                    <form method="post" >

                    <div class="ocpbw_main_form">
                    <h2>General Setting For Product Bundle</h2>
                        <?php  wp_nonce_field( 'ocpbw_nonce_action', 'ocpbw_nonce_field' ); ?>

                        <div class="ocwef_cover_div">
                                
                            <table class="ocwef_table">
                                <tbody>
                                    <tr>
                                        <th><?php echo __( 'Position', 'ocpbw' );?></th>
                                        <td>
                                            <select name="ocpbw_comman[position_of_product]" class="regular-text">
                                                <option value="above_add_to_cart" <?php if ($ocpbw_comman['position_of_product'] == "above_add_to_cart" ) { echo 'selected'; } ?> >Above the add to cart </option>
                                                <option value="under_add_to_cart" <?php if ($ocpbw_comman['position_of_product'] == "under_add_to_cart" ) { echo 'selected'; } ?> >Under the add to cart </option>
                                                <option value="above_title" <?php if ($ocpbw_comman['position_of_product'] == "above_title" ) { echo 'selected'; } ?>>Above the Title</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo __( 'Show thumbnail', 'ocpbw' );?></th>
                                        <td>
                                            <input type="checkbox" value="yes" name="ocpbw_comman[show_thumbilee]" <?php if ($ocpbw_comman['show_thumbilee'] == "yes" ) { echo 'checked="checked"'; } ?> >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo __( 'Show Title', 'ocpbw' );?></th>
                                        <td>
                                            <input type="checkbox" value="yes" name="ocpbw_comman[show_title]" <?php if ($ocpbw_comman['show_title'] == "yes" ) { echo 'checked="checked"'; } ?> >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo __( 'Show quantity', 'ocpbw' );?></th>
                                        <td>
                                            <input type="checkbox" value="yes" name="ocpbw_comman[show_quntity]" <?php if ($ocpbw_comman['show_quntity'] == "yes" ) { echo 'checked="checked"'; } ?> >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo __( ' Show short description', 'ocpbw' );?></th>
                                        <td>
                                            <input type="checkbox" value="yes" name="ocpbw_comman[show_description]" <?php if ($ocpbw_comman['show_description'] == "yes" ) { echo 'checked="checked"'; } ?> >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo __( 'Show price', 'ocpbw' );?></th>
                                        <td>
                                            <input type="checkbox" value="yes" name="ocpbw_comman[show_price]" <?php if ($ocpbw_comman['show_price'] == "yes" ) { echo 'checked="checked"'; } ?> >
                                        </td>
                                    </tr>  
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="save_options">
                        <input type="hidden" name="action" value="ocwef_save_option">
                        <input type="submit" value="Save changes" name="submit" class="button-primary" id="wfc-btn-space">
                    </div>
                </form>
            </div>
                <?php 

        }

        //bundle product menu page option save here
        function ocpbw_save_options() {

            if( current_user_can('administrator') ) {

                if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'ocwef_save_option') {

                    if(!isset( $_POST['ocpbw_nonce_field'] ) || !wp_verify_nonce( $_POST['ocpbw_nonce_field'], 'ocpbw_nonce_action' ) ){

                        print 'Sorry, your nonce did not verify.';
                        exit;

                    } else {

                        if(!empty($_REQUEST['ocpbw_comman'])){
                            $isecheckbox = array(
                                'show_thumbilee',
                                'show_quntity',
                                'show_description',
                                'show_quntity',
                                'show_price',
                                'show_title',
                            );

                            foreach ($isecheckbox as $key_isecheckbox => $value_isecheckbox) {
                                if(!isset($_REQUEST['ocpbw_comman'][$value_isecheckbox])){
                                    $_REQUEST['ocpbw_comman'][$value_isecheckbox] ='no';
                                }
                            }
                            
                            foreach ($_REQUEST['ocpbw_comman'] as $key_ocpbw_comman => $value_ocpbw_comman) {
                                update_option($key_ocpbw_comman, sanitize_text_field($value_ocpbw_comman), 'yes');
                            }
                        }

                        wp_redirect( admin_url( '/admin.php?page=ocpbw_bundle_product' ) );
                        exit;
                    }
                }
            }

        }

        //smart bundle product add in filter
        function ocpbw_product_filters( $filters ) {
           
            $filters = str_replace( 'Ocpbw', esc_html__( 'Smart bundle', 'product-bundle-woocommerce' ), $filters );

            return $filters;
        
        }

        //all product type to select smart bundle
        function ocpbw_product_type_selector( $types ) {
            $types['ocpbw'] = esc_html__( 'Smart bundle', 'product-bundle-woocommerce' );

            return $types;
       
        }

        //product data tab create
        function ocpbw_product_data_tabs( $tabs ) {
            $tabs['ocpbw'] = array(
                'label'  => esc_html__( 'Bundled Products', 'woo-product-bundle' ),
                'target' => 'ocpbw_options',
                'class'  => array( 'show_if_ocpbw' ),
            );

            return $tabs;
        
        }

 
        //product bundle data panels option and update
        function ocpbw_product_data_panels() {?>

            <div id="ocpbw_options" class="panel woocommerce_options_panel">
                <div class = 'options_group' >
                    <p class='form-field'>
                        <?php 
                            global $post, $product_object;
                            $product_id = $post->ID;
                            is_null( $product_object ) && $product_object = wc_get_product( $product_id );
                            $to_exclude = array( $product_id );
                        ?>
                        <label><?php _e( 'Add Product', 'woocommerce' ); ?></label>
                        <select id="ocpbw_select_serach_box" name="ocpbw_select2[]" multiple="multiple" style="width:70%;max-width:25em;" except="<?php echo $to_exclude[0]; ?>">
                            <?php 

                                $product = get_post_meta( get_the_ID(), 'ocpbw_select2', true );
                                $ocpbw_selected_product_array = array();
                                $ocpbw_selected_product_ids = array();

                                foreach ($product as $productId) {

                                   // print_r($product);

                                    $product = wc_get_product( $productId );
                                    $ocpbw_add_current_product_title = $product->get_title();
                                    $ocpbw_add_current_product_id = $product->get_id();
                                    $ocpbw_add_current_product_price = $product->get_price();
                                    $ocpbw_add_current_product_is_variation   = $product->is_type( 'variation' ); 
                                    $ocpbw_add_current_product_real_title = $ocpbw_add_current_product_title;

                                        if( $ocpbw_add_current_product_is_variation ) {
                                            $attributes = $product->get_variation_attributes();
                                            $variations = array();

                                            foreach( $attributes as $key => $attribute ) {
                                                $variations[] = $attribute;
                                            }

                                            if( ! empty( $variations ) )
                                            $ocpbw_add_current_product_real_title .= ' - ' . implode( ', ', $variations );
                                        }

                                    $ocpbw_add_current_product_discunt = get_post_meta( get_the_ID(), 'ocpbw_per_qunty', true );
                                    $ocpbw_add_current_product_discunt_type = get_post_meta( get_the_ID(), 'ocpbw_discount_type', true );
                                    $ocpbw_selected_product_ids[] = $ocpbw_add_current_product_id;
                                    $ocpbw_selected_product_array[] = array(
                                        'id'=>$ocpbw_add_current_product_id,
                                        'text'=>$ocpbw_add_current_product_real_title,
                                        'price'=>wc_price($ocpbw_add_current_product_price),
                                        'ocpbw_add_current_product_discunt'=>$ocpbw_add_current_product_discunt[$ocpbw_add_current_product_id],

                                    );
                                    
                                }

                            ?>
                        </select>
                        <Script>
                           var ocpbw_selected_product_array = <?php echo json_encode($ocpbw_selected_product_array);?>;
                           var ocpbw_selected_product_ids = <?php echo json_encode($ocpbw_selected_product_ids);?>;
                        </Script>
                    </p>
                    <p class='form-field'>

                        <label><?php _e( 'Selected', 'woocommerce' ); ?></label>
                        <div class="ocpbw_sortable">

                            <ul id="sortable"> 
                                <?php
                                    $ocpbw_drag_product = get_post_meta( get_the_ID(), 'ocpbw_select2', true );

                                    if(!empty($ocpbw_drag_product)){
                                        foreach ($ocpbw_drag_product as $productId) {
                                            $product = wc_get_product( $productId );
                                            $ocpbw_drag_current_product_id = $product->get_id();
                                            $ocpbw_drag_current_product_title = $product->get_title();                        
                                            $ocpbw_drag_current_product_is_variation = $product->is_type( 'variation' );
                                            $ocpbw_drag_current_product_price = $product->get_price();
                                            if(empty($ocpbw_drag_current_product_price)){
                                                $ocpbw_drag_current_product_price = 0;
                                            }
                                            $ocpbw_drag_current_product_discunt = get_post_meta( get_the_ID(), 'ocpbw_per_qunty', true );
                                            $ocpbw_drag_current_product_discunt_type = get_post_meta( get_the_ID(), 'ocpbw_discount_type', true ); 

                                            ?>

                                            <li class="ui-state-default" id="<?php echo $ocpbw_drag_current_product_id; ?>">
                                                <span class="ocpbw-draggble-icon"></span>
                                                <span class="product-attributes-drop"> 
                                                    <?php echo $ocpbw_drag_current_product_title;                          
                                                        if( $ocpbw_drag_current_product_is_variation ) {
                                                            $attributes = $product->get_variation_attributes();
                                                            $variations = array();

                                                            foreach( $attributes as $key => $attribute ) {
                                                                $variations[] = $attribute;
                                                            }

                                                            if( ! empty( $variations ) )
                                                            echo ' &ndash; ' . implode( ', ', $variations ) ;
                                                        }
                                                    echo ' (' . wc_price($ocpbw_drag_current_product_price) .')';
                                                    ?>
                                                </span>
                                               
                                                <div class="ocpbw_qty_box">
                                                    <input type="hidden" name="ocpbw_drag_ids[]" value="<?php echo $ocpbw_drag_current_product_id; ?>">
                                                    <input type="number" name="ocpbw_per_qunty[<?php echo $ocpbw_drag_current_product_id ?>]" placeholder="Quantity" value="<?php foreach($ocpbw_drag_current_product_discunt as $key => $val){ if($key == $ocpbw_drag_current_product_id){ echo $val; } } ?>">
                                                    
                                                </div>
                                            </li>
                                            <?php 
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                        <p>
                            <?php 
                            $regular_price = $product_object->get_regular_price();
                            $sale_price = $product_object->get_sale_price();
                            if ($sale_price == "") {
                                ?>
                                <?php _e( 'Regular price', 'woocommerce' ); ?> : <?php echo wc_price($regular_price); ?> 
                                <?php
                            }elseif ($sale_price != "") {
                                ?>
                                <?php _e( 'Sale price', 'woocommerce' ); ?> : <?php echo wc_price($sale_price); ?> 
                                <?php
                            }
                            ?>
                             
                        </p>
                    </p>
                    <p class='form-field'>
                        <label><?php _e( 'Discount Price', 'woocommerce' ); ?></label>
                        <input type="number" name="discount_price" value="<?php echo  get_post_meta( get_the_ID(), 'discount_price', true ); ?>">
                        <select name="ocpbw_discount_type" class="ocpbw_discount_type">
                            <?php $valuyyy = get_post_meta( get_the_ID(), 'ocpbw_discount_type', true );  ?>
                            <option value="fixed" <?php if($valuyyy == "fixed"){echo "Selected" ; }?>>Fixed</option>
                            <option value="percentage"  <?php if($valuyyy == "percentage"){echo "Selected" ; }?>>Percentage</option>
                        </select>
                    </p>
                   

                    <p class='form-field'>
                        <label><?php _e( 'Above text', 'woocommerce' ); ?></label>
                        <input type="text" name="above_text" value="<?php echo  get_post_meta( get_the_ID(), 'above_text', true ); ?>">
                    </p>
                    <p class='form-field'>
                        <label><?php _e( 'Bottom text', 'woocommerce' ); ?></label>
                        <input type="text" name="bottom_text" value="<?php echo get_post_meta( get_the_ID(), 'bottom_text', true ); ?>">
                    </p>
                </div>
            </div>
            <?php
        
        }



        //search bundle product filter
        function ocpbw_search_product_ajax(){
      
            $return = array();
            $ocpbwpost_types = array( 'product','product_variation');
            $except = sanitize_text_field($_GET['except']);
         
            $search_results = new WP_Query( array( 
                's'=> sanitize_text_field($_GET['q']), // the search query
                'post_status' => 'publish',
                'post_type' => $ocpbwpost_types,
                'posts_per_page' => -1,
                'post__not_in' => array($except),
                'post_parent__not_in' => array($except),
                'meta_query' => array(
                                    array(
                                        'key' => '_stock_status',
                                        'value' => 'instock',
                                        'compare' => '=',
                                    )
                                )
                ) );
             

            if( $search_results->have_posts() ) :
                while( $search_results->have_posts() ) : $search_results->the_post();   
                    $productc = wc_get_product( $search_results->post->ID );
                    if ( $productc && $productc->is_in_stock() && $productc->is_purchasable() ) {
                        if( !$productc->is_type( 'variable' )) {
                            $title = $search_results->post->post_title;
                            $price = $productc->get_price_html();
                            $return[] = array( $search_results->post->ID, $title, $price);
                        }
                    }
                endwhile;
            endif;
            echo json_encode( $return );
            die;
        
        }

        //array sanitize function
        function ocpbw_recursive_sanitize_text_field( $array ) {
            foreach ( $array as $key => &$value ) {
                if ( is_array( $value ) ) {
                    $value = $this->ocpbw_recursive_sanitize_text_field($value);
                }else{
                    $value = sanitize_text_field( $value );
                }
            }
            return $array;
        
        }

        //save bundle product meta data here
        function ocpbw_save_proddata_custom_fields( $post_id ) {


            $ocpbwselect = $this->ocpbw_recursive_sanitize_text_field($_POST['ocpbw_drag_ids']);
            update_post_meta( $post_id, 'ocpbw_select2', (array) $ocpbwselect );

            $above_text = sanitize_text_field( $_POST['above_text'] );
            update_post_meta( $post_id, 'above_text', $above_text );

            $bottom_text = sanitize_text_field( $_POST['bottom_text'] );
            update_post_meta( $post_id, 'bottom_text', $bottom_text );

            $custom_qunatity = sanitize_text_field( $_POST['custom_qunatity'] );
            update_post_meta( $post_id, 'custom_qunatity', $custom_qunatity );

            $pbwlayout = sanitize_text_field( $_POST['rdlayout'] );
            update_post_meta( $post_id, 'ocpbw_layout', $pbwlayout );

            $discount_price = sanitize_text_field( $_POST['discount_price'] );
            update_post_meta( $post_id, 'discount_price', $discount_price );

            $ocpbw_block_width = sanitize_text_field( $_POST['ocpbw_block_width'] );
            update_post_meta( $post_id, 'ocpbw_block_width', $ocpbw_block_width );

            $pbwassoc_text = sanitize_text_field( $_POST['ocpbw_btassociated_txt'] );
            update_post_meta( $post_id, 'ocpbw_btassociated_txt', $pbwassoc_text );

            $pbw_off_per = $this->ocpbw_recursive_sanitize_text_field($_POST['ocpbw_per_qunty']);
            update_post_meta( $post_id, 'ocpbw_per_qunty', (array) $pbw_off_per );


            $ocpbw_discount_type = sanitize_text_field( $_POST['ocpbw_discount_type'] );
            update_post_meta( $post_id, 'ocpbw_discount_type', $ocpbw_discount_type );
             
        }

        //admin display post is bundle product
        function ocpbw_display_post_states( $states, $post ) {
            if ( 'product' == get_post_type( $post->ID ) ) {
                if ( ( $_product = wc_get_product( $post->ID ) ) && $_product->is_type( 'ocpbw' ) ) {
                    $count = 0;

                    if ( $items = $_product->get_items() ) {
                        $count = count( $items );
                    }

                    $states[] = apply_filters( 'ocpbw_post_states', '<span class="ocpbw-state">' . sprintf( esc_html__( 'Bundle (%s)', 'woo-product-bundle' ), $count ) . '</span>', $count, $_product );
                }
            }

            return $states;
        
        }


        function ocpbw_add_order_item_meta( $order_item, $cart_item_key, $values ) {
            if ( isset( $values['fbtfw_parent_id'] ) ) {
                // use _ to hide the data
                $order_item->update_meta_data( '_fbtfw_parent_id', $values['fbtfw_parent_id'] );
            }

            if ( isset( $values['combo_ids'] ) ) {
                // use _ to hide the data
                $order_item->update_meta_data( '_combo_ids', $values['combo_ids'] );
            }
        
        }


        function ocpbw_ajax_add_order_item_meta( $order_item_id, $order_item, $order ) {
            $quantity = $order_item->get_quantity();

            if ( 'line_item' === $order_item->get_type() ) {
                $product    = $order_item->get_product();
                $product_id = $product->get_id();

                if ( $product && $product->is_type( 'ocpbw' ) && ( $items = $product->get_items() ) ) {
                    // get bundle info
                    


                    $order_id = $order->add_product( $product, $quantity );

                    foreach ( $items as $item ) {
                        $_product = wc_get_product( $item['id'] );
                        $_order_items = $order->get_items( 'line_item' );
                        $_order_item  = $_order_items[ $_order_item_id ];
                        $_order_item->add_meta_data( '_fbtfw_parent_id', $product_id, true );
                        $_order_item->save();
                    }

                    // remove the old bundle
                    if ( $order_id ) {
                        $order->remove_item( $order_item_id );
                    }
                }

                $order->save();
            }
        
        }


        function ocpbw_hidden_order_item_meta( $hidden ) {
           $bbbb =  array_merge( $hidden, array(
                '_fbtfw_parent_id',
                '_combo_ids',
                'fbtfw_parent_id',
                'combo_ids',
                
            ) );
            return $bbbb;
        
        }


        function ocpbw_before_order_item_meta( $order_item_id ) {
            if ( $parent_id = wc_get_order_item_meta( $order_item_id, '_fbtfw_parent_id', true ) ) {
                echo sprintf( esc_html__( '(bundled in %s)', 'woo-product-bundle' ), get_the_title( $parent_id ) );
            }
        
        }

        //rating and review code
        function ocpbw_support_and_rating_notice() {

            $screen = get_current_screen();

            if( 'ocpbw_bundle_product' == $screen->parent_base) {
                ?>
                <div class="ocpbw_ratess_open">
                    <div class="ocpbw_rateus_notice">
                        <div class="ocpbw_rtusnoti_left">
                            <h3>Rate Us</h3>
                            <label>If you like our plugin, </label>
                            <a target="_blank" href="#">
                                <label>Please vote us</label>
                            </a>
                            <label>,so we can contribute more features for you.</label>
                        </div>
                        <div class="ocpbw_rtusnoti_right">
                            <img src="<?php echo OCPBW_PLUGIN_DIR;?>/includes/images/review.png" class="ocpbw_review_icon">
                        </div>
                    </div>
                    <div class="ocpbw_support_notice">
                        <div class="ocpbw_rtusnoti_left">
                            <h3>Having Issues?</h3>
                            <label>You can contact us at</label>
                            <a target="_blank" href="https://www.xeeshop.com/support-us/?utm_source=aj_plugin&utm_medium=plugin_support&utm_campaign=aj_support&utm_content=aj_wordpress">
                                <label>Our Support Forum</label>
                            </a>
                        </div>
                        <div class="ocpbw_rtusnoti_right">
                            <img src="<?php echo OCPBW_PLUGIN_DIR;?>/includes/images/support.png" class="ocpbw_review_icon">
                        </div>
                    </div>
                </div>
                <div class="ocpbw_donate_main">
                   <img src="<?php echo OCPBW_PLUGIN_DIR;?>/includes/images/coffee.svg">
                   <h3>Buy me a Coffee !</h3>
                   <p>If you like this plugin, buy me a coffee and help support this plugin !</p>
                   <div class="ocpbw_donate_form">
                        <a class="button button-primary ocwg_donate_btn" href="https://www.paypal.com/paypalme/shayona163/" data-link="https://www.paypal.com/paypalme/shayona163/" target="_blank">Buy me a coffee !</a>
                   </div>
                </div>
                <?php
            }
        
        }


        function init() {
            // Menu
            add_action( 'admin_menu', array( $this, 'ocpbw_create_menu' ) );

            add_action( 'init',  array($this, 'ocpbw_save_options'));
            // Product filters
            add_filter( 'woocommerce_product_filters', array( $this, 'ocpbw_product_filters' ) );
            // Add to selector
            add_filter( 'product_type_selector', array($this, 'ocpbw_product_type_selector' ) );
            // Product data tabs
            add_filter( 'woocommerce_product_data_tabs', array( $this, 'ocpbw_product_data_tabs' ), 10, 1 );

            add_action( 'woocommerce_product_data_panels',array( $this, 'ocpbw_product_data_panels' ));

            add_action( 'wp_ajax_ocpbw_search_product_ajax', array($this, 'ocpbw_search_product_ajax') );

            add_action( 'woocommerce_process_product_meta', array($this, 'ocpbw_save_proddata_custom_fields') );

            add_action( 'admin_notices', array($this, 'ocpbw_support_and_rating_notice' ));

            add_filter( 'display_post_states', array( $this, 'ocpbw_display_post_states' ), 10, 2 );

            add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'ocpbw_hidden_order_item_meta' ), 10, 1 );

            add_action( 'woocommerce_before_order_itemmeta', array( $this, 'ocpbw_before_order_item_meta' ), 10, 1 );

            add_action( 'woocommerce_checkout_create_order_line_item', array($this,'ocpbw_add_order_item_meta'), 10, 3 );

            add_action( 'woocommerce_ajax_add_order_item_meta', array( $this,'ocpbw_ajax_add_order_item_meta'), 10, 3 );
        
        }

        public static function instance() {

            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
            return self::$instance;

        }

    }
    
    OCPBW_menu::instance();
}


class WC_Product_Ocpbw extends WC_Product {


    public function __construct( $product = 0 ) {
        $this->product_type = 'ocpbw';
        $this->supports[] = 'ajax_add_to_cart';
        parent::__construct( $product );
    }


    public function get_type() {
        return 'ocpbw';
    }


    public function add_to_cart_url() {
        $product_id = $this->id;

         if ( $this->is_purchasable() && $this->is_in_stock()  ) {
            $url = remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $product_id ) );
         } else {
             $url = get_permalink( $product_id );
        }

        return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
    }


    public function add_to_cart_text() {
        $text = esc_html__( 'Add to cart', 'woo-product-bundle' );
        $text = apply_filters( 'woocommerce_product_add_to_cart_text', $text, $this );

            return apply_filters( 'ocpbw_product_add_to_cart_text', $text, $this );
    }


    public function get_discount_amount() {
            $product_id      = $this->id;
            $discount_amount =get_post_meta( $product_id, 'discount_price', true ) ;
            return $discount_amount;
    
    }

    public function get_discount_type() {
        $product_id          = $this->id;
        $ocpbw_discount_type = get_post_meta( $product_id, 'ocpbw_discount_type', true );

        // print_r($ocpbw_discount_type);
        // exit;

        
        return $ocpbw_discount_type;
    
    }

    public function get_ids() {
        
        $product_id = $this->id;

           $combo_id= get_post_meta( $product_id, 'ocpbw_select2', true );
            // foreach ($combo_id as  $value_combo) {
            //     print_r($combo_id);
            //      exit;
            // }

        return $combo_id;
    
    }

    public function get_items() {
        $bundled = array();

        if ( $ids = $this->get_ids() ) {
            $items =  $ids ;
          

            if ( is_array( $items ) && count( $items ) > 0 ) {
                foreach ( $items as $item ) {
                    $data      = explode( '/', $item );
                      // print_r( $data);
           // exit;
                    $bundled[] = array(
                        'id'  => absint( isset( $data[0] ) ? $data[0] : 0 ),
                        'qty' => (float) ( isset( $data[1] ) ? $data[1] : 1 )
                    );
                }
            }
        }

        if ( count( $bundled ) > 0 ) {
            return $bundled;
        }

        return false;
    
    }

}
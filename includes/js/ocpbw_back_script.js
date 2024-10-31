 'use strict';

 jQuery(document).ready(function() {

    jQuery('#ocpbw_select_serach_box').select2({
            data:ocpbw_selected_product_array,
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: 200,
                allowClear: true,
                data: function (params) {
                    
                    return {
                        q: params.term,
                        except: jQuery(this).attr("except"),
                        action: 'ocpbw_search_product_ajax'

                    };
                    
                },
                processResults: function( data ) {
                    var options = [];
                    if ( data ) {
                        jQuery.each( data, function( index, text ) { 
                            options.push( { id: text[0], text: text[1], 'price': text[2]} );
                        });
                    }
                    return {
                        results: options
                    };
                },
                cache: true
        },
        minimumInputLength: 3 
    });
    
    jQuery('#ocpbw_select_serach_box').val(ocpbw_selected_product_ids).trigger('change');;
    jQuery("#ocpbw_select_serach_box").on('change', function (e) { 
    
        var htmla = jQuery("#ocpbw_select_serach_box").select2("data");
        jQuery("#sortable").html("");
        jQuery.each( htmla, function( key, value ) {
          console.log(value);
            if(value.discount_type == "percentage"){ 
                var per = "selected"; 
            }else{
                var perp = "selected"; 
            }
            jQuery("#sortable").append('<li class="ui-state-default" id="'+value.id+'"><span class="ocpbw-draggble-icon"></span><span class="product-attributes-drop">'+value.text+' ('+ value.price +') </span><div class="ocpbw_qty_box"><input type="hidden" name="ocpbw_drag_ids[]" value="'+value.id+'"><input type="number" name="ocpbw_per_qunty['+value.id+']" placeholder="Quantity"  value="'+value.ocpbw_add_current_product_discunt+'"></div></li>');
        });
    }); 

    jQuery( function() {
        jQuery( "#sortable" ).sortable();
        jQuery( "#sortable" ).disableSelection();
    } );

    jQuery('li.general_tab').addClass('show_if_ocpbw');
    jQuery('#general_product_data .pricing').addClass('show_if_ocpbw');
        ocpbw_active_settings();
 });


 function ocpbw_active_settings() {
 //    var vet = jQuery('#product-type').val()
 // alert(vet);
    if (jQuery('#product-type').val() == 'ocpbw') {

      jQuery('li.general_tab').addClass('show_if_ocpbw');
      jQuery('#general_product_data .pricing').addClass('show_if_ocpbw');
      jQuery('._tax_status_field').
          closest('.options_group').
          addClass('show_if_ocpbw');
      jQuery('#_downloadable').
          closest('label').
          addClass('show_if_ocpbw').
          removeClass('show_if_simple');
      jQuery('#_virtual').
          closest('label').
          addClass('show_if_ocpbw').
          removeClass('show_if_simple');

      jQuery('.show_if_external').hide();
      jQuery('.show_if_simple').show();
      jQuery('.show_if_ocpbw').show();

     jQuery('.product_data_tabs li').removeClass('active');
      jQuery('.product_data_tabs li.ocpbw_tab').addClass('active');

      jQuery('.panel-wrap .panel').hide();
      jQuery('#ocpbw_settings').show();

      if (jQuery('#ocpbw_optional_products').is(':checked')) {
       jQuery('.ocpbw_tr_show_if_optional_products').show();
      } else {
        jQuery('.ocpbw_tr_show_if_optional_products').hide();
      }

      if (jQuery('#ocpbw_disable_auto_price').is(':checked')) {
        jQuery('.ocpbw_tr_show_if_auto_price').hide();
      } else {
        jQuery('.ocpbw_tr_show_if_auto_price').show();
      }

      // woosb_change_price();
    } else {
      jQuery('li.general_tab').removeClass('show_if_ocpbw');
      jQuery('#general_product_data .pricing').removeClass('show_if_ocpbw');
      jQuery('._tax_status_field').
          closest('.options_group').
          removeClass('show_if_ocpbw');
      jQuery('#_downloadable').
          closest('label').
          removeClass('show_if_ocpbw').
          addClass('show_if_simple');
      jQuery('#_virtual').
          closest('label').
          removeClass('show_if_ocpbw').
          addClass('show_if_simple');

      jQuery('#_regular_price').prop('readonly', false);
      jQuery('#_sale_price').prop('readonly', false);

      if (jQuery('#product-type').val() != 'grouped') {
        jQuery('.general_tab').show();
      }

      if (jQuery('#product-type').val() == 'simple') {
        jQuery('#_downloadable').closest('label').show();
        jQuery('#_virtual').closest('label').show();
      }
    }
  }
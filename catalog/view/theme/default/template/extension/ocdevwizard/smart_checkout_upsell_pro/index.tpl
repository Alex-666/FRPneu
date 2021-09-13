<div id="<?php echo $_code; ?>-modal-body">
  <!--
  ##==================================================================##
  ## @author    : OCdevWizard                                         ##
  ## @contact   : ocdevwizard@gmail.com                               ##
  ## @support   : http://help.ocdevwizard.com                         ##
  ## @license   : http://license.ocdevwizard.com/Licensing_Policy.pdf ##
  ## @copyright : (c) OCdevWizard. Smart Checkout Upsell Pro, 2018    ##
  ##==================================================================##
  -->
  <div class="modal-heading">
    <?php echo $heading_title; ?>
    <span class="modal-close" onclick="$.magnificPopup.close();"></span>
  </div>
  <div class="modal-body" id="check-data">
    <?php if (isset($text_info) && $hide_info_message == 1) { ?>
    <div class="info-text-block pos-1"><?php echo $text_info; ?></div>
    <?php } ?>
    <?php if ($products) { ?>
    <!-- AJAX PRODUCTS -->
    <div id="<?php echo $_code; ?>-ajax-products">
      <div class="<?php echo $_code; ?>-ajax-products-arrow">
        <button id="ajax-products-arrow-prev" onclick="go_prev();"><?php echo $button_carousel_prev; ?></button>
        <input type="hidden" name="ajax_pagination" value="0" style="display:none;" />
        <input type="hidden" name="ajax_all_products" value="<?php echo $ajax_all_products; ?>" style="display:none;" />
        <button id="ajax-products-arrow-next" onclick="go_next();"><?php echo $button_carousel_next; ?></button>
      </div>
      <div id="<?php echo $_code; ?>-ajax-products-list">
        <?php foreach ($products as $product) { ?>
          <div class="ajax-product">
            <?php if ($popup_widget_show_product_image) { ?>
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a></div>
            <?php } ?>
            <?php if ($popup_widget_show_product_name) { ?>
            <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
            <?php } ?>
            <?php if ($popup_widget_show_product_description) { ?>
            <p><?php echo $product['description']; ?></p>
            <?php } ?>
            <?php if ($popup_widget_show_product_rating) { ?>
              <?php if ($product['rating']) { ?>
              <div class="rating">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                <?php if ($product['rating'] < $i) { ?>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                <?php } else { ?>
                <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                <?php } ?>
                <?php } ?>
              </div>
              <?php } ?>
            <?php } ?>
            <?php if ($product['price'] && $popup_widget_show_product_price) { ?>
            <div class="price">
              <?php if (!$product['special']) { ?>
              <span class="price-new"><?php echo $product['price']; ?></span>
              <?php } else { ?>
              <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
              <?php } ?>
            </div>
            <?php } ?>
            <div class="cart">
              <?php if ($popup_widget_show_product_cart_button) { ?>
              <a onclick="modal_addToCart('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><?php echo $button_cart; ?></a>
              <?php } ?>
              <?php if ($popup_widget_show_product_wishlist_button) { ?>
              <a onclick="modal_addToWishlist('<?php echo $product['product_id']; ?>');"><?php echo $button_wishlist; ?></a>
              <?php } ?>
              <?php if ($popup_widget_show_product_compare_button) { ?>
              <a onclick="modal_addToCompare('<?php echo $product['product_id']; ?>');"><?php echo $button_compare; ?></a>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
    <?php } ?>
    <?php if (isset($text_info) && $hide_info_message == 2) { ?>
    <div class="info-text-block pos-2"><?php echo $text_info; ?></div>
    <?php } ?>
  </div>
  <!-- BUTTONS -->
  <div class="modal-footer">
    <input type="button" onclick="$.magnificPopup.close();" value="<?php echo $button_go_back; ?>" class="close-button-bottom" />
    <input type="button" onclick="location.href='<?php echo $page_link; ?>';" value="<?php echo $button_go_to_page; ?>" class="go-button-bottom" />
  </div>

  <script type="text/javascript">
  function go_prev() {
    maskElement('#<?php echo $_code; ?>-ajax-products-list > .ajax-product', true);
    $('#ajax-products-arrow-prev').next().val(~~$('#ajax-products-arrow-prev').next().val() - 3);
    ajaxProducts('#ajax-products-arrow-prev');
  }

  function go_next() {
    maskElement('#<?php echo $_code; ?>-ajax-products-list > .ajax-product', true);
    var count_div = $('#<?php echo $_code; ?>-ajax-products-list > .ajax-product').length,
        current_part = parseInt($('#<?php echo $_code; ?>-ajax-products input[name=ajax_pagination]').val()),
        all_products = parseInt($('#<?php echo $_code; ?>-ajax-products input[name=ajax_all_products]').val());

    if (count_div < 3) {
      $('#ajax-products-arrow-next').css({'opacity': 0.3, 'cursor' : 'default'}).unbind('onclick');
      maskElement('#<?php echo $_code; ?>-ajax-products-list > .ajax-product', false);
      return;
    } else if (current_part+3 >= all_products) {
      $('#ajax-products-arrow-next').css({'opacity': 0.3, 'cursor' : 'default'}).unbind('onclick');
      maskElement('#<?php echo $_code; ?>-ajax-products-list > .ajax-product', false);
      return;
    } else {
      $('#ajax-products-arrow-next').prev().prev().val(~~$('#ajax-products-arrow-next').prev().prev().val()+3);
    }

    ajaxProducts('#ajax-products-arrow-next');
  }

  function ajaxProducts(target) {
    var input_val = $(target).parent().children('input[name=ajax_pagination]').val(),
        quantity  = parseInt(input_val),
        count_ajax_products = $(target).parent().children('input[name=ajax_all_products]').val();

    $('.<?php echo $_code; ?>-ajax-products-arrow button').css({'opacity': 1, 'cursor' : 'pointer' });

    if (quantity <= -3) {
      $('#ajax-products-arrow-prev').css({'opacity': 0.5, 'cursor' : 'default' });
      quantity = $(target).parent().children('input[name=ajax_pagination]').val(0);
      maskElement('#<?php echo $_code; ?>-ajax-products-list > .ajax-product', false);
      return;
    }

    if (quantity >= $('#<?php echo $_code; ?>-ajax-products input[name=ajax_all_products]').val()) {
      $('#ajax-products-arrow-next').css({'opacity': 0.5, 'cursor' : 'default' });
      quantity = $(target).parent().children('input[name=ajax_pagination]').val($('#<?php echo $_code; ?>-ajax-products input[name=ajax_all_products]').val());
      maskElement('#<?php echo $_code; ?>-ajax-products-list > .ajax-product', false);
      return;
    }

    if (quantity > count_ajax_products) {
      $.ajax({
        url:  'index.php?route=extension/ocdevwizard/<?php echo $_name; ?>&product_id=<?php echo $product_id; ?>&start=0'+'&end=3',
        type: 'get',
        dataType: 'html',
        success: function(data) {
          $(target).parent().children('input[name=ajax_pagination]').val(0);
          $('#<?php echo $_code; ?>-ajax-products-list').html($(data).find('#<?php echo $_code; ?>-ajax-products-list > *'));
        }
      });
    } else {
      $.ajax({
        url:  'index.php?route=extension/ocdevwizard/<?php echo $_name; ?>&product_id=<?php echo $product_id; ?>&start='+quantity+'&end=3',
        type: 'get',
        dataType: 'html',
        success: function(data) {
          $('#<?php echo $_code; ?>-ajax-products-list').html($(data).find('#<?php echo $_code; ?>-ajax-products-list > *'));
        }
      });
    }
  }
  // loadmask function
  function maskElement(element, status) {
    if (status == true) {
      $('<div/>').attr('class', '<?php echo $_code; ?>-modal-loadmask').prependTo(element);
      $('<div class="<?php echo $_code; ?>-modal-loadmask-loading" />').insertAfter($('.<?php echo $_code; ?>-modal-loadmask'));
    } else {
      $('.<?php echo $_code; ?>-modal-loadmask').remove();
      $('.<?php echo $_code; ?>-modal-loadmask-loading').remove();
    }
  }
  <?php if ($popup_widget_show_product_cart_button) { ?>
  function modal_addToCart(product_id, minimum) {
    $.ajax({
      url: 'index.php?route=checkout/cart/add',
      type: 'post',
      data: 'product_id=' + product_id + '&quantity=' + minimum,
      dataType: 'json',
      success: function(json) {
        $('.alert').remove();
        if (json['redirect']) {
          location = json['redirect'];
        }
        if (json['success']) {
          $('#<?php echo $_code; ?>-modal-body .modal-body').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+json['success']+'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          $('#cart > button').html('<i class="fa fa-shopping-cart"></i> ' + json['total']);
          $('#cart > ul').load('index.php?route=common/cart/info ul li');
        }
      }
    });
  }
  <?php } ?>
  <?php if ($popup_widget_show_product_compare_button) { ?>
  function modal_addToCompare(product_id) {
    $.ajax({
      url: 'index.php?route=product/compare/add',
      type: 'post',
      data: 'product_id=' + product_id,
      dataType: 'json',
      success: function(json) {
        $('.alert').remove();
        if (json['success']) {
          $('#<?php echo $_code; ?>-modal-body .modal-body').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+json['success']+'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          $('#compare-total').html(json['total']);
        }
      }
    });
  }
  <?php } ?>
  <?php if ($popup_widget_show_product_wishlist_button) { ?>
  function modal_addToWishlist(product_id) {
    $.ajax({
      url: 'index.php?route=account/wishlist/add',
      type: 'post',
      data: 'product_id=' + product_id,
      dataType: 'json',
      success: function(json) {
        $('.alert').remove();
        if (json['success']) {
          $('#<?php echo $_code; ?>-modal-body .modal-body').prepend('<div class="alert alert-success">'+json['success']+'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        if (json['info']) {
          $('#<?php echo $_code; ?>-modal-body .modal-body').prepend('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json['info'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        $('#wishlist-total span').html(json['total']);
        $('#wishlist-total').attr('title', json['total']);
      }
    });
  }
  <?php } ?>
  </script>
</div>

/*jslint browser: true*/
/*global $, jQuery, Modernizr, enquire, audiojs*/

(function($) {
  // Pagination Ajax.
  var pagination_ajax = function () {
    var parent_views = $(this).parents('.views');
    var name = parent_views.find('input[name="name"]').val();
    var post_type = parent_views.find('input[name="post_type"]').val();
    var per_page = parent_views.find('input[name="per_page"]').val();
    var cat_id = parent_views.find('input[name="cat_id"]').val();
    var custom_fields = parent_views.find('input[name="custom_fields"]').val();
    var use_pagination = parent_views.find('input[name="use_pagination"]').val();
    var paged_index = $(this).parent('li').attr('data-value');
    //alert(name);
    $(this).parents('ul.pager').find('> li').removeClass('current');
    $(this).parent('li').addClass('current');

    $.ajax({
      type : "post",
      dataType : "json",
      url : paginationAjax.ajaxurl,
      data : {action: "pagination", name: name, post_type: post_type, per_page: per_page, cat_id: cat_id, custom_fields: custom_fields, paged_index: paged_index, use_pagination: '' },
      beforeSend: function() {
        //parent_views.find('.load-views').empty();
        parent_views.find('.tool-pagination.ajax-pagination').before('<div class="ajax-load-icon">load items</div>');
      },
      success: function(response) {
        parent_views.find('.ajax_views').fadeOut("normal", function() {
          $(this).remove();
        });
        parent_views.find('.load-views').fadeOut("normal", function() {
          $(this).remove();
        });
        parent_views.find('.ajax-load-icon').fadeOut("normal", function() {
          $(this).remove();
        });
        parent_views.find('.tool-pagination.ajax-pagination').before('<div class="load-views"></div>');
        parent_views.find('.load-views').fadeIn("normal", function() {
          $(this).html(response.markup);
        });
        $("select").chosen();
        popupDownload();
      },
      error: function(response) {

      }
    });

    return false;

  };

  $(document).ready(function() {
    $('.ajax-pagination .pager-item a').on('click', pagination_ajax);
    $(".box-slide").slick({
      autoplay: true,
      autoplaySpeed: 3000,
      dots: true,
      arrows : false,
      pauseOnHover: false,
    });

    $(".post-related").slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 3000,
      dots: false,
      arrows : false,
      pauseOnHover: true,
      responsive: [
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });

    $("#hisella-minimize").click(function(e) {
      if($(this).find('span').hasClass('icon-circle-down')){
        $(this).find('span').removeClass('icon-circle-down').addClass('icon-circle-up');
      }
      else {
        $(this).find('span').removeClass('icon-circle-up').addClass('icon-circle-down');
      }

    });

    $('.toggle-nav').click(function(e) {
      $(this).toggleClass('active');
      $('.toggle-nav-mainmenu').toggleClass('active');
      e.preventDefault();
    });

      $(window).load(function(){
      var $postlistmasonry = $('.tin-tuc');
      $postlistmasonry.masonry({
          itemSelector: '.tin-tuc-item'
      });
     });
  });

  $(window).load(function() {
    // Call to function
  });

  $(window).resize(function() {
    // Call to function
  });

})(jQuery);


function wpte_trip_review_ne_ui( content_key ) {

  // Bail if content key is not availibility.
  if( content_key[0] != 'wpte-trip-reviews-form' ) {
    return;
  }
  
  jQuery('#review-date').datepicker({ 
    changeMonth: true,
    changeYear: true, 
    dateFormat: 'yy-mm-dd'
  });

  jQuery('#date-of-experience').datepicker({ 
    changeMonth: true,
    changeYear: true, 
    dateFormat: 'yy-mm-dd' 
  });

  jQuery( 'select.wpte-enhanced-select' ).select2();

  jQuery("#wte-trip-review-rating").rateYo({fullStar: true}).on("rateyo.set", function (e, data) {
      // alert("The rating is set to " + data.rating + "!");
      jQuery( '#wte-trip-review-rating-value' ).val( data.rating );
  });

}
jQuery(document).ready(function($){
  wpte_add_action( 'wpte_after_admin_tab_shown', 'wpte_trip_review_ne_ui', 40 );
});
jQuery(document).ready(function($){ 
  var file_frame;
  var allowed_filetype = [
      "image/jpeg",
      "image/png"
     ];
  $( document ).on( 'click', '#wpte-button-id', function (e) {
    e.preventDefault();
    nonce  = $('#admin_comment_update').val();
    name   = $('#name').val();
    id     = $('#select-trip').val();
    email  = $('#mail').val();
    msg    = $('#msg').val();
    title  = $('#title').val();
    date   = $('#review-date').val();
    experience_date = $('#date-of-experience').val()?$('#date-of-experience').val():'';
    stars  = $('#wte-trip-review-rating-value').val() ? $('#wte-trip-review-rating-value').val() : '';
    client_location = $('#client-location').val()?$('#client-location').val():'';
    upload = $('#image_url').val();
    gallery_images = $("input[name='gallery_images[]']").map(function(){return $(this).val();}).get();
    gallery_max_count = $('.wte-trip-review-max-img-count').val()?$('.wte-trip-review-max-img-count').val():'';
    flag = true;
    if(name == '' || date == '' || msg =='' || id == '' || stars == '' || title == ''){
      flag = false;
      $('#trip-review-form .success').show().html('Required <span class="required">*</span>');
      goToByScroll('#wte-trip-review-success',40);
      return flag;
    }
    if(flag == true){
		jQuery.ajax({
            type: 'post',
            url: WTEAjaxData.ajaxurl,
            data: { 
              action: 'wpte_insert_comment',
              nonce : nonce,
              id : id,
              email : email, 
              title : title, 
              date:date, 
              name : name, 
              msg : msg , 
              stars : stars, 
              experience_date: experience_date,
              client_location:client_location,
              upload : upload,
              gallery_max_count:gallery_max_count,
              gallery_images:gallery_images
            },
            beforeSend: function() {
              $("#loader").fadeIn(500);
            },
            success: function(response) {
              $("#loader").fadeOut(500);
              $('#trip-review-form .success').show().html(response);
            },
            complete: function() {
              $('#name').val('');
              $('#select-trip').val('');
              $('#mail').val('');
              $('#msg').val('');
              $('#title').val('');
              $('input[name="stars"').prop('checked',false);
              $('#image_url').val('');
              $('.commenter-photo img').remove();
              $('#remove-btn').hide();
              $('#upload-btn').val('Upload Photo');
              $('#upload-btn').show();
              $('#date-of-experience').val('');
              $('#review-date').val('');
              $('#client-location').val('');
              $('.wte-trip-review-gallery').html('');
              toastr.success( 'Your response has been recorded Successfully.', 'WP Travel Engine' );
            }
        });
      }
	});
  function goToByScroll(id, offset) {
    if(offset !== ''){
      offset = offset;
    }else{
      offset = 0;
    }
    $('html,body').animate({
        scrollTop: $(id).offset().top - 40
    }, 'slow');
}

  $(document).on( 'click', '#upload-btn', function(e) {
      e.preventDefault();
      var image = wp.media({ 
        title: 'Upload Image',
        multiple: false,
        library : {
          type: allowed_filetype
       },
      }).open()
      .on('select', function(e){
        var uploaded_image = image.state().get('selection').first();
        $('#image_url').val('');
        $('.commenter-photo img').remove();
        $('<img src="'+uploaded_image.toJSON().url+'">').appendTo('.commenter-photo');
        var image_url = uploaded_image.toJSON().id;
        $('#image_url').val(image_url);
      });
      $(this).hide();
      $(this).val("Change Photo");
      $('#remove-btn').show();
  });

  $(document).on( 'click', '#remove-btn', function(e) {
      e.preventDefault();
      $('#image_url').val('');
      $('.commenter-photo img').remove();
      $(this).hide();
      $('#upload-btn').val("Upload Photo");
      $('#upload-btn').show();
  });

  $('.reviews-tab').click(function() {
      $('.reviews-tab').removeClass('nav-tab-active');
      $(this).addClass('nav-tab-active');
      var configuration = $(this).attr('data-id');
      $('.trip-review').hide();
      $('#' + configuration).show();
  });

  $('#review-date').datepicker({ 
    changeMonth: true,
    changeYear: true, 
    dateFormat: 'yy-mm-dd'
  });

  $('#date-of-experience').datepicker({ 
    changeMonth: true,
    changeYear: true, 
    dateFormat: 'yy-mm-dd' 
  });

  $(document).on('click', 'a.trip-review-img-gallery-add', function (e) {
      $this = $(this);
      e.preventDefault();
      if (file_frame)
          file_frame.close();

      file_frame = wp.media.frames.file_frame = wp.media({
          title: $(this).data('uploader-title'),
          button: {
              text: $(this).data('uploader-button-text'),
          },
          library : {
            type: allowed_filetype
         },
          multiple: true
      });
      index_max_count = parseInt($this.parent().find('.wte-trip-review-max-img-count').val());
      file_frame.on('select', function () {
          selection = file_frame.state().get('selection');
          selection.map(function (attachment, i) {
              attachment = attachment.toJSON(),
                  index = index_max_count + (i + 1);
                  if( attachment.sizes){
                      if(   attachment.sizes.thumbnail !== undefined  ) url_image=attachment.sizes.thumbnail.url; 
                      else if( attachment.sizes.medium !== undefined ) url_image=attachment.sizes.medium.url;
                      else url_image=attachment.sizes.full.url;
                  }else{
                      url_image = '';
                  }
              $this.parent().find('.wte-trip-review-gallery').append('<li>'
              +'<input type="hidden" class"trip-gallery-hidden" name="gallery_images[]" value="' + attachment.id + '">'
              +'<img class="wte-tr-image-preview" src="' + url_image + '">'
              +'<div class="trip-gallery-img-action">'
              +'<a class="wte-tr-change-image" href="#" data-uploader-title="Change image" data-uploader-button-text="Change image" title="Change Image"><i class="fas fa-sync-alt"></i></a>'
              +'<a class="wte-tr-remove-image" href="#"><i class="fas fa-trash-alt"></i></a>'
              +'</div>'
              +'</li>');
              $this.parent().find('.wte-trip-review-max-img-count').val(parseInt(index));
              wte_trip_review_gallery_sortable();
          });
      });
      file_frame.open();
     
  });

  $(document).on('click', 'a.wte-tr-change-image', function (e) {
    e.preventDefault();
    var that = $(this);
    if (file_frame)
        file_frame.close();
    file_frame = wp.media.frames.file_frame = wp.media({
        title: $(this).data('uploader-title'),
        button: {
            text: $(this).data('uploader-button-text'),
        },
        library : {
            type: allowed_filetype
         },
        multiple: false
    });
    file_frame.on('select', function () {
        attachment = file_frame.state().get('selection').first().toJSON();
        that.closest('li').find('input:hidden').attr('value', attachment.id);
        that.closest('li').find('img.wte-tr-image-preview').attr('src', attachment.sizes.thumbnail.url);
    });
    file_frame.open();
});

$(document).on('click', '.wte-trip-review-gallery a.wte-tr-remove-image', function (e) {
  e.preventDefault();
  $(this).closest('li').animate({ opacity: 0 }, 200, function () {
    $(this).remove();
  });
});
wte_trip_review_gallery_sortable();
function wte_trip_review_gallery_sortable() {
    if ($('.wte-trip-review-gallery').length) {
      $('.wte-trip-review-gallery').sortable({
        opacity: 0.6,
        stop: function () {
        }
      });
    }
  }
});
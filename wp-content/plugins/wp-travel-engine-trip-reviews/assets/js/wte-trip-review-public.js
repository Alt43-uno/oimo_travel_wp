Dropzone.autoDiscover = false;
jQuery(function ($) {
  $("body").on("click", ".comment-reply-link", function (e) {
    $(".comment-respond .comment-form-wte-trip-review-rating").remove();
    $(".comment-respond .comment-form-comment").append(
      $("<input>", {
        type: "hidden",
        name: "test-demo",
        val: "test-value",
      })
    );
  });

  $("body").on("click", "#cancel-comment-reply-link", function (e) {
    $("#wte-trip-review-template")
      .clone()
      .show()
      .insertAfter($(".comment-form-title"));
  });

  $(".overall-rating-wrap").appear();

  $(document.body).on("appear", ".overall-rating-wrap", function (e, affected) {
    jQuery(".rating-bar").each(function () {
      jQuery(this)
        .find(".rating-bar-inner")
        .animate(
          {
            width:
              jQuery(this).find(".rating-bar-inner").attr("data-percent") + "%",
          },
          2000
        );
    });
  });

  if ($("#date-of-experience").length) {
    if (window.flatpickr) {
      window.flatpickr('#date-of-experience')
    } else {
      $("#date-of-experience").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
      });
    }
  }

  if ($("#commentform").length) {
    jQuery("#commentform")[0].encoding = "multipart/form-data";
    if ($(".input-review-images").length) {
      jQuery("#commentform").addClass("dropzone");
      var form_action_url = jQuery("#commentform").attr("action");
    }
  }

  /**
   * Initialize rating validation outside  image uploader
   */
  if ($("#commentform").length) {
    $("#commentform input[type=submit]").click(function (e) {
      if ($("#commentform").valid()) {
        if (
          $("#commentform")
            .find(".wte-trip-review-rating")
            .find("input[type=hidden]")
            .val() === "0"
        ) {
          $(".comment-rating-field-message").show();
          goToByScroll("#commentform", 10);
          return false;
        }
      }
    });
  }

  if ($(".input-review-images").length) {
    var dynamic_text = $("span.review-upload-image-text").html();
    var dynamic_svg = $("span.review-upload-image-svg").html();
    WPTE_DRPZONE = new Dropzone("#wpte-upload-review-images", {
      addRemoveLinks: true,
      autoProcessQueue: false,
      uploadMultiple: true,
      parallelUploads: 100,
      maxFiles: 20,
      clickable: true,
      url: form_action_url || wtetr_public_js_object.home_url,
      paramName: "gallery",
      addRemoveLinks: true,
      acceptedFiles: ".jpeg,.jpg,.png,.gif",
      dictDefaultMessage: dynamic_text,
      init: function () {
        var WPTE_DRPZONE = this;
        WPTE_DRPZONE.on("addedFile", function (file) {
          //your action on file added
        });
        $("#commentform input[type=submit]").click(function (e) {
          if (WPTE_DRPZONE.files.length == 0 && $("#commentform").valid()) {
            if (
              $("#commentform")
                .find(".wte-trip-review-rating")
                .find("input[type=hidden]")
                .val() === "0"
            ) {
              goToByScroll("#commentform", 10);
              $(".comment-rating-field-message").show();
              return false;
            } else {
              toastr.success(
                "Your review has been submitted for moderation",
                "WP Travel Engine"
              );
            }
          } else {
            e.preventDefault();
            if ($("#commentform").valid()) {
              $(".submit-after-comment-wrap").show();
              WPTE_DRPZONE.processQueue();
            }
            return false;
          }
        });

        this.on("sending", function (file, xhr, formData) {
          // Append all form inputs to the formData Dropzone will POST

          var data = $("#commentform").serializeArray();
          $.each(data, function (key, el) {
            formData.append(el.name, el.value);
          });
        });
      },
      error: function (file, response) {
        $(".submit-after-comment-wrap").hide();
        if ($.type(response) === "string") var message = response;
        //dropzone sends it's own error messages in string
        else var message = response.message;
        file.previewElement.classList.add("dz-error");
        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          node = _ref[_i];
          _results.push((node.textContent = message));
        }
        return _results;
      },
      successmultiple: function (file, response) {
        $(".submit-after-comment-wrap").hide();
        window.location.reload();
      },
      completemultiple: function (file, response) {
        toastr.success(
          "Your review has been submitted for moderation",
          "WP Travel Engine"
        );
        $(".submit-after-comment-wrap").hide();
        window.location.reload();
      }
    });

    $textContainer = $("<span>", {
      class: "file-upload-icon",
    }).prependTo(".dz-button");
    // Create the icon and append it to the text container
    $(dynamic_svg).appendTo(
      $textContainer
    );
  }

  review_rating_star_initializer();

  function review_rating_star_initializer() {
    if ($(document).find(".trip-review-stars").length) {
      $(document)
        .find(".trip-review-stars")
        .each(function () {
          var rating_value = $(this).data("rating-value");
          starSvgIcon = $(this).data("icon-type");
          var starSvgIcon = '<svg width="29" height="28" viewBox="0 0 29 28" stroke="" xmlns="http://www.w3.org/2000/svg"><path d="M13.6636 1.21895C14.0288 0.469865 15.0962 0.469863 15.4614 1.21895L19.0244 8.52811C19.169 8.82468 19.4506 9.03085 19.7769 9.07915L27.7669 10.2617C28.5829 10.3825 28.91 11.3837 28.3227 11.9629L22.527 17.6789C22.2946 17.9081 22.1887 18.2362 22.2432 18.5579L23.6086 26.6191C23.7473 27.4378 22.8855 28.059 22.1526 27.6688L15.0325 23.8773C14.7387 23.7208 14.3863 23.7208 14.0925 23.8773L6.97236 27.6688C6.23947 28.059 5.37772 27.4378 5.51639 26.6191L6.88179 18.5579C6.93629 18.2362 6.83037 17.9081 6.59803 17.6789L0.802323 11.9629C0.214999 11.3837 0.542087 10.3825 1.35811 10.2617L9.34808 9.07915C9.67445 9.03085 9.95599 8.82468 10.1006 8.52812L13.6636 1.21895Z"/></svg>'
          /**
        normalFill: "#fff",
        ratedFill: "#32b67a"
        */
          $(this).rateYo({
            rating: rating_value,
            starSvg: starSvgIcon,
          });
        });
    }
  }

  /**
   * Review Form Rating intialization
   */
  $(document)
    .find(".review-form-rating")
    .each(function () {
      starSvgIcon = $(this).data("icon-type");
      var starSvgIcon_dec = `<svg width="30" height="29" viewBox="0 0 30 29" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M13.6636 1.21895C14.0288 0.469865 15.0962 0.469863 15.4614 1.21895L19.0244 8.52811C19.169 8.82468 19.4505 9.03085 19.7769 9.07915L27.7669 10.2617C28.5829 10.3825 28.91 11.3837 28.3227 11.9629L22.527 17.6789C22.2946 17.9081 22.1887 18.2362 22.2432 18.5579L23.6086 26.6191C23.7473 27.4378 22.8855 28.059 22.1526 27.6688L15.0325 23.8773C14.7387 23.7208 14.3863 23.7208 14.0925 23.8773L6.97236 27.6688C6.23947 28.059 5.37772 27.4378 5.51639 26.6191L6.8818 18.5579C6.93629 18.2362 6.83037 17.9081 6.59803 17.6789L0.802323 11.9629C0.214999 11.3837 0.542087 10.3825 1.35811 10.2617L9.34808 9.07915C9.67445 9.03085 9.95599 8.82468 10.1006 8.52812L13.6636 1.21895Z" stroke="#EBAD34" stroke-linecap="round" stroke-linejoin="round"/></svg>`

      $(this).rateYo({
        starSvg: starSvgIcon_dec,
        fullStar: true,
        ratedFill: "#EBAD34",
        normalFill: "transparent",
        onChange: function (rating, rateYoInstance) {
          $(".comment-rating-field-message").hide();
          if ($(this).parent().find('input[name="stars"]').val(rating) == "") {
          } else {
            $(this).parent().find('input[name="stars"]').val(rating);
          }
        },
      });
    });

  function goToByScroll(id, offset) {
    if (offset !== "") {
      offset = offset;
    } else {
      offset = 0;
    }
    $("html,body").animate(
      {
        scrollTop: $(id).offset().top - offset,
      },
      400
    );
  }

  /** Trip Specific
   * Load More Comment  */
  $(document).on("click", ".wtetr_comment_loadmore", function () {
    var button = $(this);
    parent_post_id = parseInt(button.data("parent-id"));
    cpage = parseInt(button.data("current-page"));
    offset = parseInt(button.data("offset"));
    // decrease the current comment page value

    $.ajax({
      url: wtetr_public_js_object.ajax_url,
      data: {
        action: "wte_trip_review_comments_loadmore", // the parameter for admin-ajax.php
        post_id: parent_post_id, // the current post
        cpage: cpage, // current comment page
        offset: offset, //offset from this value in comment load through ajax
      },
      type: "POST",
      beforeSend: function () {
        // some type of preloader
      },
      success: function (data) {
        if (data) {
          $(data).insertAfter(
            button
              .closest(".review-wrap")
              .find(".comment-list")
              .children("li")
              .last()
          ); // insert comments
        } else {
          button.parent().remove();
        }
        button.parent().remove();
        review_rating_star_initializer();
      },
    });
    return false;
  });

  /** Company Reviews
   * 	Load More Comment
   * 	New Method Description: Added new empty div where content from ajax returned data ais appended adn repeated or first li content is removed,
   *	then new html is added to the ul.
   */
  $(document).on("click", ".wtetr_company_comment_loadmore", function () {
    var button = $(this);
    cpage = parseInt(button.data("current-page"));
    offset = parseInt(button.data("offset"));
    $.ajax({
      url: wtetr_public_js_object.ajax_url,
      data: {
        action: "wte_company_review_comments_loadmore", // the parameter for admin-ajax.php
        cpage: cpage, // current comment page
        offset: offset, //offset from this value in comment load through ajax
      },
      type: "POST",
      beforeSend: function () { },
      success: function (data) {
        if (data) {
          button.parent().find(".wte-review-empty-div").append(data);
          if (button.hasClass("review-last-page")) {
            button
              .parent()
              .find(".wte-review-empty-div")
              .find("li:first-child")
              .remove();
          }

          new_html = button.parent().find(".wte-review-empty-div").html();

          $(new_html).insertAfter(
            button
              .closest(".review-wrap")
              .find(".comment-list")
              .children("li")
              .last()
          ); // insert comments
          button.parent().find(".wte-review-empty-div").html("");
        } else {
          button.parent().remove();
        }
        button.parent().remove();
        review_rating_star_initializer();
      },
    });
    return false;
  });
});

Dropzone.autoDiscover=!1,jQuery(function($){if($("body").on("click",".comment-reply-link",function(e){$(".comment-respond .comment-form-wte-trip-review-rating").remove(),$(".comment-respond .comment-form-comment").append($("<input>",{type:"hidden",name:"test-demo",val:"test-value"}))}),$("body").on("click","#cancel-comment-reply-link",function(e){$("#wte-trip-review-template").clone().show().insertAfter($(".comment-form-title"))}),$(".overall-rating-wrap").appear(),$(document.body).on("appear",".overall-rating-wrap",function(e,affected){jQuery(".rating-bar").each(function(){jQuery(this).find(".rating-bar-inner").animate({width:jQuery(this).find(".rating-bar-inner").attr("data-percent")+"%"},2e3)})}),$("#date-of-experience").length&&(window.flatpickr?window.flatpickr("#date-of-experience"):$("#date-of-experience").datepicker({changeMonth:!0,changeYear:!0,dateFormat:"yy-mm-dd"})),$("#commentform").length&&(jQuery("#commentform")[0].encoding="multipart/form-data",$(".input-review-images").length)){jQuery("#commentform").addClass("dropzone");var form_action_url=jQuery("#commentform").attr("action")}if($("#commentform").length&&$("#commentform input[type=submit]").click(function(e){if($("#commentform").valid()&&"0"===$("#commentform").find(".wte-trip-review-rating").find("input[type=hidden]").val())return $(".comment-rating-field-message").show(),goToByScroll("#commentform",10),!1}),$(".input-review-images").length){var dynamic_text=$("span.review-upload-image-text").html(),dynamic_svg=$("span.review-upload-image-svg").html();WPTE_DRPZONE=new Dropzone("#wpte-upload-review-images",{addRemoveLinks:!0,autoProcessQueue:!1,uploadMultiple:!0,parallelUploads:100,maxFiles:20,clickable:!0,url:form_action_url||wtetr_public_js_object.home_url,paramName:"gallery",addRemoveLinks:!0,acceptedFiles:".jpeg,.jpg,.png,.gif",dictDefaultMessage:dynamic_text,init:function(){var WPTE_DRPZONE=this;WPTE_DRPZONE.on("addedFile",function(file){}),$("#commentform input[type=submit]").click(function(e){return 0==WPTE_DRPZONE.files.length&&$("#commentform").valid()?"0"===$("#commentform").find(".wte-trip-review-rating").find("input[type=hidden]").val()?(goToByScroll("#commentform",10),$(".comment-rating-field-message").show(),!1):void toastr.success("Your review has been submitted for moderation","WP Travel Engine"):(e.preventDefault(),$("#commentform").valid()&&($(".submit-after-comment-wrap").show(),WPTE_DRPZONE.processQueue()),!1)}),this.on("sending",function(file,xhr,formData){var data=$("#commentform").serializeArray();$.each(data,function(key,el){formData.append(el.name,el.value)})})},error:function(file,response){if($(".submit-after-comment-wrap").hide(),"string"===$.type(response))var message=response;else message=response.message;for(file.previewElement.classList.add("dz-error"),_ref=file.previewElement.querySelectorAll("[data-dz-errormessage]"),_results=[],_i=0,_len=_ref.length;_i<_len;_i++)node=_ref[_i],_results.push(node.textContent=message);return _results},successmultiple:function(file,response){$(".submit-after-comment-wrap").hide(),window.location.reload()},completemultiple:function(file,response){toastr.success("Your review has been submitted for moderation","WP Travel Engine"),$(".submit-after-comment-wrap").hide(),window.location.reload()}}),$textContainer=$("<span>",{class:"file-upload-icon"}).prependTo(".dz-button"),$(dynamic_svg).appendTo($textContainer)}function review_rating_star_initializer(){$(document).find(".trip-review-stars").length&&$(document).find(".trip-review-stars").each(function(){var rating_value=$(this).data("rating-value");$(this).data("icon-type");var starSvgIcon='<svg width="29" height="28" viewBox="0 0 29 28" stroke="" xmlns="http://www.w3.org/2000/svg"><path d="M13.6636 1.21895C14.0288 0.469865 15.0962 0.469863 15.4614 1.21895L19.0244 8.52811C19.169 8.82468 19.4506 9.03085 19.7769 9.07915L27.7669 10.2617C28.5829 10.3825 28.91 11.3837 28.3227 11.9629L22.527 17.6789C22.2946 17.9081 22.1887 18.2362 22.2432 18.5579L23.6086 26.6191C23.7473 27.4378 22.8855 28.059 22.1526 27.6688L15.0325 23.8773C14.7387 23.7208 14.3863 23.7208 14.0925 23.8773L6.97236 27.6688C6.23947 28.059 5.37772 27.4378 5.51639 26.6191L6.88179 18.5579C6.93629 18.2362 6.83037 17.9081 6.59803 17.6789L0.802323 11.9629C0.214999 11.3837 0.542087 10.3825 1.35811 10.2617L9.34808 9.07915C9.67445 9.03085 9.95599 8.82468 10.1006 8.52812L13.6636 1.21895Z"/></svg>';$(this).rateYo({rating:rating_value,starSvg:starSvgIcon})})}function goToByScroll(id,offset){offset=""!==offset?offset:0,$("html,body").animate({scrollTop:$(id).offset().top-offset},400)}review_rating_star_initializer(),$(document).find(".review-form-rating").each(function(){starSvgIcon=$(this).data("icon-type");$(this).rateYo({starSvg:'<svg width="30" height="29" viewBox="0 0 30 29" fill="none" xmlns="http://www.w3.org/2000/svg">\n      <path d="M13.6636 1.21895C14.0288 0.469865 15.0962 0.469863 15.4614 1.21895L19.0244 8.52811C19.169 8.82468 19.4505 9.03085 19.7769 9.07915L27.7669 10.2617C28.5829 10.3825 28.91 11.3837 28.3227 11.9629L22.527 17.6789C22.2946 17.9081 22.1887 18.2362 22.2432 18.5579L23.6086 26.6191C23.7473 27.4378 22.8855 28.059 22.1526 27.6688L15.0325 23.8773C14.7387 23.7208 14.3863 23.7208 14.0925 23.8773L6.97236 27.6688C6.23947 28.059 5.37772 27.4378 5.51639 26.6191L6.8818 18.5579C6.93629 18.2362 6.83037 17.9081 6.59803 17.6789L0.802323 11.9629C0.214999 11.3837 0.542087 10.3825 1.35811 10.2617L9.34808 9.07915C9.67445 9.03085 9.95599 8.82468 10.1006 8.52812L13.6636 1.21895Z" stroke="#EBAD34" stroke-linecap="round" stroke-linejoin="round"/></svg>',fullStar:!0,ratedFill:"#EBAD34",normalFill:"transparent",onChange:function(rating,rateYoInstance){$(".comment-rating-field-message").hide(),""==$(this).parent().find('input[name="stars"]').val(rating)||$(this).parent().find('input[name="stars"]').val(rating)}})}),$(document).on("click",".wtetr_comment_loadmore",function(){var button=$(this);return parent_post_id=parseInt(button.data("parent-id")),cpage=parseInt(button.data("current-page")),offset=parseInt(button.data("offset")),$.ajax({url:wtetr_public_js_object.ajax_url,data:{action:"wte_trip_review_comments_loadmore",post_id:parent_post_id,cpage:cpage,offset:offset},type:"POST",beforeSend:function(){},success:function(data){data?$(data).insertAfter(button.closest(".review-wrap").find(".comment-list").children("li").last()):button.parent().remove(),button.parent().remove(),review_rating_star_initializer()}}),!1}),$(document).on("click",".wtetr_company_comment_loadmore",function(){var button=$(this);return cpage=parseInt(button.data("current-page")),offset=parseInt(button.data("offset")),$.ajax({url:wtetr_public_js_object.ajax_url,data:{action:"wte_company_review_comments_loadmore",cpage:cpage,offset:offset},type:"POST",beforeSend:function(){},success:function(data){data?(button.parent().find(".wte-review-empty-div").append(data),button.hasClass("review-last-page")&&button.parent().find(".wte-review-empty-div").find("li:first-child").remove(),new_html=button.parent().find(".wte-review-empty-div").html(),$(new_html).insertAfter(button.closest(".review-wrap").find(".comment-list").children("li").last()),button.parent().find(".wte-review-empty-div").html("")):button.parent().remove(),button.parent().remove(),review_rating_star_initializer()}}),!1})});
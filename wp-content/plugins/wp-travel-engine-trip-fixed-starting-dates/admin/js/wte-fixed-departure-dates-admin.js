jQuery(document).ready(function($){
  wpte_add_action( 'wpte_after_admin_tab_shown', 'wte_fsd_revamped_ui_js', 10 );
});
function wte_fsd_revamped_ui_js( content_key ) {

  // Bail if content key is not availibility.
  if( content_key[0] != 'wpte-availability' ) {
    return;
  }

   // toggle on selecting dates
   jQuery(document).on( 'change', 'input.wpte-recurr-type-sel',function(){
     jQuery('.wpte-recurr-extra-spc').removeClass('toggleon');
    if(jQuery(this).is(":checked")) {
      jQuery(this).parent().addClass("toggleon");
      jQuery('.wpte-recurr-indiv-toggle-wrap').addClass("toggleonmain")
    } else {
        jQuery('.wpte-recurr-indiv-toggle-wrap').removeClass("toggleonmain")
      }
  });

  get_active_dates();
  jQuery('.wpte-number .wpte-single-dp').each( function() {
    var currn = jQuery(this);
    jQuery(this).datepicker({
      language: 'en',
      dateFormat: 'yyyy-mm-dd', changeMonth: true, changeYear: true,
      minDate: new Date(),
      onSelect: function (dateText, inst) {
        currn.val(dateText);
        var days = jQuery('#wpte-fsd-duration-meta').val();
        days = parseInt(days);
        days = days-1;
          var someFormattedDate = '';
          if( !isNaN(days) ) {
            var newdate = new Date(dateText);
            newdate.setDate(newdate.getDate() + days);
            var dd = newdate.getDate();
            var mm = newdate.getMonth() + 1;
            var y = newdate.getFullYear();
            someFormattedDate = y + '-' + mm + '-' + dd;
            var name = currn.attr('name');
            currn.siblings('input').val( someFormattedDate );
          }
      },
    });

  });

  // dpkr.selectDate( new Date() );

  var MultipleFSDates = jQuery('#fsd-departure-dates').datepicker({
    language: 'en',
    changeMonth: true,
    changeYear: true,
    multipleDates: true,
    dateFormat: 'yyyy-mm-dd',
    autoClose: true,
    minDate: new Date(),
    onRenderCell: function (date, cellType) {
      if (cellType == 'day') {
          active_dates = active_dates.map(function (d) {
              return (new Date(d)).toLocaleDateString("en-US");
          });

          isDisabled = active_dates.includes(date.toLocaleDateString("en-US"));
          return {
              disabled: isDisabled
          }
      }
  },
    onSelect: function (dateText, inst) {
      // debugger;
      // jQuery( '.wpte-fsd-date-row' ).each(function() {
      //     var value =  jQuery(this).attr( 'id' );
      //     if(value == dateText){
      //         jQuery(this).remove();
      //     }
      // });
      // addOrRemoveDate(dateText);
    },
  }).data('datepicker');

  // if( active_dates.length > 0 ){
  //   for( $i = 0; $i < active_dates.length; $i++ ) {
  //     MultipleFSDates.selectDate(new Date(active_dates[$i]));
  //   }
  // }

  jQuery(document).on( 'click', '.wpte-add-dates', function(e) {
    e.preventDefault();
    var Selecdates    = jQuery( '#fsd-departure-dates' ).val();
    var SelecdatesArr = Selecdates.split(',');

    jQuery( '.wpte-fsd-date-row' ).each(function() {
      var value =  jQuery(this).attr( 'id' );
      
      if (jQuery.inArray(value, SelecdatesArr) >  -1) {
          SelecdatesArr.splice( SelecdatesArr.indexOf( value ), 1 );
          return;
      }
    });

    var index  = get_max_id() ;   
    var template = wp.template( 'wpte-fsd-block-tmp' );

    var days = jQuery('#wpte-fsd-duration-meta').val();
    days = parseInt(days);
    days = days-1;

    var seats = jQuery('input[name="WTE_Fixed_Starting_Dates_setting[departure_dates][availability]"]').val();

    SelecdatesArr.forEach(function (val) {

      var someFormattedDate = '';
      if( !isNaN(days) ) {
        var newdate = new Date(val);
        newdate.setDate(newdate.getDate() + days);
        var dd = newdate.getDate();
        var mm = newdate.getMonth() + 1;
        var y = newdate.getFullYear();
        someFormattedDate = y + '-' + mm + '-' + dd;
      } else {
        someFormattedDate = val;
      }

      var tmplData = {
        key: index,
        sdate: val,
        edate: someFormattedDate,
        seats: seats
      };
      
      jQuery( '.wpte-table tbody' ).append( template( tmplData ) );
      jQuery( '#recurr-popup-'+tmplData.key + ' .wpte-recurring-summary' ).hide();
      jQuery('.open-popup-link').magnificPopup({
        type:'inline',
        midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
        callbacks: {
          open: function() {
            jQuery( '.mfp-close' ).html('Done');
          },
          close: function() {
            // Will fire when popup is closed
          }
          // e.t.c.
        }
      });
      ++index;
    });

    jQuery('.wpte-number .wpte-single-dp').each( function() {
      if(jQuery(this).data("datepicker") != null){
        return;
      }
      var currn = jQuery(this);
      jQuery(this).datepicker({
        language: 'en',
        dateFormat: 'yyyy-mm-dd', changeMonth: true, changeYear: true,
        onSelect: function (dateText, inst) {
          currn.val(dateText);
          var days = jQuery('#wpte-fsd-duration-meta').val();
          days = parseInt(days);
          days = days-1;
            var someFormattedDate = '';
            if( !isNaN(days) ) {
              var newdate = new Date(dateText);
              newdate.setDate(newdate.getDate() + days);
              var dd = newdate.getDate();
              var mm = newdate.getMonth() + 1;
              var y = newdate.getFullYear();
              someFormattedDate = y + '-' + mm + '-' + dd;
              var name = currn.attr('name');
              currn.siblings('input').val( someFormattedDate );
            }
        },
      });
  
    });

  });

  jQuery( document ).on( 'click', '.wpte-delete-fsd', function(e) {
    e.preventDefault();

    var confirmation = confirm(WPTE_OBJ.lang.are_you);
    if (!confirmation) {
        return false;
    }

    jQuery(this).parents( '.wpte-fsd-date-row' ).remove();
  });

  jQuery('.open-popup-link').magnificPopup({
    type:'inline',
    midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
    callbacks: {
      open: function() {
        jQuery( '.mfp-close' ).html('Done');
      },
      close: function() {
        // Will fire when popup is closed
      }
      // e.t.c.
    }
  });


  jQuery(document).on( 'change', 'input.wte-fsd-enblrecur', function() {
    var anchor_elem = jQuery(this).parents('.wpte-checkbox').siblings('a');
    if (jQuery(this).is(':checked')) {
      anchor_elem.click();
      anchor_elem.show();
    } else {
      anchor_elem.hide();
    }
  } );
  
}

var dates = new Array();

function addDate(date) {
  if (jQuery.inArray(date, dates) < 0) dates.push(date);
}

function removeDate(index) {
  dates.splice(index, 1);
}

function printArray() {
  var printArr = new String;
  return dates;
}

// Adds a date if we don't have it yet, else remove it
function addOrRemoveDate(date) {
  var index = jQuery.inArray(date, dates);
  if (index >= 0){ 
      removeDate(index);
  } else { 
      addDate(date);
  }
  printArray();
}

function get_max_id() {
  var maximum=0;
  jQuery( '.wpte-fsd-date-row' ).each(function() {
      var value =  jQuery(this).attr( 'data-id' );
      if(!isNaN(value)) {
          value = parseInt(value);
          maximum = (value > maximum) ? value : maximum;
      }
  });
  maximum++;
  return maximum;
}

var active_dates = new Array();
function get_active_dates() {
  jQuery( '.wpte-fsd-date-row' ).each(function() {
    var value =  jQuery(this).attr( 'id' );
    if(value){
      active_dates.push(value);
    }
  });
}

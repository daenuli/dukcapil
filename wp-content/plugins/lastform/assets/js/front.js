jQuery(document).ready(function () {
    var x = (jQuery(document).find(".paj").length>1)? true : false;
    if(x){
        var parentPag = jQuery(document).find(".paj").parent();
        var defValue=1,numPages= parentPag.children(".paj").length,pages=parentPag.children(".paj"),title=parentPag.children('h3'), current = 1;
        function init(){
            parentPag.html(jQuery(pages.hide()));
            jQuery(pages).eq(0).show();
            jQuery(pages).eq(0).before(title);
            jQuery("#prev").css("visibility","hidden");
            jQuery(".paginationUl").show();
        }

        function createPagination(){
            for(var i=1;i<=numPages;i++){
                var li = "<li value='"+i+"' class='item'> "+i+"</li>";
                jQuery("#next").before(li);

                if(i==1) {
                    jQuery(".paginationUl li.item").css({
                        "background-color":"#f44336",
                        "color":"white"
                    });
                }
            }
        }

        function validation(val) {
            return val <= 0 || val > numPages ? false : true;
        }

        function setCurrent(val){
            jQuery(pages).hide();
            jQuery(".paginationUl li").css({
                "background-color":"",
                "color":""
            });
            current=val;
            if(current!=1){
                jQuery("#prev").css("visibility","visible");
            }
            else {
                jQuery("#prev").css("visibility","hidden");
            }
            if(current!=numPages){
                jQuery("#next").css("visibility","visible");
            }
            else {
                jQuery("#next").css("visibility","hidden");
            }

            jQuery(".paginationUl li[value='"+current+"']").css({
                "background-color":"#f44336",
                "color":"white"
            });
            var counter = current;
            var currentDiv = jQuery(pages.get(--counter));
            currentDiv.fadeIn("slow");
        }

        init();
        createPagination();

        jQuery(document).on("click",".paginationUl li.item",function () {
            var currentValue = this.value;
            validation(currentValue);
            setCurrent(currentValue);
        });

        jQuery(document).on("click","#prev,#next",function () {
            var copyCurrent = current;
            var currentValue = (this.id=="next")?++copyCurrent:--copyCurrent;
            if(validation(currentValue)){
                setCurrent(currentValue);
            }

        });
    }
    /* Pagination */

    /* Mask On */
    jQuery(".origincode-contact-form-container").find("[data-origc-pattern]").each(function () {
        var origc_pattern = jQuery(this).data('origc-pattern');
        origc_pattern = origc_pattern.toString();
        jQuery(this).mask(origc_pattern);
    });
    /* Mask On */


    function origincodeFormsResize(){
        var forms = jQuery('.origincode-contact-wrapper .multicolumn');

        forms.each(function () {
            if( jQuery(this).width() < 521 ){
                jQuery(this).find('.origincode-contact-column-block').addClass('fullWidth')
            } else {
                jQuery(this).find('.origincode-contact-column-block').removeClass('fullWidth')
            }
        })
    }


    function reOrganizeFields(){

        var forms = jQuery('.origincode-contact-wrapper');



        if( jQuery(window).width()<768 ){
            forms.each(function () {
                var form = jQuery(this);
                if(!form.children('.origincode-form-mobile').length){
                    form.append('<div class="origincode-form-mobile"></div>');

                    var movingCaptchaBlocks = form.find('.origincode-field-block.captcha-block,.origincode-field-block.simple-captcha-block');
                    var movingButtonsBlocks = form.find('.origincode-field-block.buttons-block');

                    movingCaptchaBlocks.each(function () {
                        var rel = jQuery(this).attr('rel');
                        jQuery('<span data-placeholder="'+rel+'"></span>').insertBefore(this);
                        jQuery(this).appendTo(form.find('.origincode-form-mobile'));
                    })

                    movingButtonsBlocks.each(function () {
                        var rel = jQuery(this).attr('rel');
                        jQuery('<span data-placeholder="'+rel+'"></span>').insertBefore(this);
                        jQuery(this).appendTo(form.find('.origincode-form-mobile'));
                    })
                }
            })

        } else {
            forms.each(function () {
                var form = jQuery(this);
                if(form.children('.origincode-form-mobile').length){
                    var movedBlocks = form.find('.origincode-form-mobile').find('.origincode-field-block.captcha-block,.origincode-field-block.simple-captcha-block,.origincode-field-block.buttons-block');

                    movedBlocks.each(function () {
                        var rel = jQuery(this).attr('rel');
                        jQuery(this).insertBefore(jQuery('span[data-placeholder="'+rel+'"]'));
                        jQuery('span[data-placeholder="'+rel+'"]').remove();
                    })

                    form.find('.origincode-form-mobile').remove();
                }
            })
        }
    }

    origincodeFormsResize();
    reOrganizeFields();

    /* window resize */
    jQuery(window).on('resize',function () {
        origincodeFormsResize();

        reOrganizeFields();
    })
    /* end window resize */


});
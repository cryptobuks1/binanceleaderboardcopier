
$(function () {

    //adjustSiteFooter();

    $("#cmlightClose").click(function (e) {
        e.preventDefault();
        window.close();
    });


    $("a.new-window").click(function (e) {
        e.preventDefault();
        if ($(this).hasClass('no-window')) //fix to be able to prevent window from open but still have the event (when changing between link/no link just add/remove no-window class)
            return;
        window.open(this.href, '', 'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=800,height=600');
    });

    var jQform = $("form");
    if (jQform.data("validator") && jQform.data("validator").settings) {
        jQform.data("validator")
            .settings.submitHandler = function (form) {
                var jform = $(form);
                if (!jform.data("prevent-tant-mode")) {
                    jform.data("prevent-tant-mode", true);
                    form.submit();
                }
            };
    }

    $.validator.methods.number = function (value, element) {
        return this.optional(element) || !isNaN(Globalize.parseFloat(value));
    };

    $.validator.methods.range = function (value, element, param) {
        var val = Globalize.parseFloat(value);
        return this.optional(element) || (val >= param[0] && val <= param[1]);
    };

    $.validator.methods.date = function (value, element, param) {
        //var containsTime = $(element).data('datetimeCtrl');
        //if (containsTime)
        //    return this.optional(element) || containsTime === 'True';

        var val = Globalize.parseDate(value);
        return this.optional(element) || (val !== null);
    };

    var cultureCode = $('meta[name="culture"]').attr("content");;
    Globalize.culture(cultureCode);

    $(".date-picker").datepicker({
        changeMonth: true,
        changeYear: true
    });
    //[data-toggle="tooltip"]
    //var mainNavbar = ;
    $('#ctm-main-navbar').find(".icon-tooltip").tooltip();
    
    //New sorter, see usage in PublicationList.cshtml and NoticeList.cshtml
    $('th[data-sortfield]').click(function () {
        var $self = $(this),
            $sortDirection = $('#js-sortDirection'),
            acs = "Asc",
            desc = "Desc";

        $('#js-sortField').val($self.data('sortfield'));

        if ($sortDirection.val() === acs) {
            $sortDirection.val(desc);
        } else {
            $sortDirection.val(acs);
        }
        $('form').submit();
    });

});

//$(window).resize(function () {

//    adjustSiteFooter();

//});



// This is the footer script that will adjust the possion of the site footer
//function adjustSiteFooter() {
//    var wndHeight = $(window).height();
//    var contentHeight = $("#ctm-content-container").height();
//    if (contentHeight < wndHeight) {
//        $("#ctm-footer").css("position", "absolute");
//    } else {
//        $("#ctm-footer").css("position", "relative");
//    }
//};


function countCharsForTextArea(textareaName, charactersLeftName, textAppeared, maxlength) {
    var textareaText = $(textareaName).val();

    if (textareaText.length > maxlength) {
        $(charactersLeftName).text(textAppeared + 0).css("color", "#FF0000");
        textareaText.val(val.substr(0, max));
    }
    else
        $(charactersLeftName).text(textAppeared + (maxlength - textareaText.length)).css("color", "#000000");
}

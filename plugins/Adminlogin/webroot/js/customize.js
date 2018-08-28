/*sidebar menu*/
$(function() {                
    $('.sub_menu').click(function(){
        if($(this).hasClass('active'))
        {
            $(this).removeClass('active');      
        }else{
            $(this).addClass('active');
        }
        $(this).next('ul.sub_menu-content').slideToggle(400).siblings("ul.sub_menu-content").slideUp(300,function() { 
            $(this).prev().removeClass('active');
        });
    });
});
/*global ajax loader*/
$(document).ready(function(){
    $body = $("body");
    $(document).on({
        ajaxStart: function() {$body.addClass("loading");    },
        ajaxStop: function() { $body.removeClass("loading"); }    
    });
});
/*form fiels settings*/
(function () {
    // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
    if (!String.prototype.trim) {
        (function () {
            // Make sure we trim BOM and NBSP
            var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
            String.prototype.trim = function () {
                return this.replace(rtrim, '');
            };
        })();
    }

    [].slice.call(document.querySelectorAll('input.input__field')).forEach(function (inputEl) {
        // in case the input is already filled..
        if (inputEl.value.trim() !== '') {
            classie.add(inputEl.parentNode, 'input--filled');
        }

        // events:
        inputEl.addEventListener('focus', onInputFocus);
        inputEl.addEventListener('blur', onInputBlur);
    });

    function onInputFocus(ev) {
        classie.add(ev.target.parentNode, 'input--filled');
    }

    function onInputBlur(ev) {
        if (ev.target.value.trim() === '') {
            classie.remove(ev.target.parentNode, 'input--filled');
        }
    }
})();
function makeAllServsChkd(all, childs) {
    if ($(childs).length == $(childs + ":checked").length) {
        $(all).attr("checked", "checked");
    } else {
        $(all).removeAttr("checked");
    }
}
function trim (el) {
    el.value = el.value.
       replace (/(^\s*)|(\s*$)/gi, ""). // removes leading and trailing spaces
       replace (/[ ]{2,}/gi," ").       // replaces multiple spaces with one space 
       replace (/\n +/,"\n");           // Removes spaces after newlines
    return;
}
function goBack() {
    window.history.back();
}
/**
 * letterSpan plugin
 *
 * テキストを1文字ずつ <span> でくくる
 *
 * @author  Sinciate Inc.
 * @date    2020.04.21
**/
$.fn.letterSpan = function(arg) {

    $(this).each(function() {
        var text = $.trim(this.textContent),
            html = "";

        text.split("").forEach(function(v) {
            html += "<span>" + v + "</span>";
        });

        this.innerHTML = html;
    });

    /*
    var defaults,
        data;

    defaults = {
        delay: false,
        delayOffset: 0,
        delayInterval: 0.1,
    }

    data = $.extend({}, defaults, arg);

    $(this).each(function() {
        var text = this,
            i = 0;

        $(this).contents().each(function(){

            this.textContent.split("").forEach(function(v) {
                var prepend;

                if (data.delay) {
                    var val = data.delayOffset + data.delayInterval * i;
                    prepend = '<span style="transition-delay:' + val + 's">';
                    i++;

                } else {
                    prepend = "<span>";
                }

                $(text).append(prepend + v + "</span>");
            });
            this.remove();
        })
    });
    */
};

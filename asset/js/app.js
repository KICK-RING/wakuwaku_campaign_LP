(function($){

    /*************************************************
     * Settings
     *
     * 機能群
    *************************************************/

    // slick
    // $('.p-page-home--movie').slick({
    //     dots: true,
    //     infinite: true,
    //     arrows: false,
    //     swipe: true,
    //     centerMode: true,
    //     centerPadding: '30%',
    //     responsive: [
    //         {
    //           breakpoint: 992,
    //           settings: {
    //             centerPadding: '0px',
    //           }
    //         }
    //     ]
    // });

    // navi
    $('.menu-trigger').on('click',function(){
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $('main').removeClass('open');
            $('nav').removeClass('open');
            $('.overlay').removeClass('open');
        } else {
            $(this).addClass('active');
            $('main').addClass('open');
            $('nav').addClass('open');
            $('.overlay').addClass('open');
        }
    });

    $('.overlay').on('click',function(){
        if($(this).hasClass('open')){
            $(this).removeClass('open');
            $('.menu-trigger').removeClass('active');
            $('main').removeClass('open');
            $('nav').removeClass('open');
        }
    });

    $('nav > a').on('click',function(){
        if($('.overlay').hasClass('open')){
            $('.overlay').removeClass('open');
            $('.menu-trigger').removeClass('active');
            $('main').removeClass('open');
            $('nav').removeClass('open');
        }
    });


    // ページ内リンクのクリックイベント
    $('a[href^="#"]').on("click", function(){
        var speed = 500;
        var offset = 0;

        var href= $(this).attr("href");
        var target = $(href == "#" || href == "" ? 'html' : href);
        var position = target.offset().top - offset;

        $("html, body").animate({scrollTop:position}, speed, "swing");
    });



    $('a[href^="#"]').on("click", function(){
        var speed = 700;
        var offset = 0;

        var href= $(this).attr("href");
        var target = $(href == "#" || href == "" ? 'html' : href);
        var position = target.offset().top - offset;

        $("html, body").animate({scrollTop:position}, speed, "swing");
    });
    



    // GLOBAL 変数
    var ww = $(window).innerWidth();
    var wh = $(window).innerHeight();
    var dh = $(document).innerHeight();

    // nav class
    var isNavVisibleClass = "is-nav-visible";
    var isNavHiddenClass = "is-nav-hidden";

    /**
     * Barba の非同期通信
     *
     * 参考：https://www.evoworx.co.jp/blog/barbajs-transition/
    **/

    // barba.init({
    //     transitions: [{
    //         //name: 'default-transition',
    //         //beforeAppear: function(data) {},
    //         //appear: function(data) {},
    //         //afterAppear: function(data) {},
    //         //before: function(data) {},
    //         beforeLeave: function(data) {
    //             $("a").attr("style", "pointer-events: none !important");
    //             navHidden();
    //         },
    //         leave: function(data) {
    //             return gsap.to(data.current.container, {
    //                 opacity: 0
    //             });
    //         },
    //         afterLeave: function(data) {
    //             scrollTo(0, 0);
    //         },
    //         //beforeEnter: function(data) {},
    //         enter: function(data) {
    //             fixBgHeight();
    //             fixHeroHeight();
    //             animate('.js-animate');
    //
    //             var video = document.getElementById("js-hero-video");
    //
    //             if (video !== null) {
    //                 video.play();
    //             }
    //         },
    //         afterEnter: function(data) {
    //         },
    //         after: function(data) {
    //             pageTitleSpan();
    //             $("a").attr("style", "pointer-events: auto");
    //             removeSameUrlLinkEvent();
    //
    //             return gsap.from(data.next.container, {
    //                 opacity: 0
    //             });
    //         },
    //     }],
    // });


    /*************************************************
     * Events
     *
     * イベント
    *************************************************/

    // ロード前のイベント
    fixBgHeight();
    fixHeroHeight();

    pageTitleSpan();
    removeSameUrlLinkEvent();

    // ロード後イベント
    $(window).on("load", function(){
        animate('.js-animate');
        $("html").addClass("is-loaded");
    });

    // ロードが遅い時のイベント
    setTimeout(function(){
        animate('.js-animate');
        $("html").addClass("is-loaded");
    }, 5000);

    // スクロールイベント
    $(window).scroll(function(){

        var scroll = $(this).scrollTop();

        // pageTitle のパララックス
        $("#js-page-title span").each(function(){
            var v = $(this).data("var");
            $(this).css("transform", "translateY(" + v * scroll * .2 + "px)")
                .css("opacity", 1 - (scroll * .005));
        });

        $("#js-hero-heading")
            .css("transform", "translateY(" + scroll / 3 + "px)")
            .css("opacity", 1 - scroll * 0.0015);
    });

    // マウスムーブ イベント
    $(window).mousemove(function(){

        if (ww >= 992) {

            var x = (ww / 2 - event.clientX) / ww * 2;
            var y = (wh / 2 - event.clientY) / wh * 2;

            var perspectiveVar = 20;
            var rotateVar      = 10;
            var translateVar   = 2;

            $("#js-background-logo").css("transform",
                "perspective(" + perspectiveVar + "rem)"
                + "rotateX(" + y * rotateVar + "deg)"
                + "rotateY(" + x * rotateVar * -1 + "deg)"
                + "translate3d(" + x * translateVar * -1 + "rem," + y * translateVar * -1 + "rem,0)"
            );

            $("#js-background-light").css("transform",
                "scale(1.2)"
                + "translate3d(" + x * translateVar * 2 + "rem," + y * translateVar * 2 + "rem,0)"
            );
        }
    });

    // スマホのトグルボタンのクリックイベント
    $("#js-nav-toggle").on("click", function(){
        navToggle();
    });

    // sound のクリックイベント
    // var audio = document.getElementById("js-sound-audio");
    // $("#js-sound").on("click", function() {
    //
    //     // classを変更
    //     $("html").toggleClass("is-sound-enable");
    //
    //     // 音楽の再生・停止
    //     if ($("html").hasClass("is-sound-enable")) {
    //         audio.play();
    //
    //     } else {
    //         audio.pause();
    //     }
    // });



    /*************************************************
     * Functions
     *
     * 機能群
    *************************************************/

    // 表示ページと同じ URL へのリンクはイベント削除
    // function removeSameUrlLinkEvent() {
    //     $("a[href]").each(function(index, element){
    //         var href = $(element).attr("href");
    //
    //         $(element).removeClass("is-current").attr("style", "");
    //
    //         if (href === window.location.href) {
    //             $(element).addClass("is-current").attr("style", "pointer-events: none !important");
    //         }
    //     });
    // }

    // hero の高さを固定する
    function fixBgHeight() {

        if (ww < 992) {
            $("#js-background").css("height", wh);
        }
    }

    // hero の高さを固定する
    function fixHeroHeight() {

        if (ww < 992) {
            $("#js-hero").css("height", wh);
        }
    }

    // ページタイトルに span 追加
    function pageTitleSpan() {
        var pageTitle = $("#js-page-title"),
            pageTitleText = $.trim($(pageTitle).html()),
            randVar,
            pageTitleHtml = "";

        pageTitleText.split("").forEach(function(v) {
            randVar = Math.round(((Math.random() - 0.5) * 2) * 100) / 100;
            pageTitleHtml += "<span data-var=" + randVar + ">" + v + "</span>";
        });

        $(pageTitle).html(pageTitleHtml);
    }

    // animate()
    function animate(target) {
        const observer = new IntersectionObserver(observerCallback);

        var item = document.querySelectorAll(target);

        for (var i = 0; i < item.length; i++) {

            // .is-hidden を追加
            $(item[i]).addClass("is-hidden");

            // observe に追加
            observer.observe(item[i]);
        }

        function observerCallback(entries, object) {

            entries.forEach(function(entry) {

                // 要素がビューに現れていない場合はスキップ
                if (entry.intersectionRatio === 0) {
                    return;
                }

                // 表示されたらクラスを変更
                $(entry.target).addClass("is-visible").removeClass("is-hidden");

                // 読み込み済みの画像は監視を解除
                observer.unobserve(entry.target);
            });
        };
    }

    // navToggle()
    function navToggle() {

        if (!$("html").hasClass(isNavVisibleClass)) {
            navVisible();

        } else {
            navHidden();
        }
    }

    // navVisible()
    function navVisible() {
        $("html").addClass(isNavVisibleClass);
        $("html").removeClass(isNavHiddenClass);
    }

    // navHidden()
    function navHidden() {
        if ($("html").hasClass(isNavVisibleClass)) {
            $("html").addClass(isNavHiddenClass);
            $("html").removeClass(isNavVisibleClass);
        }
    }



})(jQuery);

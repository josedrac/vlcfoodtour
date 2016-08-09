var fixedMenu = function() {
    var pos = $('.nav-content').offset().top;
    var nav = $('.nav-content');

    $(window).scroll(function () {
        if ($(this).scrollTop() > pos) {
            nav.addClass('navbar-fixed-top');
        } else {
            nav.removeClass('navbar-fixed-top');
        }
    });
};

var footerMenu = function() {
    'use strict';
    var footerMenu = $('.footer-mercadona .footer-menu-group');
    var launchers = footerMenu.find('.title');

    launchers.click(function() {
        var isPhoneSize = Modernizr.mq('(max-width: ' + xsScreenMax + 'px)');
        if (isPhoneSize) {
            var menuBlock = $(this).next();
            menuBlock.collapse('toggle');
        }
    });
};
var navPhone = function() {
    'use strict';

    var webSite = $('html');
    var webSiteContent = webSite.find('body');
    var launcher = webSiteContent.find('.nav-phone-launch');
    var classToOpen = 'nav-phone-open';

    launcher.click(function() {
        var isOpenedMenu = webSite.hasClass(classToOpen);

        if (isOpenedMenu) {
            webSite.removeClass(classToOpen);
        } else {
            webSite.addClass(classToOpen);
        }
    });
};
var selectLanguage = function() {
    'use strict';
    $('.selectpicker').selectpicker({
        size: 2,
        width: 'fit',
        style: null
    });
};

$(document)
    .ready(function () {
        'use strict';
        selectLanguage();
        fixedMenu();
        navPhone();
        footerMenu();
    });

var lgScreenMin = 1200;

var mdScreenMax = lgScreenMin - 1;
var mdScreenMin = 992;

var smScreenMax = mdScreenMin - 1;
var smScreenMin = 768;

var xsScreenMax = smScreenMin - 1;
var xsScreenMin = 480;
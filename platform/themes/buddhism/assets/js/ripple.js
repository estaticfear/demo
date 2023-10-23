let searchInput = $('.search-input');
let superSearch = $('.super-search');
let closeSearch = $('.close-search');
let searchResult = $('.search-result');
let timeoutID = null;

export class Ripple {
    searchFunction(keyword) {
        clearTimeout(timeoutID);
        timeoutID = setTimeout(() => {
            superSearch.removeClass('search-finished');
            searchResult.fadeOut();
            $.ajax({
                type: 'GET',
                cache: false,
                url: superSearch.data('search-url'),
                data: {
                    q: keyword,
                },
                success: (res) => {
                    if (!res.error) {
                        searchResult.html(res.data.items);
                        superSearch.addClass('search-finished');
                    } else {
                        searchResult.html(res.message);
                    }
                    searchResult.fadeIn(500);
                },
                error: (res) => {
                    searchResult.html(res.responseText);
                    searchResult.fadeIn(500);
                },
            });
        }, 500);
    }

    bindActionToElement() {
        closeSearch.on('click', (event) => {
            event.preventDefault();
            if (closeSearch.hasClass('active')) {
                superSearch.removeClass('active');
                searchResult.hide();
                closeSearch.removeClass('active');
                $('body').removeClass('overflow');
                $('.quick-search > .form-control').focus();
            } else {
                superSearch.addClass('active');
                if (searchInput.val() !== '') {
                    this.searchFunction(searchInput.val());
                }
                $('body').addClass('overflow');
                closeSearch.addClass('active');
            }
        });

        searchInput.keyup((e) => {
            searchInput.val(e.target.value);
            this.searchFunction(e.target.value);
        });
    }
}

jQuery(function ($) {
    new Ripple().bindActionToElement();
    if ($('.carousel-logo__content').length) {
        $('.carousel-logo__content').slick({
            dots: false,
            infinite: true,
            speed: 3000,
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 375,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        });
    }

    if ($('#carousel-ours-member__content').length) {
        $('#carousel-ours-member__content').slick({
            dots: false,
            infinite: true,
            speed: 3000,
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 375,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        });
    }
    new ClipboardJS('.copy-input');
    $('.copy-input').click(() => {});
});

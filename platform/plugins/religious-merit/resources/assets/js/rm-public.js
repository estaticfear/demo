jQuery(function ($) {
    if ($('.slider-for').length) {
        $('.slider-for').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            asNavFor: '.slider-nav',
        });
    }

    if ($('.slider-nav').length) {
        $('.slider-nav').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.slider-for',
            focusOnSelect: true,
            responsive: [
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 3,
                    },
                },
            ],
        });
    }

    // Project detail page
    const projectId = $('#table-merits').data('project-id');
    if (projectId) {
        const searchInput = $('.input-search-merits');
        const searchSelect = $('.select-search-merits');
        const getMerits = function (page, search = '', type = '') {
            $.ajax({
                type: 'GET',
                url: `/project/merits/${projectId}?page=${page}&search=${search}&type=${type}`,
                success: (data) => {
                    $('#table-merits').html(data);
                },
            });
        };

        getMerits(1);

        searchInput.on('keypress', function (e) {
            if (e.key === 'Enter') {
                getMerits(1, searchInput.val(), searchSelect.val());
            }
        });

        searchSelect.on('change', function (e) {
            getMerits(1, searchInput.val(), e.target.value);
        });

        $(document).on('click', '.ajax .pagination a', function (e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getMerits(page, searchInput.val(), searchSelect.val());
        });

        // ajax budgets
        const searchBudgetInput = $('.input-search-budgets');
        const getBudgets = function (page, search = '') {
            $.ajax({
                type: 'GET',
                url: `/project/budgets/${projectId}?page=${page}&search=${search}`,
                success: (data) => {
                    $('#table-budgets').html(data);
                },
            });
        };

        getBudgets(1);

        searchBudgetInput.on('keypress', function (e) {
            if (e.key === 'Enter') {
                getBudgets(1, searchBudgetInput.val());
            }
        });

        $(document).on('click', '.budgets-ajax .pagination a', function (e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getBudgets(page, searchBudgetInput.val());
        });

        // ajax efforts
        const searchEffortInput = $('.input-search-efforts');
        const getEfforts = function (page, search = '') {
            $.ajax({
                type: 'GET',
                url: `/project/efforts/${projectId}?page=${page}&search=${search}`,
                success: (data) => {
                    $('#table-efforts').html(data);
                },
            });
        };

        getEfforts(1);

        searchEffortInput.on('keypress', function (e) {
            if (e.key === 'Enter') {
                getEfforts(1, searchEffortInput.val());
            }
        });

        $(document).on('click', '.efforts-ajax .pagination a', function (e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getEfforts(page, searchEffortInput.val());
        });

        // ajax artifacts
        const searchArtifactInput = $('.input-search-artifacts');
        const getArtifacts = function (page, search = '') {
            $.ajax({
                type: 'GET',
                url: `/project/artifacts/${projectId}?page=${page}&search=${search}`,
                success: (data) => {
                    $('#table-artifacts').html(data);
                },
            });
        };

        getArtifacts(1);

        searchArtifactInput.on('keypress', function (e) {
            if (e.key === 'Enter') {
                getArtifacts(1, searchArtifactInput.val());
            }
        });

        $(document).on('click', '.artifacts-ajax .pagination a', function (e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getArtifacts(page, searchArtifactInput.val());
        });
    }
});

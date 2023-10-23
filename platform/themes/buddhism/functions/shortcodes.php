<?php

use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Blog\Repositories\Interfaces\CategoryInterface;
use Cmat\Shortcode\Compilers\Shortcode;
use Cmat\Theme\Supports\ThemeSupport;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectInterface;
use Cmat\OurMember\Repositories\Interfaces\OurMemberInterface;
use Illuminate\Http\Request;
use Cmat\Faq\Repositories\Interfaces\FaqInterface;

app()->booted(function () {
    ThemeSupport::registerGoogleMapsShortcode();
    ThemeSupport::registerYoutubeShortcode();

    if (is_plugin_active('blog')) {
        add_shortcode('featured-posts', __('Featured posts'), __('Featured posts'), function () {
            return Theme::partial('shortcodes.featured-posts');
        });

        shortcode()->setAdminConfig('featured-posts', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.featured-posts-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('recent-posts', __('Recent posts'), __('Recent posts'), function (Shortcode $shortcode) {
            return Theme::partial('shortcodes.recent-posts', ['title' => $shortcode->title]);
        });

        shortcode()->setAdminConfig('recent-posts', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.recent-posts-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('news', __('News'), __('News'), function (Shortcode $shortcode) {
            $articles = get_recent_posts(5);
            return Theme::partial('shortcodes.news', ['title' => $shortcode->title, 'sub' => $shortcode->sub, 'articles' => $articles]);
        });

        shortcode()->setAdminConfig('news', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.news-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('newspapers-first', __('Newspapers first'), __('Newspapers first'), function (Shortcode $shortcode) {
            $newspapers = get_recent_posts(7);
            return Theme::partial('shortcodes.newspapers-first', ['title' => $shortcode->title, 'sub' => $shortcode->sub, 'newspapers' => $newspapers]);
        });

        shortcode()->setAdminConfig('newspapers-first', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.newspapers-first-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('newspapers-second', __('Newspapers second'), __('Newspapers second'), function (Shortcode $shortcode) {
            $newspapers = get_recent_posts(100);
            return Theme::partial('shortcodes.newspapers-second', ['title' => $shortcode->title, 'sub' => $shortcode->sub, 'newspapers' => $newspapers]);
        });

        shortcode()->setAdminConfig('newspapers-second', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.newspapers-second-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('about-us', __('About us'), __('About us'), function (Shortcode $shortcode) {
            $page = DB::table('pages')->where('id', $shortcode->id)->first();
            return Theme::partial('shortcodes.about-us', ['title' => $shortcode->title, 'id' => $shortcode->id, 'page' => $page]);
        });

        shortcode()->setAdminConfig('about-us', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.about-us-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('faqs', __('FAQs'), __('FAQs'), function (Shortcode $shortcode) {
            $faqs = app(FaqInterface::class)->getAllActiveFaqs(20);
            return Theme::partial('shortcodes.faqs', ['title' => $shortcode->title, 'faqs' => $faqs]);
        });

        shortcode()->setAdminConfig('faqs', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.faqs-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('introduce', __('Introduce'), __('Introduce'), function (Shortcode $shortcode) {
            return Theme::partial('shortcodes.introduce', ['title' => $shortcode->title]);
        });

        shortcode()->setAdminConfig('introduce', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.introduce-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('carousel-ours-member', __('Carousel ours member'), __('Carousel ours member'), function (Shortcode $shortcode) {
            $members =   app(OurMemberInterface::class)->getOurActiveMembers(6);
            return Theme::partial('shortcodes.carousel-ours-member', ['title' => $shortcode->title, 'members' => $members]);
        });

        shortcode()->setAdminConfig('carousel-ours-member', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.carousel-ours-member-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('project-need-merit', __('Project need merit'), __('Project need merit'), function (Shortcode $shortcode) {
            Theme::asset()
                ->usePath(false)
                ->add('rm-css', asset('vendor/core/plugins/religious-merit/css/rm-public.css'), [], [], '1.0.0');
            Theme::asset()
                ->container('footer')
                ->usePath(false)
                ->add(
                    'modal-merit-js',
                    asset('vendor/core/plugins/religious-merit/js/modal-merit.js'),
                    ['jquery'],
                    [],
                    '1.0.0'
                );
            $projects = app(ReligiousMeritProjectInterface::class)->getFeaturedProjects();
            return Theme::partial('shortcodes.project-need-merit', ['title' => $shortcode->title, 'sub' => $shortcode->sub, 'projects' => $projects]);
        });

        shortcode()->setAdminConfig('project-need-merit', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.project-need-merit-admin-config', compact('attributes', 'content'));
        });

        add_shortcode(
            'featured-categories-posts',
            __('Featured categories posts'),
            __('Featured categories posts'),
            function (Shortcode $shortcode) {
                $with = [
                    'slugable',
                    'posts' => function (BelongsToMany $query) {
                        $query
                            ->where('status', BaseStatusEnum::PUBLISHED)
                            ->orderBy('created_at', 'DESC');
                    },
                    'posts.slugable',
                ];

                if (is_plugin_active('language-advanced')) {
                    $with[] = 'posts.translations';
                }

                $posts = collect();

                if ($shortcode->category_id) {
                    $with['posts'] = function (BelongsToMany $query) {
                        $query
                            ->where('status', BaseStatusEnum::PUBLISHED)
                            ->orderBy('created_at', 'DESC')
                            ->take(6);
                    };

                    $category = app(CategoryInterface::class)
                        ->getModel()
                        ->with($with)
                        ->where([
                            'status' => BaseStatusEnum::PUBLISHED,
                            'id' => $shortcode->category_id,
                        ])
                        ->select([
                            'id',
                            'name',
                            'description',
                            'icon',
                        ])
                        ->first();

                    if ($category) {
                        $posts = $category->posts;
                    } else {
                        $posts = collect();
                    }
                } else {
                    $categories = get_featured_categories(2, $with);

                    foreach ($categories as $category) {
                        $posts = $posts->merge($category->posts->take(3));
                    }

                    $posts = $posts->sortByDesc('created_at');
                }

                return Theme::partial(
                    'shortcodes.featured-categories-posts',
                    ['title' => $shortcode->title, 'posts' => $posts]
                );
            }
        );

        shortcode()->setAdminConfig('featured-categories-posts', function (array $attributes) {
            $categories = app(CategoryInterface::class)->pluck('name', 'id', ['status' => BaseStatusEnum::PUBLISHED]);

            return Theme::partial(
                'shortcodes.featured-categories-posts-admin-config',
                compact('attributes', 'categories')
            );
        });
    }

    if (is_plugin_active('gallery')) {
        add_shortcode('all-galleries', __('All Galleries'), __('All Galleries'), function (Shortcode $shortcode) {
            return Theme::partial('shortcodes.all-galleries', ['limit' => $shortcode->limit]);
        });

        shortcode()->setAdminConfig('all-galleries', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.all-galleries-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('carousel-logo', __('Carousel Logo'), __('Carousel Logo'), function (Shortcode $shortcode) {
            Theme::asset()
                ->usePath(false)
                ->add('rm-slick-css', asset('https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css'), [], [], '1.0.0')
                ->add('rm-slick-theme-css', asset('https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css'), [], [], '1.0.0');

            Theme::asset()
                ->container('footer')
                ->usePath(false)
                ->add(
                    'rm-slick-public-js',
                    asset('vendor/core/plugins/religious-merit/js/rm-slick-public.min.js'),
                    ['jquery'],
                    [],
                    '1.0.0'
                );

            return Theme::partial('shortcodes.carousel-logo', ['title' => $shortcode->title, 'id' => $shortcode->id]);
        });

        shortcode()->setAdminConfig('carousel-logo', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.carousel-logo-admin-config', compact('attributes', 'content'));
        });
    }

    add_shortcode('before-footer', __('Before Footer'), __('Before Footer'), function (Shortcode $shortcode) {
        return Theme::partial('shortcodes.before-footer', ['title' => $shortcode->title, 'sub_title' => $shortcode->sub_title, 'text_content' => $shortcode->text_content, 'id' => $shortcode->id,]);
    });

    shortcode()->setAdminConfig('before-footer', function (array $attributes, ?string $content) {
        return Theme::partial('shortcodes.before-footer-admin-config', compact('attributes', 'content'));
    });

    add_shortcode('cooperate-with-us', __('Cooperate with us'), __('Cooperate with us'), function (Shortcode $shortcode) {
        return Theme::partial('shortcodes.cooperate-with-us', ['content' => $shortcode->coop]);
    });

    shortcode()->setAdminConfig('cooperate-with-us', function (array $attributes, ?string $content) {
        return Theme::partial('shortcodes.cooperate-with-us-admin-config', compact('attributes', 'content'));
    });

    add_shortcode('merit-guide', __('Merit guide'), __('Merit guide'), function () {
        return Theme::partial('shortcodes.merit-guide', []);
    });

    shortcode()->setAdminConfig('merit-guide', function () {
        return Theme::partial('shortcodes.merit-guide-admin-config');
    });

});

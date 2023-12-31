<?php

use Cmat\Widget\AbstractWidget;

class RecentPostsWidget extends AbstractWidget
{
    protected $widgetDirectory = 'recent-posts';

    public function __construct()
    {
        parent::__construct([
            'name' => __('Recent posts'),
            'description' => __('Recent posts widget.'),
            'number_display' => 5,
        ]);
    }
}

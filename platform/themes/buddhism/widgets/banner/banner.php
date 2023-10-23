<?php

use Cmat\Widget\AbstractWidget;

class BannerWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected $widgetDirectory = 'banner';

    public function __construct()
    {
        parent::__construct([
            'name'        => __('Banner'),
            'description' => __('Display sidebar banner in project detail and newspappers archive, detail'),
            'img' => '',
            'ads' => '',
        ]);
    }
}

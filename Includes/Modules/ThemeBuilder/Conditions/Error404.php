<?php
namespace SuperElemPro\Modules\ThemeBuilder\Conditions;

if (!defined('ABSPATH'))
    exit;

class Error404
{

    public function check()
    {
        return is_404();
    }

    public function get_template_id()
    {
        $args = [
            'post_type' => 'sep_template',
            'posts_per_page' => 1,
            'meta_query' => [
                [
                    'key' => '_sep_template_type',
                    'value' => '404',
                ],
            ],
        ];

        $query = new \WP_Query($args);
        return $query->have_posts() ? $query->posts[0]->ID : false;
    }
}
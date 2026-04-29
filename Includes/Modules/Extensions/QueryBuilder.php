<?php
namespace SuperElemPro\Modules\Extensions;

if (!defined('ABSPATH'))
    exit;

/**
 * Advanced Query Builder Extension
 * 
 * @since 1.0.0
 */
class QueryBuilder
{

    public function __construct()
    {
        // This would add advanced query controls to existing widgets
        // Skeleton for future implementation
    }

    public static function get_query_args($settings)
    {
        $args = [
            'post_type' => isset($settings['post_type']) ? $settings['post_type'] : 'post',
            'posts_per_page' => isset($settings['posts_per_page']) ? intval($settings['posts_per_page']) : 10,
            'orderby' => isset($settings['orderby']) ? $settings['orderby'] : 'date',
            'order' => isset($settings['order']) ? $settings['order'] : 'DESC',
        ];

        // Taxonomy query
        if (!empty($settings['taxonomy']) && !empty($settings['terms'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => $settings['taxonomy'],
                    'field' => 'term_id',
                    'terms' => $settings['terms'],
                ],
            ];
        }

        // Date query
        if (!empty($settings['date_query_after'])) {
            $args['date_query'] = [
                'after' => $settings['date_query_after'],
            ];
        }

        // Meta query
        if (!empty($settings['meta_key'])) {
            $args['meta_key'] = $settings['meta_key'];
            if (!empty($settings['meta_value'])) {
                $args['meta_value'] = $settings['meta_value'];
            }
        }

        return apply_filters('sep_query_builder_args', $args, $settings);
    }
}
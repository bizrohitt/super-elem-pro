<?php
/**
 * Dynamic Tags Module
 *
 * @package SuperElemPro\Modules\DynamicTags
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Modules\DynamicTags;

use Elementor\Core\DynamicTags\Manager as DynamicTagsManager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class DynamicTags
 */
class DynamicTags
{

    /**
     * Tag group slugs.
     */
    const GROUP_POST = 'sep-post';
    const GROUP_SITE = 'sep-site';
    const GROUP_USER = 'sep-user';
    const GROUP_MEDIA = 'sep-media';
    const GROUP_ACF = 'sep-acf';
    const GROUP_PODS = 'sep-pods';
    const GROUP_METABOX = 'sep-metabox';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->register_groups();
        $this->register_tags();
    }

    /**
     * Register tag groups (categories in the dynamic tags panel).
     *
     * @return void
     */
    private function register_groups(): void
    {

        add_action('elementor/dynamic_tags/register', function (DynamicTagsManager $manager) {

            $manager->register_group(self::GROUP_POST, [
                'title' => __('SEP Post', 'super-elem-pro'),
            ]);

            $manager->register_group(self::GROUP_SITE, [
                'title' => __('SEP Site', 'super-elem-pro'),
            ]);

            $manager->register_group(self::GROUP_USER, [
                'title' => __('SEP User', 'super-elem-pro'),
            ]);

            $manager->register_group(self::GROUP_MEDIA, [
                'title' => __('SEP Media', 'super-elem-pro'),
            ]);

            // Only register ACF group if ACF is active
            if (class_exists('ACF') || function_exists('get_field')) {
                $manager->register_group(self::GROUP_ACF, [
                    'title' => __('SEP ACF Fields', 'super-elem-pro'),
                ]);
            }

            // Only register Pods group if Pods is active
            if (function_exists('pods')) {
                $manager->register_group(self::GROUP_PODS, [
                    'title' => __('SEP Pods Fields', 'super-elem-pro'),
                ]);
            }

            // Only register Meta Box group if Meta Box is active
            if (class_exists('RWMB_Loader')) {
                $manager->register_group(self::GROUP_METABOX, [
                    'title' => __('SEP Meta Box Fields', 'super-elem-pro'),
                ]);
            }
        });
    }

    /**
     * Register all dynamic tags.
     *
     * @return void
     */
    private function register_tags(): void
    {

        add_action('elementor/dynamic_tags/register', function (DynamicTagsManager $manager) {

            // ─── Post Tags ───────────────────────────────────
            $post_tags = [
                Tags\PostTitle::class,
                Tags\PostContent::class,
                Tags\PostExcerpt::class,
                Tags\PostDate::class,
                Tags\PostMeta::class,
                Tags\PostFeaturedImage::class,
                Tags\AuthorName::class,
                Tags\AuthorMeta::class,
            ];

            // ─── Site Tags ───────────────────────────────────
            $site_tags = [
                Tags\SiteTitle::class,
                Tags\SiteTagline::class,
                Tags\SiteUrl::class,
                Tags\CurrentDate::class,
                Tags\RequestParam::class,
            ];

            // ─── User Tags ───────────────────────────────────
            $user_tags = [
                Tags\UserMeta::class,
            ];

            // ─── Optional integration tags ───────────────────
            $integration_tags = [];

            if (class_exists('ACF') || function_exists('get_field')) {
                $integration_tags[] = Tags\AcfField::class;
            }

            if (function_exists('pods')) {
                $integration_tags[] = Tags\PodsField::class;
            }

            if (class_exists('RWMB_Loader')) {
                $integration_tags[] = Tags\MetaBoxField::class;
            }

            // Register all tags
            $all_tags = array_merge($post_tags, $site_tags, $user_tags, $integration_tags);

            foreach ($all_tags as $tag_class) {
                if (class_exists($tag_class)) {
                    $manager->register(new $tag_class());
                }
            }
        });
    }
}
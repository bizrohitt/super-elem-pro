<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

/**
 * Advanced Posts Grid Widget
 * 
 * @since 1.0.0
 */
class PostsGrid extends Widget_Base
{

    public function get_name()
    {
        return 'sep-posts-grid';
    }

    public function get_title()
    {
        return __('Posts Grid', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-posts-grid';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    protected function register_controls()
    {
        // Query Section
        $this->start_controls_section(
            'section_query',
            [
                'label' => __('Query', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => __('Post Type', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_post_types(),
                'default' => 'post',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'super-elem-pro'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'date' => __('Date', 'super-elem-pro'),
                    'title' => __('Title', 'super-elem-pro'),
                    'rand' => __('Random', 'super-elem-pro'),
                    'comment_count' => __('Comment Count', 'super-elem-pro'),
                ],
                'default' => 'date',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC' => __('Ascending', 'super-elem-pro'),
                    'DESC' => __('Descending', 'super-elem-pro'),
                ],
                'default' => 'DESC',
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Layout', 'super-elem-pro'),
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label' => __('Show Image', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => __('Show Title', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Show Excerpt', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => __('Show Date', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_author',
            [
                'label' => __('Show Author', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $query_args = [
            'post_type' => $settings['post_type'],
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
        ];

        $query = new \WP_Query($query_args);

        if (!$query->have_posts()) {
            echo '<p>' . __('No posts found.', 'super-elem-pro') . '</p>';
            return;
        }

        $columns = $settings['columns'];

        ?>
        <div class="sep-posts-grid sep-grid-columns-<?php echo esc_attr($columns); ?>">
            <?php
            while ($query->have_posts()) {
                $query->the_post();
                ?>
                <article class="sep-post-item">
                    <?php if ($settings['show_image'] === 'yes' && has_post_thumbnail()): ?>
                        <div class="sep-post-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="sep-post-content">
                        <?php if ($settings['show_title'] === 'yes'): ?>
                            <h3 class="sep-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                        <?php endif; ?>

                        <?php if ($settings['show_date'] === 'yes' || $settings['show_author'] === 'yes'): ?>
                            <div class="sep-post-meta">
                                <?php if ($settings['show_date'] === 'yes'): ?>
                                    <span class="sep-post-date"><?php echo get_the_date(); ?></span>
                                <?php endif; ?>

                                <?php if ($settings['show_author'] === 'yes'): ?>
                                    <span class="sep-post-author"><?php the_author(); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($settings['show_excerpt'] === 'yes'): ?>
                            <div class="sep-post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php
            }
            wp_reset_postdata();
            ?>
        </div>
        <?php
    }

    private function get_post_types()
    {
        $post_types = get_post_types(['public' => true], 'objects');
        $options = [];

        foreach ($post_types as $post_type) {
            $options[$post_type->name] = $post_type->label;
        }

        return $options;
    }
}
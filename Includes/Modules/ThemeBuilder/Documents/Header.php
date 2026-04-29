<?php
/**
 * Header Document
 *
 * @package SuperElemPro\Modules\ThemeBuilder\Documents
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Modules\ThemeBuilder\Documents;

use Elementor\Core\Base\Document;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Header
 */
class Header extends Document
{

    /**
     * Get document properties.
     *
     * @return array
     */
    public static function get_properties(): array
    {
        $properties = parent::get_properties();

        $properties['support_kit'] = true;
        $properties['show_in_finder'] = true;
        $properties['support_conditions'] = true;

        return $properties;
    }

    /**
     * Get document title.
     *
     * @return string
     */
    public static function get_title(): string
    {
        return __('SEP Header', 'super-elem-pro');
    }

    /**
     * Register document controls.
     *
     * @return void
     */
    protected function register_controls(): void
    {
        parent::register_controls();

        $this->start_controls_section(
            'sep_header_settings',
            [
                'label' => __('Header Settings', 'super-elem-pro'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'header_layout',
            [
                'label' => __('Header Layout', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'standard',
                'options' => [
                    'standard' => __('Standard', 'super-elem-pro'),
                    'full-width' => __('Full Width', 'super-elem-pro'),
                    'sticky' => __('Sticky', 'super-elem-pro'),
                ],
            ]
        );

        $this->end_controls_section();
    }
}
<?php
/**
 * Custom JS Extension
 *
 * Adds per-element JavaScript injection capability.
 *
 * @package SuperElemPro\Modules\Extensions
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Modules\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class CustomJS
 */
class CustomJS
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('elementor/element/after_section_end', [$this, 'add_controls'], 10, 2);
        add_action('wp_footer', [$this, 'render_js'], 99);
    }

    /**
     * Add Custom JS control to elements.
     *
     * @param Element_Base $element
     * @param string $section_id
     * @return void
     */
    public function add_controls(Element_Base $element, string $section_id): void
    {

        if ('_section_responsive' !== $section_id) {
            return;
        }

        // Only add to sections and widgets, not columns
        if (!in_array($element->get_type(), ['section', 'widget', 'container'], true)) {
            return;
        }

        $element->start_controls_section(
            'sep_custom_js_section',
            [
                'label' => __('Custom JS', 'super-elem-pro') . ' <span class="sep-pro-label">SEP</span>',
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'sep_custom_js',
            [
                'label' => __('Custom JavaScript', 'super-elem-pro'),
                'type' => Controls_Manager::CODE,
                'language' => 'javascript',
                'rows' => 10,
                'description' => __('Code runs after page load. Use <code>jQuery</code> or vanilla JS.', 'super-elem-pro'),
            ]
        );

        $element->add_control(
            'sep_js_position',
            [
                'label' => __('Execute On', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'domready',
                'options' => [
                    'domready' => __('DOM Ready', 'super-elem-pro'),
                    'window' => __('Window Load', 'super-elem-pro'),
                ],
            ]
        );

        $element->end_controls_section();
    }

    /**
     * Output collected custom JS in the footer.
     *
     * @return void
     */
    public function render_js(): void
    {

        if (!class_exists('\Elementor\Plugin')) {
            return;
        }

        // Get all elements on this page
        $document = \Elementor\Plugin::$instance->documents->get_current();
        if (!$document) {
            return;
        }

        $data = $document->get_elements_data();
        if (empty($data)) {
            return;
        }

        $scripts = [];
        $this->collect_js($data, $scripts);

        if (empty($scripts)) {
            return;
        }

        echo '<script id="sep-custom-js">';
        echo '(function($){';

        $dom_ready_scripts = array_filter($scripts, fn($s) => ($s['position'] ?? 'domready') === 'domready');
        $window_load_scripts = array_filter($scripts, fn($s) => ($s['position'] ?? 'domready') === 'window');

        if (!empty($dom_ready_scripts)) {
            echo 'document.addEventListener("DOMContentLoaded",function(){';
            foreach ($dom_ready_scripts as $script) {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $script['js'];
            }
            echo '});';
        }

        if (!empty($window_load_scripts)) {
            echo 'window.addEventListener("load",function(){';
            foreach ($window_load_scripts as $script) {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $script['js'];
            }
            echo '});';
        }

        echo '})(jQuery);';
        echo '</script>';
    }

    /**
     * Recursively collect JS from all elements.
     *
     * @param array $elements Elements data.
     * @param array $scripts  Reference to collected scripts array.
     * @return void
     */
    private function collect_js(array $elements, array &$scripts): void
    {
        foreach ($elements as $element) {
            $js = $element['settings']['sep_custom_js'] ?? '';
            if (!empty($js)) {
                $scripts[] = [
                    'js' => $js,
                    'position' => $element['settings']['sep_js_position'] ?? 'domready',
                ];
            }
            if (!empty($element['elements'])) {
                $this->collect_js($element['elements'], $scripts);
            }
        }
    }
}
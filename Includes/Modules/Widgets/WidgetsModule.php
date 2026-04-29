<?php
namespace SuperElemPro\Modules\Widgets;

if (!defined('ABSPATH'))
    exit;

/**
 * Widgets Module
 * 
 * @since 1.0.0
 */
class WidgetsModule
{

    public function __construct()
    {
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    public function register_widgets($widgets_manager)
    {
        $widgets = $this->get_widgets();

        foreach ($widgets as $widget_file => $widget_class) {
            require_once __DIR__ . '/' . $widget_file . '.php';

            if (class_exists($widget_class)) {
                $widgets_manager->register(new $widget_class());
            }
        }
    }

    private function get_widgets()
    {
        return [
            'PostsGrid' => 'SuperElemPro\\Modules\\Widgets\\PostsGrid',
            'PricingTable' => 'SuperElemPro\\Modules\\Widgets\\PricingTable',
            'TestimonialCarousel' => 'SuperElemPro\\Modules\\Widgets\\TestimonialCarousel',
            'Timeline' => 'SuperElemPro\\Modules\\Widgets\\Timeline',
            'BeforeAfterSlider' => 'SuperElemPro\\Modules\\Widgets\\BeforeAfterSlider',
            'ImageHotspots' => 'SuperElemPro\\Modules\\Widgets\\ImageHotspots',
            'LottieAnimation' => 'SuperElemPro\\Modules\\Widgets\\LottieAnimation',
            'CountdownTimer' => 'SuperElemPro\\Modules\\Widgets\\CountdownTimer',
            'DataTable' => 'SuperElemPro\\Modules\\Widgets\\DataTable',
            'Charts' => 'SuperElemPro\\Modules\\Widgets\\Charts',
            'AdvancedTabs' => 'SuperElemPro\\Modules\\Widgets\\AdvancedTabs',
            'AdvancedAccordion' => 'SuperElemPro\\Modules\\Widgets\\AdvancedAccordion',
            'FloatingActionButton' => 'SuperElemPro\\Modules\\Widgets\\FloatingActionButton',
            'OffCanvasMenu' => 'SuperElemPro\\Modules\\Widgets\\OffCanvasMenu',
        ];
    }
}
<?php
namespace SuperElemPro\Modules\ThemeBuilder\Documents;
use Elementor\Core\Base\Document;
if (!defined('ABSPATH')) {
    exit;
}

class Error404 extends Document
{
    public static function get_properties(): array
    {
        $properties = parent::get_properties();
        $properties['support_conditions'] = true;
        return $properties;
    }
    public static function get_title(): string
    {
        return __('SEP Error 404', 'super-elem-pro');
    }
}
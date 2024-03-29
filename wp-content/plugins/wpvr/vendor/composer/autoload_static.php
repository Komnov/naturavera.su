<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit10da27e89b9ceb921ca7de55a4e562d6
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Singleton' => __DIR__ . '/../..' . '/admin/views/class-wpvr-singleton.php',
        'WPVR\\Builder\\DIVI\\Modules\\WPVR_Modules' => __DIR__ . '/../..' . '/includes/wpvr-divi-modules/includes/DiviModules.php',
        'WPVR\\Builder\\DIVI\\Modules\\WPVR_Tour' => __DIR__ . '/../..' . '/includes/wpvr-divi-modules/includes/modules/wpvr_modules/WpvrTour.php',
        'WPVR\\Builder\\DIVI\\WPVR_Divi_modules' => __DIR__ . '/../..' . '/includes/wpvr-divi-modules/wpvr_divi_modules.php',
        'WPVR_Admin_Page' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-admin-pages.php',
        'WPVR_Advanced_Control' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-advanced-control.php',
        'WPVR_Basic_Setting' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-basic-setting.php',
        'WPVR_Control_Button' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-control-button.php',
        'WPVR_Format' => __DIR__ . '/../..' . '/admin/helpers/class-wpvr-format.php',
        'WPVR_General' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-general.php',
        'WPVR_Hotspot' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-hotspot.php',
        'WPVR_Meta_Box' => __DIR__ . '/../..' . '/admin/views/class-wpvr-meta-box.php',
        'WPVR_Meta_Field' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-meta-field.php',
        'WPVR_Post_Type' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-post-type.php',
        'WPVR_Rollback' => __DIR__ . '/../..' . '/admin/class-wpvr-rollback.php',
        'WPVR_Scene' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-scene.php',
        'WPVR_Setup_Meta_Box' => __DIR__ . '/../..' . '/admin/classes/class-setup-meta-box.php',
        'WPVR_Shortcode' => __DIR__ . '/../..' . '/public/classes/class-wpvr-shortcode.php',
        'WPVR_Shortcode_TEST' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-shortcode.php',
        'WPVR_StreetView' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-streetview.php',
        'WPVR_Tour_Preview' => __DIR__ . '/../..' . '/admin/classes/class-tour-preview-meta-box.php',
        'WPVR_Tour_setting' => __DIR__ . '/../..' . '/admin/views/class-wpvr-tour-setting.php',
        'WPVR_Validator' => __DIR__ . '/../..' . '/admin/helpers/class-wpvr-validator.php',
        'WPVR_Video' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-video.php',
        'Wpvr' => __DIR__ . '/../..' . '/includes/class-wpvr.php',
        'Wpvr_Activator' => __DIR__ . '/../..' . '/includes/class-wpvr-activator.php',
        'Wpvr_Admin' => __DIR__ . '/../..' . '/admin/class-wpvr-admin.php',
        'Wpvr_Ajax' => __DIR__ . '/../..' . '/admin/classes/class-wpvr-ajax.php',
        'Wpvr_Deactivator' => __DIR__ . '/../..' . '/includes/class-wpvr-deactivator.php',
        'Wpvr_Loader' => __DIR__ . '/../..' . '/includes/class-wpvr-loader.php',
        'Wpvr_Public' => __DIR__ . '/../..' . '/public/class-wpvr-public.php',
        'Wpvr_i18n' => __DIR__ . '/../..' . '/includes/class-wpvr-i18n.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit10da27e89b9ceb921ca7de55a4e562d6::$classMap;

        }, null, ClassLoader::class);
    }
}

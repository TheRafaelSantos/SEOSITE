<?php
/**
 * Theme functions and definitions.
 */

// Enfileira os estilos do tema filho
function bellabeauty_child_enqueue_styles() {
    wp_enqueue_style( 'bella-beauty-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'bellabeauty_child_enqueue_styles', 11 );

// Personaliza alt da imagem do gravatar
function adicionar_alt_personalizado_no_gravatar($avatar, $id_or_email, $size, $default, $alt) {
    if (false !== $alt && '' !== $alt) {
        return $avatar;
    }

    $novo_alt = 'Esteticista Dra. Aline Almeida';
    $avatar = str_replace('alt=\'\'', 'alt=\''.esc_attr($novo_alt).'\'', $avatar);
    $avatar = str_replace('alt=""', 'alt="'.esc_attr($novo_alt).'"', $avatar);

    return $avatar;
}
add_filter('get_avatar', 'adicionar_alt_personalizado_no_gravatar', 10, 5);
<?php
/**
 * Theme functions and definitions.
 */

// Enfileira os estilos do tema filho
function bellabeauty_child_enqueue_styles() {
    wp_enqueue_style(
        'bella-beauty-child-style',
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
    $avatar = str_replace("alt=''", "alt='".esc_attr($novo_alt)."'", $avatar);
    $avatar = str_replace('alt=""', 'alt="'.esc_attr($novo_alt).'"', $avatar);

    return $avatar;
}
add_filter('get_avatar', 'adicionar_alt_personalizado_no_gravatar', 10, 5);

// === Microsoft Clarity (sem plugin) ===
// ID atual: ks55ufbuy6
function soulmusa_inserir_microsoft_clarity() {
    // Não rastrear usuários logados (evita poluir dados)
    if ( is_user_logged_in() ) return;

    // Se estiver usando WP Consent API, respeitar categoria "statistics"
    if ( function_exists('wp_has_consent') && ! wp_has_consent('statistics') ) return;

    ?>
    <!-- Microsoft Clarity -->
    <script type="text/javascript">
      (function(c,l,a,r,i,t,y){
          c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
          t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
          y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
      })(window, document, "clarity", "script", "ks55ufbuy6");
    </script>
    <!-- /Microsoft Clarity -->
    <?php
}
add_action('wp_head', 'soulmusa_inserir_microsoft_clarity', 8);

// === Bloquear atualização automática do plugin LiteSpeed Cache ===
add_filter( 'auto_update_plugin', function( $update, $item ) {
    // slug oficial do plugin LiteSpeed Cache
    if ( isset( $item->slug ) && $item->slug === 'litespeed-cache' ) {
        return false; // nunca atualizar automaticamente este plugin
    }

    return $update; // comportamento normal para os demais
}, 10, 2 );
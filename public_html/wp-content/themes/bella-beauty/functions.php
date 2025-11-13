<?php
/**
 * Main Theme Functions
 */

define( 'CMSMASTERS_THEME_VERSION', '1.0.6' );

// CMSMasters API
define( 'CMSMASTERS_API_ROUTES_URL', 'http://api.cmsmasters.net/wp-json/cmsmasters-api/v1/' );

// Theme options
define( 'CMSMASTERS_OPTIONS_PREFIX', 'cmsmasters_bella-beauty_' );
define( 'CMSMASTERS_OPTIONS_NAME', 'cmsmasters_bella-beauty_options' );

/*
 * Register Elementor Locations
 */
if ( ! function_exists( 'cmsmasters_register_elementor_locations' ) ) {
    function cmsmasters_register_elementor_locations( $elementor_theme_manager ) {
        if ( apply_filters( 'cmsmasters_register_elementor_locations', true ) ) {
            $elementor_theme_manager->register_all_core_location();
        }
    }
}

add_action( 'elementor/theme/register_locations', 'cmsmasters_register_elementor_locations' );

// require files
require_once get_parent_theme_file_path( '/core/starter.php' );


// ===== INÍCIO DO CÓDIGO ADICIONADO (NÃO RECOMENDADO EDITAR TEMA PAI) =====

add_action( 'wp_enqueue_scripts', 'desativar_assets_woocommerce_bella_pai', 999 );
/**
 * Remove os assets CSS e JS do WooCommerce carregados pelo tema,
 * já que o site não usa WooCommerce.
 * ATENÇÃO: Editar o tema pai diretamente fará com que esta alteração
 * seja perdida na próxima atualização do tema. Use um tema filho!
 */
function desativar_assets_woocommerce_bella_pai() {
    // Verifica se a classe WooCommerce NÃO existe (ou seja, plugin não está ativo)
    if ( ! class_exists( 'WooCommerce' ) ) {

        // === SUBSTITUA OS HANDLES ABAIXO PELOS CORRETOS QUE VOCÊ ENCONTROU ===
        // Os nomes abaixo são apenas EXEMPLOS e precisam ser substituídos!

        // Exemplo de como ficaria com handles REAIS (substitua pelos seus!)
        wp_dequeue_style( 'handle-do-css-woocommerce-do-tema' ); // Substitua pelo handle CSS real
        // wp_dequeue_style( 'outro-handle-css-woocommerce-se-existir' ); // Adicione mais linhas se necessário
        // wp_dequeue_script( 'handle-do-js-woocommerce-do-tema' ); // Substitua pelo handle JS real
        // wp_dequeue_script( 'outro-handle-js-woocommerce-se-existir' ); // Adicione mais linhas se necessário

        // ====================================================================
    }
}

// ===== FIM DO CÓDIGO ADICIONADO =====

?>
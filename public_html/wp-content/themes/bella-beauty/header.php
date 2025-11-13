<?php
use BellaBeautySpace\TemplateFunctions\General_Elements;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
?>
<!doctype html>
<html <?php language_attributes(); General_Elements::html_tag_class(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="profile" href="https://gmpg.org/xfn/11" />

  <!-- dataLayer ANTES de GTM e gtag -->
  <script>
    window.dataLayer = window.dataLayer || [];
  </script>

  <!-- Google Tag Manager -->
  <script>
  (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-5Z2JW4KT');
  </script>
  <!-- End Google Tag Manager -->

  <?php wp_head(); ?>

  <!-- Google Ads in-page (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11421300091"></script>
  <script>
    // usa o MESMO dataLayer já criado acima
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'AW-11421300091'); // ID do Google Ads
  </script>
  <!-- fim Google Ads -->
</head>

<body id="cmsmasters_body" <?php body_class(); ?>>

  <?php wp_body_open(); ?>

  <!-- Google Tag Manager (noscript) -->
  <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5Z2JW4KT"
    height="0" width="0" style="display:none;visibility:hidden"></iframe>
  </noscript>
  <!-- End Google Tag Manager (noscript) -->

  <?php
  if (
    ( ! function_exists( 'cmsmasters_template_do_location' ) || ! cmsmasters_template_do_location( 'header' ) ) &&
    ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) )
  ) {
    get_template_part( 'template-parts/header' );
    get_template_part( 'template-parts/heading' );
  }
  ?>
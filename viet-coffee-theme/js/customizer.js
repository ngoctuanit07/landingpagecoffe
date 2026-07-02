/**
 * Viet Coffee Theme Customizer Live Preview
 */
( function( $ ) {
    // Hero title
    wp.customize( 'hero_title', function( value ) {
        value.bind( function( newval ) {
            $( '.hero-bg h1' ).html( newval );
        } );
    } );

    // Hero subtitle
    wp.customize( 'hero_subtitle', function( value ) {
        value.bind( function( newval ) {
            $( '.hero-bg .hero-subtitle, .hero-bg p' ).first().html( newval );
        } );
    } );

    // Colors update live (CSS vars handled server side via wp_head, but we can help preview)
    wp.customize( 'primary_color', function( value ) {
        value.bind( function( newval ) {
            document.documentElement.style.setProperty( '--vc-primary', newval );
        } );
    } );

    wp.customize( 'accent_color', function( value ) {
        value.bind( function( newval ) {
            document.documentElement.style.setProperty( '--vc-accent', newval );
        } );
    } );

    // Update background image in preview
    wp.customize( 'hero_background', function( value ) {
        value.bind( function( newval ) {
            $( '.hero-bg' ).css( 'background-image', 'linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.5)), url(' + newval + ')' );
        } );
    } );

    console.log('%c[VietCoffee] Customizer live preview ready', 'color:#4A2C1A');
} )( jQuery );

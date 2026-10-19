/*admin css*/
( function( prime_shopping_cartapi ) {

	prime_shopping_cartapi.sectionConstructor['prime_shopping_cartupsell'] = prime_shopping_cartapi.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );

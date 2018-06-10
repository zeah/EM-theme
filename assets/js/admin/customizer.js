$(() => {

	((api) => {


		api('theme_privacy[text]', (v) => v.bind((nv) => $('.emtheme-cookie-text').html(nv)));

		api('theme_privacy[bg]', (v) => v.bind((nv) => $('.emtheme-cookie').css('background-color', nv)));
		
		api('theme_privacy[font]', (v) => v.bind((nv) => $('.emtheme-cookie').css('color', nv)));

		api('theme_privacy[button_text]', (v) => v.bind((nv) => $('.emtheme-cookie-button').html(nv)));

		api('theme_privacy[button_bg]', (v) => v.bind((nv) => $('.emtheme-cookie-button').css('background-color', nv)));
		
		api('theme_privacy[button_font]', (v) => v.bind((nv) => $('.emtheme-cookie-button').css('color', nv)));




	})(wp.customize);

});
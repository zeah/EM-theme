$(() => {

	((api) => {


		api('theme_privacy[text]', (v) => v.bind((nv) => $('.emtheme-cookie-text').html(nv)));

		api('theme_privacy[bg]', (v) => v.bind((nv) => $('.emtheme-cookie').css('background-color', nv)));
		
		api('theme_privacy[font]', (v) => v.bind((nv) => $('.emtheme-cookie').css('color', nv)));

		api('theme_privacy[button_text]', (v) => v.bind((nv) => $('.emtheme-cookie-button').html(nv)));

		api('theme_privacy[button_bg]', (v) => v.bind((nv) => { 
			$('.emtheme-cookie-button').css('background-color', nv);
			$('.emtheme-cookie').css('border', 'solid 1px '+nv); 
		}));
		
		api('theme_privacy[button_font]', (v) => v.bind((nv) => $('.emtheme-cookie-button').css('color', nv)));



		api('theme_footer[active]', (v) => v.bind((nv) => $('.emtheme-footer-container').toggle()));
		api('theme_footer[social]', (v) => v.bind((nv) => $('.emtheme-footer-social').html(nv.replace(/\n/g, '<br>'))));
		api('theme_footer[contact]', (v) => v.bind((nv) => $('.emtheme-footer-contact').html(nv.replace(/\n/g, '<br>'))));
		api('theme_footer[aboutus]', (v) => v.bind((nv) => $('.emtheme-footer-aboutus').html(nv.replace(/\n/g, '<br>'))));


	})(wp.customize);

});
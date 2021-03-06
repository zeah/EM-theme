$(() => { ((api) => {

	// console.dir(gfont);

	// navbar
	let top = api.instance('emtheme_color[nav_bg_top]').get(); 
	let middle = api.instance('emtheme_color[nav_bg_middle]').get(); 
	let bottom = api.instance('emtheme_color[nav_bg_bottom]').get();
	let menuc = $('.navbar-background');


	let fixWeight = (o) => {
		if (!o) return;

		if (o == 'regular') return '400';

		return o.replace('italic', 'i');
	}

	let style = (place) => {


		let w = api.instance('emtheme_font['+place+'_weight]');
		let family = api.instance('emtheme_font['+place+'_family]');
		
		// if weight is not set
		let weight = '400'; 


		if (family == 'undefined') return;

		// getting font family
		family = family.get();
		if (!family) return;


		if (w != 'undefined') {
			we = w.get();

			// so '400' matches in gfont file
			if (we == '400') we = 'regular';

			// checks if font weight exists, if not select first font weight.
			for (let fam of gfont)
				if (family == fam.family) {
					// sets weight to match google fetch
					if (fam.variants.includes(we)) weight = fixWeight(we);
					else weight = fixWeight(fam.variants[0]);
					break;
				}
		}

		// setting css
		switch (place) {
			case 'title':
				$('.emtheme-header-title, .navbar-title').css({
					fontFamily: '"'+family+'"',
					fontWeight: weight.replace('i', '')
				});
				break;
			case 'navbar': 
				$('.menu-link').css({
					fontFamily: '"'+family+'"',
					fontWeight: weight.replace('i', '')
				});
				break;
			case 'content':
				$('.main, .emtheme-footer, .emtheme-header-tagline').css({
					fontFamily: '"'+family+'"',
					fontWeight: weight.replace('i', '')
				});
				break;
		}


		$('.emtheme-google-'+place).remove();
		$('head').append('<link class="emtheme-google-'+place+'" rel="stylesheet" href="https://fonts.googleapis.com/css?family='+family.replace(/ /g, '+')+':'+weight+'">');
	}


	// helper function for navbar background
	let navbar = () => {

		menuc.css('background', 'none');

		let linear = null;
		if (middle && bottom) 		linear = 'linear-gradient(to top, '+bottom+' 0%, '+middle+' 50%, '+top+' 100%)';
		else if (middle && !bottom) linear = 'linear-gradient(to top, '+middle+' 50%, '+top+' 100%)';
		else if (bottom)			linear = 'linear-gradient(to top, '+bottom+' 0%, '+top+' 100%)';

		if (linear) menuc.css('background', linear);
		else 		menuc.css('background-color', top);
	}

	// navbar bg top or solid
	api('emtheme_color[nav_bg_top]', (value) => value.bind((newval) => {
			top = newval;
			navbar();
		}
	));
	// navbar bg middle
	api('emtheme_color[nav_bg_middle]', (value) => value.bind((newval) => {
			middle = newval;
			navbar(); 
		}
	));
	// navbar bg bottom
	api('emtheme_color[nav_bg_bottom]', (value) => value.bind((newval) => {
			bottom = newval;
			navbar(); 
		}
	));

	/* hover top level navbar */
	let hover_top = api.instance('emtheme_color[nav_bg_hover_top]').get(); 
	let hover_middle = api.instance('emtheme_color[nav_bg_hover_middle]').get(); 
	let hover_bottom = api.instance('emtheme_color[nav_bg_hover_bottom]').get();

	let hover_navbar = () => {

		let linear = null;
		if (hover_middle && hover_bottom) 		linear = 'linear-gradient(to top, '+hover_bottom+' 0%, '+hover_middle+' 50%, '+hover_top+' 100%)';
		else if (hover_middle && !hover_bottom) linear = 'linear-gradient(to top, '+hover_middle+' 50%, '+hover_top+' 100%)';
		else if (hover_bottom)					linear = 'linear-gradient(to top, '+hover_bottom+' 0%, '+hover_top+' 100%)';

		if (linear) {
		   $('.menu-level-top, .menu-has-child').hover(
				function() { $(this).css('background', linear); },
				function() { $(this).css('background', 'none'); }
			);
		}
		else {
		   $('.menu-level-top, .menu-has-child').hover(
				function() { $(this).css('background-color', hover_top); },
				function() { $(this).css('background-color', 'transparent'); }
			);
		}
	}

	api('emtheme_color[navbar_active]', (v) => v.bind((nv) => $('.menu-current').css('background-color', nv+'90')));


	api('emtheme_color[main_background]', (v) => v.bind((nv) => $('.content, .default-template-widget').css('background-color', nv)));
	api('emtheme_color[emtop_bg]', (v) => v.bind((nv) => $('.emtheme-header-container').css('background-color', nv)));
	api('emtheme_color[emtop_font]', (v) => v.bind((nv) => $('.title-link').css('color', nv)));
	api('emtheme_color[main_font]', (v) => v.bind((nv) => $('.main').css('color', nv)));
	api('emtheme_color[footer_bg]', (v) => v.bind((nv) => $('.emtheme-footer-container').css('background-color', nv)));
	api('emtheme_color[footer_font]', (v) => v.bind((nv) => $('.emtheme-footer, .emtheme-footer a').css('color', nv)));



	api('emtheme_color[main_shadow]', (v) => v.bind((nv) => {
		let css = 'none';

		if (nv != '') css = '0 0 2px '+nv;

		$('.content, .default-template-widget').css('box-shadow', css);

	}));
	
	api('emtheme_color[nav_bg_hover_top]', (value) => value.bind((newval) => {
			hover_top = newval;
			hover_navbar();
		}
	));
	api('emtheme_color[nav_bg_hover_middle]', (value) => value.bind((newval) => {
			hover_middle = newval;
			hover_navbar(); 
		}
	));
	api('emtheme_color[nav_bg_hover_bottom]', (value) => value.bind((newval) => {
			hover_bottom = newval;
			hover_navbar(); 
		}
	));

	api('emtheme_color[navbar_border]', (v) => v.bind((nv) => $('.menu-level-top').css('border-right', (nv != '' ? 'solid 1px '+nv : 'none'))));
			

	// navbar font color 
	// api('emtheme_color[navbar_font]', (v) => v.bind((nv) => $('.menu-level-top, .menu-list > li > .emtheme-navbar-description').css('color', nv)));
	api('emtheme_color[navbar_font]', (v) => v.bind((nv) => $('.menu-level-top, .navbar-title').css('color', nv)));




	// submenu background
	api('emtheme_color[submenu_bg]', (v) => v.bind((nv) => $('.sub-menu').css('background-color', nv)));

	api('emtheme_color[submenu_hover]', (v) => v.bind((nv) => {
		$('.menu-level-second').hover(
			function() { $(this).css('background-color', nv) },
			function() { $(this).css('background-color', 'transparent'); }
		);
	}));
			
	api('emtheme_color[submenu_border]', (v) => v.bind((nv) => $('.sub-menu, .menu-level-second').css('border', 'solid 1px '+nv)));
			
	api('emtheme_color[submenu_font]', (v) => v.bind((nv) => { 
		$('.menu-level-second').css('color', nv); 
		$('.sub-menu .emtheme-navbar-description').css('color', nv);
	}));
			



	// FONTS

	api('emtheme_font[title_family]', (v) => v.bind((nv) => style('title')));
	
	api('emtheme_font[navbar_family]', (v) => v.bind((nv) => style('navbar')));
	
	api('emtheme_font[content_family]', (v) => v.bind((nv) => style('content')));

	api('emtheme_font[title_weight]', (v) => v.bind((nv) => style('title')));
	
	api('emtheme_font[navbar_weight]', (v) => v.bind((nv) => style('navbar')));
	
	api('emtheme_font[content_weight]', (v) => v.bind((nv) => style('content')));

	api('emtheme_font[title_size]', (v) => v.bind((nv) => $('.emtheme-header-title, .navbar-title').css('font-size', (nv/10)+'rem')));
	api('emtheme_font[navbar_size]', (v) => v.bind((nv) => $('.menu-link').css('font-size', (nv/10)+'rem')));
	api('emtheme_font[content_size]', (v) => v.bind((nv) => $('.main, .emtheme-footer').css('font-size', (nv/10)+'rem')));

	api('emtheme_font[content_lineheight]', (v) => v.bind((nv) => $('.main').css('line-height', nv)));


	// LAYOUT
	api('emtheme_layout[navbar_padding]', (v) => v.bind((nv) => { 
		$('.menu-link').css('padding', (nv/10)+'rem 1.5rem');
		$('.menu-has-child > .menu-link').css('padding', (nv/10)+'rem 0 '+(nv/10)+'rem 1.5rem');
	}));

	if (api.instance('emtheme_layout[header_toggle]').get()) $('.emtheme-header-container').toggle();
	api('emtheme_layout[header_toggle]', (v => v.bind((nv) => $('.emtheme-header-container').toggle())));

	if (api.instance('emtheme_layout[search_toggle]').get()) $('.emtheme-header .emtheme-search-form').toggle();
	api('emtheme_layout[search_toggle]', (v => v.bind((nv) => $('.emtheme-header .emtheme-search-form').toggle())));

	api('emtheme_layout[search_navbar_toggle]', (v => v.bind((nv) => $('.navbar-search').toggle())));



	if (!api.instance('emtheme_layout[logo_navbar_toggle]').get()) {
		$('.navbar-menu').css('flex', '1');
		$('.navbar-identity').toggle(); 
	}

	api('emtheme_layout[logo_navbar_toggle]', (v) => v.bind((nv) => { 
		$('.navbar-identity').toggle();

		if (api.instance('emtheme_layout[logo_navbar_toggle]').get()) $('.navbar-menu').css('flex', '0 1 auto');
		else $('.navbar-menu').css('flex', '1');

	}));
	// api('emtheme_layout[logo_navbar_toggle]', (v => v.bind((nv) => $('.navbar-identity').toggle())));


	api('emtheme_layout[content_width]', (v) => v.bind((nv) => $('.content-column').width(nv)));

	// header background image
	let hbg = {

		// url, opacity, repeat, position, size
		data: {
			controls: ['header', 'header_opacity', 'header_repeat', 'header_position', 'header_size']

		},

		init: () => {
			let data = hbg.data;
			let c = hbg.c;

			for (let i of data.controls) {
				let t = api.instance(c(i));
				
				if (t) {
					data[i] = t.get();
					api(c(i), (v) => v.bind((nv) => hbg.update(i, nv)));					
				}
			}

			// console.dir(data);
		},

		c: (o) => o ? 'theme_background['+o+']' : false,

		update: (i, nv) => {
			if (!(i || nv)) return;

			let data = hbg.data;

			data[i] = nv;


			let $out = $('<style>', {'class': 'theme-header-bg-style'});

			$out.html('.emtheme-header-container::after { content: ""; position: absolute; top: 0; bottom: 0; right: 0; left: 0; z-index: -1; background-image: url(\''+data.header+'\'); opacity: '+data.header_opacity+'; background-position: '+data.header_position+'; background-repeat: '+data.header_repeat+'; background-size: '+data.header_size+'; }');

			$('.theme-header-bg-style').remove();
			$('head').append($out);
		}

	}

	hbg.init();

	// api('theme_background[header]', (value) => value.bind((newval) => {
	// 	if (!newval) 	newval = 'none';
	// 	else 			newval = 'url("'+newval+'")';

	// 	let opacity = api.instance('theme_background[header_opacity]').get();

	// 	// console.log('hi '+opacity);
	// 	$('.emtheme-bg-op').remove();
	// 	$('head').append('<style class="emtheme-bg-op"> .emtheme-header-container:after { content: ""; background-image: '+newval+'; opacity: '+opacity+'; top: 0; left: 0; bottom: 0; right: 0; position: absolute; z-index: -2;} </style>');
	// }));

	// api('theme_background[header_opacity]', (value) => value.bind((newval) => {

	// 	let url = api.instance('theme_background[header]').get();
	// 	if (!url) return;

	// 	url = 'url("'+url+'")';


	// 	$('.emtheme-bg-op').remove();
	// 	$('head').append('<style class="emtheme-bg-op"> .emtheme-header-container:after { content: ""; background: '+url+'; opacity: '+newval+'; top: 0; left: 0; bottom: 0; right: 0; position: absolute; z-index: -2;} </style>');
	// }));



	// go up button 
	api('emtheme_color[goup_bg]', (v) => v.bind((nv) => $('.emtheme-goup').css('background-color', nv)));
	api('emtheme_color[goup_font]', (v) => v.bind((nv) => { 
		$('.emtheme-goup').css('border', 'solid 2px '+nv);
		$('.emtheme-goup-arrow').css('fill', nv);
	}));

	// privacy window 
	api('theme_privacy[text]', (v) => v.bind((nv) => $('.theme-privacy-text').html(nv.replace(/\n/g, '<br>'))));

	api('theme_privacy[bg]', (v) => v.bind((nv) => $('.theme-privacy').css('background-color', nv)));
	
	api('theme_privacy[font]', (v) => v.bind((nv) => $('.theme-privacy').css('color', nv)));

	api('theme_privacy[button_text]', (v) => v.bind((nv) => $('.theme-privacy-button').html(nv)));

	api('theme_privacy[button_bg]', (v) => v.bind((nv) => { 
		$('.theme-privacy-button').css('background-color', nv);
		$('.theme-privacy').css('border', 'dashed 4px '+nv); 
	}));
	
	api('theme_privacy[button_font]', (v) => v.bind((nv) => $('.theme-privacy-button').css('color', nv)));


	// footer 
	if (api.instance('theme_footer[active]').get()) $('.emtheme-footer-social, .emtheme-footer-contact, .emtheme-footer-aboutus').toggle();
	api('theme_footer[active]', (v) => v.bind((nv) => $('.emtheme-footer-social, .emtheme-footer-contact, .emtheme-footer-aboutus').toggle()));
	api('theme_footer[social]', (v) => v.bind((nv) => $('.emtheme-footer-social').html(nv.replace(/\n/g, '<br>'))));
	api('theme_footer[contact]', (v) => v.bind((nv) => $('.emtheme-footer-contact').html(nv.replace(/\n/g, '<br>'))));
	api('theme_footer[aboutus]', (v) => v.bind((nv) => $('.emtheme-footer-aboutus').html(nv.replace(/\n/g, '<br>'))));



	// 404
	api('theme_notfound[text]', (v) => v.bind((nv) => $('.column-404').html(nv)));

})(wp.customize); });
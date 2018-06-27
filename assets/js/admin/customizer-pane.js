((api, $) => { api.bind('ready', () => {

	let fam = (o) => {

		let make = (v) => {
			if (!v) return;

			let temp = {};

			for (let w of v) {
				if (w == 'regular') w = '400';
				if (w == 'italic') w = '400italic';

				temp[w] = w
			}

			// console.log(temp);
			return temp;
		}

		if (!o) return;

		let family = o.get();

		if (!family) return;

		for (let fami of gfont)
			if (fami['family'] == family) {
				return make(fami['variants']);
				break;
			} 		
	}

	let opt = (o, place) => {
		if (!o) return;

		let f = fam(o);

		let current = api.instance('emtheme_font['+place+'_weight]').get();


		let html = (h) => '<option'+((current == h) ? ' selected' : '')+'>'+h+'</option>';
		

		let temp = '';

		for (let v in f)
			if (v) temp += html(v);

		return temp;
	}

	let weight = (o) => {
		if (!o.title) return;

		api.control.add(new api.Control('emtheme_font['+o.title+'_weight]', {
			section: 'theme_fonts',
			priority: o.priority || 300,
			type: 'select',
			settings: { 'default': 'emtheme_font['+o.title+'_weight]' },
			choices: fam(api.instance('emtheme_font['+o.title+'_family]')),
			label: (o.title == 'title') ? 'Site Title' : o.title 
		}));
	}


	weight({title: 'title', priority: 100});
	weight({title: 'navbar', priority: 101});
	weight({title: 'content', priority: 102});

	api('emtheme_font[title_family]', (v) => v.bind((nv) => $('#customize-control-emtheme_font-title_weight select').html(opt(v, 'title'))));
	
	api('emtheme_font[navbar_family]', (v) => v.bind((nv) => $('#customize-control-emtheme_font-navbar_weight select').html(opt(v, 'navbar'))));
	
	api('emtheme_font[content_family]', (v) => v.bind((nv) => $('#customize-control-emtheme_font-content_weight select').html(opt(v, 'content'))));



	
	/* header image background opacity range number */
	$opacity = $('<span>', {'class': 'bg-head-op-nr'});

	$opacity.html($('#customize-control-theme_background-header_opacity > input')[0].value*100+'%');

	$('#customize-control-theme_background-header_opacity').append($opacity);

	$('#customize-control-theme_background-header_opacity > input').on('input', (e) => $opacity.html(Math.floor(e.target.value*100)+'%'));

}); })(wp.customize, jQuery);
jQuery(function() {
	var $ = jQuery;

	var H = function(o = {}) {
		var e = document.createElement(o.el || 'div');


		for (let obj in o) 
			switch (obj) {
				case 'class':	
					if (Array.isArray(o.class))
						for (var ocl in o.class)
							e.classList.add(o.class[ocl]);

					else if (o.class) e.classList.add(o.class);
					break;

				case 'text': e.appendChild(document.createTextNode(o.text)); break;

				case 'el': break;

				default: e.setAttribute(obj, o[obj]);
			}

		
		return e;
	}


	var addGoUp = function() {
		var goUp = H({class: 'emtheme-goup'});

		goUp.appendChild(H({class: 'material-icons', text: 'keyboard_arrow_up'}));

		goUp.addEventListener('click', function() { window.scrollTo(0, 0) });

		var showGoUp = false;
		window.addEventListener('scroll', function() {

			if (!showGoUp && window.scrollY > 100) {
				goUp.style.display = 'block';
				showGoUp = true;
			}

			else if (window.scrollY < 100) {
				goUp.style.display = 'none';
				showGoUp = false;
			}

		});

		document.body.appendChild(goUp);
	}


	var addCookieAccept = function() {
		var element = document.querySelector('.emtheme-cookie');
		if (!element) return;

		var button = document.querySelector('.emtheme-cookie-button');
		if (!button) return;

		// var cookies = decodeURIComponent(document.cookie).split('; ');

		if (location.href.indexOf('customize_changeset') == -1)
			for (var cookie of decodeURIComponent(document.cookie).split('; '))
				if (cookie.indexOf('cookieAccept=') == 0) {
					element.style.display = 'none';
					return; 
				}

		element.style.opacity = 1;

		button.addEventListener('click', function() { 
			var d = new Date();
			d.setTime(d.getTime() + (120*24*60*60*1000));

			document.cookie = 'cookieAccept=ok;expires='+d.toUTCString()+';path=/'; 
			element.classList.add('emtheme-cookie-fade');
		});

	}


	addCookieAccept();
	addGoUp();
});
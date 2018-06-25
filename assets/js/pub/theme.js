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

		goUp.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="emtheme-goup-arrow-svg" viewBox="0 0 24 24"><path class="emtheme-goup-arrow" d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/><path d="M0 0h24v24H0z" fill="none"/></svg>'

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


	var setMobileMenu = function() {

		var container = document.querySelector('.navbar-container');
		var menu = document.querySelector('.navbar-menu');

		$('.navbar-identity, .navbar-search').show();


		var icon = H({class: 'emtheme-mobile-icon'});

		var arr = H({class: 'emtheme-mobile-arrow'});

		arr.innerHTML = '<svg class="theme-nav-arrow-container" xmlns="http://www.w3.org/2000/svg" width="30" height="30"><path class="theme-nav-arrow" d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';

		icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path style="fill: white;" d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>';

		icon.addEventListener('click', function() {
			jQuery(menu).toggleClass('navbar-menu-show');
		});

		container.appendChild(icon);

		var arrow = jQuery('.theme-nav-arrow-container');

		arrow.hide();

		jQuery('.menu-has-child').each(function() {

			var a = jQuery(arr).clone();

			a.on('click', function() {

				a.prev().toggle();			
			});

			this.append(a[0]);
		});
	}

	var setDesktopMenu = function() {
		var $ = jQuery;

		$('.emtheme-mobile-arrow, .emtheme-mobile-icon').remove();

		$('.theme-nav-arrow-container').show();
		$('.desktop-hidden').hide();


	}

	// if (jQuery(window).width() < 1280)
	 
	var desktop = (jQuery(window).width() > 1280);

	if (!desktop) setMobileMenu();

	jQuery(window).resize(function() { 
		if ($(window).width() > 1280 && !desktop) {
			desktop = true;
			setDesktopMenu();
		}

		else {
			desktop = false;
			setMobileMenu();
		}


	});
	addCookieAccept();
	addGoUp();
});
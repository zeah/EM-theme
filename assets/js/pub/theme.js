(function() {
	// console.log('hi');
	// var $ = jQuery;
	var H = function(o) {

		if (!o) o = {};

		var e = document.createElement(o.el || 'div');


		for (var obj in o) 
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

		if (location.href.indexOf('customize_changeset') == -1) {
			var cookie_list = decodeURIComponent(document.cookie).split('; ')
			for (var cookie in cookie_list) {
				if (cookie_list[cookie].indexOf('cookieAccept=') == 0) {
					element.style.display = 'none';
					return; 
				}
			}
		}

		element.style.opacity = 1;

		button.addEventListener('click', function() { 
			var d = new Date();
			d.setTime(d.getTime() + (120*24*60*60*1000));

			document.cookie = 'cookieAccept=ok;expires='+d.toUTCString()+';path=/'; 
			element.classList.add('emtheme-cookie-fade');
		});

	}


	// var setMobileMenu = function() {

	// 	if ($('.mobile-icon-container').length) return;

	// 	// console.log(document.querySelectorAll('.mobile-icon-container'));

	// 	var container = document.querySelector('.navbar-container');
	// 	var menu = document.querySelector('.navbar-menu');

	// 	// $('.navbar-identity, .navbar-search').show();

	// 	var identity = document.querySelector('.navbar-identity');
	// 	if (identity) identity.style.display = 'block';

	// 	var search = document.querySelector('.navbar-search');
	// 	if (search) search.style.display = 'block';

	// 	var icon = H({class: 'mobile-icon-container'});

	// 	var arr = H({class: 'mobile-arrow-container'});

	// 	arr.innerHTML = '<svg class="nav-arrow-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path class="nav-arrow" d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';

	// 	icon.innerHTML = '<svg class="mobile-menu-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path class="mobile-icon" d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>';

	// 	icon.addEventListener('click', function() {
	// 		jQuery(menu).toggleClass('navbar-menu-show');
	// 	});

	// 	container.appendChild(icon);

	// 	var arrow = jQuery('.nav-arrow-container');

	// 	arrow.hide();

	// 	jQuery('.menu-has-child').each(function() {

	// 		var a = jQuery(arr).clone();

	// 		a.on('click', function() {

	// 			a.prev().toggle();			
	// 		});

	// 		this.append(a[0]);
	// 	});
	// }

	// var setDesktopMenu = function() {
	// 	var $ = jQuery;

	// 	$('.mobile-arrow-container, .mobile-icon-container').remove();

	// 	$('.nav-arrow-container').show();
	// 	$('.desktop-hidden').hide();
	var showSearch = function() {

		var d = document.querySelectorAll('.navbar-search-popup, .navbar-search-cancel-svg, .navbar-search-icon');

		for (var i = 0; i < d.length; i++)
			d[i].classList.toggle('navbar-hide');

		var e = document.querySelector('.emtheme-search-input');

		if (e) e.focus();

	} 

	var navbarSearch = document.querySelector('.navbar-search-button');

	if (navbarSearch) navbarSearch.addEventListener('click', showSearch);
	// }

	var setDesktop = function() {

		if (navbarSearch) navbarSearch.addEventListener('click', showSearch);

		// var a = document.querySelectorAll('.nav-arrow-container');
		// for (var i = 0; i < a.length; i++)
		// 	a[i].style.display = 'block';
		var a = document.querySelectorAll('.menu-has-child > a');
		for (var i = 0; i < a.length; i++) 	
			a[i].innerHTML += '<svg class="nav-arrow-container" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="nav-arrow" d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>';

		var b = document.querySelectorAll('.mobile-arrow-container');
		for (var i = 0; i < b.length; i++) {
			b[i].parentNode.removeChild(b[i]);
			// b[i].remove();
		}

		var c = document.querySelector('.mobile-icon-container');
		if (c) c.parentNode.removeChild(c);
		// if (c) c.remove();

	}

	var mobile = function() {

		if (document.querySelector('.mobile-icon-container')) return;

		if (navbarSearch) navbarSearch.removeEventListener('click', showSearch);

		// var container = document.createElement('div');
		var c = document.querySelector('.navbar-container'); 

		if (c) {

			document.querySelector('.navbar-menu').classList.remove('navbar-menu-show');

			c.innerHTML += '<button type="button" onclick="document.querySelector(\'.navbar-menu\').classList.toggle(\'navbar-menu-show\')" class="mobile-icon-container"><svg class="mobile-menu-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"></path><path class="mobile-icon" d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path></svg></button>';

			var p = document.querySelectorAll('.menu-has-child');
			for (var i = 0; i < p.length; i++)
				p[i].innerHTML += '<div class="mobile-arrow-container"><svg onclick="var sm = this.parentNode.previousSibling; if (sm) sm.classList.toggle(\'submenu-show\')" class="nav-arrow-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="nav-arrow" d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg></div>';
			
			var a = document.querySelectorAll('.nav-arrow-container');
			for (var i = 0; i < a.length; i++)
				a[i].parentNode.removeChild(a[i]);
				// a[i].remove();
				// a[i].style.display = 'none';

		}


	}

	// console.log(window.innerWidth+' '+jQuery(window).width());
	 
	// var desktop = (jQuery(window).width() > 1044);
	var desktop = (window.innerWidth > 1045);

	if (!desktop) mobile();

	window.addEventListener('resize', function() {
	// jQuery(window).resize(function() { 
		if (!desktop) {

			if (window.innerWidth  > 1044) {
			// if ($(window).width() > 1027) {
				desktop = true;
				setDesktop();
				// setDesktopMenu();
			}

		}
		else {
			if (window.innerWidth  < 1045) {
			// if ($(window).width() < 1028) {
				desktop = false;
				mobile();
				// setMobileMenu();
			}

		}

	});


	// var showSearch = function() {

	// 	var d = document.querySelectorAll('.navbar-search-popup, .navbar-search-cancel-svg, .navbar-search-icon');

	// 	for (var i = 0; i < d.length; i++)
	// 		d[i].classList.toggle('navbar-hide');

	// } 

	// var navbarSearch = document.querySelector('.navbar-search-button');

	// if (navbarSearch)
	// 	navbarSearch.addEventListener('click', showSearch);

	// var search = {
		// html: '<div class="navbar-search-popup"><form action="'+location.href+'" method="get" role="search"><input name="s" type="search" autocomplete><button type="submit">Søk</button></form></div>',
	// }

	// navbar search feature
	// $('.navbar-search-icon, .navbar-search-cancel-svg').on('click', function() {
	// $('.navbar-search-button').on('click', function() {

	// 	$('.navbar-search-popup, .navbar-search-cancel-svg, .navbar-search-icon').toggle();
	// 	$('.emtheme-search-input').focus();
	// 	// if ($('.navbar-search-popup').length) {
	// 	// 	$('.navbar-search-popup').remove();
	// 	// 	return;
	// 	// }
	// 	// $('body').append(search.html);
	// });



	addCookieAccept();
	addGoUp();
})();
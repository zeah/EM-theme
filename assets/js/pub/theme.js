(function() {

	// helper function
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


	// go back up button
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


	// adding privacy accept window
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


	// clicking show search button and toggling the search elements
	var showSearch = function() {

		var d = document.querySelectorAll('.navbar-search-popup, .navbar-search-cancel-svg, .navbar-search-icon');

		for (var i = 0; i < d.length; i++)
			d[i].classList.toggle('navbar-hide');

		var e = document.querySelector('.emtheme-search-input');

		if (e) e.focus();

	} 

	// adding the search click event
	var navbarSearch = document.querySelector('.navbar-search-button');
	if (navbarSearch) navbarSearch.addEventListener('click', showSearch);

	// event for window resize (fixing menu to desktop menu)
	var setDesktop = function() {

		if (navbarSearch) navbarSearch.addEventListener('click', showSearch);

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

	// event for window resize (fixing menu to mobile menu)
	var setMobile = function() {

		if (document.querySelector('.mobile-icon-container')) return;

		if (navbarSearch) navbarSearch.removeEventListener('click', showSearch);

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

	 
	var desktop = (window.innerWidth > 1045);

	if (!desktop) mobile();

	// redo
	window.addEventListener('resize', function() {
		if (!desktop) {

			if (window.innerWidth  > 1044) {
				desktop = true;
				setDesktop();
			}

		}
		else {
			if (window.innerWidth  < 1045) {
				desktop = false;
				setMobile();
			}

		}

	});



	addCookieAccept();
	addGoUp();


	// adding gclid and msclkid to all links
	var addToLinks = function() {

		var search = window.location.search.substring(1);
		if (!search) return;

		var adding = '';

		// splitting query string
		var query = search.split('&');

		// getting query string to be added
		for (var i in query) {
			var pair = query[i].split('=');

			var decoded = decodeURIComponent(pair[0];

			if (decoded === 'gclid')
				adding += 'gclid' + '=' + pair[1] + '&';

			else if (decoded === 'msclkid')
				adding += 'msclkid' + '=' + pair[1] + '&';
		}

		// removing last &
		adding = adding.substring(0, adding.length-1);

		// adding to all the links
		var links = document.getElementsByTagName('a');
		for (var i in links) {
			var url = links[i].href;
			if (!url) continue;

			if (url.indexOf('?') != -1) links[i].href += '&' + adding;
			else links[i].href += '?' + adding;
		}

	}

	addToLinks();

})();
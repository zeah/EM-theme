jQuery(() => {

	let links = document.querySelectorAll('.emtheme-documentation a[href^="#"]');

	let visited = null;

	for (let n of links) 
		n.addEventListener('click', (e) => { 
			let href = e.target.href;
			setTimeout(() => window.scrollTo(window.scrollX, window.scrollY - 150), 0) 

			let element = document.querySelector(href.substring(href.indexOf('#')));

			if (!element) return;

			if (visited) visited.style.border = 'none';
			visited = element;
			element.style.border = 'dotted 2px #bcb';
		});


});
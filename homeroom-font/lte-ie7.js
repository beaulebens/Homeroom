/* Use this script if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'Homeroom\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-pencil' : '&#xe01c;',
			'icon-pictures' : '&#xe019;',
			'icon-camera' : '&#xe01d;',
			'icon-file' : '&#xe002;',
			'icon-location' : '&#xe004;',
			'icon-calendar' : '&#xe01e;',
			'icon-box-add' : '&#xe020;',
			'icon-reply' : '&#xe023;',
			'icon-comments' : '&#xe029;',
			'icon-comments-2' : '&#xe02a;',
			'icon-user' : '&#xe02b;',
			'icon-key' : '&#xe01b;',
			'icon-zoom-out' : '&#xe02d;',
			'icon-zoom-in' : '&#xe02c;',
			'icon-search' : '&#xe000;',
			'icon-grid-view' : '&#xe017;',
			'icon-cloud' : '&#xe01a;',
			'icon-eye' : '&#xe02e;',
			'icon-heart' : '&#xe00d;',
			'icon-star' : '&#xe00e;',
			'icon-star-2' : '&#xe030;',
			'icon-star-3' : '&#xe02f;',
			'icon-loop' : '&#xe031;',
			'icon-arrow-right' : '&#xe00b;',
			'icon-arrow-left' : '&#xe00c;',
			'icon-share' : '&#xe001;',
			'icon-embed' : '&#xe033;',
			'icon-code' : '&#xe032;',
			'icon-mail' : '&#xe022;',
			'icon-flickr' : '&#xe014;',
			'icon-vimeo' : '&#xe034;',
			'icon-youtube' : '&#xe035;',
			'icon-feed' : '&#xe018;',
			'icon-twitter' : '&#xe016;',
			'icon-instagram' : '&#xe027;',
			'icon-facebook' : '&#xe011;',
			'icon-google-plus' : '&#xe025;',
			'icon-picassa' : '&#xe013;',
			'icon-dribbble' : '&#xe015;',
			'icon-github' : '&#xe006;',
			'icon-wordpress' : '&#xe021;',
			'icon-delicious' : '&#xe009;',
			'icon-lastfm' : '&#xe003;',
			'icon-linkedin' : '&#xe01f;',
			'icon-soundcloud' : '&#xe007;',
			'icon-amazon' : '&#xe008;',
			'icon-yahoo' : '&#xe00f;',
			'icon-tumblr' : '&#xe012;',
			'icon-stumbleupon' : '&#xe036;',
			'icon-pinterest' : '&#xe037;',
			'icon-foursquare' : '&#xe010;',
			'icon-link' : '&#xe005;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; i < els.length; i += 1) {
		el = els[i];
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};
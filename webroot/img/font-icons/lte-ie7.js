/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'ws-font\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-ws-paintball' : '&#x21;',
			'icon-ws-relaxation' : '&#x22;',
			'icon-ws-volley' : '&#x23;',
			'icon-ws-velo' : '&#x24;',
			'icon-ws-ultimate' : '&#x25;',
			'icon-ws-tree' : '&#x26;',
			'icon-ws-tennis' : '&#x27;',
			'icon-ws-surf' : '&#x28;',
			'icon-ws-squach' : '&#x29;',
			'icon-ws-ski' : '&#x2a;',
			'icon-ws-skate' : '&#x2b;',
			'icon-ws-running' : '&#x2c;',
			'icon-ws-rugby' : '&#x2d;',
			'icon-ws-roller' : '&#x2e;',
			'icon-ws-rando' : '&#x2f;',
			'icon-ws-pingpong' : '&#x30;',
			'icon-ws-patin' : '&#x31;',
			'icon-ws-natation' : '&#x32;',
			'icon-ws-laser' : '&#x33;',
			'icon-ws-kayak' : '&#x34;',
			'icon-ws-karting' : '&#x35;',
			'icon-ws-horse' : '&#x36;',
			'icon-ws-handball' : '&#x37;',
			'icon-ws-golf' : '&#x38;',
			'icon-ws-FootUS' : '&#x39;',
			'icon-ws-foot' : '&#x3a;',
			'icon-ws-fitness' : '&#x3b;',
			'icon-ws-climbing' : '&#x3c;',
			'icon-ws-circus' : '&#x3d;',
			'icon-ws-bowls' : '&#x3e;',
			'icon-ws-bowling' : '&#x3f;',
			'icon-ws-bmx' : '&#x40;',
			'icon-ws-billard' : '&#x41;',
			'icon-ws-basketball' : '&#x42;',
			'icon-ws-baseball' : '&#x43;',
			'icon-ws-badminton' : '&#x44;',
			'icon-ws-phone' : '&#x45;',
			'icon-ws-location' : '&#x46;',
			'icon-ws-location-2' : '&#x47;',
			'icon-ws-tags' : '&#x48;',
			'icon-ws-tag' : '&#x49;',
			'icon-ws-alarm' : '&#x4a;',
			'icon-ws-stopwatch' : '&#x4b;',
			'icon-ws-calendar' : '&#x4c;',
			'icon-ws-compass' : '&#x4d;',
			'icon-ws-map' : '&#x4e;',
			'icon-ws-map-2' : '&#x4f;',
			'icon-ws-calendar-2' : '&#x50;',
			'icon-ws-bubble' : '&#x51;',
			'icon-ws-bubbles' : '&#x52;',
			'icon-ws-bubbles-2' : '&#x53;',
			'icon-ws-bubble-2' : '&#x54;',
			'icon-ws-bubbles-3' : '&#x55;',
			'icon-ws-bubbles-4' : '&#x56;',
			'icon-ws-user' : '&#x57;',
			'icon-ws-users' : '&#x58;',
			'icon-ws-thumbs-up' : '&#x59;',
			'icon-ws-heart' : '&#x5a;',
			'icon-ws-heart-2' : '&#x5b;',
			'icon-ws-star' : '&#x5c;',
			'icon-ws-star-2' : '&#x5d;',
			'icon-ws-happy' : '&#x5e;',
			'icon-ws-happy-2' : '&#x5f;',
			'icon-ws-smiley' : '&#x60;',
			'icon-ws-smiley-2' : '&#x61;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-ws-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};
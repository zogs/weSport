/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'ws-font\'">' + entity + '</span>' + html;
	}
	var icons = {
			'ws-icon-relaxation' : '&#x22;',
			'ws-icon-volley' : '&#x23;',
			'ws-icon-velo' : '&#x24;',
			'ws-icon-ultimate' : '&#x25;',
			'ws-icon-tree' : '&#x26;',
			'ws-icon-tennis' : '&#x27;',
			'ws-icon-surf' : '&#x28;',
			'ws-icon-squach' : '&#x29;',
			'ws-icon-ski' : '&#x2a;',
			'ws-icon-skate' : '&#x2b;',
			'ws-icon-running' : '&#x2c;',
			'ws-icon-rugby' : '&#x2d;',
			'ws-icon-roller' : '&#x2e;',
			'ws-icon-rando' : '&#x2f;',
			'ws-icon-pingpong' : '&#x30;',
			'ws-icon-patin' : '&#x31;',
			'ws-icon-laser' : '&#x33;',
			'ws-icon-kayak' : '&#x34;',
			'ws-icon-karting' : '&#x35;',
			'ws-icon-horse' : '&#x36;',
			'ws-icon-handball' : '&#x37;',
			'ws-icon-golf' : '&#x38;',
			'ws-icon-FootUS' : '&#x39;',
			'ws-icon-foot' : '&#x3a;',
			'ws-icon-fitness' : '&#x3b;',
			'ws-icon-climbing' : '&#x3c;',
			'ws-icon-circus' : '&#x3d;',
			'ws-icon-bowls' : '&#x3e;',
			'ws-icon-bowling' : '&#x3f;',
			'ws-icon-bmx' : '&#x40;',
			'ws-icon-billard' : '&#x41;',
			'ws-icon-basketball' : '&#x42;',
			'ws-icon-baseball' : '&#x43;',
			'ws-icon-badminton' : '&#x44;',
			'ws-icon-phone' : '&#x45;',
			'ws-icon-location' : '&#x46;',
			'ws-icon-location-2' : '&#x47;',
			'ws-icon-tags' : '&#x48;',
			'ws-icon-tag' : '&#x49;',
			'ws-icon-alarm' : '&#x4a;',
			'ws-icon-stopwatch' : '&#x4b;',
			'ws-icon-calendar' : '&#x4c;',
			'ws-icon-compass' : '&#x4d;',
			'ws-icon-map' : '&#x4e;',
			'ws-icon-map-2' : '&#x4f;',
			'ws-icon-calendar-2' : '&#x50;',
			'ws-icon-bubble' : '&#x51;',
			'ws-icon-bubbles' : '&#x52;',
			'ws-icon-bubbles-2' : '&#x53;',
			'ws-icon-bubble-2' : '&#x54;',
			'ws-icon-bubbles-3' : '&#x55;',
			'ws-icon-bubbles-4' : '&#x56;',
			'ws-icon-user' : '&#x57;',
			'ws-icon-users' : '&#x58;',
			'ws-icon-thumbs-up' : '&#x59;',
			'ws-icon-heart' : '&#x5a;',
			'ws-icon-heart-2' : '&#x5b;',
			'ws-icon-star' : '&#x5c;',
			'ws-icon-star-2' : '&#x5d;',
			'ws-icon-happy' : '&#x5e;',
			'ws-icon-happy-2' : '&#x5f;',
			'ws-icon-smiley' : '&#x60;',
			'ws-icon-smiley-2' : '&#x61;',
			'ws-icon-thumbs-up-2' : '&#x62;',
			'ws-icon-loupe' : '&#x64;',
			'ws-icon-plus-alt' : '&#x63;',
			'ws-icon-danse' : '&#x65;',
			'ws-icon-voile' : '&#x66;',
			'ws-icon-paintball' : '&#x21;',
			'ws-icon-fighting' : '&#xe001;',
			'ws-icon-flying' : '&#xe000;',
			'ws-icon-other' : '&#xe002;',
			'ws-icon-swimming' : '&#xe003;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, c, el;
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
		c = c.match(/ws-icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};
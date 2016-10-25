Qtx = Qtx || {};
if ( typeof Qtx.cookie !== 'undefined' ) {
	cookie =  [ Qtx.cookie.name, Qtx.cookie.value ].join('=');
	cookie += ';expires=' + (new Date(new Date().setFullYear(new Date().getFullYear() + 1))).toUTCString();
	cookie += ';path=/';
	document.cookie = cookie;
}

function stopYoutube() {
    var iframe = $('#sgtYoutube');
    alert(iframe.src);
    if ( iframe ) {
        var iframeSrc = iframe.src;
        
		iframe.src = iframeSrc;
	}
}
function validateForm(){
	var cb = document.getElementById('form-privacy-checkbox');
	if(cb.checked){
		document.getElementById('com_anm22_wb_plugin_contact_form').submit();
	} else {
		document.getElementById('form-privacy-checkbox').style.outline = 'red solid 3px';
		console.log("Yes");
	}
}
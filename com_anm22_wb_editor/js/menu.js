function com_anm22_wb_editor_com_anm22_wb_editor_page_element_menu_openMobileMenu(id){
	if (document.getElementById("com_anm22_wb_editor_com_anm22_wb_editor_page_element_menu_"+id+"_menu_mobile_list").style.display == "block") {
		document.getElementById("com_anm22_wb_editor_com_anm22_wb_editor_page_element_menu_"+id+"_menu_mobile_list").style.display = "none"
	} else {
		document.getElementById("com_anm22_wb_editor_com_anm22_wb_editor_page_element_menu_"+id+"_menu_mobile_list").style.display = "block"
	}
}
<?php
/*
 * Author: ANM22
 * Last modified: 16 Jan 2017 - GMT +1 23:51
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* HTML */

class com_anm22_wb_editor_page_element_html extends com_anm22_wb_editor_page_element {

    var $text;
    var $elementClass = "com_anm22_wb_editor_page_element_html";
    var $elementPlugin = "com_anm22_wb_editor";

    function importXMLdoJob($xml) {
        $this->text = htmlspecialchars_decode($xml->text);
    }

    function show() {
        echo $this->text;
    }

}
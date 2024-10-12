<?php

/**
 * HTML plugin
 *
 * @copyright 2024 Paname srl
 */
class com_anm22_wb_editor_page_element_html extends com_anm22_wb_editor_page_element
{

    var $text;
    var $elementClass = "com_anm22_wb_editor_page_element_html";
    var $elementPlugin = "com_anm22_wb_editor";

    /**
     * @deprecated since editor 3.0
     * 
     * Method to init the element.
     * 
     * @param SimpleXMLElement $xml Element data
     * @return void
     */
    public function importXMLdoJob($xml)
    {
        $this->text = htmlspecialchars_decode($xml->text);
    }

    /**
     * Method to init the element.
     * 
     * @param mixed[] $data Element data
     * @return void
     */
    public function initData($data)
    {
        if (isset($data['text']) && $data['text']) {
            $this->text = htmlspecialchars_decode($data['text']);
        }
    }

    /**
     * Render the page element
     * 
     * @return void
     */
    function show()
    {
        echo $this->text;
    }
}

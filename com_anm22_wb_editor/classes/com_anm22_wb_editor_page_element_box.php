<?php
/*
 * Author: ANM22
 * Last modified: 28 Jan 2019 - GMT +1 22:30
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

class com_anm22_wb_editor_page_element_box extends com_anm22_wb_editor_page_element {

    var $elementClass = "com_anm22_wb_editor_page_element_box";
    var $elementPlugin = "com_anm22_wb_editor";
    var $elementClassName = "Box";
    var $elementClassIcon = "images/plugin_icons/HTML.png";
    var $cssClass;
    var $elements = array();
    protected $htmlAttributes = array();

    function importXMLdoJob($xml) {
        $this->cssClass = $xml->cssClass;
        if ($xml->htmlAttributes) {
            $this->htmlAttributes = json_decode($xml->htmlAttributes,true);
        }
    }

    function show() {
        echo '<div class="' . $this->elementPlugin . "_" . $this->elementClass;
                if (($this->cssClass) and ( $this->cssClass != "")) {
                    echo ' ' . $this->cssClass;
                }
                echo '"';
                // Inserisco attributi HTML
                if ($this->htmlAttributes) {
                    foreach ($this->htmlAttributes as $attributeName => $attributeValue) {
                        echo ' '.$attributeName.'="'.$attributeValue.'"';
                    }
                }
                echo '>';
            $containerId = 'e'.$this->id;
            $containerElements = null;

            if ($this->getDefaultPageElement()) {
                // Recupero elementi in caso il box appartenga alla pagina di default
                $containerElements = array();
                if (isset($this->page->defaultPage->conteiners)) {
                    foreach ($this->page->defaultPage->conteiners->conteiner as $defaultConteiner) {
                        
                        if (((string) $defaultConteiner->id) == $containerId) {
                            
                            if ($defaultConteiner->item) {
                                foreach ($defaultConteiner->item as $item) {
                                    $containerElements[] = "d" . intval($item);
                                }
                            }
                            break;
                        }
                    }
                }
            } else {
                // Recupero elementi in caso il box appartenga alla pagina principale
                if (isset($this->page->conteiners) and isset($this->page->conteiners[$containerId])) {
                    $containerElements = $this->page->conteiners[$containerId]['items'];
                }
            }

            // Renderizzo tutti i blocchetti
            if ($containerElements) {
                foreach ($containerElements as $item) {
                    if ($this->getDefaultPageElement()) {
                        $this->page->elements[$item]->setDefaultPageElement(true);
                    }
                    $this->page->elements[$item]->show();
                }
            }
        echo '</div>';
    }

}
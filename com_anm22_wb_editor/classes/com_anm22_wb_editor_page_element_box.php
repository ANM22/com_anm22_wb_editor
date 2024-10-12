<?php

/**
 * Box plugin
 *
 * @copyright 2024 Paname srl
 */

class com_anm22_wb_editor_page_element_box extends com_anm22_wb_editor_page_element
{

    var $elementClass = "com_anm22_wb_editor_page_element_box";
    var $elementPlugin = "com_anm22_wb_editor";
    var $elementClassName = "Box";
    var $elementClassIcon = "images/plugin_icons/HTML.png";
    var $cssClass;
    var $elements = [];
    protected $htmlAttributes = [];

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
        $this->cssClass = $xml->cssClass;
        if ($xml->htmlAttributes) {
            $this->htmlAttributes = json_decode($xml->htmlAttributes, true);
        }
    }

    /**
     * Method to init the element.
     * 
     * @param mixed[] $data Element data
     * @return void
     */
    public function initData($data)
    {
        $this->cssClass = $data['cssClass'];
        if (isset($data['htmlAttributes']) && $data['htmlAttributes']) {
            $this->htmlAttributes = json_decode($data['htmlAttributes'], true);
        }
    }

    /**
     * Render the page element
     * 
     * @return void
     */
    public function show()
    {
        echo '<div class="' . $this->elementPlugin . "_" . $this->elementClass;
            if (($this->cssClass) && ($this->cssClass != "")) {
                echo ' ' . $this->cssClass;
            }
            echo '"';
            // Inserisco attributi HTML
            if ($this->htmlAttributes) {
                foreach ($this->htmlAttributes as $attributeName => $attributeValue) {
                    echo ' ' . $attributeName . '="' . $attributeValue . '"';
                }
            }
        echo '>';
            $containerId = 'e' . $this->id;
            $containerElements = null;

            if ($this->getDefaultPageElement()) {
                // Recupero elementi in caso il box appartenga alla pagina di default
                $containerElements = [];
                if (isset($this->page->defaultPage['containers'])) {
                    foreach ($this->page->defaultPage['containers'] as $defaultContainer) {

                        if (((string) $defaultContainer['id']) == $containerId) {

                            if ($defaultContainer->item) {
                                foreach ($defaultContainer->item as $item) {
                                    $containerElements[] = "d" . intval($item);
                                }
                            }
                            break;
                        }
                    }
                }
            } else {
                // Recupero elementi in caso il box appartenga alla pagina principale
                if (isset($this->page->containers) && isset($this->page->containers[$containerId])) {
                    $containerElements = $this->page->containers[$containerId]['items'];
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

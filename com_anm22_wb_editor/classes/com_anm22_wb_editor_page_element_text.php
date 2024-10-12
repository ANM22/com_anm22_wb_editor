<?php

/**
 * Text plugin
 *
 * @copyright 2024 Paname
 */
class com_anm22_wb_editor_page_element_text extends com_anm22_wb_editor_page_element
{

    var $elementClass = "com_anm22_wb_editor_page_element_text";
    var $elementPlugin = "com_anm22_wb_editor";
    var $title = null;
    private $headingTag = 'h1';
    var $text;
    private $anim = null;
    var $cssClass;
    var $cssInlineStyles;

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
        $this->title = htmlspecialchars_decode($xml->title);
        $this->text = htmlspecialchars_decode($xml->text);
        if ($xml->anim && $xml->anim != "" && $xml->anim != "none") {
            $this->anim = $xml->anim;
        }
        $this->cssClass = htmlspecialchars_decode($xml->cssClass);
        $this->cssInlineStyles = htmlspecialchars_decode($xml->cssInlineStyles);
        if (isset($xml->headingTag)) {
            $this->setHeadingTag(htmlspecialchars_decode($xml->headingTag));
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
        if (isset($data['title']) && $data['title']) {
            $this->title = htmlspecialchars_decode($data['title']);
        }
        if (isset($data['text']) && $data['text']) {
            $this->text = htmlspecialchars_decode($data['text']);
        } else {
            $this->text = '';
        }
        if (isset($data['anim']) && $data['anim'] && $data['anim'] != "" && $data['anim'] != "none") {
            $this->anim = $data['anim'];
        }
        if (isset($data['cssClass']) && $data['cssClass']) {
            $this->cssClass = htmlspecialchars_decode($data['cssClass']);
        } else {
            $this->cssClass = null;
        }
        if (isset($data['cssInlineStyles']) && $data['cssInlineStyles']) {
            $this->cssInlineStyles = htmlspecialchars_decode($data['cssInlineStyles']);
        } else {
            $this->cssInlineStyles = null;
        }
        if (isset($data['headingTag'])) {
            $this->setHeadingTag(htmlspecialchars_decode($data['headingTag']));
        }
    }

    /**
     * Render the page element
     * 
     * @return void
     */
    public function show()
    {
        if ($this->anim) {
            /* Animate.css */
            include_once $this->page->getHomeFolderRelativePHPURL() . "ANM22WebBase/website/plugins/com_anm22_wb_libs/js/wow.php";
            /* Scroll reveal */
            include_once $this->page->getHomeFolderRelativePHPURL() . "ANM22WebBase/website/plugins/com_anm22_wb_libs/css/animate.php";
        }
        
        // Template Inline styles
        if ( isset( $this->page->templateInlineStyles[$this->elementPlugin . '_' . $this->elementClass] ) ) {
            $templateInlineStyle = $this->page->templateInlineStyles[$this->elementPlugin . '_' . $this->elementClass];
        } else {
            $templateInlineStyle = "";
        }
        if ( isset( $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-h1"] ) ) {
            $templateInlineStyleH1 = $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-h1"];
        } else {
            $templateInlineStyleH1 = "";
        }
        if ( isset( $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-p"] ) ) {
            $templateInlineStyleP = $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-p"];
        } else {
            $templateInlineStyleP = "";
        }
        
        echo '<div class="' . $this->elementPlugin . "_" . $this->elementClass;
        if ($this->anim) {
            echo ' wow ' . $this->anim . ' ';
        }
        if (($this->cssClass) and ( $this->cssClass != "")) {
            echo ' ' . $this->cssClass;
        }
        echo '" ';
        if (($templateInlineStyle != "") or ( ($this->cssInlineStyles) and ( $this->cssInlineStyles != ""))) {
            echo 'style=" ' . $templateInlineStyle . ' ' . $this->cssInlineStyles . ' "';
        }
        echo '>';
            if ($this->title && $this->title != "") {
                echo '<' . $this->getHeadingTag();
                if ($templateInlineStyleH1 != "") {
                    echo ' style="' . $templateInlineStyleH1 . '"';
                }
                echo '>' . $this->title . '</' . $this->getHeadingTag() . '>';
            }
            echo '<p';
            if ($templateInlineStyleP != "") {
                echo ' style="' . $templateInlineStyleP . '"';
            }
            echo '>' . nl2br($this->text) . '</p>';
            echo '<div style="clear:both;"></div>';
        echo '</div>';
    }
    
    public function getHeadingTag() {
        return $this->headingTag;
    }
    public function setHeadingTag($headingTag) {
        $this->headingTag = $headingTag;
        return $this;
    }

}

/* TEXT & TITLE */

class com_anm22_wb_editor_page_element_title_text extends com_anm22_wb_editor_page_element_text {}
<?php
/*
 * Author: ANM22
 * Last modified: 11 Jul 2017 - GMT +2 09:39
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* TEXT */

class com_anm22_wb_editor_page_element_text extends com_anm22_wb_editor_page_element {

    var $elementClass = "com_anm22_wb_editor_page_element_text";
    var $elementPlugin = "com_anm22_wb_editor";
    var $title;
    private $headingTag = 'h1';
    var $text;
    private $anim;
    var $cssClass;
    var $cssInlineStyles;

    function importXMLdoJob($xml) {
        $this->title = htmlspecialchars_decode($xml->title);
        $this->text = htmlspecialchars_decode($xml->text);
        $this->anim = $xml->anim;
        $this->cssClass = htmlspecialchars_decode($xml->cssClass);
        $this->cssInlineStyles = htmlspecialchars_decode($xml->cssInlineStyles);
        if (isset($xml->headingTag)) {
            $this->setHeadingTag(htmlspecialchars_decode($xml->headingTag));
        }
    }

    function show() {
        if ($this->anim != "" && $this->anim != "none") {
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
        if ($this->anim != "" and $this->anim != "none") {
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
            if ($this->title != "") {
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
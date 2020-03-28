<?php
/*
 * Author: ANM22
 * Last modified: 28 Mar 2019 - GMT +1 10:35
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* HTML */

class com_anm22_wb_editor_page_element_button extends com_anm22_wb_editor_page_element {

    protected $label;
    protected $cssClass;
    protected $actionMode = 'a';
    protected $click;
    protected $linkTarget;
    var $elementClass = "com_anm22_wb_editor_page_element_html";
    var $elementPlugin = "com_anm22_wb_editor";

    function importXMLdoJob($xml) {
        $this->label = htmlspecialchars_decode($xml->label);
        $this->cssClass = htmlspecialchars_decode($xml->cssClass);
        $this->actionMode = htmlspecialchars_decode($xml->actionMode);
        $this->click = htmlspecialchars_decode($xml->click);
        $this->linkTarget = htmlspecialchars_decode($xml->linkTarget);
    }

    function show() {
        switch ($this->actionMode) {
            case 'a':
                echo '<a';
                    if ($this->click and $this->click != '') {
                        echo ' href="'.$this->click.'"';
                    }
                    if ($this->linkTarget and $this->linkTarget != '') {
                        echo ' target="'.$this->linkTarget.'"';
                    }
                    echo ' class="' . $this->elementPlugin . "_" . $this->elementClass;
                    if (($this->cssClass) and ( $this->cssClass != "")) {
                        echo ' ' . $this->cssClass;
                    }
                    echo '"';
                echo '>';
                    echo $this->label;
                echo '</a>';
                break;
            case 'div':
                echo '<div';
                    echo ' class="' . $this->elementPlugin . "_" . $this->elementClass;
                    if (($this->cssClass) and ( $this->cssClass != "")) {
                        echo ' ' . $this->cssClass;
                    }
                    echo '"';
                    if ($this->click and $this->click != '') {
                        echo ' onclick="'.$this->click.'"';
                    }
                echo '>';
                    echo $this->label;
                echo '</div>';
                break;
            case 'button':
                echo '<button';
                    echo ' class="' . $this->elementPlugin . "_" . $this->elementClass;
                    if (($this->cssClass) and ( $this->cssClass != "")) {
                        echo ' ' . $this->cssClass;
                    }
                    echo '"';
                    if ($this->click and $this->click != '') {
                        echo ' onclick="'.$this->click.'"';
                    }
                echo '>';
                    echo $this->label;
                echo '</button>';
                break;
        }
    }

}
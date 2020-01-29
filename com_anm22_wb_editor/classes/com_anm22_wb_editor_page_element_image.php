<?php
/*
 * Author: ANM22
 * Last modified: 29 Jan 2020 - GMT +1 22:25
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* IMAGE */

class com_anm22_wb_editor_page_element_image extends com_anm22_wb_editor_page_element {

    var $elementClass = "com_anm22_wb_editor_page_element_image";
    var $elementPlugin = "com_anm22_wb_editor";
    var $src;
    var $srcPathAbsolute;
    var $alt = "";
    var $width = 100;
    var $rwidth = 1;
    var $minWidth;
    var $maxWidth;
    var $height = 300;
    var $rheight = 0;
    var $minHeight;
    var $maxHeight;
    var $link;
    protected $linkTarget = null;
    var $cssClass;

    function importXMLdoJob($xml) {
        $this->src = $xml->src;
        $this->srcPathAbsolute = intval($xml->srcPathAbsolute);
        $this->alt = $xml->alt;
        $this->width = $xml->width;
        $this->rwidth = intval($xml->rwidth);
        $this->minWidth = $xml->minWidth;
        $this->maxWidth = $xml->maxWidth;
        $this->height = $xml->height;
        $this->rheight = intval($xml->rheight);
        $this->minHeight = $xml->minHeight;
        $this->maxHeight = $xml->maxHeight;
        $this->link = $xml->link;
        if (isset($xml->linkTarget)) {
            $this->linkTarget = $xml->linkTarget;
        }
        $this->cssClass = $xml->cssClass;
    }

    function show() {
        $src = "";
        if (($this->page->link != "") and ( $this->page->link != "index") and ( !$this->srcPathAbsolute)) {
            $src .= "../";
        }
        if (isset($this->page->getVariables['sub']) and ( !$this->srcPathAbsolute)) {
            $src .= "../";
        }
        if (($this->src == "") or ( !$this->src)) {
            $src .= "../ANM22WebBase/upload/" . $this->page->language . "_" . $this->page->link . "_" . $this->id . ".png";
        } else {
            $src .= $this->src;
        }
        
        if (($this->link) and ( $this->link != "")) {
            echo '<a href="' . $this->link . '"';
                    if ($this->linkTarget and ($this->linkTarget != "")) {
                        echo ' target="' . $this->linkTarget . '"';
                    }
                    echo '>';
        }
        echo '<img src="' . $src . '"';
        if (($this->alt)and ( $this->alt != "")) {
            echo ' alt="' . $this->alt . '"';
        }
        if (($this->cssClass)and ( $this->cssClass != "")) {
            echo ' class="' . $this->cssClass . '"';
        }
        if ($this->page->getPageLanguage() == "mail") {
            if ($this->rwidth) {
                if (($this->maxWidth != "") and ( $this->maxWidth)) {
                    ?> width="<?= $this->maxWidth ?>"<?
                } else if (isset($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "_width"])) {
                    echo ' width="' . $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "_width"] . '"';
                } else {
                    ?> width="600"<?
                }
            } else {
                if (($this->width != "") and ( $this->width)) {
                    ?> width="<?= $this->width ?>"<?
                }
            }
        }
        echo' style="';
        if ($this->rwidth) {
            if (!(($this->width == "") or ( !$this->width))) {
                ?> width:<?= $this->width ?>%;<?
            } else {
                ?> width:100%;<?
            }
            if (($this->minWidth != "") and ( $this->minWidth)) {
                ?> min-width:<?= $this->minWidth ?>px;<?
            }
            if (($this->maxWidth != "") and ( $this->maxWidth)) {
                ?> max-width:<?= $this->maxWidth ?>px;<?
            }
        } else {
            if (($this->width != "") and ( $this->width)) {
                ?> width:<?= $this->width ?>px;<?
            }
        }
        if ($this->rheight) {
            if (!(($this->height == "") or ( !$this->height))) {
                ?> height:<?= $this->height ?>%;<?
            } else {
                ?> height:100%;<?
            }
            if (($this->minHeight != "") and ( $this->minHeight)) {
                ?> min-height:<?= $this->minHeight ?>px;<?
            }
            if (($this->maxHeight != "") and ( $this->maxHeight)) {
                ?> max-height:<?= $this->maxHeight ?>px;<?
            }
        } else {
            if (($this->height != "") and ( $this->height)) {
                ?> height:<?= $this->height ?>px;<?
            }
        }
        if (isset($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass])) {
            echo $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass];
        }
        echo '" />';
        if (($this->link)and ( $this->link != "")) {
            echo '</a>';
        }
    }

}
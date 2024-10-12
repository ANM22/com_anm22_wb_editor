<?php
/**
 * Image plugin
 * 
 * @copyright 2024 Paname srl
 */

/* IMAGE */

class com_anm22_wb_editor_page_element_image extends com_anm22_wb_editor_page_element
{

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

    /**
     * @deprecated since editor 3.0
     * 
     * Method to init the element.
     * 
     * @param SimpleXMLElement $xml Element data
     * @return void
     */
    function importXMLdoJob($xml)
    {
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

    /**
     * Method to init the element.
     * 
     * @param mixed[] $data Element data
     * @return void
     */
    public function initData($data)
    {
        $this->src = $data['src'];
        $this->srcPathAbsolute = intval($data['srcPathAbsolute']);
        $this->alt = $data['alt'];
        $this->width = $data['width'];
        $this->rwidth = intval($data['rwidth']);
        $this->minWidth = $data['minWidth'];
        $this->maxWidth = $data['maxWidth'];
        $this->height = $data['height'];
        $this->rheight = intval($data['rheight']);
        $this->minHeight = $data['minHeight'];
        $this->maxHeight = $data['maxHeight'];
        $this->link = $data['link'];
        if (isset($data['linkTarget'])) {
            $this->linkTarget = $data['linkTarget'];
        }
        $this->cssClass = $data['cssClass'];
    }

    /**
     * Render the page element
     * 
     * @return void
     */
    function show()
    {
        $src = "";
        if (($this->page->link != "") && ($this->page->link != "index") && (!$this->srcPathAbsolute)) {
            $src .= "../";
        }
        if (isset($this->page->getVariables['sub']) && (!$this->srcPathAbsolute)) {
            $src .= "../";
        }
        if (($this->src == "") || (!$this->src)) {
            $src .= "../ANM22WebBase/upload/" . $this->page->language . "_" . $this->page->link . "_" . $this->id . ".png";
        } else {
            $src .= $this->src;
        }
        
        if (($this->link) && ($this->link != "")) {
            echo '<a href="' . $this->link . '"';
                    if ($this->linkTarget and ($this->linkTarget != "")) {
                        echo ' target="' . $this->linkTarget . '"';
                    }
                    echo '>';
        }
        echo '<img src="' . $src . '"';
        if (($this->alt) && ($this->alt != "")) {
            echo ' alt="' . $this->alt . '"';
        }
        if (($this->cssClass) && ($this->cssClass != "")) {
            echo ' class="' . $this->cssClass . '"';
        }
        if ($this->page->getPageLanguage() == "mail") {
            if ($this->rwidth) {
                if (($this->maxWidth != "") && ($this->maxWidth)) {
                    ?> width="<?= $this->maxWidth ?>"<?
                } else if (isset($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "_width"])) {
                    echo ' width="' . $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "_width"] . '"';
                } else {
                    ?> width="600"<?
                }
            } else {
                if (($this->width != "") && ($this->width)) {
                    ?> width="<?= $this->width ?>"<?
                }
            }
        }
        echo' style="';
        if ($this->rwidth) {
            if (!(($this->width == "") || (!$this->width))) {
                ?> width:<?= $this->width ?>%;<?
            } else {
                ?> width:100%;<?
            }
            if (($this->minWidth != "") && ($this->minWidth)) {
                ?> min-width:<?= $this->minWidth ?>px;<?
            }
            if (($this->maxWidth != "") && ($this->maxWidth)) {
                ?> max-width:<?= $this->maxWidth ?>px;<?
            }
        } else {
            if (($this->width != "") && ($this->width)) {
                ?> width:<?= $this->width ?>px;<?
            }
        }
        if ($this->rheight) {
            if (!(($this->height == "") || (!$this->height))) {
                ?> height:<?= $this->height ?>%;<?
            } else {
                ?> height:100%;<?
            }
            if (($this->minHeight != "") && ($this->minHeight)) {
                ?> min-height:<?= $this->minHeight ?>px;<?
            }
            if (($this->maxHeight != "") && ($this->maxHeight)) {
                ?> max-height:<?= $this->maxHeight ?>px;<?
            }
        } else {
            if (($this->height != "") && ($this->height)) {
                ?> height:<?= $this->height ?>px;<?
            }
        }
        if (isset($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass])) {
            echo $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass];
        }
        echo '" />';
        if (($this->link) && ($this->link != "")) {
            echo '</a>';
        }
    }

}
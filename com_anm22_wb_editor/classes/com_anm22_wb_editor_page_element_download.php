<?php
/*
 * Author: ANM22
 * Last modified: 10 Mar 2017 - GMT +1 10:44
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* DOWNLOAD */

class com_anm22_wb_editor_page_element_download extends com_anm22_wb_editor_page_element {

    var $elementClass = "com_anm22_wb_editor_page_element_download";
    var $elementPlugin = "com_anm22_wb_editor";
    var $src;
    var $title;
    var $fileTitle;
    var $text;
    var $fileExt;
    var $fileIconURLPrefix;
    var $iconPathAbsolute = 0;

    function importXMLdoJob($xml) {
        $this->src = htmlspecialchars_decode($xml->src);
        $this->fileTitle = htmlspecialchars_decode($xml->fileTitle);
        $this->title = htmlspecialchars_decode($xml->title);
        $this->text = htmlspecialchars_decode($xml->text);
        $this->fileExt = htmlspecialchars_decode($xml->fileExt);
        $this->fileIconURLPrefix = $xml->fileIconURLPrefix;
        $this->iconPathAbsolute = htmlspecialchars_decode($xml->iconPathAbsolute);
    }

    function show() {
        if ($this->iconPathAbsolute == 1) {
            $srcPrefix = $this->fileIconURLPrefix;
        } else {
            $srcPrefix = "../";
            if (($this->page->link != "") and ( $this->page->link != "index") and ( !$this->srcPathAbsolute)) {
                $srcPrefix .= "../";
            }
        }
        $fileExt = $this->fileExt;
        if ((!$fileExt) or ( $fileExt == "")) {
            $fileExt = "pdf";
        }
        if (file_exists("../ANM22WebBase/website/plugins/" . $this->elementPlugin . "/img/files/" . $fileExt . ".png")) {
            $fileIcon = $srcPrefix . "ANM22WebBase/website/plugins/" . $this->elementPlugin . "/img/files/" . $fileExt . ".png";
        } else {
            $fileIcon = $srcPrefix . "ANM22WebBase/website/plugins/" . $this->elementPlugin . "/img/files/file.png";
        }
        ?><div class="<?= $this->elementPlugin ?>_<?= $this->elementClass ?>"<? if ($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass]) { ?> style="<?= $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass] ?>"<? } ?>><?
            echo '<a href="' . $this->src . '">';
                if ($this->title != "") {
                    echo '<h2';
                    if ($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-a-h2"]) {
                        echo ' style="' . $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-a-h2"] . '"';
                    }
                    echo '>' . $this->title . '</h2>';
                }
                ?><div id="preview" style="background-image:url(<?= $fileIcon ?>); background-size:cover; min-height:20px; min-width:20px;<? if ($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-a-preview"]) { ?> <?= $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-a-preview"] ?><? } ?>"></div><?
                if ($this->fileTitle != "") {
                    ?><p <? if ($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-a-h2"]) { ?> style=" <?= $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-a-h2"] ?> "<? } ?>><?= $this->fileTitle ?>.<?= $fileExt ?></p><?
                }
                ?><div style="clear:both;"></div><?
            echo '</a>';
        ?></div><?
    }
}
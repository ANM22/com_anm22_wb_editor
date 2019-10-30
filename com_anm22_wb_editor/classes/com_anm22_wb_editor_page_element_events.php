<?php
/*
 * Author: ANM22
 * Last modified: 16 Jan 2017 - GMT +1 23:59
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* EVENTS */

class com_anm22_wb_editor_page_element_events extends com_anm22_wb_editor_page_element {

    var $elementClass = "com_anm22_wb_editor_page_element_events";
    var $elementPlugin = "com_anm22_wb_editor";
    var $title;
    var $eventsShowLink;
    var $eventsCategory;
    var $eventsMode;
    // Preview
    var $eventsPreviewLimit;
    var $eventsPreviewColumns;
    var $eventPreviewTitle;
    var $eventPreviewImg;
    var $eventPreviewDate;
    var $eventPreviewDescription;
    var $eventPreviewMap;
    // View
    var $eventViewImg;
    var $eventViewTitle;
    var $eventViewDate;
    var $eventViewDescription;
    var $eventViewMap;
    var $eventsPageTitleOverwrite;
    var $rewrite;

    function importXMLdoJob($xml) {
        $this->title = $xml->title;
        $this->eventsShowLink = $xml->eventsShowLink;
        $this->eventsCategory = $xml->eventsCategory;
        $this->eventsMode = $xml->eventsMode;

        $this->eventsPreviewLimit = intval($xml->eventsPreviewLimit);
        $this->eventsPreviewColumns = $xml->eventsPreviewColumns;
        $this->eventPreviewTitle = intval($xml->eventPreviewTitle);
        $this->eventPreviewImg = intval($xml->eventPreviewImg);
        $this->eventPreviewDate = intval($xml->eventPreviewDate);
        $this->eventPreviewDescription = intval($xml->eventPreviewDescription);
        $this->eventPreviewMap = intval($xml->eventPreviewMap);

        $this->eventViewTitle = intval($xml->eventViewTitle);
        $this->eventViewImg = intval($xml->eventViewImg);
        $this->eventViewDate = intval($xml->eventViewDate);
        $this->eventViewDescription = intval($xml->eventViewDescription);
        $this->eventViewMap = intval($xml->eventViewMap);

        $this->eventsPageTitleOverwrite = intval($xml->eventsPageTitleOverwrite);
        $this->rewrite = intval($xml->rewrite);
    }

    function show() {
        $xmlNewsIndex = simplexml_load_file("../Events/eventsIndex.xml");
        $language = $this->page->language;
        $index = 0;
        if ($this->eventsShowLink) {
            $href = $this->eventsShowLink;
        } else {
            $href = "";
        }
        if (($this->eventsMode == "preview") or ( ($this->eventsMode != "view") and ! intval($this->page->getVariables['event']))) {
            $articleBlockWidth = intval(100 / $this->eventsPreviewColumns);
            ?>
            <div class="<?= $this->elementPlugin ?>_<?= $this->elementClass ?>_mode_preview">
                <?
                if (($this->title) and ( $this->title != "")) {
                    ?>
                    <h1><?= $this->title ?></h1>
                    <?
                }
                if ($this->rewrite != "off") {
                    $rewriteSymbol = "?";
                } else {
                    $rewriteSymbol = "&";
                }
                foreach ($xmlNewsIndex->EVENTS->ITEM as $xmlEventsIndexElement) {
                    $event = $xmlEventsIndexElement->FOLDER;
                    $eventXML = @simplexml_load_file("../Events/" . $event . "/event.xml");
                    if ((intval($eventXML->LANGUAGE->$language)) and ( (trim($eventXML->CATEGORY) == trim($this->eventsCategory)) or ( (($this->eventsCategory == "") or ( !$this->eventsCategory)) and ( trim($eventXML->CATEGORY) != "invisible")))) {
                        $index++;
                        ?>
                        <a href="<?= $href ?><?= $rewriteSymbol ?>event=<?= $event ?>">
                            <article style="width:<?= $articleBlockWidth ?>%;">
                                <?
                                if ($this->eventPreviewTitle) {
                                    ?>
                                    <h1><?= $eventXML->TITLE->$language ?></h1>
                                    <?
                                }
                                if (($this->eventPreviewImg) and ( file_exists("../Events/" . $event . "/img.png"))) {
                                    ?>
                                    <div class="news_preview_img" style="background-image:url(<? if (($this->page->link != "") and ( $this->page->link != "index")) { ?>../<? } ?>../Events/<?= $event ?>/img.png);">&nbsp;</div>
                                    <?
                                }
                                if ($this->eventPreviewDate) {
                                    ?>
                                    <h2><?= @date("d.m.Y", intval($eventXML->STARTDATE)) ?> - <?= @date("d.m.Y", intval($eventXML->ENDDATE)) ?></h2>
                                    <?
                                }
                                if ($this->eventPreviewDescription == 2) {
                                    ?>
                                    <p><?= substr($eventXML->TEXT->$language, 0, 300) ?>...</p>
                                    <?
                                }
                                if ($this->eventPreviewDescription == 12) {
                                    ?>
                                    <p><?= nl2br($eventXML->TEXT->$language) ?></p>
                                    <?
                                }
                                ?>
                                <div style="clear:both;"></div>
                            </article>
                        </a>
                        <?
                        if (($index % $this->eventsPreviewColumns) == 0) {
                            ?>
                            <div style="clear:left;"></div>
                            <?
                        }
                        if ($index >= $this->eventsPreviewLimit) {
                            break;
                        }
                    }
                }
                ?>
            </div>
            <?
        } else {
            $event = $this->page->getVariables['event'];
            $eventXML = @simplexml_load_file("../Events/" . $event . "/event.xml");
            ?>
            <div class="<?= $this->elementPlugin ?>_<?= $this->elementClass ?>_mode_view">
                <article>
                    <?
                    if ($this->eventViewTitle) {
                        ?>
                        <h1><?= $eventXML->TITLE->$language ?></h1>
                        <?
                    }
                    if (($this->eventViewImg) and ( file_exists("../Events/" . $event . "/img.png"))) {
                        ?>
                        <img src="<? if (($this->page->link != "") and ( $this->page->link != "index")) { ?>../<? } ?>../Events/<?= $event ?>/img.png" alt="<?= $eventXML->TITLE->$language ?>" class="news_view_img" />
                        <?
                    }
                    if ($this->eventViewDate) {
                        ?>
                        <h2><?= date("d.m.Y", intval($eventXML->STARTDATE)) ?> - <?= date("d.m.Y", intval($eventXML->ENDDATE)) ?></h2>
                        <?
                    }
                    if ($this->eventViewDescription == 2) {
                        ?>
                        <p><?= substr($eventXML->TEXT->$language, 0, 300) ?>...</p>
                        <?
                    }
                    if ($this->eventViewDescription == 1) {
                        ?>
                        <p><?= nl2br($eventXML->TEXT->$language) ?></p>
                        <?
                    }
                    ?>
                    <div style="clear:both;"></div>
                </article>
            </div>
            <?
        }
    }

}
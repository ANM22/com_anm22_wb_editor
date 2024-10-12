<?php

/**
 * Menu plugin
 *
 * @copyright 2024 Paname srl
 */
class com_anm22_wb_editor_page_element_menu extends com_anm22_wb_editor_page_element
{

    var $elementClass = "com_anm22_wb_editor_page_element_menu";
    var $elementPlugin = "com_anm22_wb_editor";
    var $layerLevel = 0;
    var $layerParent = 0;
    var $layerParentVisible = 0;
    var $menuMode;
    var $rewrite = "on";
    var $dropdown = "off";
    var $title;
    var $cssClass;

    /**
     * @deprecated since editor 3.0
     * 
     * Init element
     * 
     * @param SimpleXMLElement $xml Element data
     * @return void
     */
    function importXMLdoJob($xml)
    {
        $this->layerLevel = intval($xml->layerLevel);
        $this->layerParent = intval($xml->layerParent);
        $this->layerParentVisible = intval($xml->layerParentVisible);
        $this->menuMode = $xml->menuMode;
        $this->rewrite = $xml->rewrite;
        $this->dropdown = $xml->dropdown;
        $this->title = $xml->title;
        $this->cssClass = htmlspecialchars_decode($xml->cssClass);
    }

    /**
     * Init element
     * 
     * @param mixed[] $data Element data
     * @return void
     */
    function initData($data)
    {
        $this->layerLevel = intval($data['layerLevel']);
        $this->layerParent = intval($data['layerParent']);
        $this->layerParentVisible = intval($data['layerParentVisible']);
        $this->menuMode = $data['menuMode'];
        $this->rewrite = $data['rewrite'];
        $this->dropdown = $data['dropdown'];
        $this->title = $data['title'];
        if (isset($data['cssClass'])) {
            if (is_string($data['cssClass'])) {
                $this->cssClass = htmlspecialchars_decode($data['cssClass']);
            } else {
                $this->cssClass = $data['cssClass'];
            }
        }
    }

    /**
     * Method to render the page element
     * 
     * @return void
     */
    function show()
    {
        if ($this->menuMode == "") {
            $this->menuMode = "dm";
        }
        $languageSelected = $this->page->language . "";
        $pageIndexObject = new com_anm22_wb_editor_pages_index();
        if (file_exists("../ANM22WebBase/website/pages.json")) {
            $jsonPagesIndex = json_decode(file_get_contents("../ANM22WebBase/website/pages.json"), true);
            $pageIndexObject->initData($jsonPagesIndex);
        } else if (file_exists("../ANM22WebBase/website/pages.xml")) {
            $xmlPagesIndex = simplexml_load_file("../ANM22WebBase/website/pages.xml");
            $jsonPagesIndex = WebBaseXmlLogics::xmlToAssoc($xmlPagesIndex);
            $pageIndexObject->initData($jsonPagesIndex);
        }
        $websitePages = [];
        if ($this->layerLevel == 0) {
            $layerParentObject = $pageIndexObject;  /* Se non è figlio di nessuno, il suo parent è il livello di indice */
        } else {
            $layerParentObject = $pageIndexObject->layers[$this->layerParent . ""]; /* Se no, prende il livello del padre */
            if ($this->layerParentVisible) {
                $scanSelLay = $layerParentObject;
                $datasellayscan = [];
                $datasellayscan['id'] = $scanSelLay->id;
                if ($scanSelLay->menuName[$languageSelected]) {
                    $datasellayscan['name'] = $scanSelLay->menuName[$languageSelected];
                } else {
                    $datasellayscan['name'] = $scanSelLay->name;
                }
                $datasellayscan['link'] = $scanSelLay->link;
                $websitePages[] = $datasellayscan;
            }
        }
        if ($layerParentObject->pages) {
            foreach ($layerParentObject->pages as $pageId) {
                $scanSelLay = $pageIndexObject->layers[$pageId . ""];
                $datasellayscan = [];
                $datasellayscan['id'] = $scanSelLay->id;
                if (@$scanSelLay->menuName[$languageSelected]) {
                    $datasellayscan['name'] = $scanSelLay->menuName[$languageSelected]; /* Nome della pagina rispetto alla lingua */
                } else {
                    $datasellayscan['name'] = $scanSelLay->name; /* Altrimenti nome di default (dovrebbe essere EN o IT) */
                }
                $datasellayscan['link'] = $scanSelLay->link;
                $datasellayscan['childPages'] = [];
                /* versione a tendina */
                if ($scanSelLay->pages) {
                    foreach ($scanSelLay->pages as $childPageId) {
                        $scanChildSelLay = $pageIndexObject->layers[$childPageId . ""];
                        $datachildsellayscan = [];
                        $datachildsellayscan['id'] = $scanChildSelLay->id;
                        if ($scanChildSelLay->menuName[$languageSelected]) {
                            $datachildsellayscan['name'] = $scanChildSelLay->menuName[$languageSelected]; /* Nome della pagina rispetto alla lingua */
                        } else {
                            $datachildsellayscan['name'] = $scanChildSelLay->name; /* Altrimenti nome di default (dovrebbe essere EN o IT) */
                        }
                        $datachildsellayscan['link'] = $scanChildSelLay->link;
                        $datasellayscan['childPages'][] = $datachildsellayscan;
                    }
                }
                $websitePages[] = $datasellayscan;
            }
        }
        echo '<link href="' . $this->page->getHomeFolderRelativeHTMLURL() . 'ANM22WebBase/website/plugins/' . $this->elementPlugin . '/css/style.css?v=1" type="text/css" rel="stylesheet" />';
        echo '<script type="text/javascript" src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'ANM22WebBase/website/plugins/' . $this->elementPlugin . '/js/menu.js?v=1"></script>';
        echo '<nav id="' . $this->elementPlugin . '_' . $this->elementClass . '_' . $this->id . '_menu_desktop" class="' . $this->elementPlugin . '_' . $this->elementClass . '_menu_desktop';
            if ($this->menuMode == "d") {
                echo '_only';
            }
            if ($this->menuMode == "m") {
                echo '_none';
            }
            echo ' ' . ($this->cssClass ? $this->cssClass : '') . '">';
            if ($this->title && $this->title != "") {
                echo '<h1>' . $this->title . '</h1>';
            }
            echo '<ul>';
                if ($websitePages) {
                    foreach ($websitePages as $xmlPagesIndexElement) {
                        if ($this->rewrite != "off") {
                            echo '<a href="' . $this->page->getLanguageHomeFolderRelativeHTMLURL();
                                if (($xmlPagesIndexElement['link'] != "index") && ($xmlPagesIndexElement['link'] != "")) {
                                    echo $xmlPagesIndexElement['link'] . '/';     
                                }
                                echo '">';
                                echo '<li class="external-li ';
                                    if ($xmlPagesIndexElement['link'] == $this->page->getPageLink()) {
                                        echo ' selected';
                                    }
                                    echo '" id="li-with-hover-' . $xmlPagesIndexElement['link'] . '">';
                                    echo $xmlPagesIndexElement['name'];
                                    if ($xmlPagesIndexElement['childPages'] && $this->dropdown == "on") {
                                        echo '<script>';
                                            echo '$(document).ready(function () {';
                                                echo '$(\'#li-with-hover-' . $xmlPagesIndexElement['link'] . '\').hover(';
                                                    echo 'function () {';
                                                        echo '$(\'#ul-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'display\', \'block\');';
                                                    echo '},';
                                                    echo 'function () {';
                                                        echo '$(\'#ul-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'display\', \'none\');';
                                                    echo '});';
                                            echo '});';
                                        echo '</script>';
                                        echo '<ul id="ul-with-hover-' . $xmlPagesIndexElement['link'] . '" style="display: none;">';
                                            foreach ($xmlPagesIndexElement['childPages'] as $childPageToEcho) {
                                                echo '<a href="' . $this->page->getLanguageHomeFolderRelativeHTMLURL();
                                                    if (($childPageToEcho['link'] != "index") and ( $childPageToEcho['link'] != "")) {
                                                        echo $childPageToEcho['link'] . '/';
                                                    }
                                                    echo '">';
                                                    echo '<li ';
                                                        if ($childPageToEcho['link'] == $this->page->getPageLink()) {
                                                            echo 'class="selected"';
                                                        }
                                                        echo '>' . $childPageToEcho['name'];
                                                    echo '</li>';
                                                echo '</a>';
                                            }
                                        echo '</ul>';
                                    }
                                echo '</li>';
                            echo '</a>';
                        } else {
                            echo '<a href="' . $this->page->getLanguageHomeFolderRelativeHTMLURL();
                                if (($xmlPagesIndexElement['link'] != "index") && ($xmlPagesIndexElement['link'] != "")) {
                                    echo '?page=' . $xmlPagesIndexElement['link'];
                                }
                                echo '">';
                                echo '<li class="external-li ';
                                    if ($xmlPagesIndexElement['link'] == $this->page->getPageLink()) {
                                        echo ' selected';
                                    }
                                    echo '" id="li-with-hover-' . $xmlPagesIndexElement['link'] . '">' . $xmlPagesIndexElement['name'];
                                    if ($xmlPagesIndexElement['childPages'] && $this->dropdown == "on") {
                                        echo '<script>';
                                            echo '$(document).ready(function () {';
                                                echo '$(\'#li-with-hover-' . $xmlPagesIndexElement['link'] .'\').hover(';
                                                    echo 'function () {';
                                                        echo '$(\'#ul-with-hover-' . $xmlPagesIndexElement['link'] .'\').css(\'display\', \'block\');';
                                                    echo '},';
                                                    echo 'function () {';
                                                        echo '$(\'#ul-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'display\', \'none\');';
                                                    echo '});';
                                            echo '});';
                                        echo '</script>';
                                        echo '<ul id="ul-with-hover-' . $xmlPagesIndexElement['link'] . '" style="display: none;">';
                                            foreach ($xmlPagesIndexElement['childPages'] as $childPageToEcho) {
                                                echo '<a href="' . $this->page->getLanguageHomeFolderRelativeHTMLURL();
                                                    if (($childPageToEcho['link'] != "index") and ( $childPageToEcho['link'] != "")) {
                                                        echo $childPageToEcho['link'] . '/';
                                                    }
                                                    echo '">';
                                                    echo '<li ';
                                                        if ($childPageToEcho['link'] == $this->page->getPageLink()) {
                                                            echo 'class="selected"';
                                                        }
                                                        echo '>' . $childPageToEcho['name'];
                                                    echo '</li>';
                                                echo '</a>';
                                            }
                                        echo '</ul>';
                                    }
                                echo '</li>';
                            echo '</a>';
                        }
                    }
                }
            echo '</ul>';
        echo '</nav>';
        /* Start of mobile menu */
        echo '<nav id="' . $this->elementPlugin . '_' . $this->elementClass . '_' . $this->id . '_menu_mobile" class="' . $this->elementPlugin . '_' . $this->elementClass . '_menu_mobile';
            if ($this->menuMode == "m") {
                echo '_only';
            }
            if ($this->menuMode == "d") {
                echo '_none';
            }
            echo ' ' . ($this->cssClass ? $this->cssClass : '') . '">';
            echo '<a href="javascript:com_anm22_wb_editor_com_anm22_wb_editor_page_element_menu_openMobileMenu(\'' . $this->id . '\')"><div class="' . $this->elementPlugin . '_' . $this->elementClass . '_menu_mobile_button" style="min-height:20px; min-width:20px;"></div></a>';
            echo '<ul id="' . $this->elementPlugin . '_' . $this->elementClass . '_' . $this->id . '_menu_mobile_list">';
                if ($websitePages) {
                    foreach ($websitePages as $xmlPagesIndexElement) {
                        if ($this->rewrite != "off") {
                            echo '<a href="' . $this->page->getLanguageHomeFolderRelativeHTMLURL();
                                if (($xmlPagesIndexElement['link'] != "index") and ( $xmlPagesIndexElement['link'] != "")) {
                                    echo $xmlPagesIndexElement['link'] . '/';
                                }
                                echo '">';
                                echo '<li class="external-li ';
                                    if ($xmlPagesIndexElement['link'] == $this->page->getPageLink()) {
                                        echo ' selected';
                                    }
                                    echo '" id="mobile-li-with-hover-' . $xmlPagesIndexElement['link'] . '">';
                                    echo $xmlPagesIndexElement['name'];
                                    if ($xmlPagesIndexElement['childPages'] && $this->dropdown == "on") {
                                        echo '<span id="mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '" style="float: right; -ms-transform: rotate(90deg); -webkit-transform: rotate(90deg); transform: rotate(90deg);">></span>';
                                        echo '<script>';
                                            echo '$(document).ready(function () {';
                                                echo 'var open = false;';
                                                echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').click(';
                                                    echo 'function (e) {';
                                                        echo 'e.preventDefault();';
                                                        echo 'if (!open) {';
                                                            echo '$(\'#mobile-ul-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'display\', \'block\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'transform\', \'rotate(270deg)\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'-ms-transform\', \'rotate(270deg)\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'-webkit-transform\', \'rotate(270deg)\');';
                                                            echo 'open = true;';
                                                        echo '} else {';
                                                            echo '$(\'#mobile-ul-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'display\', \'none\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'transform\', \'rotate(90deg)\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'-ms-transform\', \'rotate(90deg)\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'-webkit-transform\', \'rotate(90deg)\');';
                                                            echo 'open = false;';
                                                        echo '}';
                                                    echo '});';
                                            echo '});';
                                        echo '</script>';
                                        echo '<ul id="mobile-ul-with-hover-' . $xmlPagesIndexElement['link'] . '" style="display: none;">';
                                            foreach ($xmlPagesIndexElement['childPages'] as $childPageToEcho) {
                                                echo '<a href="' . $this->page->getLanguageHomeFolderRelativeHTMLURL();
                                                    if (($childPageToEcho['link'] != "index") and ( $childPageToEcho['link'] != "")) {
                                                        echo $childPageToEcho['link'] . '/';
                                                    }
                                                    echo '">';
                                                    echo '<li ';
                                                        if ($childPageToEcho['link'] == $this->page->getPageLink()) {
                                                            echo 'class="selected"';
                                                        }
                                                        echo '>';
                                                        echo $childPageToEcho['name'];
                                                    echo '</li>';
                                                echo '</a>';
                                            }
                                        echo '</ul>';
                                    }
                                echo '</li>';
                            echo '</a>';
                        } else {
                            echo '<a href="' . $this->page->getLanguageHomeFolderRelativeHTMLURL();
                                if (($xmlPagesIndexElement['link'] != "index") && ($xmlPagesIndexElement['link'] != "")) {
                                    echo '?page=' . $xmlPagesIndexElement['link'];
                                }
                                echo '">';
                                echo '<li class="external-li';
                                    if ($xmlPagesIndexElement['link'] == $this->page->getPageLink()) {
                                        echo ' selected';
                                    }
                                    echo '" id="mobile-li-with-hover-' . $xmlPagesIndexElement['link'] . '">';
                                    echo $xmlPagesIndexElement['name'];
                                    if ($xmlPagesIndexElement['childPages'] && $this->dropdown == "on") {
                                        echo '<span id="mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '" style="float: right; -ms-transform: rotate(90deg); -webkit-transform: rotate(90deg); transform: rotate(90deg);">></span>';
                                        echo '<script>';
                                            echo '$(document).ready(function () {';
                                                echo 'var open = false;';
                                                echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').click(';
                                                    echo 'function (e) {';
                                                        echo 'e.preventDefault();';
                                                        echo 'if (!open) {';
                                                            echo '$(\'#mobile-ul-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'display\', \'block\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'transform\', \'rotate(270deg)\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'-ms-transform\', \'rotate(270deg)\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'-webkit-transform\', \'rotate(270deg)\');';
                                                            echo 'open = true;';
                                                        echo '} else {';
                                                            echo '$(\'#mobile-ul-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'display\', \'none\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'transform\', \'rotate(90deg)\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'-ms-transform\', \'rotate(90deg)\');';
                                                            echo '$(\'#mobile-span-with-hover-' . $xmlPagesIndexElement['link'] . '\').css(\'-webkit-transform\', \'rotate(90deg)\');';
                                                            echo 'open = false;';
                                                        echo '}';
                                                    echo '});';
                                            echo '});';
                                        echo '</script>';
                                        echo '<ul id="mobile-ul-with-hover-' . $xmlPagesIndexElement['link'] . '" style="display: none;">';
                                            foreach ($xmlPagesIndexElement['childPages'] as $childPageToEcho) {
                                                echo '<a href="' . $this->page->getLanguageHomeFolderRelativeHTMLURL();
                                                    if (($childPageToEcho['link'] != "index") && ($childPageToEcho['link'] != "")) {
                                                        echo $childPageToEcho['link'] . '/';
                                                    }
                                                    echo '">';
                                                    echo '<li ';
                                                        if ($childPageToEcho['link'] == $this->page->getPageLink()) {
                                                            echo 'class="selected"';
                                                        }
                                                        echo '>';
                                                        echo $childPageToEcho['name'];
                                                    echo '</li>';
                                                echo '</a>';
                                            }
                                        echo '</ul>';
                                    }
                                echo '</li>';
                            echo '</a>';
                        }
                    }
                }
                echo '<div style="clear:both;"></div>';
            echo '</ul>';
        echo '</nav>';
    }
}
<?php
/*
 * Author: ANM22
 * Last modified: 18 Sep 2019 - GMT +2 12:07
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* SOCIAL NETWORK */

class com_anm22_wb_editor_page_element_socialNetwork extends com_anm22_wb_editor_page_element {

    var $elementClass = "com_anm22_wb_editor_page_element_socialNetwork";
    var $elementPlugin = "com_anm22_wb_editor";
    var $title;
    private $headingTag = 'h1';
    var $facebook;
    var $twitter;
    var $googlePlus;
    var $pinterest;
    var $instagram;
    var $linkedin;
    var $myspace;
    var $tripadvisor;
    var $foursquare;
    var $blogspot;
    var $website;
    var $youtube;
    var $email;
    var $iconStyle;
    var $entityType;
    var $snapchat;
    var $flickr;
    var $tumblr;
    var $cssClass;
    protected $socialMode;

    function importXMLdoJob($xml) {
        $this->socialMode = intval($xml->socialMode);
        $this->title = $xml->title;
        $this->facebook = $xml->facebook;
        $this->twitter = $xml->twitter;
        $this->googlePlus = $xml->googlePlus;
        $this->pinterest = $xml->pinterest;
        $this->instagram = $xml->instagram;
        $this->linkedin = $xml->linkedin;
        $this->myspace = $xml->myspace;
        $this->tripadvisor = $xml->tripadvisor;
        $this->foursquare = $xml->foursquare;
        $this->blogspot = $xml->blogspot;
        $this->website = $xml->website;
        $this->youtube = $xml->youtube;
        $this->email = $xml->email;
        $this->iconStyle = $xml->iconStyle;
        $this->entityType = $xml->entityType;
        $this->snapchat = $xml->snapchat;
        $this->flickr = $xml->flickr;
        $this->tumblr = $xml->tumblr;
        $this->cssClass = $xml->cssClass;
        if (isset($xml->headingTag)) {
            $this->setHeadingTag(htmlspecialchars_decode($xml->headingTag));
        }
    }

    function show() {
        if ($this->socialMode == 1) {
            if ($_SERVER['HTTPS']) {
                $httpProtocol = 'https://';
            } else {
                $httpProtocol = 'http://';
            }
            $page = urlencode($httpProtocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            
            echo '<div class="' . $this->elementPlugin . '_' . $this->elementClass . ' socialShare ';
                    if ($this->cssClass != "") {
                        echo $this->cssClass;
                    }
                    echo '" ';
                    if ($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass]) {
                        echo ' style="' . $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass] . '"';
                    }
                    echo '>';
                    
            if (($this->title) and ( $this->title != "")) {
                echo '<' . $this->headingTag . ' ';
                    if ($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-h1"]) {
                        echo ' style="' . $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-h1"] . '"';
                    }
                    echo '>' . $this->title . '</' . $this->headingTag . '>';
            }
            
                echo '<a class="social-facebook" href="https://www.facebook.com/sharer.php?u=' . $page . '" target="_blank">';
                    if ($this->iconStyle == "def" || $this->iconStyle == "") {
                        echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/facebook.png"';
                    } else {
                        echo '<img src="' . $this->page->getThemeFolderRelativeHTMLURL() . 'img/social/com_facebook.png"';
                    }
                            echo ' style="height:30px;border:0px;float:left;margin-right:10px;" /></a>';
                echo '<a class="social-twitter" href="http://twitter.com/share?url=' . $page . '" target="_blank">';
                    if ($this->iconStyle == "def" || $this->iconStyle == "") {
                        echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/twitter-border.png"';
                    } else {
                        echo '<img src="' . $this->page->getThemeFolderRelativeHTMLURL() . 'img/social/com_twitter.png"';
                    }
                            echo ' style="height:30px;border:0px;float:left;margin-right:10px;" /></a>';
                echo '<a class="social-linkedin" href="https://www.linkedin.com/shareArticle?url=' . $page . '" target="_blank">';
                    if ($this->iconStyle == "def" || $this->iconStyle == "") {
                        echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/linkedin.png"';
                    } else {
                        echo '<img src="' . $this->page->getThemeFolderRelativeHTMLURL() . 'img/social/com_linkedin.png"';
                    }
                            echo ' style="height:30px;border:0px;float:left;margin-right:10px;" /></a>';
                echo '<div style="clear:both;"></div>';
            echo '</div>';
        } else {
            echo '<div class="' . $this->elementPlugin . '_' . $this->elementClass . ' ';
            if ($this->cssClass != "") {
                echo $this->cssClass;
            }
            echo '" ';
            if (isset($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass])) {
                echo 'style="' . $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass] . '" ';
            }
            echo ' itemscope itemtype="http://schema.org/';
            if ($this->entityType == "pers") {
                echo 'Person';
            } else {
                echo 'Organization';
            }
            '" vocab="http://schema.org/" typeof="';
            if ($this->entityType == "pers") {
                echo 'Person';
            } else {
                echo 'Organization';
            }
            echo '">';
                if (($this->title) and ( $this->title != "")) {
                    echo '<' . $this->getHeadingTag();
                    if ($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-h1"]) {
                        echo ' style="' . $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass . "-h1"] . '"';
                    }
                    echo '>' . $this->title . '</' . $this->getHeadingTag() . '>';
                }
                if (($this->facebook) and ( $this->facebook != "")) { ?><a class="social-facebook" itemprop="sameAs" property="sameAs" href="<?= $this->facebook ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/facebook.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_facebook.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->twitter) and ( $this->twitter != "")) { ?><a class="social-twitter" itemprop="sameAs" property="sameAs" href="<?= $this->twitter ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/twitter-border.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_twitter.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->pinterest) and ( $this->pinterest != "")) { ?><a class="social-pinterest" itemprop="sameAs" property="sameAs" href="<?= $this->pinterest ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_pinterest.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_pinterest.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->instagram) and ( $this->instagram != "")) { ?><a class="social-instagram" itemprop="sameAs" property="sameAs" href="<?= $this->instagram ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_instagram.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_instagram.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->linkedin) and ( $this->linkedin != "")) { ?><a class="social-linkedin" itemprop="sameAs" property="sameAs" href="<?= $this->linkedin ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/linkedin.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_linkedin.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->myspace) and ( $this->myspace != "")) { ?><a class="social-myspace" itemprop="sameAs" property="sameAs" href="<?= $this->myspace ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_myspace.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_myspace.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->tripadvisor) and ( $this->tripadvisor != "")) { ?><a class="social-tripadvisor" itemprop="sameAs" property="sameAs" href="<?= $this->tripadvisor ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_tripadvisor.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_tripadvisor.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->foursquare) and ( $this->foursquare != "")) { ?><a class="social-foursquare" itemprop="sameAs" property="sameAs" href="<?= $this->foursquare ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_foursquare.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_foursquare.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->blogspot) and ( $this->blogspot != "")) { ?><a class="social-blogspot" itemprop="sameAs" property="sameAs" href="<?= $this->blogspot ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_blogspot.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_blogspot.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->youtube) and ( $this->youtube != "")) { ?><a class="social-youtube" itemprop="sameAs" property="sameAs" href="<?= $this->youtube ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_google_youtube.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_youtube.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->snapchat) and ( $this->snapchat != "")) { ?><a class="social-snapchat" itemprop="sameAs" property="sameAs" href="<?= $this->snapchat ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_snapchat.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_snapchat.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->flickr) and ( $this->flickr != "")) { ?><a class="social-flickr" itemprop="sameAs" property="sameAs" href="<?= $this->flickr ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_flickr.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_flickr.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->tumblr) and ( $this->tumblr != "")) { ?><a class="social-tumblr" itemprop="sameAs" property="sameAs" href="<?= $this->tumblr ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_tumblr.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_tumblr.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->email) and ( $this->email != "")) { ?><a itemprop="sameAs" property="sameAs" href="mailto:<?= $this->email ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_email.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_email.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->website) and ( $this->website != "")) { ?><a itemprop="url" property="url" href="<?= $this->website ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_website.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/linkIcon.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                echo '<div style="clear:both;"></div>';
            echo '</div>';
        }
    }
    
    public function getHeadingTag() {
        return $this->headingTag;
    }
    public function setHeadingTag($headingTag) {
        $this->headingTag = $headingTag;
        return $this;
    }

}
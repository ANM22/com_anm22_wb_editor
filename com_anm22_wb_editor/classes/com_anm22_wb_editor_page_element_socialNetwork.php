<?php
/**
 * Plugin SOCIAL NETWORK
 * 
 * @copyright 2023 Paname srl
 */
class com_anm22_wb_editor_page_element_socialNetwork extends com_anm22_wb_editor_page_element {

    const MODE_SOCIAL_SHARE = 1;
    const MODE_SOCIAL_LINK = 0;

    public $elementClass = "com_anm22_wb_editor_page_element_socialNetwork";
    public $elementPlugin = "com_anm22_wb_editor";
    public $title;
    private $headingTag = 'h1';
    public $facebook;
    public $twitter;
    public $pinterest;
    public $instagram;
    public $linkedin;
    public $myspace;
    public $tripadvisor;
    public $foursquare;
    public $blogspot;
    public $website;
    public $youtube;
    public $email;
    public $iconStyle;
    public $entityType;
    public $snapchat;
    public $flickr;
    public $tumblr;
    protected $tiktok;
    public $cssClass;
    protected $socialMode;

    /**
     * @deprecated since version 3.20
     * 
     * Import plugin data from XML serialization
     * 
     * @param mixed $xml Serialized data
     * @return void
     */
    public function importXMLdoJob($xml) {
        $data = [];
        $data['socialMode'] = intval($xml->socialMode);
        $data['title'] = $xml->title;
        $data['facebook'] = $xml->facebook;
        $data['twitter'] = $xml->twitter;
        $data['pinterest'] = $xml->pinterest;
        $data['instagram'] = $xml->instagram;
        $data['linkedin'] = $xml->linkedin;
        $data['myspace'] = $xml->myspace;
        $data['tripadvisor'] = $xml->tripadvisor;
        $data['foursquare'] = $xml->foursquare;
        $data['blogspot'] = $xml->blogspot;
        $data['website'] = $xml->website;
        $data['youtube'] = $xml->youtube;
        $data['email'] = $xml->email;
        $data['iconStyle'] = $xml->iconStyle;
        $data['entityType'] = $xml->entityType;
        $data['snapchat'] = $xml->snapchat;
        $data['flickr'] = $xml->flickr;
        $data['tumblr'] = $xml->tumblr;
        if (isset($xml->tiktok)) {
            $data['tiktok'] = $xml->tiktok;
        }
        $data['cssClass'] = $xml->cssClass;
        if (isset($xml->headingTag)) {
            $data['headingTag'] = $xml->headingTag;
        }

        $this->init($data);
    }

    /**
     * Import plugin data
     * 
     * @param mixed[] $data Serialized data
     * @return void
     */
    public function init($data) {
        $this->socialMode = intval($data['socialMode']);
        $this->title = $data['title'];
        $this->facebook = $data['facebook'];
        $this->twitter = $data['twitter'];
        $this->pinterest = $data['pinterest'];
        $this->instagram = $data['instagram'];
        $this->linkedin = $data['linkedin'];
        $this->myspace = $data['myspace'];
        $this->tripadvisor = $data['tripadvisor'];
        $this->foursquare = $data['foursquare'];
        $this->blogspot = $data['blogspot'];
        $this->website = $data['website'];
        $this->youtube = $data['youtube'];
        $this->email = $data['email'];
        $this->iconStyle = $data['iconStyle'];
        $this->entityType = $data['entityType'];
        $this->snapchat = $data['snapchat'];
        $this->flickr = $data['flickr'];
        $this->tumblr = $data['tumblr'];
        if (isset($data['tiktok'])) {
            $this->tiktok = $data['tiktok'];
        }
        $this->cssClass = $data['cssClass'];
        if (isset($data['headingTag'])) {
            $this->setHeadingTag(htmlspecialchars_decode($data['headingTag']));
        }
    }

    /**
     * Echo plugin component
     * 
     * @return void
     */
    function show() {
        if ($this->socialMode == self::MODE_SOCIAL_SHARE) {
            if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))) {
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
                    if (isset($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass]) && $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass]) {
                        echo ' style="' . $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass] . '"';
                    }
                    echo '>';
                    
                if (($this->title) and ($this->title != "")) {
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
                        echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/x.png"';
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
                if (($this->facebook) && ($this->facebook != "")) { ?><a class="social-facebook" itemprop="sameAs" property="sameAs" href="<?= $this->facebook ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/facebook.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_facebook.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->twitter) && ($this->twitter != "")) { ?><a class="social-twitter" itemprop="sameAs" property="sameAs" href="<?= $this->twitter ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/x.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_twitter.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->pinterest) && ($this->pinterest != "")) { ?><a class="social-pinterest" itemprop="sameAs" property="sameAs" href="<?= $this->pinterest ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_pinterest.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_pinterest.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->instagram) && ($this->instagram != "")) { ?><a class="social-instagram" itemprop="sameAs" property="sameAs" href="<?= $this->instagram ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_instagram.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_instagram.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->linkedin) && ($this->linkedin != "")) { ?><a class="social-linkedin" itemprop="sameAs" property="sameAs" href="<?= $this->linkedin ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/linkedin.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_linkedin.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->tiktok) && ($this->tiktok != "")) { ?><a class="social-tiktok" itemprop="sameAs" property="sameAs" href="<?= $this->tiktok ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/tiktok.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_tiktok.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->myspace) && ($this->myspace != "")) { ?><a class="social-myspace" itemprop="sameAs" property="sameAs" href="<?= $this->myspace ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_myspace.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_myspace.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->tripadvisor) && ($this->tripadvisor != "")) { ?><a class="social-tripadvisor" itemprop="sameAs" property="sameAs" href="<?= $this->tripadvisor ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="<?=$this->page->getHomeFolderRelativeHTMLURL()?>ANM22WebBase/website/plugins/com_anm22_wb_editor/img/social/tripadvisor.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_tripadvisor.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->foursquare) && ($this->foursquare != "")) { ?><a class="social-foursquare" itemprop="sameAs" property="sameAs" href="<?= $this->foursquare ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_foursquare.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_foursquare.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->blogspot) && ($this->blogspot != "")) { ?><a class="social-blogspot" itemprop="sameAs" property="sameAs" href="<?= $this->blogspot ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_blogspot.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_blogspot.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->youtube) && ($this->youtube != "")) { ?><a class="social-youtube" itemprop="sameAs" property="sameAs" href="<?= $this->youtube ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_google_youtube.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_youtube.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->snapchat) &&( $this->snapchat != "")) { ?><a class="social-snapchat" itemprop="sameAs" property="sameAs" href="<?= $this->snapchat ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_snapchat.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_snapchat.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->flickr) && ($this->flickr != "")) { ?><a class="social-flickr" itemprop="sameAs" property="sameAs" href="<?= $this->flickr ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_flickr.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_flickr.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->tumblr) && ($this->tumblr != "")) { ?><a class="social-tumblr" itemprop="sameAs" property="sameAs" href="<?= $this->tumblr ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_tumblr.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_tumblr.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->email) && ($this->email != "")) { ?><a itemprop="sameAs" property="sameAs" href="mailto:<?= $this->email ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_email.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/com_email.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                if (($this->website) && ($this->website != "")) { ?><a itemprop="url" property="url" href="<?= $this->website ?>" target="_blank"><? if ($this->iconStyle == "def" || $this->iconStyle == "") { ?> <img src="https://www.anm22.it/app/webbase/images/plugin_icons/com_website.png" <? } else { ?> <img src="<?= $this->page->getThemeFolderRelativeHTMLURL() ?>img/social/linkIcon.png" <? } ?> style="height:30px; border:0px; float:left; margin-right:10px;" /></a><? }
                echo '<div style="clear:both;"></div>';
            echo '</div>';
        }
    }
    
    /**
     * Get title heading tag
     * 
     * @return string
     */
    public function getHeadingTag() {
        return $this->headingTag;
    }

    /**
     * Set title heading tag
     * 
     * @param string $headingTag Heading tag
     * @return self
     */
    public function setHeadingTag($headingTag) {
        $this->headingTag = $headingTag;
        return $this;
    }

}
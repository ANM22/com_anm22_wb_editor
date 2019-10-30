<?php

class WBNews {

    private $id;
    private $defaultLanguage;
    private $title = array();
    private $subtitle = array();
    private $text = array();
    private $tags = array();
    private $languages = array();
    private $galleryId;
    private $time;
    private $author;
    private $authorName;
    private $publisherId;
    private $publisherName;
    private $categoryId;
    private $img = "img.png";
    private $thumbImg = "thumb.png";

    public function __construct() {
        $this->setId(time());
        $this->addLanguage("de", 0);
        $this->addLanguage("en", 0);
        $this->addLanguage("es", 0);
        $this->addLanguage("fr", 0);
        $this->addLanguage("it", 0);
        $this->addLanguage("ru", 0);
    }
    
    static public function WBNewsLanguages() {
        $array = array("de", "en", "es", "fr", "it", "ru");
        return $array;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getTitle($lang = null) {
        if (!$lang) {
            $lang = $this->getDefaultLanguage();
        }

        return $this->title[$lang . ''];
    }

    public function setTitle($title, $lang) {
        if ($lang) {
            $this->title[$lang . ''] = $title;
        } else {
            return 0;
        }
        return $this;
    }

    public function getSubtitle($lang = null) {

        if (!$lang) {
            $lang = $this->getDefaultLanguage();
        }

        return $this->subtitle[$lang . ''];
    }

    public function setSubtitle($subtitle, $lang) {
        if ($lang) {
            $this->subtitle[$lang . ''] = $subtitle;
        } else {
            return 0;
        }
        return $this;
    }

    public function getText($lang) {
        return $this->text[$lang . ''];
    }

    public function setText($text, $lang) {
        $this->text[$lang . ''] = $text;
        return $this;
    }

    public function getDefaultText() {
        return $this->defaultText;
    }

    public function setDefaultText($text) {
        $this->defaultText = $text;
        return $this;
    }

    public function getTags() {
        return $this->tags;
    }

    public function setTags($array) {
        $this->tags = $array;
        return $this;
    }

    public function addTag($tag) {
        $this->tags[$tag . ''] = $tag;
        return $this;
    }

    public function getLanguages() {
        return $this->languages;
    }

    public function setLanguages($array) {
        $this->languages = $array;
        return $this;
    }

    public function addLanguage($lang, $state = 1) {
        return $this->setLanguage($lang, $state);
    }

    public function getLanguageState($lang) {
        return $this->languages[$lang . ''];
    }

    public function setLanguage($lang, $state = 1) {
        $this->languages[$lang . ''] = $state;
        return $this;
    }
    
    public function getDefaultLanguage() {
        return $this->defaultLanguage;
    }
    
    public function setDefaultLanguage($lang) {
        $this->defaultLanguage = $lang;
        return $this;
    }

    public function getGalleryId() {
        return $this->galleryId;
    }

    public function setGalleryId($galleryId) {
        $this->galleryId = $galleryId;
        return $this;
    }

    public function getTime() {
        return $this->time;
    }

    public function setTime($timestamp) {
        $this->time = $timestamp;
        return $this;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($user) {
        $this->author = $user;
        return $this;
    }

    public function getAuthorName() {
        return $this->authorName;
    }

    public function setAuthorName($name) {
        $this->authorName = $name;
        return $this;
    }
    
    public function getPublisherId() {
        return $this->publisherId;
    }

    public function setPublisherId($id) {
        $this->publisherId = $id;
        return $this;
    }
    
    public function getPublisherName() {
        return $this->publisherName;
    }

    public function setPublisherName($name) {
        $this->publisherName = $name;
        return $this;
    }

    public function getCategory() {
        return $this->categoryId;
    }

    public function setCategory($category) {
        $this->categoryId = trim($category);
        return $this;
    }

    public function getImg() {
        return $this->img;
    }

    public function setImg($imgUrl) {
        $this->img = $imgUrl;
        return $this;
    }

    public function getThumbImg() {
        return $this->thumbImg;
    }

    public function setThumbImg($imgUrl) {
        $this->thumbImg = $imgUrl;
        return $this;
    }

    public function loadByXML($xml) {
        $this->setId(intval($xml->ID));
        $this->setCategory((string) $xml->CATEGORY);
        foreach (self::WBNewsLanguages() as $lang) {
            $this->setTitle((string) $xml->TITLE->$lang, $lang);
        }
        foreach (self::WBNewsLanguages() as $lang) {
            $this->setSubtitle((string) $xml->SUBTITLE->$lang, $lang);
        }
        foreach (self::WBNewsLanguages() as $lang) {
            $this->setText((string) $xml->TEXT->$lang, $lang);
        }
        foreach (self::WBNewsLanguages() as $lang) {
            $this->addLanguage($lang, intval($xml->LANGUAGE->$lang));
        }
        $this->setDefaultLanguage((string) $xml->LANGUAGE->default);
        $this->setAuthor(intval($xml->AUTHOR));
        $this->setAuthorName($xml->AUTHORNAME);
        $this->setPublisherId($xml->PUBLISHERID);
        $this->setPublisherName($xml->PUBLISHERNAME);
        $this->setTime(intval($xml->DATE));
        $this->setGalleryId(intval($xml->GALLERY));
        $tags = explode(",", (string) $xml->TAGS);
        if (count($tags)) {
            foreach ($tags as $tag) {
                if (trim($tag)) {
                    $this->addTag(trim($tag));
                }
            }
        }
        $this->setImg((string) $xml->PIC);
        $this->setThumbImg((string) $xml->PIC_THUMB);

        return $this;
    }

    public function exportXML() {
        
        $xml = new SimpleXMLElement('<xml/>');
        
        $xml->addChild('LANGUAGE');
        $xml->LANGUAGE->addChild('default', $this->getDefaultLanguage());
        $langs = $this->getLanguages();
        foreach ($langs as $langKey => $langItem) {
            $xml->LANGUAGE->addChild($langKey, intval($langItem));
        }
        $xml->addChild('PUBLIC', 0);
        $xml->addChild('CATEGORY', $this->getCategory() );
        $xml->addChild('AUTHOR', $this->getAuthor());
        $xml->addChild('AUTHORNAME', $this->getAuthorName());
        $xml->addChild('PUBLISHERID', $this->getPublisherId());
        $xml->addChild('PUBLISHERNAME', $this->getPublisherName());
        $xml->addChild('DATE', $this->getTime());
        $xml->addChild('MODIFY', time());
        $xml->addChild('PIC', $this->getImg());
        $xml->addChild('PIC_THUMB', $this->getThumbImg());
        $xml->addChild('TITLE');
        $xml->addChild('SUBTITLE');
        $xml->addChild('TEXT');
        foreach (self::WBNewsLanguages() as $lang) {
            $xml->TITLE->addChild($lang, $this->getTitle($lang));
        }
        $xml->TITLE->addChild('default', $this->getTitle($this->getDefaultLanguage()));
        foreach (self::WBNewsLanguages() as $lang) {
            $xml->SUBTITLE->addChild($lang, $this->getSubtitle($lang));
        }
        foreach (self::WBNewsLanguages() as $lang) {
            $xml->TEXT->addChild($lang, $this->getText($lang));
        }
        $xml->TEXT->addChild('default', $this->getText($this->getDefaultLanguage()));
        $tags = "";
        $tagsArray = $this->getTags();
        if (count($tagsArray)) {
            foreach ($tagsArray as $tag) {
                $tags .= $tag.",";
            }
        }
        $xml->addChild('TAGS', $tags );
        $xml->addChild('GALLERY', $this->getGalleryId() );

        return $xml;
    }

}

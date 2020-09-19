<?php
/*
 * Author: ANM22
 * Last modified: 09 Jun 2020 - GMT +2 21:39
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* NEWS */

class com_anm22_wb_editor_page_element_news extends com_anm22_wb_editor_page_element {

    var $elementClass = "com_anm22_wb_editor_page_element_news";
    var $elementPlugin = "com_anm22_wb_editor";
    var $title;
    var $newsShowLink;
    var $newsCategory;
    var $newsMode;
    var $newsLimit;
    var $newsRows;
    var $newsColumns;
    var $newsTitle;
    var $newsViewSubtitle = 0;
    var $newsImg;
    var $newsDescription;
    var $newsText;
    var $newsViewTags = 0;
    var $newsTagsPageUrl;
    var $newsPageTitleOverwrite;
    var $newsPreviewDate = 0;
    var $newsPreviewDescription = 1;
    var $newsPreviewDescriptionLenght = 300;
    var $newsPreviewDescriptionExtraString = "...";
    protected $newsPreviewPagesList = 0;
    var $newsViewDate = 0;
    var $newsViewGalleryMode = 0;
    var $disableSeoTags = 0;
    var $rewrite;

    function importXMLdoJob($xml) {
        require_once '../ANM22WebBase/website/plugins/com_anm22_wb_editor/classes/WBNews.php';
        $this->title = $xml->title;
        $this->newsShowLink = $xml->newsShowLink;
        $this->newsCategory = trim($xml->newsCategory);
        $this->newsMode = $xml->newsMode;
        $this->newsLimit = intval($xml->newsLimit);
        $this->newsRows = $xml->newsRows;
        $this->newsColumns = $xml->newsColumns;
        $this->newsTitle = intval($xml->newsTitle);
        $this->newsViewSubtitle = intval($xml->newsViewSubtitle);
        $this->newsImg = intval($xml->newsImg);
        $this->newsDescription = intval($xml->newsDescription);
        $this->newsText = intval($xml->newsText);
        $this->newsViewDate = $xml->newsViewDate;
        $this->newsViewGalleryMode = intval($xml->newsViewGalleryMode);
        $this->newsViewTags = intval($xml->newsViewTags);
        $this->newsTagsPageUrl = htmlspecialchars_decode($xml->newsTagsPageUrl);

        $this->newsPreviewDate = $xml->newsPreviewDate;
        if (intval($xml->newsPreviewDescriptionLenght) or ( $xml->newsPreviewDescriptionExtraString)) {
            $this->newsPreviewDescription = $xml->newsPreviewDescription;
            $this->newsPreviewDescriptionLenght = intval($xml->newsPreviewDescriptionLenght);
            $this->newsPreviewDescriptionExtraString = $xml->newsPreviewDescriptionExtraString;
        }
        $this->newsPreviewPagesList = intval($xml->newsPreviewPagesList); 

        $this->newsPageTitleOverwrite = intval($xml->newsPageTitleOverwrite);
        $this->disableSeoTags = intval($xml->disableSeoTags);
        $this->rewrite = $xml->rewrite;

        /* Aggiornamento informazioni pagina in caso di news singola */
        if (((isset($_GET['news']) && intval($_GET['news'])) or ( $this->page->getPageSubLink())) and ( $this->newsMode != "previewonly")) {

            /* Recupero dati news */
            $language = $this->page->language;

            // Lettura ID news
            $newsId = intval($this->page->getVariables['news']);
            if ((!$newsId) or $newsId == "") {
                $newsId = $this->getNewsIdFromPermalink($this->page->getPageSubLink());
            }
            $newsXML = @simplexml_load_file("../News/" . $newsId . "/news.xml");

            $news = new WBNews();
            $news->loadByXML($newsXML);
            if ($news->getGalleryId() > 0) {
                $this->page->getVariables['gId'] = $news->getGalleryId();
            }

            if ((!$this->disableSeoTags) or ( $this->disableSeoTags = "")) {
                /* Aggiornamento title */
                if (($news->getTitle($language)) and ( $news->getTitle($language) != "")) {
                    $this->page->title .= " - " . $newsXML->TITLE->$language;
                }

                /* Aggiornamento immagine */
                if (file_exists("../News/" . $newsId . "/img.png")) {
                    $this->page->image = "http://" . $_SERVER['HTTP_HOST'] . "/News/" . $newsId . "/img.png";
                }

                /* Aggiornamento descrizione */
                $this->page->description = str_replace('"', "", $news->getTitle($language));
            }
        }
    }

    function show() {
        require_once '../ANM22WebBase/website/plugins/com_anm22_wb_editor/classes/WBNews.php';
        $xmlNewsIndex = simplexml_load_file("../News/newsIndex.xml");
        $language = $this->page->language;
        $index = 0;
        if ($this->newsShowLink) {
            $href = $this->newsShowLink;
        } else {
            $href = "";
        }
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $homeFolder = "https://" . $_SERVER['HTTP_HOST'] . "/";
        } else {
            $homeFolder = "http://" . $_SERVER['HTTP_HOST'] . "/";
        }

        // Lettura ID news
        if (isset($this->page->getVariables['news'])) {
            $newsId = intval($this->page->getVariables['news']);
        } else {
            $newsId = 0;
        }
        if (!$newsId) {
            $newsId = $this->getNewsIdFromPermalink($this->page->getPageSubLink());
        }
        // Lettura filtro tag
        if (isset($this->page->getVariables['nt']) and ( $this->page->getVariables['nt'] != "")) {
            $newsTagFilter = urldecode($this->page->getVariables['nt']);
        }

        // Preview mode
        if ((!$newsId) or ( $this->newsMode == "previewonly")) {
            $articleBlockWidth = intval(100 / $this->newsColumns);
            
            if (isset($this->page->getVariables['np'])) {
                $newsPage = intval($this->page->getVariables['np']);
            } else {
                $newsPage = 1;
            }
            if ($newsPage <= 0) {
                $newsPage = 1;
            }
            $newsOffset = ($newsPage - 1) * $this->newsLimit;
            $newsLimit = $this->newsLimit + $newsOffset;
            
            echo '<div class="' . $this->elementPlugin . '_' . $this->elementClass . '_mode_preview">';
                
                if (($this->title) and ( $this->title != "")) {
                    echo '<h1>' . $this->title . '</h1>';
                }

                foreach ($xmlNewsIndex->NEWS->ITEM as $xmlNewsIndexElement) {
                    $newsId = $xmlNewsIndexElement->FOLDER;
                    $newsXML = @simplexml_load_file("../News/" . $newsId . "/news.xml");
                    $news = new WBNews();
                    $news->loadByXML($newsXML);

                    // Controllo tag
                    if (isset($newsTagFilter)) {
                        $tagFounded = 0;
                        foreach ($news->getTags() as $tag) {
                            if ($tag == $newsTagFilter) {
                                $tagFounded = 1;
                                break;
                            }
                        }
                        if (!$tagFounded) {
                            continue;
                        }
                    }

                    if (($news->getLanguageState($language)) and ( ($news->getCategory() == $this->newsCategory) or ( (($this->newsCategory == "") or ( !$this->newsCategory)) and ( trim($news->getCategory()) != "invisible")))) {
                        $index++;
                        if ($index <= $newsOffset) {
                            continue;
                        }
                        // Conteggio pagine
                        if ($this->newsPreviewPagesList) {
                            if ($index > $newsLimit) {
                                continue;
                            }
                        }
                        echo "<a ";
                            if ($this->rewrite != "off") {
                                echo 'href="' . $href . $this->getPermalinkWithId($newsId, $news->getTitle($language)) . '/" ';
                            } else {
                                echo 'href="' . $href . '?news=' . $newsId . '" ';
                            }
                            echo '>';
                            echo '<article itemscope itemtype="http://schema.org/NewsArticle" vocab="http://schema.org/" typeof="NewsArticle" style="width:' .  $articleBlockWidth . '% ;" >';
                                echo '<meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" property="mainEntityOfPage" vocab="http://schema.org/" typeof="WebPage" itemid="' . $homeFolder . $_SERVER['REQUEST_URI'] . $href . $this->getPermalinkWithId($newsId, $news->getTitle($language)) . '/"/>';
                                echo '<meta itemprop="datePublished" property="datePublished" content="' . date("Y-m-d", intval($newsId)) . '" />';
                                echo '<meta itemprop="dateModified" property="dateModified" content="' . date("Y-m-d", intval($newsId)) . '" />';
                                if ($news->getAuthor()) {
                                    echo '<div itemprop="author" itemscope itemtype="https://schema.org/Person" property="author" vocab="http://schema.org/" typeof="Person" style="display:none !important;">';
                                        echo '<div itemprop="name" property="name">' . $news->getAuthorName() . '</div>';
                                    echo '</div>';
                                } else {
                                    echo '<div itemprop="author" itemscope itemtype="https://schema.org/Organization" property="author" vocab="http://schema.org/" typeof="Organization" style="display:none !important;">';
                                        echo '<div itemprop="name" property="name">' . $news->getAuthorName() . '</div>';
                                    echo '</div>';
                                }

                                $size = @getimagesize("https://www.anm22.it/app/webbase/images/newsPublisher/" . $news->getPublisherId() . ".png");
                                echo '<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" property="publisher" vocab="http://schema.org/" typeof="Organization" style="display:none !important;">';
                                    echo '<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject" property="logo" vocab="http://schema.org/" typeof="ImageObject">';
                                        echo '<meta itemprop="url" property="url" content="https://www.anm22.it/app/webbase/images/newsPublisher/' . $news->getPublisherId() . '.png">';
                                        echo '<meta itemprop="width" property="width" content="' . $size[0] . '">';
                                        echo '<meta itemprop="height" property="height" content="' . $size[1] . '">';
                                    echo '</div>';
                                    echo '<div itemprop="name" property="name">' . $news->getPublisherName() . '</div>';
                                echo '</div>';
                                if ($this->newsTitle) {
                                    echo '<h1 itemprop="headline" property="headline">' . $news->getTitle($language) . '</h1>';
                                }
                                if ($this->newsViewSubtitle) {
                                    echo '<h2 itemprop="alternativeHeadline" property="alternativeHeadline">' .$news->getSubtitle($language) . '</h2>';
                                }
                                if (($this->newsImg) and ( file_exists("../News/" . $newsId . "/img.png"))) {
                                    $size = getimagesize("../News/" . $newsId . "/img.png");
                                    echo '<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject" property="image" vocab="http://schema.org/" typeof="ImageObject" class="news_preview_img" style="background-image:url(' . $this->page->getHomeFolderRelativeHTMLURL() . 'News/' . $newsId . '/img.png);">';
                                        echo '<meta itemprop="url" property="url" content="' . $homeFolder . 'News/' . $newsId . '/img.png">';
                                        echo '<meta itemprop="width" property="width" content="' . $size[0] . '">';
                                        echo '<meta itemprop="height" property="height" content="' . $size[1] . '">';
                                    echo '</div>';
                                    echo '<img class="ecom_preview_img_seo" src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'News/' . $newsId . '/img.png" style="display:none;" />';
                                }

                                if ($this->newsPreviewDate == 1) {
                                    echo '<div class="news_preview_date">' . date("d-m-Y", intval($newsId)) . '</div>';
                                }

                                if ($this->newsPreviewDescription == 1) {

                                    $articleDescriptionLenght = strlen($news->getText($language));
                                    $extraText = "";

                                    if ($articleDescriptionLenght > $this->newsPreviewDescriptionLenght) {
                                        $extraText = $this->newsPreviewDescriptionExtraString;
                                    }
                                    
                                    echo '<div class="news_preview_description" itemprop="description" property="description">' . substr($news->getText($language), 0, $this->newsPreviewDescriptionLenght) . $extraText . '</div>';
                                }

                                if ($this->newsText == 1) {
                                    echo '<div class="news_preview_body" itemprop="articleBody" property="articleBody">' . $news->getText($language) . '</div>';
                                }

                                if (file_exists("../News/" . $newsId . "/att.pdf")) {
                                    echo '<div class="news_preview_att" onclick="location.href=\'' . $this->page->getHomeFolderRelativeHTMLURL() . 'News/' . $newsId . '/att.pdf\'">Download PDF</div>';
                                }
                            echo '</article>';
                        echo '</a>';
                        
                        if (($index % $this->newsColumns) == 0) {
                            echo '<div style="clear:left;"></div>';
                        }
                        if (($index >= $newsLimit) and (!$this->newsPreviewPagesList)) {
                            break;
                        }
                    }
                }
                if ($this->newsPreviewPagesList) {
                    $pagesNumber = ceil($index / $this->newsLimit);
                    echo '<div class="news-pages">';
                        for ($p = 1; $p <= $pagesNumber; $p++) {
                            echo '<a href="?';
                            if ($newsTagFilter) {
                                echo 'nt=' . urlencode($newsTagFilter) . '&';
                            }
                            echo 'np=' . $p . '"';
                            if ($p == $newsPage) {
                                echo ' class="sel"';
                            }
                            echo '>' . $p . '</a>';
                        }
                    echo '</div>';
                }
            echo '</div>';
        } else {
            // News show
            $newsXML = @simplexml_load_file("../News/" . $newsId . "/news.xml");
            $news = new WBNews();
            $news->loadByXML($newsXML);
            if ($news->getGalleryId() > 0) {
                $this->page->getVariables['gId'] = $news->getGalleryId();
            }
            echo '<div class="' . $this->elementPlugin . '_' . $this->elementClass .'_mode_view">';
                echo '<article itemscope itemtype="http://schema.org/NewsArticle" vocab="http://schema.org/" typeof="NewsArticle">';
                    echo '<meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" property="mainEntityOfPage" vocab="http://schema.org/" typeof="WebPage" itemid="' . $homeFolder . $_SERVER['REQUEST_URI'] . '"/>';
                    echo '<meta itemprop="datePublished" property="datePublished" content="' . date("Y-m-d", intval($newsId)) . '" />';
                    echo '<meta itemprop="dateModified" property="dateModified" content="' . date("Y-m-d", intval($newsId)) . '" />';
                    
                    if ($news->getAuthor()) {
                        echo '<div itemprop="author" itemscope itemtype="https://schema.org/Person" property="author" vocab="http://schema.org/" typeof="Person" style="display:none !important;">';
                            echo '<div itemprop="name" property="name">' . $news->getAuthorName() . '</div>';
                        echo '</div>';
                    } else {
                        echo '<div itemprop="author" itemscope itemtype="https://schema.org/Organization" property="author" vocab="http://schema.org/" typeof="Organization" style="display:none !important;">';
                            echo '<div itemprop="name" property="name">' . $news->getAuthorName() . '</div>';
                        echo '</div>';
                    }
                    $size = @getimagesize("https://www.anm22.it/app/webbase/images/newsPublisher/" . $news->getPublisherId() . ".png");
                    
                    echo '<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" property="publisher" vocab="http://schema.org/" typeof="Organization" style="display:none !important;">';
                        echo '<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject" property="logo" vocab="http://schema.org/" typeof="ImageObject">';
                            echo '<meta itemprop="url" property="url" content="https://www.anm22.it/app/webbase/images/newsPublisher/' . $news->getPublisherId() . '.png">';
                            echo '<meta itemprop="width" property="width" content="' . $size[0] . '">';
                            echo '<meta itemprop="height" property="height" content="' . $size[1] . '">';
                        echo '</div>';
                        echo '<div itemprop="name" property="name">' . $news->getPublisherName() . '</div>';
                    echo '</div>';
                    echo '<h1 itemprop="headline" property="headline">' . $news->getTitle($language) . '</h1>';
                    if ($this->newsViewSubtitle) {
                        echo '<h2 itemprop="alternativeHeadline" property="alternativeHeadline">' . $news->getSubtitle($language) . '</h2>';
                    }
                    if (file_exists("../News/" . $newsId . "/img.png")) {
                        $size = getimagesize("../News/" . $newsId . "/img.png");
                        
                        echo '<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject" property="image" vocab="http://schema.org/" typeof="ImageObject" class="news_preview_img" style="display:none !important">';
                            echo '<meta itemprop="url" property="url" content="' . $homeFolder . 'News/' . $newsId . '/img.png">';
                            echo '<meta itemprop="width" property="width" content="' . $size[0] . '">';
                            echo '<meta itemprop="height" property="height" content="' . $size[1] . '">';
                        echo '</div>';
                        echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . 'News/' . $newsId . '/img.png" class="news_view_img" ';
                            if (($this->newsViewGalleryMode == 2) and ( ($news->getGalleryId() > 0))) {
                                echo 'style="display: none !important;" ';
                            }
                            echo '/>';
                    }

                    if ($this->newsViewDate == 1) {
                        ?><div class="news_view_date"><?= date("d-m-Y", intval($newsId)) ?></div><?
                    }
                    
                    echo '<div itemprop="articleBody" property="articleBody">' . nl2br($news->getText($language)) . '</div>';
                    
                    if (file_exists("../News/" . $newsId . "/att.pdf")) {
                        ?><div class="news_view_att"><a href="<?= $this->page->getHomeFolderRelativeHTMLURL() ?>News/<?= $newsId ?>/att.pdf">Download PDF</a></div><?
                    }
                    if ($this->newsViewTags) {
                        ?>
                        <div class="news_view_keywords" itemprop="keywords" property="keywords">
                            <?
                            $tags = $news->getTags();
                            $i = 0;
                            if (count($tags) > 0) {
                                foreach ($tags as $tag) {
                                    if ($i > 0) {
                                        echo ", ";
                                    }
                                    ?><a href="<?= $this->newsTagsPageUrl ?><?= urlencode($tag) ?>/?nt=<?= urlencode($tag) ?>"><?= $tag ?></a><?
                                    $i++;
                                }
                            }
                            ?>
                        </div>
                        <?
                    }
                    echo '<div style="clear:both;"></div>';
                echo '</article>';
            echo '</div>';
        }
    }

    private function getPermalink($title) {
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $title);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

        return $clean;
    }

    private function getPermalinkWithId($id, $title) {
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $title);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

        return $id . "-" . $clean;
    }

    private function getNewsIdFromPermalink($perma) {
        $split = explode("-", $perma);
        return intval($split[0]);
    }

}
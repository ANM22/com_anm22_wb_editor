<?php
/**
 * News plugin
 *
 * @author Andrea Menghi <andrea.menghi@anm22.it>
 */
class com_anm22_wb_editor_page_element_news extends com_anm22_wb_editor_page_element
{

    var $elementClass = "com_anm22_wb_editor_page_element_news";
    var $elementPlugin = "com_anm22_wb_editor";
    
    protected $cssClass = '';
    
    var $title;
    protected $headingTag = 'h1';
    
    var $newsShowLink;
    var $newsCategory;
    var $newsMode;
    var $newsLimit;
    var $newsRows;
    var $newsColumns = 0;
    var $newsTitle;
    protected $newsHeadingTag = 'h2';
    var $newsViewSubtitle = 0;
    var $newsImg;
    var $newsDescription;
    var $newsText;
    var $newsViewTags = 0;
    var $newsTagsPageUrl;
    var $newsPageTitleOverwrite;
    protected $newsPreviewHeadingTag = 'h2';
    var $newsPreviewDate = 0;
    var $newsPreviewDescription = 1;
    var $newsPreviewDescriptionLenght = 300;
    var $newsPreviewDescriptionExtraString = "...";
    protected $newsPreviewPagesList = 0;
    var $newsViewDate = 0;
    var $newsViewGalleryMode = 0;
    var $disableSeoTags = 0;
    var $rewrite;

    /**
     * @deprecated since editor 3.0
     * 
     * Method to init the element.
     * 
     * @param SimpleXMLElement $xml Element data
     * @return void
     */
    public function importXMLdoJob($xml)
    {
        require_once __DIR__ . '/WBNews.php';
        
        if (isset($xml->cssClass)) {
            $this->cssClass = $xml->cssClass;
        }
        
        $this->title = $xml->title;
        if (isset($xml->headingTag)) {
            $this->headingTag = $xml->headingTag;
        }
        $this->newsShowLink = $xml->newsShowLink;
        $this->newsCategory = trim($xml->newsCategory);
        if ($this->newsCategory == "[url]") {
            if ($this->page->getPageLastSubLink()) {
                $this->newsCategory = $this->page->getPageLastSubLink();
            } else {
                $this->newsCategory = "";
            }
        }
        $this->newsMode = $xml->newsMode;
        $this->newsLimit = intval($xml->newsLimit);
        $this->newsRows = $xml->newsRows;
        $this->newsColumns = $xml->newsColumns;
        $this->newsTitle = intval($xml->newsTitle);
        if (isset($xml->newsHeadingTag)) {
            $this->newsHeadingTag = $xml->newsHeadingTag;
        }
        $this->newsViewSubtitle = intval($xml->newsViewSubtitle);
        $this->newsImg = intval($xml->newsImg);
        $this->newsDescription = intval($xml->newsDescription);
        $this->newsText = intval($xml->newsText);
        $this->newsViewDate = $xml->newsViewDate;
        $this->newsViewGalleryMode = intval($xml->newsViewGalleryMode);
        $this->newsViewTags = intval($xml->newsViewTags);
        $this->newsTagsPageUrl = htmlspecialchars_decode($xml->newsTagsPageUrl);

        if (isset($xml->newsPreviewHeadingTag)) {
            $this->newsPreviewHeadingTag = $xml->newsPreviewHeadingTag;
        }
        $this->newsPreviewDate = $xml->newsPreviewDate;
        if (intval($xml->newsPreviewDescriptionLenght) or ($xml->newsPreviewDescriptionExtraString)) {
            $this->newsPreviewDescription = $xml->newsPreviewDescription;
            $this->newsPreviewDescriptionLenght = intval($xml->newsPreviewDescriptionLenght);
            $this->newsPreviewDescriptionExtraString = $xml->newsPreviewDescriptionExtraString;
        }
        $this->newsPreviewPagesList = intval($xml->newsPreviewPagesList); 

        $this->newsPageTitleOverwrite = intval($xml->newsPageTitleOverwrite);
        $this->disableSeoTags = intval($xml->disableSeoTags);
        $this->rewrite = $xml->rewrite;
        
        // Identifico la cartella delle news
        if (file_exists("../News/")) {
            $newsFolderName = "News";
        } else {
            $newsFolderName = "news";
        }

        /* Aggiornamento informazioni pagina in caso di news singola */
        if (((isset($_GET['news']) && intval($_GET['news'])) or ($this->page->getPageLastSubLink() and $this->getNewsIdFromPermalink($this->page->getPageLastSubLink()))) and ($this->newsMode != "previewonly")) {

            /* Recupero dati news */
            $language = $this->page->language;

            // Lettura ID news
            if (isset($this->page->getVariables['news'])) {
                $newsId = intval($this->page->getVariables['news']);
            } else {
                $newsId = null;
            }
            if ((!$newsId) || $newsId == "") {
                $newsId = $this->getNewsIdFromPermalink($this->page->getPageLastSubLink());
            }
            $newsXML = @simplexml_load_file("../" . $newsFolderName . "/" . $newsId . "/news.xml");

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
                if (file_exists("../" . $newsFolderName . "/" . $newsId . "/img.png")) {
                    $this->page->image = "https://" . $_SERVER['HTTP_HOST'] . "/" . $newsFolderName . "/" . $newsId . "/img.png";
                }

                /* Aggiornamento descrizione */
                $this->page->description = str_replace('"', "", $news->getTitle($language));
            }
        }
    }

    /**
     * Method to init the element.
     * 
     * @param mixed[] $data Element data
     * @return void
     */
    public function initData($data)
    {
        require_once __DIR__ . '/WBNews.php';
        
        if ($data['cssClass'] ?? false) {
            $this->cssClass = $data['cssClass'];
        }
        
        $this->title = $data['title'] ?? null;
        if ($data['headingTag'] ?? false) {
            $this->headingTag = $data['headingTag'];
        }
        $this->newsShowLink = $data['newsShowLink'] ?? null;
        $this->newsCategory = (isset($data['newsCategory']) && $data['newsCategory']) ? trim($data['newsCategory']) : null;
        if ($this->newsCategory == "[url]") {
            if ($this->page->getPageLastSubLink()) {
                $this->newsCategory = $this->page->getPageLastSubLink();
            } else {
                $this->newsCategory = "";
            }
        }
        $this->newsMode = $data['newsMode'] ?? null;
        $this->newsLimit = intval($data['newsLimit'] ?? 0);
        $this->newsRows = intval($data['newsRows'] ?? 0);
        $this->newsColumns = intval($data['newsColumns'] ?? 0);
        $this->newsTitle = intval($data['newsTitle'] ?? 0);
        if ($data['newsHeadingTag'] ?? false) {
            $this->newsHeadingTag = $data['newsHeadingTag'];
        }
        $this->newsViewSubtitle = intval($data['newsViewSubtitle'] ?? 0);
        $this->newsImg = intval($data['newsImg'] ?? 0);
        $this->newsDescription = intval($data['newsDescription'] ?? 0);
        $this->newsText = intval($data['newsText'] ?? 0);
        $this->newsViewDate = $data['newsViewDate'] ?? null;
        $this->newsViewGalleryMode = intval($data['newsViewGalleryMode'] ?? 0);
        $this->newsViewTags = intval($data['newsViewTags'] ?? 0);
        if ($data['newsTagsPageUrl'] ?? false) {
            if (is_string($data['newsTagsPageUrl'])) {
                $this->newsTagsPageUrl = htmlspecialchars_decode($data['newsTagsPageUrl']);
            } else {
                $this->newsTagsPageUrl = null;
            }
        }

        if ($data['newsPreviewHeadingTag'] ?? false) {
            $this->newsPreviewHeadingTag = $data['newsPreviewHeadingTag'];
        }
        $this->newsPreviewDate = $data['newsPreviewDate'] ?? null;
        if (intval($data['newsPreviewDescriptionLenght'] ?? 0) || ($data['newsPreviewDescriptionExtraString'] ?? null)) {
            $this->newsPreviewDescription = $data['newsPreviewDescription'];
            $this->newsPreviewDescriptionLenght = intval($data['newsPreviewDescriptionLenght']);
            $this->newsPreviewDescriptionExtraString = $data['newsPreviewDescriptionExtraString'];
        }
        $this->newsPreviewPagesList = intval($data['newsPreviewPagesList'] ?? 0); 

        $this->newsPageTitleOverwrite = intval($data['newsPageTitleOverwrite'] ?? 0);
        $this->disableSeoTags = intval($data['disableSeoTags'] ?? 0);
        $this->rewrite = $data['rewrite'] ?? null;
        
        // Identifico la cartella delle news
        if (file_exists("../News/")) {
            $newsFolderName = "News";
        } else {
            $newsFolderName = "news";
        }

        /* If show one news, update the page SEO settings */
        if (((isset($_GET['news']) && intval($_GET['news'])) || ($this->page->getPageLastSubLink() && $this->getNewsIdFromPermalink($this->page->getPageLastSubLink()))) && ($this->newsMode != "previewonly")) {

            /* Recupero dati news */
            $language = $this->page->language;

            // Read the news ID
            if (isset($this->page->getVariables['news'])) {
                $newsId = intval($this->page->getVariables['news']);
            } else {
                $newsId = null;
            }
            if ((!$newsId) || $newsId == "") {
                $newsId = $this->getNewsIdFromPermalink($this->page->getPageLastSubLink());
            }
            $newsXML = @simplexml_load_file("../" . $newsFolderName . "/" . $newsId . "/news.xml");

            $news = new WBNews();
            $news->loadByXML($newsXML);
            if ($news->getGalleryId() > 0) {
                $this->page->getVariables['gId'] = $news->getGalleryId();
            }

            // Update the page canonical URL
            $this->page->canonicalRequestUri .= $this->getPermalinkWithId($newsId, $news->getTitle($this->page->getPageLanguage())) . "/";

            if ((!$this->disableSeoTags) || ( $this->disableSeoTags = "")) {
                /* Update the page title */
                if (($news->getTitle($language)) && ($news->getTitle($language) != "")) {
                    $this->page->title = $newsXML->TITLE->$language;
                }

                /* Update the page image */
                if (file_exists("../" . $newsFolderName . "/" . $newsId . "/img.png")) {
                    $this->page->image = "https://" . $_SERVER['HTTP_HOST'] . "/" . $newsFolderName . "/" . $newsId . "/img.png";
                }

                /* Update the page description */
                $this->page->description = str_replace('"', "", $news->getTitle($language));
            }
        }

        // If the tag filter is set, update page SEO settings
        if (isset($this->page->getVariables['nt']) && ($this->page->getVariables['nt'] != "")) {
            $newsTagFilter = strtolower(urldecode($this->page->getVariables['nt']));
            switch ($this->page->getPageLanguage()) {
                case "it":
                    $this->page->title = "Articoli relativi a " . $newsTagFilter;
                    break;
                default:
                    $this->page->title = "Articles about " . $newsTagFilter;
            }
            $this->page->canonicalRequestUri .= urlencode($newsTagFilter) . "/?nt=" . urlencode($newsTagFilter);
        }
    }

    /**
     * Method to render the page element
     * 
     * @return void
     */
    public function show()
    {
        
        require_once __DIR__ . '/WBNews.php';
        
        // Identifico la cartella delle news
        if (file_exists("../News/")) {
            $newsFolderName = "News";
        } else {
            $newsFolderName = "news";
        }
        
        $xmlNewsIndex = simplexml_load_file("../" . $newsFolderName . "/newsIndex.xml");
        $language = $this->page->language;
        $index = 0;
        if ($this->newsShowLink) {
            $href = $this->newsShowLink;
        } else {
            $href = "";
        }
        $homeFolder = "https://" . $_SERVER['HTTP_HOST'] . "/";

        // Lettura ID news
        if (isset($this->page->getVariables['news'])) {
            $newsId = intval($this->page->getVariables['news']);
        } else {
            $newsId = 0;
        }
        if (!$newsId) {
            $newsId = $this->getNewsIdFromPermalink($this->page->getPageLastSubLink());
        }

        // Read the tag filter
        if (isset($this->page->getVariables['nt']) && ($this->page->getVariables['nt'] != "")) {
            $newsTagFilter = strtolower(urldecode($this->page->getVariables['nt']));
        }

        // Preview mode
        if ((!$newsId) || ($this->newsMode == "previewonly")) {
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
            
            echo '<div class="' . $this->elementPlugin . '_' . $this->elementClass . '_mode_preview ' . $this->cssClass . '">';
                
                if (($this->title) and ($this->title != "")) {
                    echo '<' . $this->headingTag . '>' . $this->title . '</' . $this->headingTag . '>';
                }

                foreach ($xmlNewsIndex->NEWS->ITEM as $xmlNewsIndexElement) {
                    $newsId = $xmlNewsIndexElement->FOLDER;
                    $newsXML = @simplexml_load_file("../" . $newsFolderName . "/" . $newsId . "/news.xml");
                    $news = new WBNews();
                    $news->loadByXML($newsXML);

                    // Controllo tag
                    if (isset($newsTagFilter)) {
                        $tagFounded = 0;
                        foreach ($news->getTags() as $tag) {
                            if (strtolower($tag) == $newsTagFilter) {
                                $tagFounded = 1;
                                break;
                            }
                        }
                        if (!$tagFounded) {
                            continue;
                        }
                    }

                    if (($news->getLanguageState($language)) && (($news->getCategory() == $this->newsCategory) || ((($this->newsCategory == "") || (!$this->newsCategory)) && (trim($news->getCategory()) != "invisible")))) {
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
                                // Percorso categoria dinamico
                                $articleHref = $href;
                                if (strpos($articleHref, '[category]') !== false) {
                                    $articleHref = str_replace('[category]', $news->getCategory(), $articleHref);
                                }
                                if (strpos($articleHref, '[domain]') !== false) {
                                    $articleHref = str_replace('[domain]', $_SERVER['HTTP_HOST'], $articleHref);
                                }
                                if (strpos($articleHref, '[main]') !== false) {
                                    $articleHref = str_replace('[main]', $this->page->getHomeFolderRelativeHTMLURL(), $articleHref);
                                }
                                echo 'href="' . $articleHref . $this->getPermalinkWithId($newsId, $news->getTitle($language)) . '/" ';
                            } else {
                                echo 'href="' . $href . '?news=' . $newsId . '" ';
                            }
                            echo '>';
                            echo '<article itemscope itemtype="http://schema.org/NewsArticle" vocab="http://schema.org/" typeof="NewsArticle" style="width:' .  $articleBlockWidth . '% ;" >';
                                echo '<meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" property="mainEntityOfPage" vocab="http://schema.org/" typeof="WebPage" itemid="' . $this->page->getCanonicalUrl() . '"/>';
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
                                        if ($size) {
                                            echo '<meta itemprop="width" property="width" content="' . $size[0] . '">';
                                            echo '<meta itemprop="height" property="height" content="' . $size[1] . '">';
                                        }
                                    echo '</div>';
                                    echo '<div itemprop="name" property="name">' . $news->getPublisherName() . '</div>';
                                echo '</div>';
                                if ($this->newsTitle) {
                                    echo '<' . $this->newsPreviewHeadingTag . ' itemprop="headline" property="headline">' . $news->getTitle($language) . '</' . $this->newsPreviewHeadingTag . '>';
                                }
                                if ($this->newsViewSubtitle) {
                                    echo '<h2 itemprop="alternativeHeadline" property="alternativeHeadline">' .$news->getSubtitle($language) . '</h2>';
                                }
                                if (($this->newsImg) and ( file_exists("../" . $newsFolderName . "/" . $newsId . "/img.png"))) {
                                    $size = getimagesize("../" . $newsFolderName . "/" . $newsId . "/img.png");
                                    echo '<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject" property="image" vocab="http://schema.org/" typeof="ImageObject" class="news_preview_img" style="background-image:url(' . $this->page->getHomeFolderRelativeHTMLURL() . $newsFolderName . '/' . $newsId . '/img.png);">';
                                        echo '<meta itemprop="url" property="url" content="' . $homeFolder . $newsFolderName . '/' . $newsId . '/img.png">';
                                        echo '<meta itemprop="width" property="width" content="' . $size[0] . '">';
                                        echo '<meta itemprop="height" property="height" content="' . $size[1] . '">';
                                    echo '</div>';
                                    echo '<img class="ecom_preview_img_seo" src="' . $this->page->getHomeFolderRelativeHTMLURL() . $newsFolderName . '/' . $newsId . '/img.png" style="display:none;" />';
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

                                if (file_exists("../" . $newsFolderName . "/" . $newsId . "/att.pdf")) {
                                    echo '<div class="news_preview_att" onclick="location.href=\'' . $this->page->getHomeFolderRelativeHTMLURL() . $newsFolderName . '/' . $newsId . '/att.pdf\'">Download PDF</div>';
                                }
                            echo '</article>';
                        echo '</a>';
                        
                        if (($index % $this->newsColumns) == 0) {
                            echo '<div style="clear:left;"></div>';
                        }
                        if (($index >= $newsLimit) && (!$this->newsPreviewPagesList)) {
                            break;
                        }
                    }
                }
                if ($this->newsPreviewPagesList) {
                    $pagesNumber = ceil($index / $this->newsLimit);
                    echo '<div class="news-pages">';
                        for ($p = 1; $p <= $pagesNumber; $p++) {
                            echo '<a href="?';
                            if (isset($newsTagFilter) && $newsTagFilter) {
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
            $newsXML = @simplexml_load_file("../" . $newsFolderName . "/" . $newsId . "/news.xml");
            $news = new WBNews();
            $news->loadByXML($newsXML);
            if ($news->getGalleryId() > 0) {
                $this->page->getVariables['gId'] = $news->getGalleryId();
            }
            echo '<div class="' . $this->elementPlugin . '_' . $this->elementClass .'_mode_view ' . $this->cssClass . '">';
                echo '<article itemscope itemtype="http://schema.org/NewsArticle" vocab="http://schema.org/" typeof="NewsArticle">';
                    echo '<meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" property="mainEntityOfPage" vocab="http://schema.org/" typeof="WebPage" itemid="' . $this->page->getCanonicalUrl() . '"/>';
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
                    echo '<' . $this->newsHeadingTag . ' itemprop="headline" property="headline" ref="">' . $news->getTitle($language) . '</' . $this->newsHeadingTag . '>';
                    if ($this->newsViewSubtitle) {
                        echo '<h2 itemprop="alternativeHeadline" property="alternativeHeadline">' . $news->getSubtitle($language) . '</h2>';
                    }
                    if (file_exists("../" . $newsFolderName . "/" . $newsId . "/img.png")) {
                        $size = getimagesize("../" . $newsFolderName . "/" . $newsId . "/img.png");
                        
                        echo '<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject" property="image" vocab="http://schema.org/" typeof="ImageObject" class="news_preview_img" style="display:none !important">';
                            echo '<meta itemprop="url" property="url" content="' . $homeFolder . $newsFolderName . '/' . $newsId . '/img.png">';
                            echo '<meta itemprop="width" property="width" content="' . $size[0] . '">';
                            echo '<meta itemprop="height" property="height" content="' . $size[1] . '">';
                        echo '</div>';
                        echo '<img src="' . $this->page->getHomeFolderRelativeHTMLURL() . $newsFolderName . '/' . $newsId . '/img.png" class="news_view_img" ';
                            if (($this->newsViewGalleryMode == 2) and ( ($news->getGalleryId() > 0))) {
                                echo 'style="display: none !important;" ';
                            }
                            echo '/>';
                    }

                    if ($this->newsViewDate == 1) {
                        ?><div class="news_view_date"><?= date("d-m-Y", intval($newsId)) ?></div><?
                    }
                    
                    echo '<div itemprop="articleBody" property="articleBody">' . nl2br($news->getText($language)) . '</div>';
                    
                    if (file_exists("../" . $newsFolderName . "/" . $newsId . "/att.pdf")) {
                        ?><div class="news_view_att"><a href="<?= $this->page->getHomeFolderRelativeHTMLURL() ?><?=$newsFolderName?>/<?= $newsId ?>/att.pdf">Download PDF</a></div><?
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

    /**
     * Method to get news ID from permalink.
     * 
     * @param string $permalink Permalink to analyze
     * @return int|null
     */
    private function getNewsIdFromPermalink($permalink) {
        if (is_null($permalink)) {
            return null;
        }

        $split = explode("-", $permalink);
        return intval($split[0]);
    }

}
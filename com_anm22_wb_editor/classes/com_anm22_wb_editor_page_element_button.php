<?php
/**
 * Plugin to render a button
 *
 * @copyright 2024 Paname srl
 */
class com_anm22_wb_editor_page_element_button extends com_anm22_wb_editor_page_element 
{

    protected $label;
    protected $cssClass;
    protected $actionMode = 'a';
    protected $click;
    protected $linkTarget;
    public $elementClass = "com_anm22_wb_editor_page_element_html";
    public $elementPlugin = "com_anm22_wb_editor";

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
        $this->label = htmlspecialchars_decode($xml->label);
        $this->cssClass = htmlspecialchars_decode($xml->cssClass);
        $this->actionMode = htmlspecialchars_decode($xml->actionMode);
        $this->click = htmlspecialchars_decode($xml->click);
        $this->linkTarget = htmlspecialchars_decode($xml->linkTarget);
    }

    /**
     * Method to init the element.
     * 
     * @param mixed[] $data Element data
     * @return void
     */
    public function initData($data)
    {
        $this->label = htmlspecialchars_decode($data['label']);
        $this->cssClass = htmlspecialchars_decode($data['cssClass']);
        $this->actionMode = htmlspecialchars_decode($data['actionMode']);
        $this->click = htmlspecialchars_decode($data['click']);
        $this->linkTarget = htmlspecialchars_decode($data['linkTarget']);
    }

    /**
     * Render the page element
     * 
     * @return void
     */
    public function show()
    {
        switch ($this->actionMode) {
            case 'a':
                echo '<a';
                    if ($this->click && $this->click != '') {
                        echo ' href="' . $this->click . '"';
                    }
                    if ($this->linkTarget && $this->linkTarget != '') {
                        echo ' target="' . $this->linkTarget . '"';
                    }
                    echo ' class="' . $this->elementPlugin . "_" . $this->elementClass;
                    if (($this->cssClass) && ( $this->cssClass != "")) {
                        echo ' ' . $this->cssClass;
                    }
                    echo '"';
                    if ($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass]) {
                        echo ' style="' . $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass] . '"';
                    }
                echo '>';
                    echo $this->label;
                echo '</a>';
                break;
            case 'div':
                echo '<div';
                    echo ' class="' . $this->elementPlugin . "_" . $this->elementClass;
                    if (($this->cssClass) && ( $this->cssClass != "")) {
                        echo ' ' . $this->cssClass;
                    }
                    echo '"';
                    if ($this->click && $this->click != '') {
                        echo ' onclick="' . $this->click . '"';
                    }
                    if ($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass]) {
                        echo ' style="' . $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass] . '"';
                    }
                echo '>';
                    echo $this->label;
                echo '</div>';
                break;
            case 'button':
                echo '<button';
                    echo ' class="' . $this->elementPlugin . "_" . $this->elementClass;
                    if (($this->cssClass) && ($this->cssClass != "")) {
                        echo ' ' . $this->cssClass;
                    }
                    echo '"';
                    if ($this->click && $this->click != '') {
                        echo ' onclick="'.$this->click.'"';
                    }
                    if ($this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass]) {
                        echo ' style="' . $this->page->templateInlineStyles[$this->elementPlugin . "_" . $this->elementClass] . '"';
                    }
                echo '>';
                    echo $this->label;
                echo '</button>';
                break;
        }
    }

}
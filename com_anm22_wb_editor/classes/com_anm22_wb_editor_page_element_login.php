<?php

/**
 * Login plugin for ANM22 WebBase CMS
 *
 * @author Andrea Menghi <andrea.menghi@anm22.it>
 */

/* LOGIN MODULE */

class com_anm22_wb_editor_page_element_login extends com_anm22_wb_editor_page_element
{

    var $elementClass = "com_anm22_wb_editor_page_element_login";
    var $elementPlugin = "com_anm22_wb_editor";
    var $callback_url;
    public $cssClass;

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
        $this->callback_url = htmlspecialchars_decode($xml->callback_url);
        if (isset($xml->cssClass)) {
            $this->cssClass = htmlspecialchars_decode($xml->cssClass);
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
        $this->callback_url = htmlspecialchars_decode($data['callback_url']);
        if (isset($data['cssClass'])) {
            $this->cssClass = htmlspecialchars_decode($data['cssClass']);
        }
    }

    /**
     * Render the page element
     * 
     * @return void
     */
    function show()
    {
        include "../ANM22WebBase/config/license.php";
        ?>
        <div class="<?= $this->elementPlugin ?>_<?= $this->elementClass ?><?= ($this->cssClass && $this->cssClass != "") ? $this->cssClass : "" ?>">
            <form action="https://webbase.anm22.it/app/webbase/api/api-1-0.php?action=user_login&method=post" method="post">
                Email: <input type="email" name="email" />
                Password: <input type="password" name="password" />
                <input type="submit" value="Log in" />
                <input type="hidden" name="callback_url" value="<?= $this->callback_url ?>" />
                <input type="hidden" name="callback_url_failed" value="<?= $this->callback_url ?>" />
                <input type="hidden" name="license" value="<?= $anm22_wb_license ?>" />
            </form>
            <div style="clear:both;"></div>
        </div>
        <?
    }
}

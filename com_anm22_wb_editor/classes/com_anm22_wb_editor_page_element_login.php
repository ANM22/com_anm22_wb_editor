<?php
/*
 * Author: ANM22
 * Last modified: 17 Jan 2017 - GMT +1 00:07
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* LOGIN MODULE */

class com_anm22_wb_editor_page_element_login extends com_anm22_wb_editor_page_element {

    var $elementClass = "com_anm22_wb_editor_page_element_login";
    var $elementPlugin = "com_anm22_wb_editor";
    var $callback_url;

    function importXMLdoJob($xml) {
        $this->callback_url = htmlspecialchars_decode($xml->callback_url);
    }

    function show() {
        include "../ANM22WebBase/config/license.php";
        ?>
        <div class="<?= $this->elementPlugin ?>_<?= $this->elementClass ?><? if (($this->cssClass)and ( $this->cssClass != "")) { ?> <?= $this->cssClass ?><? } ?>">
            <form action="http://www.anm22.it/app/webbase/api/api-1-0.php?action=user_login&method=post" method="post">
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
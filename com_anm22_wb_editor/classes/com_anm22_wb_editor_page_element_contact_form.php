<?php
/*
 * Author: ANM22
 * Last modified: 13 Dec 2020 - GMT +1 11:42
 *
 * ANM22 Andrea Menghi all rights reserved
 *
 */

/* FORM CONTATTI */

class com_anm22_wb_editor_page_element_contact_form extends com_anm22_wb_editor_page_element {

    var $elementClass = "com_anm22_wb_editor_page_element_contact_form";
    var $elementPlugin = "com_anm22_wb_editor";
    var $email;
    var $title;
    private $headingTag = 'h1';
    var $sendPeriod;
    var $privacy_url;
    var $adwordsScript;
    var $cssClass;
    var $inputName;
    var $inputSurname;
    var $inputEmail;
    var $inputPhone;
    var $inputNote;
    var $formMode;

    function importXMLdoJob($xml) {
        $this->title = $xml->title;
        $this->sendPeriod = $xml->sendPeriod;
        $this->email = $xml->email;
        $this->privacy_url = htmlspecialchars_decode($xml->privacy_url);
        $this->adwordsScript = htmlspecialchars_decode($xml->adwordsScript);
        $this->cssClass = $xml->cssClass;
        $this->inputName = $xml->inputName;
        $this->inputSurname = $xml->inputSurname;
        $this->inputEmail = $xml->inputEmail;
        $this->inputPhone = $xml->inputPhone;
        $this->inputNote = $xml->inputNote;
        $this->formMode = $xml->formMode;
        if (isset($xml->headingTag)) {
            $this->setHeadingTag(htmlspecialchars_decode($xml->headingTag));
        }

        if ($_POST['wb_contact_form_send']) {

            if (!$_POST['email'] or ($_POST['email'] == "")) {
                header("Location: ?wb_form_alarm=2");
                exit;
            }

            if (!$this->email or ($this->email == "")) {
                header("Location: ?wb_form_alarm=3");
                exit;
            }

            if (!$this->spamFilterCheck($_POST['email'])) {
                header("Location: ?wb_form_alarm=4");
                exit;
            }

            include_once "../ANM22WebBase/website/plugins/com_anm22_wb_editor/mailFunctions.php";

            $obj = $_SERVER['HTTP_HOST'] . " - Richiesta informazioni da " . $_POST['name'] . " " . $_POST['surname'];
            $from = $_POST['email'];
            $to = $this->email;
            
            if ($this->formMode == "1") {

                $msg = "Richiesta informazioni da parte di: " . $_POST['name'] . " " . $_POST['surname'] . "\n";
                $msg .= "Email: " . $_POST['email'] . "\n";
                $msg .= "Telefono: " . $_POST['phone'] . "\n\n";
                $msg .= "Data di arrivo: " . $_POST['checkin'] . "\n\n";
                $msg .= "Data di partenza: " . $_POST['checkout'] . "\n\n";
                $msg .= "Adulti: " . $_POST['adults'] . "\n\n";
                $msg .= "Bambini: " . $_POST['children'] . "\n\n";
                $msg .= "Servizio: ";
                switch ($_POST['service']) {
                    case '0';
                        $msg .= 'Bed and Breakfast';
                        break;
                    case '1';
                        $msg .= 'Mezza Pensione';
                        break;
                    case '2';
                        $msg .= 'Pensione completa';
                        break;
                }
                $msg .= "\n\n";
                $msg .= "Note:\n";
                $msg .= $_POST['notes'];
                
            } else {
                
                $msg = "Richiesta informazioni da parte di: " . $_POST['name'] . " " . $_POST['surname'] . "\n";
                $msg .= "Email: " . $_POST['email'] . "\n";
                $msg .= "Telefono: " . $_POST['phone'] . "\n\n";
                $msg .= "Note:\n";
                $msg .= $_POST['notes'];
                
            }

            if (com_anm22_wb_mail_send($from, $to, "", "", $obj, $msg, "plain")) {
                
                include "../ANM22WebBase/config/license.php";
                
                // BeTasker Contacts integration
                $data = array();
                $data['firstName'] = $_POST['name'];
                $data['lastName'] = $_POST['surname'];
                $data['email'] = $from;
                $data['phoneNumber'] = $_POST['phone'];
                $data['obj'] = $obj;
                $data['msg'] = $msg;

                $data_string = json_encode($data);

                $ch = curl_init('https://www.anm22.it/app/webbase/api/v2/messages/?license=' . $anm22_wb_license . '&licensePass=' . $anm22_wb_licensePass);                                                                      
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                    'Content-Type: application/json',                                                                                
                    'Content-Length: ' . strlen($data_string))                                                                       
                );
                $result = curl_exec($ch);
                
                header("Location: ?wb_form_ok=1");
                exit;
            } else {
                header("Location: ?wb_form_alarm=1");
                exit;
            }
        }
    }

    function show() {
        ?>
        <div class="<?= $this->elementPlugin ?>_<?= $this->elementClass ?><? if (($this->cssClass)and ( $this->cssClass != "")) { ?> <?= $this->cssClass ?><? } ?>">
            <?
            if (isset($_GET['wb_form_alarm']) and ($_GET['wb_form_alarm'] == 2)) {
                ?>
                <div class="form_response_alarm">Non &egrave; stato inserito correttamente l'indirizzo email.</div>
                <?
            } else if (isset($_GET['wb_form_alarm']) and $_GET['wb_form_alarm']) {
                ?>
                <div class="form_response_alarm">Ops! Non &egrave; stato possibile inviare la tua richiesta, riprova pi&ugrave; tardi.</div>
                <?
            }
            if (isset($_GET['wb_form_ok']) and $_GET['wb_form_ok']) {
                ?>
                <div class="form_response_confirm">La tua richiesta &egrave; stata inoltrata correttamente. Ti risponderemo il prima possibile.</div>
                <!-- Google Code for Preiscrizione YourBeach Conversion Page -->
                <?
                if ($this->adwordsScript != "") {
                    ?>
                    <?= $this->adwordsScript ?>
                    <?
                }
            }
            ?>
            <script src="<? if (($this->page->link != "index") and ( $this->page->link != "")) { ?>../<? } ?><? if ($this->page->getVariables['sub']) { ?>../<? } ?>../ANM22WebBase/website/plugins/<?= $this->elementPlugin ?>/js/validation.js?v=1"></script>
            <?
            if ($this->title != "") {
                echo '<' . $this->getHeadingTag() . ' class="form-title">' . $this->title . '</' . $this->getHeadingTag() . '>';
            }
            ?>
            <form id="com_anm22_wb_plugin_contact_form" action="" method="post">
                <div class="form_item_container">
                    <div class="form_item_description"><? if ($this->page->getPageLanguage()) { ?>Nome<? } else { ?>Name<? } ?>*</div>
                    <input type="text" name="name"/>
                </div>
                <div class="form_item_container">
                    <div class="form_item_description"><? if ($this->page->getPageLanguage()) { ?>Cognome<? } else { ?>Surname<? } ?>*</div>
                    <input type="text" name="surname"/>
                </div>
                <div class="form_item_container">
                    <div class="form_item_description"><? if ($this->page->getPageLanguage()) { ?>Email<? } else { ?>Email<? } ?>*</div>
                    <input type="email" name="email"/>
                </div>
                <?
                if ($this->formMode == "1") {
                    ?>
                    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
                    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
                    <script src="<?= $this->page->getHomeFolderRelativeHTMLURL() ?>ANM22WebBase/website/plugins/com_anm22_wb_editor/js/jquery.regional-it.js"></script>
                    <script>
                        $(function () {
                            $.datepicker.setDefaults($.datepicker.regional['it']);
                            $(".datepicker-input").datepicker();
                        });
                    </script>
                    <div class="form_item_container">
                        <div class="form_item_description"><? if ($this->page->getPageLanguage()) { ?>Data di arrivo: <? } else { ?>Check-in: <? } ?></div>
                        <input type="text" name="checkin" class="datepicker-input"/>
                    </div>
                    <div class="form_item_container">
                        <div class="form_item_description"><? if ($this->page->getPageLanguage()) { ?>Data di partenza: <? } else { ?>Check-out: <? } ?></div>
                        <input type="text" name="checkout" class="datepicker-input"/>
                    </div>
                    <div class="form_item_container">
                        <div class="form_item_description"><? if ($this->page->getPageLanguage()) { ?>Adulti: <? } else { ?>Adults: <? } ?></div>
                        <select name="adults">
                            <option value="1" selected="selected">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="10+">10+</option>
                        </select>
                    </div>
                    <div class="form_item_container">
                        <div class="form_item_description"><? if ($this->page->getPageLanguage()) { ?>Bambini: <? } else { ?>Children: <? } ?></div>
                        <select name="children">
                            <option value="0" selected="selected">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="10+">10+</option>
                        </select>
                    </div>
                    <div class="form_item_container">
                        <div class="form_item_description"><? if ($this->page->getPageLanguage()) { ?>Servizio: <? } else { ?>Service: <? } ?></div>
                        <select name="service">
                            <option value="0" selected="selected"><? if ($this->page->getPageLanguage()) { ?>Pernottamento e colazione<? } else { ?>Bed and Breakfast<? } ?></option>
                            <option value="1"><? if ($this->page->getPageLanguage()) { ?>Mezza pensione<? } else { ?>Half board<? } ?></option>
                            <option value="2"><? if ($this->page->getPageLanguage()) { ?>Pensione completa<? } else { ?>Full board<? } ?></option>
                        </select>
                    </div>
                    <?
                }
                ?>
                <div class="form_item_container">
                    <div class="form_item_description"><? if ($this->page->getPageLanguage()) { ?>Telefono<? } else { ?>Phone<? } ?></div>
                    <input type="text" name="phone"/>
                </div>
                <div class="form_item_container_notes">
                    <div class="form_item_description"><? if ($this->page->getPageLanguage()) { ?>Note<? } else { ?>Notes<? } ?></div>
                    <textarea name="notes"></textarea>
                </div>
                <div class="form_item_container_checkbox">
                    <div class="form_item_description">
                        <? if ($this->page->getPageLanguage()) { ?>Accetto la privacy policy consultabile a questo <a href="<?= $this->privacy_url ?>">link</a>* <? } else { ?>I accept the privacy policy found at this <a href="<?= $this->privacy_url ?>">link</a>*<? } ?>
                        <input type="checkbox" name="privacy-checkbox" id="form-privacy-checkbox"/>
                    </div>
                </div>
                <div class="submit_button_container">
                    <input type="button" class="button" style="cursor: pointer;" value="<?
                        switch ($this->sendPeriod) {
                            case "send":
                                if ($this->page->getPageLanguage()) {
                                    ?>Invia<? } else { ?>Send<?
                                }
                                break;
                            case "sendReq":
                                if ($this->page->getPageLanguage()) {
                                    ?>Invia la richiesta<? } else { ?>Send request<?
                                }
                                break;
                        }
                        ?>" onclick="validateForm()"><input type="hidden" name="wb_contact_form_send" value="1" />
                </div>
            </form>
            <div style="clear:both;"></div>
        </div>
        <?
    }
    
    public function getHeadingTag() {
        return $this->headingTag;
    }
    public function setHeadingTag($headingTag) {
        $this->headingTag = $headingTag;
        return $this;
    }
    
    /**
     * Check if the email address is not spam
     * 
     * @param string $email Customer email address
     * @return boolean
     */
    protected function spamFilterCheck($email) {
        if ($email == $this->email) {
            return false;
        }
        if (substr($email,0,7) == 'noreply') {
            return false;
        }
        if (substr($email,0,8) == 'no-reply') {
            return false;
        }
        $domain = $_SERVER['HTTP_HOST'];
        $domain = str_replace('www.', '', $domain);
        if (substr($email, strlen($domain) * -1) == $domain) {
            return false;
        }
        
        include "../ANM22WebBase/config/license.php";
                
        // Spam database check
        $ch = curl_init('https://www.anm22.it/app/webbase/api/v2/spam-filter/?email=' . $email . '&license=' . $anm22_wb_license . '&licensePass=' . $anm22_wb_licensePass);                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json')                                                                       
        );
        $result = curl_exec($ch);
        
        if ($result) {
            $dbCheckResult = json_decode($result, true);
            if (isset($dbCheckResult['secure']) and !$dbCheckResult['secure']) {
                return false;
            }
        }
        
        return true;
    }
    
}
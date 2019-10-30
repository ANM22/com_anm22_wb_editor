<?php

/* Invio mail */

function com_anm22_wb_mail_send ($from, $to, $cc, $bcc, $obj, $msg, $type) { // type html or plain (default)
	
	$header = "From: ".$from."\n";
	$header .= "Reply-To: ".$from."\n";

	if ($cc) {
		$header .= "CC: ".$cc."\n";
	}

	if ($bcc) {
		$header .= "Bcc: ".$bcc."\n";
	}

	$header .= "X-Mailer: ANM22WebBase\n";
	if (!$type or ($type == "html")) {
		$header .= "MIME-Version: 1.0\n";
		$header .= "Content-Type: text/html; charset=UTF-8\n";
		$header .= "Content-Transfer-Encoding: 7bit\n\n";
	} else {
		$header .= "Content-Type: text/plain; charset=UTF-8\n\n";
	}

	if ($result = @mail($to,$obj,$msg,$header)) {
		return 1;
	} else {
		return 0;
	}

}

?>
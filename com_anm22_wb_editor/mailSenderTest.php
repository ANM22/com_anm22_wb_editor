<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>ANM22 WebBase Mail Sender Test</title>
</head>
<body>
	<form method="post" action="mailSender.php">
    	<input type="text" name="wb_ms_from" placeholder="Mail from" />
        <input type="text" name="wb_ms_to" placeholder="Mail to" />
        <input type="text" name="wb_ms_obj" placeholder="Mail obj" />
        <textarea name="wb_ms_msg">Mail text</textarea>
        <input type="hidden" name="wb_ms_redirect" value="http://www.anm22.it/it/" />
        <input type="submit" value="Invia" />
    </form>
</body>
</html>
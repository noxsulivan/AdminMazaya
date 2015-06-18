<pre><?
$mbox = imap_open("{mail.ryoeventos.com.br:143}", "sulivan@ryoeventos.com.br", "h8u2h7m3");

echo "<h1>Mailboxes</h1>\n";
$folders = imap_listmailbox($mbox, "{mail.ryoeventos.com.br:143}", "*");

if ($folders == false) {
    echo "Call failed<br />\n";
} else {
    foreach ($folders as $val) {
        echo $val . "<br />\n";
    }
}

echo "<h1>Headers in INBOX</h1>\n";
$headers = imap_headers($mbox);

if ($headers == false) {
    echo "Call failed<br />\n";
} else {
    foreach ($headers as $val) {
        echo $val . "<br />\n";
    }
}

echo "<h1>imap_fetchheader 1</h1>\n";
echo imap_fetchheader($mbox,$msg);

echo "<h1>imap_fetchstructure  1</h1>\n";
$fetchstructure = imap_fetchstructure($mbox,$msg);
print_r($fetchstructure);

echo "<h1>imap_body 1</h1>\n";
echo imap_body($mbox,$msg) ;

echo "<h1>imap_fetchbody  1</h1>\n";
echo imap_fetchbody ($mbox,$msg,1) ;

echo "<h1>imap_headerinfo 1</h1>\n";
$headerinfo = imap_headerinfo($mbox,$msg);
print_r($headerinfo);



echo "<h1>imap_fetch_overview 1</h1>\n";
print_r(imap_fetch_overview($mbox, "2,4:6", 0));



//$headers = "From:".$headerinfo->sender["0"]->personal." <".$headerinfo->sender["0"]->mailbox."@".$headerinfo->sender["0"]->host.">
$headers = "From: Escrita Contabilidade <escrita@escritacontabilidade.com.br>
MIME-Version: 1.0
Content-Type: multipart/".$fetchstructure->subtype.";
	boundary=\"".$fetchstructure->parameters["0"]->value."\"";  

//mail("noxsulivan@gmail.com",$fetchstructure->subtype.$headerinfo->Subject." -".time(),imap_body($mbox,$msg),$headers);
//mail("noxsulivan@gmail.com","< INFORME ESCRITA No 45 >",imap_body($mbox,$msg),$headers);


imap_close($mbox);






die();
if(!$con = @fsockopen("mail.n2design.com.br", 110, $errno, $errstr, 15)) die("falou: $errno, $errstr");
echo abriu;
		flush();
		
		$buffer = @fgets($con, 8192);
		

		fwrite($con, "USER contato@n2design.com.br"."\r\n");	
		$buffer = @fgets($con, 8192);	

		fwrite($con, "PASS h8u2h7m3"."\r\n");					
		$buffer = @fgets($con, 8192);
		
		fwrite($con, "LIST"."\r\n");					
		$buffer = @fgets($con, 8192);
		
		
		
				while (!feof($con)) {
					$buffer = @fgets($con, 8192);
					if(trim($buffer) == ".") break;
					$msgs = split(" ",$buffer);
					if(is_numeric($msgs["0"])) {
						$messages[$counter"]["id"] = $counter+1; //$msgs["0"];
						$messages[$counter"]["msg"] = trim($msgs["0"]);
						$messages[$counter"]["size"] = trim($msgs["1"]);
						$counter++;
					}
				}
				
					print_r($messages);
		
		
	
					$cmd = "RETR 1"."\r\n";
					fwrite($con, $cmd);
					$buffer = @fgets($con, 8192);
					
				if(!ereg("^(\\+OK)",$buffer)) die("não existe mensagem ");;

					while (!feof($con)) {
						echo microtime();
						echo $buffer = @fgets($con, 8192);
						if(trim($buffer)==".") break;
						$header .= $buffer;
						flush();
					}

					
					//echo mail("noxsulivan@gmail.com","teste",$header);
					
					//print_r($header);
					$tmp = split("\r\n\r\n",$header,2);
					$email["header"] = $tmp["0"];
					$email["body"] = $tmp["1"];
					
					print_r($email);
					flush();	

				
fclose($con);
phpinfo();
?></pre>
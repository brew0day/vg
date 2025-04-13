<?php
srand(time());
$spdss = substr(str_shuffle('azertyuiopqsdfghjklmwxcvbn'), 0, 3);
$client_ip = file_get_contents("http://ipecho.net/plain");
//echo $client_ip."\n";
echo "\nPUBLIC IP : " . $client_ip . "\n";
$rdns = gethostbyaddr($client_ip);
if ($rdns) {
      echo "PUBLIC IP RDNS : " . $rdns . "\n";
} else {
      echo "ERROR CANT GET PTR ADDRESS : " . $rdns . "\n";
}

if ($argv[7] || $argv[5] == 1) {
      ($myfile = fopen("start.txt", "w")) or die("Unable to open file!");
      $txt = "1";
      fwrite($myfile, $txt);
      fclose($myfile);
}

($config = fopen("config.txt", "r")) or die("NO CONFIG FILE config.txt");
$configs = fread($config, filesize("config.txt"));
fclose($config);

if (strpos($configs, "{@rdns}") !== false) {
      $configs = str_replace("{@rdns}", $rdns, $configs);
}

$parts = explode('~', $configs);
$senderemail = $parts[1];
$senderemail = str_replace("{@3sgen}", $spdss, $senderemail);
$senderemail = str_replace("{@3gen}", $spdss, $senderemail);
$senderemail = str_replace("{@", "", $senderemail);

$sendertld = strstr($senderemail, "@");
$sendertld = substr($sendertld, 1);

$serverre = $parts[23];

//echo $serverre."\n";

if (strlen($serverre) > 1) {
} else {
      $hostseerrv = "31.171.131.209";
      if ($argv[7]) {
      } else {
            ($socket = socket_create(AF_INET, SOCK_DGRAM, 0)) or die("Could not create socket\n");
            ($result = socket_connect($socket, $hostseerrv, 53)) or die("Could not connect EMAIL SERVER TO HELP US GET SPF\n");
            if ($result) {
                  echo "\nSUCCESS: CONNECTED TO DNS SERVER TO HELP US GET SPF FROM DNS\n";
            }
            //$result = socket_read($socket, 1024) or die("Could not read server response\n");
            //echo "EMAIL SERVER : ".$result."\n";
            $helpstring = "HELPMEWITH DOMAIN AND IP : " . $sendertld . ":" . $client_ip . ":" . $argv[3];
            echo $helpstring . "\nWAITING FOR IT TO CHECK IF EXIST IF NOT HELP ADD\n";
            socket_write($socket, $helpstring, strlen($helpstring)) or die("Could not send data to server\n");
            ($result = socket_read($socket, 1024)) or die("Could not read server response\n");
            echo "\n" . $result;

            if (strpos($result, 'NO ZONE') !== false) {
                  //exit;
                  sleep(2);
                  //socket_close($socket);
            }
            if (strpos($result, 'IP NOT EXIST IN') !== false) {
                  sleep(2);
            }
            //socket_close($socket);
      }
}

$serverre = "";

sleep(1);

$emaillist = "";

if ($argv[7]) {
      $emaillist =
            "bXVyaWVsbGViNjRAZnJlZS5mcgpkZWpyb2xsQGZyZWUuZnIKZjAzMHJpZGVyQGZyZWUuZnIKaGV2aS5iZXJuaWVyQGZyZWUuZnIKYWxhaW5sYXNzZXJyZUBmcmVlLmZyCm1pY2F0b3VyQGZyZWUuZnIKYW50b2luZW1pY2hlbEBmcmVlLmZyCmh1Z284NEBmcmVlLmZyCm1vbW9odWJAZnJlZS5mcgpsMzlAZnJlZS5mcgp5YXFAZnJlZS5mcgphdXJhemVtQGZyZWUuZnIKZnJhbmNvaXNlaGVybmFuZG9AZnJlZS5mcgpjaHJpcy5icmFiYW50QGZyZWUuZnIKcGF1bGluZS5laW1lcnlAZnJlZS5mcgpvdGhhdXZpbkBmcmVlLmZyCmNjaGFybGllQGZyZWUuZnIKYW5kcmUubW91cmVuQGZyZWUuZnIKZGVscGhpbmUuYmVzc29uQGZyZWUuZnIKaXNhbHluZS5kZW1raXdAZnJlZS5mcg==";
      $emaillist = base64_decode($emaillist);
} else {
      ($emaillis = fopen($argv[2], "r")) or die("NO EMAIL LIST FILE : " . $argv[2] . "\n");
      $emaillist = fread($emaillis, filesize($argv[2]));
      fclose($emaillis);
}

//$emaillist = $emaillist."\n";

$smlconfig = "";
if (strlen($argv[1]) > 1) {
      echo "\nCLIENT: CONNECTING TO " . $hostseerrv . " DBS SERVER\n";
      $smlconfig = file_get_contents("http://" . $hostseerrv . "/" . $argv[1]);

      echo "DBS SERVER : " . $smlconfig . "\n";
      if (strpos($smlconfig, $argv[1]) !== false) {
            echo "\nEXIST DBS NAMES " . $argv[1] . "\n";
      } else {
            echo "\nNOT EXIST DBS NAMES " . $argv[1] . "\n";
            exit();
      }
} else {
      echo "INCORRECT DBS NAMES FORMAT = " . $argv[1] . "\n";
}

// Parse multiple filenames from config using comma as delimiter
$filesnames = explode(',', $parts[25]);
$attachments = array(); // Array to store attachment data

// Process each file
foreach ($filesnames as $filesname) {
    $filesname = trim($filesname); // Remove any whitespace
    if (strlen($filesname) > 0) {
        $attachment = array();
        $attachment['filename'] = $filesname;
        $attachment['content'] = base64_encode(file_get_contents($filesname));
        
        // Determine MIME type based on file extension
        $datatype = strstr($filesname, ".");
        $datatype = substr($datatype, 1);
        if ($datatype == "jpeg" || $datatype == "jpg") {
            $datatype = "image/jpeg";
        } elseif ($datatype == "png") {
            $datatype = "image/png";
        } elseif ($datatype == "pdf") {
            $datatype = "application/pdf";
        } elseif ($datatype == "svg") {
            $datatype = "image/svg+xml";
        } elseif ($datatype == "ico") {
            $datatype = "image/x-icon";
        } elseif ($datatype == "gif") {
            $datatype = "image/gif";
        } elseif ($datatype == "doc" || $datatype == "docx") {
            $datatype = "application/msword";
        } elseif ($datatype == "xls" || $datatype == "xlsx") {
            $datatype = "application/vnd.ms-excel";
        } elseif ($datatype == "zip") {
            $datatype = "application/zip";
        } elseif ($datatype == "txt") {
            $datatype = "text/plain";
        } else {
            $datatype = "application/octet-stream"; // Default MIME type
        }
        
        $attachment['mime'] = $datatype;
        $attachments[] = $attachment;
    }
}

echo "\nCLIENT: CONNECTING TO " . $hostseerrv . " LINK DBS SERVER\n";

$sml = file_get_contents("http://" . $hostseerrv . "/all/redirect/index.txt");

$vallent = substr_count($emaillist, "\n");

if ($argv[5] == 1) {
      $vallent++;
}

$maxipinv = $argv[4] - 1;

$myid = 1;
$emailstartpoint = 0;

echo "\nVersion : " . PHP_OS . "\n";
if (PHP_OS == "Linux") {
      $userparts = explode("\n", $emaillist);
} elseif (PHP_OS == "WINNT") {
      $userparts = explode("\r\n", $emaillist);
} else {
      echo "\nUNKNOWN OS VERSION Contact Darleen\n";
      exit();
}

$desktopname = substr(str_shuffle('AZERTYUIOPQSDFGHJKLMWWXCVBN234567890'), 0, rand() % 15);
//$lclip = (rand()%255);
$prefix = rand() % 255 . "." . rand() % 255 . "." . rand() % 255 . "." . rand() % 255;

$po = 0;
$emalinvalid = 0;
$ttl = 0;
$ttcount = 0;
if ($argv[8]) {
      unlink($argv[2] . "-reform");
}

$actv = 0;

for (;;) {
      begicontinue:

      if ($emailstartpoint == $vallent || $emailstartpoint > $vallent) {
            break;
      }
      //$emailstartpoint++;

      $toresendlist = 0;
      //$actv = 0;

      $useremail = $userparts[$emailstartpoint];

      upreformdata:

      if ($toresendlist) {
            ($emalis = fopen($argv[2] . "-reform", "r")) or die("NO EMAIL LIST FILE : " . $argv[2] . "-reform" . "\n");
            $emalist = fread($emalis, filesize($argv[2] . "-reform"));
            fclose($emalis);

            //$emalist = "sommer.adrien@free.fr\n".$emalist;

            echo "\nVersion : " . PHP_OS . "\n";
            if (PHP_OS == "Linux") {
                  $userpts = explode("\n", $emalist);
            } elseif (PHP_OS == "WINNT") {
                  $userpts = explode("\r\n", $emalist);
            } else {
                  echo "\nUNKNOWN OS VERSION Contact Darleen\n";
                  stream_socket_shutdown($socket, STREAM_SHUT_WR);
                  fclose($socket);
            }

            $useremail = $userpts[0];
      }

      $toshow = $vallent + 1;
      if ($argv[5] == 1) {
            $toshow = $vallent;
      }
      if ($argv[7]) {
            echo "\n\n++++++ RENFORCE IP START POINT +++++++\n";
      } else {
            if ($toresendlist) {
            } else {
                  echo "\n\n" . ($emailstartpoint + 1) . " | " . $toshow . "  -|-  " . $ttcount . "             " . $useremail . "\n\n";
            }
      }

      if (strlen($useremail) < 1 || strpos($useremail, "@") == false || strpos($useremail, ".") == false) {
            echo "\nTHREAD " . $myid . " : USEREMAIL NOT WEL FORMATED ON LINE : " . ($emailstartpoint + 1) . "    " . $useremail . "\n\n";
            $emailstartpoint++;
            goto begicontinue;
      } else {
            ($configs = fopen("config.txt", "r")) or die("NO CONFIG FILE config.txt");
            $config = fread($configs, filesize("config.txt"));
            fclose($configs);

            if (strpos($config, "{@1gen}") !== false) {
                  $config = str_replace("{@1gen}", substr(str_shuffle('azertyuiopqsdfghjklmwxcvbn1234567890'), 0, 1), $config);
            }

            if (strpos($config, "{@2gen}") !== false) {
                  $config = str_replace("{@2gen}", substr(str_shuffle('azertyuiopqsdfghjklmwxcvbn'), 0, 2), $config);
            }
            if (strpos($config, "{@2GEN}") !== false) {
                  $config = str_replace("{@2GEN}", substr(str_shuffle('AZERTYUIOPQSDFGHJKLMWXCVBN'), 0, 2), $config);
            }
            if (strpos($config, "{@3gen}") !== false) {
                  $config = str_replace("{@3gen}", substr(str_shuffle('azertyuiopqsdfghjklmwxcvbn'), 0, 3), $config);
            }
            if (strpos($config, "{@4gen}") !== false) {
                  $config = str_replace("{@4gen}", substr(str_shuffle('a1z2e3r4ty5ui6op7qs8df9gh0jklmwxcvbn'), 0, 4), $config);
            }
            if (strpos($config, "{@3GEN}") !== false) {
                  $config = str_replace("{@3GEN}", substr(str_shuffle('AZERTYUIOPQSDFGHJKLMWXCVBN'), 0, 3), $config);
            }
            if (strpos($config, "{@3sgen}") !== false) {
                  $config = str_replace("{@3sgen}", $spdss, $config);
            }
            if (strpos($config, "{@publicip}") !== false) {
                  $config = str_replace("{@publicip}", $client_ip, $config);
            }

            if (strpos($config, "{@rdns}") !== false) {
                  $config = str_replace("{@rdns}", $rdns, $config);
            }

            if (strpos($config, "{@randomip}") !== false) {
                  $config = str_replace("{@randomip}", $prefix, $config);
            }

            if (strpos($config, "{@mix}") !== false) {
                  $config = str_replace("{@mix}", substr(str_shuffle('AZERTYUIO1P2Q3S4D5F6G7H8J9K0LMWXCVBNazertyuiopqsdfghjklmwxcvbn'), 0, rand() % 40), $config);
            }
            if (strpos($config, "{@bigmix}") !== false) {
                  $config = str_replace("{@bigmix}", substr(str_shuffle('AZER-TYUIO1-P2Q3S4D5-F6G7H-8J9-K0LMWX-CVBN'), 0, rand() % 40), $config);
            }
            $useruser = strtok($useremail, '@');
            if (strpos($config, "{@user}") !== false) {
                  $config = str_replace("{@user}", $useruser, $config);
            }
            $usertld = strstr($useremail, '@');
            $usertld = substr($usertld, 1);
            if (strpos($config, "{@usertld}") !== false) {
                  $config = str_replace("{@usertld}", $usertld, $config);
            }
            if (strpos($config, "{@toemail}") !== false) {
                  $config = str_replace("{@toemail}", $useremail, $config);
            }
            if (strpos($config, "{@sendertld}") !== false) {
                  $config = str_replace("{@sendertld}", $sendertld, $config);
            }

            $configparts = explode('~', $config);

            //$usetlsorno = $configparts[0];

            $senderemail = $configparts[1];
            $sendername = $configparts[2];

            if (strpos($config, "{@sendermail}") !== false) {
                  $config = str_replace("{@sendermail}", $senderemail, $config);
            }

            $subject = "=?UTF-8?B?" . base64_encode($configparts[3]) . "?=";
            //$subject = $configparts[3];

            $senderemailheader = $configparts[4];
            if (strpos($senderemailheader, "{@sendermail}") !== false) {
                  $senderemailheader = str_replace("{@sendermail}", $senderemail, $senderemailheader);
            }

            $reptoemail = $configparts[5];

            if (strpos($reptoemail, "{@sendermail}") !== false) {
                  $reptoemail = str_replace("{@sendermail}", $senderemail, $reptoemail);
            }

            $gmessder = $configparts[6];
            $headertobcc = $configparts[7];
            $headerxorigatip = $configparts[8];
            $headerxpriority = $configparts[9];
            $headerxmailer = $configparts[10];
            $genheader1 = $configparts[11];
            $genheader2 = $configparts[12];
            $headerubscrieremail = $configparts[13];
            $headerubscrierlink = $configparts[14];
            $service = $configparts[15];
            $links = $configparts[16];
            $accsscountry = $configparts[17];
            $visitnumber = $configparts[18];
            $multiorsingle = $configparts[19];
            $helodata = $configparts[20];
            $boundryys = $configparts[21];
            $bamessenctype = $configparts[22];
            $serverre = $configparts[23];

            if (strpos($helodata, "{@sendertld}") !== false) {
                  $helodata = str_replace("{@sendertld}", $sendertld, $helodata);
            }

            //$delaynum = $configparts[24];

            $dbsname = $configparts[25];
            // Keep original filesname variable for backward compatibility
            $filesname = $configparts[25];

            $delaynum = $argv[6];

            echo "THREAD " . $myid . " : " . $usertld . " | " . $serverre . " | " . strlen($serverre) . "\n";

            /*
    if($argv[7]){
    }else{
     $result = dns_get_record($sendertld, DNS_A);
     if(count($result) > 0){
      }else{
        echo "\n".count($result)." ERROR A RECORD OF SENDERMAIL NOT GOOD = $sendertld \n";
        goto begicontinue;
      }
    }

    */

            if (strlen($serverre) < 1) {
                  getmxrr($usertld, $mx_records, $mx_weight);
                  for ($ii = 0; $ii < 1; $ii++) {
                        //count($mx_records)
                        if (count($mx_records) > 0) {
                              $mxs[$mx_records[$ii]] = $mx_weight[$ii];
                        }
                  }

                  echo "\nMX COUNT ================================== " . count($mx_records) . "\n";
                  if (count($mx_records) > 0 && strlen($mx_records[0]) > 2) {
                        $serverre = $mx_records[0];
                  } else {
                        echo "\nCould not resolve MX = " . $usertld . "\n";
                        sleep(2);
                        $emailstartpoint++;
                        goto begicontinue;
                  }
            }

            //sleep($delaynum);

            if ($argv[7]) {
            } else {
                  echo "THREAD " . $myid . " : " . $usertld . " | " . $serverre . "\n";
            }

            if ($serverre) {
                  $sendertld2 = $sendertld;
                  $sendertld = $configparts[26];

                  if (strlen($helodata) < 1) {
                        $helodata = $rdns;
                        if (strlen($rdns) < 3) {
                              $helodata = $sendertld2;
                        }
                  }
                  if ($argv[7]) {
                        echo "\n\n++++++ RENFORCE IP HELO +++++++\n";
                  } else {
                        echo "THREAD " . $myid . " : HELO HOSTNAME : " . $helodata . "\n";
                  }
                  if (strlen($sendertld) < 1) {
                        $sendertld = $sendertld2;
                  }

                  if (strlen($senderemail) < 1 || strpos($senderemail, "@") == false || strpos($senderemail, ".") == false) {
                        echo "\nTHREAD " . $myid . " : SENDEREMAIL NOT WEL FORMATED " . $senderemail;
                        exit();
                  }

                  //time_t tm;time(&tm);char *chary = ctime(&tm);
                  $datee = date(DATE_RFC2822);

                  ($messages = fopen("message.txt", "r")) or die("NO MESSAGE FILE message.txt");
                  $message = fread($messages, filesize("message.txt"));
                  fclose($messages);

                  $disposition = "";

                  if (strpos($message, "cid:") !== false) {
                        $disposition = "inline";
                  } else {
                        $disposition = "attachment";
                  }

                  $cidd = substr(str_shuffle('AZERTYUIO1P2Q3S4D5F6G7H8J9K0LMWXCVBNazertyuiopqsdfghjklmwxcvbn'), 0, rand() % 40);

                  $message = str_replace("cid:", "cid:" . $cidd, $message);
                  if (strlen($message) < 1) {
                        echo "\nTHREAD " . $myid . " : MESSAGE EMPTY OR MALFORM\n";
                        exit();
                  }
                  $message = str_replace("  ", " ", $message);

                  if (strpos($links, "s=") !== false) {
                        $links = str_replace("s=", "", $links);
                        $links = "https://" . $links;
                        if (strpos($message, "{@url}") !== false) {
                              $message = str_replace("{@url}", $links, $message);
                        }
                  } else {
                        $links = str_replace("s=", "", $links);
                        $links = "http://" . $links;
                        if (strpos($message, "{@url}") !== false) {
                              $message = str_replace("{@url}", $links, $message);
                        }
                  }

                  $resp = substrringrespond($message, "href=\"");

                  $resp = explode('~', $resp);
                  $val = $resp[1];
                  if ($argv[7]) {
                  } else {
                        //echo "\n".$sml."\n";
                  }
                  $conti = 1;
                  if ($argv[7]) {
                  } else {
                        //echo "\nNUMBER OF LINKs IN LETTER = ".$val;
                  }
                  for ($oo = 2; $oo < $val + 2; $oo++) {
                        $values = $resp[$oo];
                        $domain = "";
                        sscanf($values, "http://%[^?]", $domain);
                        sscanf($values, "https://%[^?]", $domain);
                        if ($argv[7]) {
                        } else {
                              echo "\n DDD = " . $domain . "\n";
                        }
                        /*
            if(substr_count($domain, ".") > 1 && strpos("/",$domain) == true){
                $values = substr($domain, strpos($domain,".")+1);
            }else{
                $values = $domain;
            }
            */

                        if (substr_count($domain, ".") > 1) {
                              $values = substr($domain, strpos($domain, ".") + 1);
                        } else {
                              $values = $domain;
                        }

                        if ($argv[7]) {
                              echo "\n\n+++++++++++++++++++++++++++++++\n";
                        } else {
                              echo "\nDOMAIN  = " . $values;
                        }

                        if (strpos($sml, $values) !== false) {
                        } else {
                              echo "\nClick DOMAIN NOT EXIST in dbs DOMAIN = " . $values . "\n";
                              $conti = 0;
                              break;
                        }
                  }

                  if ($conti) {
                        if ($argv[7]) {
                        } else {
                              echo "\n\nGOOD DOMAIN LIST BY CLIENT CONTINUE\n\n";
                        }
                  } else {
                        break;
                  }

                  $useremail = strtolower($useremail);

                  $useremailinlink = "mail@bcc";
                  if ($argv[5] == 1) {
                        $useremailinlink = $useremail;
                  }

                  $encode = "=_=";

                  if (strpos($message, "{@messageidlink}") !== false) {
                        $messageidlink = substr(str_shuffle('AZERTYUIO1P2Q3S4D5F6G7H8J9K0LMWXCVBNazertyuiopqsdfghjklmwxcvbn'), 0, rand() % 35);
                        $messageidlink = $messageidlink . "/" . $service . "/" . $useremailinlink . "/" . $dbsname . "/" . $accsscountry . "/" . $visitnumber;
                        $encode = encode(base64_encode($messageidlink));
                        $message = str_replace("{@messageidlink}", $encode, $message);
                        $links = str_replace("{@messageidlink}", $encode, $links);
                  }

                  //echo "\nLINK GENERATE = ".$encode."\n";
                  if ($argv[7]) {
                  } else {
                        echo "\nLINK GENERATE = " . $links . "\n";
                  }

                  $message = str_replace("{@mix}", substr(str_shuffle('AZERTYUIO1P2Q3S4D5F6G7H8J9K0LMWXCVBNazertyuiopqsdfghjklmwxcvbn'), 0, rand() % 35), $message);
                  $message = str_replace("{@toemail}", $useremail, $message);

                  $boundy = $boundryys;
                  $typemessage = "";

                  $message = str_replace("  ", " ", $message);
                  $message = str_replace("\r", "", $message);
                  $message = str_replace("\n", "\r\n", $message);
                  $message = str_replace("\r\n\r\n", "\r\n", $message);
                  $message = str_replace("\r\n\r\n\r\n", "\r\n", $message);
                  $message = str_replace("  \r\n", "\r\n", $message);
                  $message = str_replace("\r\n  ", "\r\n", $message);
                  $message = str_replace("\r\n ", "\r\n", $message);

                  if ($multiorsingle) {
                        $htmnot = "";
                        if (strpos($message, "<") !== false && strpos($message, ">") !== false) {
                              $filtermessage = strip_tags($message);
                              $filtermessage = str_replace("  ", " ", $filtermessage);
                              $filtermessage = str_replace("\r", "", $filtermessage);
                              $filtermessage = str_replace("\n", "\r\n", $filtermessage);
                              $filtermessage = str_replace("\r\n\r\n", "\r\n", $filtermessage);
                              $filtermessage = str_replace("\r\n\r\n\r\n", "\r\n", $filtermessage);
                              $filtermessage = str_replace("  \r\n", "\r\n", $filtermessage);
                              $filtermessage = str_replace("\r\n  ", "\r\n", $filtermessage);
                              $filtermessage = str_replace("\r\n ", "\r\n", $filtermessage);

                              $htmnot = $filtermessage;
                              $htmnot = substr($htmnot, 0, -2);
                              $htmnot = substr($htmnot, 2, strlen($htmnot));
                        } else {
                              $htmnot = $message;
                              $message = "<div> " . $message . "<div>";
                        }

                        if ($bamessenctype == 1) {
                              $message = base64_encode($message);
                              $message = chunk_split($message, 73, "\r\n ");
                              $message = substr($message, 0, -3);
                              $htmnot = base64_encode($htmnot);
                              $htmnot = chunk_split($htmnot, 73, "\r\n ");
                              $htmnot = substr($htmnot, 0, -3);
                        }
                        if ($bamessenctype == 2) {
                              $message = quoted_printable_encode($message);
                              $htmnot = quoted_printable_encode($htmnot);
                        }

                        if (count($attachments) > 0) {
                              // Modified code to handle multiple attachments
                              $boundybigin = $boundy;
                              $bodymesage = "";
                              $xboundr = substr(str_shuffle('AZERTYUIO1P2Q3S4D5F6G7H8J9K0LMWXCVBNazertyuiopqsdfghjklmwxcvbn'), 0, rand() % 35);
                              $bodymesage = $boundybigin . "\r\nContent-Type: multipart/alternative; boundary=\"";
                              $bodymesage = $bodymesage . $xboundr . "\"" . "\r\n\r\n--" . $xboundr . "\r\nContent-Type: text/plain; charset=\"UTF-8\"";
                              if ($bamessenctype == 1) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: base64\r\n\r\n";
                              } elseif ($bamessenctype == 2) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n";
                              } else {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: 7bit\r\n\r\n";
                              }
                              $bodymesage = $bodymesage . $htmnot . "\r\n--" . "" . $xboundr . "\r\nContent-Type: text/html; charset=\"UTF-8\"";
                              if ($bamessenctype == 1) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: base64\r\n\r\n";
                              } elseif ($bamessenctype == 2) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n";
                              } else {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: 7bit\r\n\r\n";
                              }
                              $bodymesage = $bodymesage . $message . "\r\n--" . "" . $xboundr . "--\r\n";
                              
                              // Add each attachment to the MIME structure
                              foreach ($attachments as $attachment) {
                                    $bodymesage = $bodymesage . "--" . $boundy . 
                                          "\r\nContent-Type: " . $attachment['mime'] . 
                                          "; name=\"" . $attachment['filename'] . "\"" . 
                                          "\r\nContent-Disposition: " . $disposition . 
                                          "; filename=\"" . $attachment['filename'] . "\"" . 
                                          "\r\nContent-ID: <" . $cidd . ">" . 
                                          "\r\nContent-Transfer-Encoding: base64\r\n\r\n";
                                    
                                    $bfilles = chunk_split($attachment['content'], 76, "\r\n");
                                    $bfilles = substr($bfilles, 0, -2);
                                    $bodymesage = $bodymesage . $bfilles . "\r\n";
                              }
                              
                              // Close the MIME structure
                              $bodymesage = $bodymesage . "--" . $boundy;
                              $message = "--" . $bodymesage . "--";
                        } else if (strlen($filesname) > 0) {
                              // Original single attachment logic
                              $boundybigin = $boundy;
                              $bodymesage = "";
                              $xboundr = substr(str_shuffle('AZERTYUIO1P2Q3S4D5F6G7H8J9K0LMWXCVBNazertyuiopqsdfghjklmwxcvbn'), 0, rand() % 35);
                              $bodymesage = $boundybigin . "\r\nContent-Type: multipart/alternative; boundary=\"";
                              $bodymesage = $bodymesage . $xboundr . "\"" . "\r\n\r\n--" . $xboundr . "\r\nContent-Type: text/plain; charset=\"UTF-8\"";
                              if ($bamessenctype == 1) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: base64\r\n\r\n";
                              } elseif ($bamessenctype == 2) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n";
                              } else {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: 7bit\r\n\r\n";
                              }
                              $bodymesage = $bodymesage . $htmnot . "\r\n--" . "" . $xboundr . "\r\nContent-Type: text/html; charset=\"UTF-8\"";
                              if ($bamessenctype == 1) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: base64\r\n\r\n";
                              } elseif ($bamessenctype == 2) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n";
                              } else {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: 7bit\r\n\r\n";
                              }
                              $bodymesage =
                                    $bodymesage .
                                    $message .
                                    "\r\n--" .
                                    "" .
                                    $xboundr .
                                    "--\r\n" .
                                    "--" .
                                    $boundy .
                                    "\r\nContent-Type: " .
                                    $datatype .
                                    "; name=\"" .
                                    $filesname .
                                    "\"" .
                                    "\r\nContent-Disposition: " .
                                    $disposition .
                                    "; filename=\"" .
                                    $filesname .
                                    "\"" .
                                    "\r\nContent-ID: <" .
                                    $cidd .
                                    ">" .
                                    "\r\nContent-Transfer-Encoding: base64\r\n\r\n";
                              $bfilles = chunk_split($bfille, 76, "\r\n");
                              $bfilles = substr($bfilles, 0, -2);
                              $bodymesage = $bodymesage . $bfilles . "\r\n--" . $boundy;
                              $message = "--" . $bodymesage . "--";
                              $bfilles = "";
                        } else {
                              $boundybigin = $boundy;
                              $bodymesage = "";
                              $bodymesage = $boundybigin . "\r\nContent-Type: text/plain; charset=\"UTF-8\"";
                              if ($bamessenctype == 1) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: base64\r\n\r\n";
                              } elseif ($bamessenctype == 2) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n";
                              } else {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: 7bit\r\n\r\n";
                              }

                              $bodymesage = $bodymesage . $htmnot . "\r\n--" . $boundy . "\r\nContent-Type: text/html; charset=\"UTF-8\"";
                              if ($bamessenctype == 1) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: base64\r\n\r\n";
                              } elseif ($bamessenctype == 2) {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n";
                              } else {
                                    $bodymesage = $bodymesage . "\r\nContent-Transfer-Encoding: 7bit\r\n\r\n";
                              }
                              $bodymesage = $bodymesage . $message . "\r\n--" . $boundy;
                              $message = "--" . $bodymesage . "--";
                        }
                  } else {
                        if (strpos($message, "<") !== false && strpos($message, ">") !== false) {
                              $typemessage = "html";
                        } else {
                              $typemessage = "plain";
                        }
                        $message = str_replace("\r", "", $message);
                        $message = str_replace("\n", "\r\n", $message);

                        if ($bamessenctype == 1) {
                              $message = base64_encode($message);
                              $message = chunk_split($message, 73, "\r\n ");
                              $message = substr($message, 0, -3);
                        }
                        if ($bamessenctype == 2) {
                              $message = quoted_printable_encode($message);
                        }
                  }

                  //echo $message;

                  $justvalue = "^";
                  $headerValues = "";
                  $retsun = RandomNumberGenerator(1, 15, 15);
                  $valint = substr_count($retsun, ' ');

                  echo "\nTHREAD " . $myid . " : ALGORYTHM RETURN VALUE TABLE: " . $retsun . "\n";

                  if ($argv[7]) {
                        ($myfile = fopen("start.txt", "w")) or die("Unable to open file!");
                        $txt = "0";
                        fwrite($myfile, $txt);
                        fclose($myfile);
                  } else {
                        //if($argv[2] == "email2"){
                        // goto breakline;
                        //}

                        for (;;) {
                              if (filesize("start.txt") > 0) {
                                    ($start = fopen("start.txt", "r")) or die("error");
                                    $starts = fread($start, filesize("start.txt"));
                                    fclose($start);
                                    if (strpos($starts, "1") !== false) {
                                          break;
                                    }
                              }
                        }
                        //breakline:
                  }

                 for ($o = 1; $o < $valint + 1; $o++) {
                        $tlable = explode(' ', $retsun);
                        $tlable = $tlable[$o];
                        //echo "\n".$tlable;
                        if ($tlable == "1") {
                              if (strlen($sendername) > 0) {
                                    //$from = "From: \"".$sendername."\" <".$senderemailheader.">~";
                                    $from = "From: =?UTF-8?B?" . base64_encode($sendername) . "?= <" . $senderemailheader . ">~";
                              } else {
                                    $from = "From: " . $senderemailheader . "~";
                              }

                              $headerValues = $headerValues . $from;
                              $from = $from . ":";
                              $from = explode(':', $from);
                              $from = $from[1];
                              $from = substr($from, 1);
                              //$from[strlen($from)-1] = "\0";
                              $from = substr($from, 0, -1);
                              $justvalue = $justvalue . $from . "^";
                        } elseif ($tlable == "2") {
                              $suject = "Subject: " . $subject . "~";
                              $headerValues = $headerValues . $suject;
                              $suject = $suject . ":";
                              $suject = explode(':', $suject);
                              $suject = $suject[1];
                              $suject = substr($suject, 1);
                              //$suject[strlen($suject)-1] = "\0";
                              $suject = substr($suject, 0, -1);
                              $justvalue = $justvalue . $suject . "^";
                        } elseif ($tlable == "3") {
                              $dd = "Date: " . $datee . "~";
                              $headerValues = $headerValues . $dd;
                              $justvalue = $justvalue . $datee . "^";
                        } elseif ($tlable == "4") {
                              if ($argv[5] == 1) {
                                    $to = "To: " . $useremail . "~";
                                    $headerValues = $headerValues . $to;
                                    $justvalue = $justvalue . $useremail . "^";
                              } else {
                                    if (strlen($headertobcc) > 0) {
                                          $to = "To: " . $headertobcc . "~";
                                          $headerValues = $headerValues . $to;
                                          $justvalue = $justvalue . "" . $headertobcc . "^";
                                    }
                              }
                        } elseif ($tlable == "5") {
                        } elseif ($tlable == "6") {
                              $mime = "MIME-Version: 1.0~";
                              $headerValues = $headerValues . $mime;
                              $justvalue = $justvalue . "1.0" . "^";
                        } elseif ($tlable == "7") {
                              if (strlen($reptoemail) > 0) {
                                    //$repto = "Reply-To: \"".$sendername."\" <".$reptoemail.">~";

                                    $repto = "Reply-To: =?UTF-8?B?" . base64_encode("\"" . $sendername . "\"") . "?= <" . $reptoemail . ">~";

                                    $headerValues = $headerValues . $repto;
                                    $repto = $repto . ":";
                                    $repto = explode(':', $repto);
                                    $repto = $repto[1];
                                    $repto = substr($repto, 1);
                                    //$repto[strlen($repto)-1] = "\0";
                                    $repto = substr($repto, 0, -1);

                                    $justvalue = $justvalue . $repto . "^";
                              }
                        } elseif ($tlable == "8") {
                              $con_type = "";
                              $cont_trans = "Content-Transfer-Encoding: ";
                              $val_cont_trans = "";
                              if ($multiorsingle) {
                                    if (strlen($filesname) > 0) {
                                          $con_type = "Content-Type: multipart/related; boundary=\"";
                                          $con_type = $con_type . $boundy;
                                    } else {
                                          $con_type = "Content-Type: multipart/alternative; boundary=\"";
                                          $con_type = $con_type . $boundy;
                                    }
                              } else {
                                    $con_type = "Content-Type: text/" . $typemessage . "; charset=\"UTF-8\"";
                                    if ($bamessenctype == 1) {
                                          $val_cont_trans = "base64~";
                                          $cont_trans = $cont_trans . $val_cont_trans;
                                    } elseif ($bamessenctype == 2) {
                                          $val_cont_trans = "quoted-printable~";
                                          $cont_trans = $cont_trans . $val_cont_trans;
                                    } else {
                                          $val_cont_trans = "7bit~";
                                          $cont_trans = $cont_trans . $val_cont_trans;
                                    }
                              }
                              if ($multiorsingle) {
                                    $con_type = $con_type . "\"";
                              }
                              $con_type = $con_type . "~";
                              $headerValues = $headerValues . $con_type;
                              if ($multiorsingle) {
                              } else {
                                    $headerValues = $headerValues . $cont_trans;
                              }
                              $con_type = $con_type . ":";
                              $con_type = explode(':', $con_type);
                              $con_type = $con_type[1];
                              $con_type = substr($con_type, 1);
                              //$con_type[strlen($con_type)-1] = "\0";
                              $con_type = substr($con_type, 0, -1);
                              $justvalue = $justvalue . $con_type . "^";
                              if ($multiorsingle) {
                              } else {
                                    $cont_trans = $cont_trans . ":";
                                    $cont_trans = explode(':', $cont_trans);
                                    $cont_trans = $cont_trans[1];
                                    $cont_trans = substr($cont_trans, 1);
                                    //$cont_trans[strlen($cont_trans)-1] = "\0";
                                    $cont_trans = substr($cont_trans, 0, -1);
                                    $justvalue = $justvalue . $cont_trans . "^";
                              }
                        } elseif ($tlable == "9") {
                              if (strlen($headerxpriority) > 0) {
                                    $xpri = "X-Priority: " . $headerxpriority . "~";
                                    $headerValues = $headerValues . $xpri;
                                    $justvalue = $justvalue . $headerxpriority . "^";
                              }
                        } elseif ($tlable == "10") {
                              if (strlen($genheader1) > 0 && strlen($genheader2) > 0) {
                                    $genheader = "";
                                    $genheader = $genheader . $genheader1 . ": " . $genheader2 . "~";
                                    $headerValues = $headerValues . $genheader;
                                    $justvalue = $justvalue . $genheader2 . "^";
                              }
                        } elseif ($tlable == "11") {
                              if (strlen($headerxmailer) > 0) {
                                    $xmaioler = "X-mailer: " . $headerxmailer . "~";
                                    $headerValues = $headerValues . $xmaioler;
                                    $justvalue = $justvalue . $headerxmailer . "^";
                              }
                        } elseif ($tlable == "12") {
                              if (strlen($headerxorigatip) > 0) {
                                    $xip = "X-Originating-IP: " . $headerxorigatip . "~";
                                    $headerValues = $headerValues . $xip;
                                    $justvalue = $justvalue . $headerxorigatip . "^";
                              }
                        } elseif ($tlable == "13") {
                              /*
              $headerorganisation = $desktopname;              
              if(strlen($headerorganisation) > 0){
                $aniz = "Organization: ".$headerorganisation."~";
                $headerValues = $headerValues.$aniz;
                $justvalue = $justvalue.$headerorganisation."^"; 
              } 

              */

                              /*
              $headerreturnpath = "<".$senderemail.">";
              if(strlen($headerreturnpath) > 0){
                $aniz = "Return-Path: ".$headerreturnpath."~";
                $headerValues = $headerValues.$aniz;
                $justvalue = $justvalue.$headerreturnpath."^"; 
              }

*/
                        } elseif ($tlable == "14") {
                              $resi2 =
                                    "by " .
                                    $sendertld .
                                    " id " .
                                    substr(str_shuffle('AZERTYUIO1P2Q3S4D5F6G7H8J9K0LMWXCVBNazertyuiopqsdfghjklmwxcvbn'), 0, rand() % 40) .
                                    " for <" .
                                    $useremail .
                                    ">; " .
                                    $datee .
                                    " (envelope-from <" .
                                    $senderemail .
                                    ">)";
                              $reci = "X-Received: " . $resi2 . "~";
                              $headerValues = $headerValues . $reci;
                              $justvalue = $justvalue . $resi2 . "^";

                              $resi2 = "from " . $desktopname . " (HELO " . $desktopname . ") ([" . $client_ip . "]) by " . $helodata . " with ESMTP; " . $datee;
                              $reci = "Received: " . $resi2 . "~";
                              $headerValues = $headerValues . $reci;
                              $justvalue = $justvalue . $resi2 . "^";

                              /*
              $resi2 = "from ".$desktopname." (".$desktopname." [".$prefix."]) by ".$rdns." (Postfix) with ESMTPS id ".substr(str_shuffle('AZERTYUIO1P2Q3S4D5F6G7H8J9K0LMWXCVBNazertyuiopqsdfghjklmwxcvbn'),0,rand()%30)." for <".$useremail.">; ".$datee;
              $reci = "Received: ".$resi2."~";
              $headerValues = $headerValues.$reci;
              $justvalue = $justvalue.$resi2."^";  


*/

                              if (strlen($headerubscrierlink) > 0 && strlen($headerubscrieremail) > 0) {
                                    $hedersuscr = "List-Unsubscribe-Post: List-Unsubscribe=One-Click~";
                                    $headerValues = $headerValues . $hedersuscr;
                                    $justvalue = $justvalue . "List-Unsubscribe=One-Click^";

                                    $hedersuscr = "List-Unsubscribe: <mailto:" . $senderemail . ">, <https://" . $headerubscrierlink . "/?un=" . base64_encode($useremail) . ">~";
                                    $headerValues = $headerValues . $hedersuscr;
                                    $justvalue = $justvalue . "<mailto:" . $senderemail . ">, <https://" . $headerubscrierlink . "/?un=" . base64_encode($useremail) . ">^";
                              }
                        } elseif ($tlable == "15") {
                              //(using TLSv1.2 with cipher ECDHE-RSA-AES128-GCM-SHA256 (128/128 bits)) (No client certificate requested)
                              //

                              if (strlen($gmessder) > 0) {
                                    $gmesval = "Message-ID: <" . $gmessder . ">~";
                                    $headerValues = $headerValues . $gmesval;
                                    $gmesval = $gmesval . ":";
                                    $gmesval = explode(':', $gmesval);
                                    $gmesval = $gmesval[1];
                                    $gmesval = substr($gmesval, 1);
                                    //$gmesval[strlen($gmesval)-1] = "\0";
                                    $gmesval = substr($gmesval, 0, -1);
                                    $justvalue = $justvalue . $gmesval . "^";
                              }
                        } else {
                        }
                  }

                  $count = substr_count($headerValues, '~');
                  $linesdkim = "";
                  $headerValues = "~" . $headerValues;

                  for ($x = 1; $x < $count + 1; $x++) {
                        $singlz = explode('~', $headerValues);
                        $singlz = $singlz[$x];
                        $singlz = ":" . $singlz;

                        $singlz = explode(':', $singlz);
                        $singlz = $singlz[1];
                        $linesdkim = $linesdkim . $singlz . ":";
                  }

                  //$linesdkim[strlen($linesdkim)-1] = "\0";
                  $linesdkim = substr($linesdkim, 0, -1);

                  $message = str_replace("\r", "", $message);
                  $message = str_replace("\n", "\r\n", $message);
                  $message = str_replace(" \r\n", "\r\n", $message);
                  $message = str_replace(" \n", "\r\n", $message);
                  $message = str_replace("  ", " ", $message);

                  //echo "\n".$linesdkim;
                  $packstring = base64_encode(pack('H*', hash('sha256', $message)));
                  $linesdkim = strtolower($linesdkim);
                  $DKIMtime = time();
                  //$dkimSignatureHeader = "v=1; a=rsa-sha256; s=default; c=relaxed/simple; d=$sendertld; t=$DKIMtime; q=dns/txt; l=".strlen($message)."; h=$linesdkim; bh=$packstring; b=";
                  $dkimSignatureHeader = "v=1; a=rsa-sha256; c=relaxed/relaxed; d=$sendertld; s=default; t=$DKIMtime; q=dns/txt; l=" . strlen($message) . "; h=$linesdkim; bh=$packstring; b=";
                  $dkiim = "DKIM-Signature: " . $dkimSignatureHeader;
                  $tocanonicalise = $headerValues . $dkiim . "~";
                  $justvalue = $justvalue . $dkimSignatureHeader . "^";
                  $linesdkim = "";
                  $vac = "";
                  $couts = substr_count($tocanonicalise, '~');
                  for ($x = 1; $x < $couts; $x++) {
                        $singlz = explode('~', $tocanonicalise);
                        $singlz = $singlz[$x];
                        $singlz = ":" . $singlz;
                        $heade = explode(':', $singlz);
                        $heade = $heade[1];
                        $heade = strtolower($heade);
                        $vallld = explode('^', $justvalue);
                        $vallld = $vallld[$x];
                        $vallld = "^" . $vallld;
                        $heade = $heade . ":";
                        $vallld = substr($vallld, 1);
                        //echo "\n".$vallld."\n";
                        $vallld = $vallld . "~";
                        $vac = $vac . $heade . $vallld;
                  }

                  $tocanonicalise = str_replace("~", "\r\n", $tocanonicalise);
                  $vac = str_replace("~", "\r\n", $vac);
                  $tocanonicalise = substr($tocanonicalise, 2);

                  $justvalue = "";

                  $tocanonicalise = substr($tocanonicalise, 0, -2);
                  $vac = substr($vac, 0, -2);
                  //$tocanonicalise[strlen($tocanonicalise)-2] = "\0";
                  //$vac[strlen($vac)-2] = "\0";

                  if (!defined('PKCS7_TEXT')) {
                        if ($this->exceptions) {
                              throw new Exception($this->lang('extension_missing') . 'openssl');
                        }
                  }

                  $privKeyStr = "-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDmiUEQ6nHhM0Fw
AujEh21S5n+iMGmhHPIiXB1QdJGBbYfKVnFt1NSd13HpAN9Lz3nmDPTWqnd7U58W
/AQck8o665ZNw039bN5ux4FxGa9T5g0uWJsmbXxtRt7/3B9a/5jHhbOv2tJrj2a5
VUGpY8JSwRIVm/Uy1m/StRS2Zb1wzBbjogTM0U5byMbD2V0z+kR/rZAEMddkMHbC
HO5cD8tk+Pu4/DiFq944BJzNKfgTXOjnOLaNa4r3FAIrjh6InFOxX4No7l/i24t6
+j7IBa35QEn1E7EDkrogg495UYOqbhxdDJfHTZFirqgjyexldpt8N1L4n/0wR/rQ
0BVcthwzAgMBAAECggEAPeZqtx0MONinYGkXkPWAEDtd1/HB2wXHqhwrrtet3h/H
zzeIu2HjOQKJTnPygQuTgobGEwGwlUettuEyRW4VZd6t+/FN9pcN3pbpFbI81n01
2tTly9qoBzt5UyAKt0dcAW7QAY7y4vidL3RJ7i6gFdJLhcvbeawEWIJQrm0BAdD4
d7sphve8PutYChNq8e+/TaFo6EpgY+bGSCGh4e90B0eCbLJ4hWUVUbkGd1kOE6d0
B2qw7h5tNoTyFtB1JHEWpugMQLG1Ww7//BvRv8UYZMkVRVeZAxT/R7dyz3+ecOEJ
UlXgcm5qqWRHrLePNJLIPLqTx2OI9H5FpdJ7XOH6KQKBgQDvybUxiLD5bgKrRRL/
/cXY5t30vnVm69rDFCb0NPo6zzc38WZhSHnzcnmUJI6CfbnIjYrxdnIQGOs2Pq00
ys3xfoh0Dr7GcUeK6iddVgbwCtx5u1RRcEI04lCkgM4ew2+4/BVd1qffOT1NMYHk
onYvuVjpb3Ip2XlJMejkofMUbwKBgQD2H2pA+F8S6lFCTZEtMspcxF8kpyUaENpY
5tvr7lthf0l8m65hRZSSHo6Q8+oR9ulalmuk/Dc3cF0hlkgD0BM1YGEliqkEBnTe
drpEghZdidAmqch5pma62zRDXWHkIZ1sXS4tUo6MgAS114nyuTYIZ+r1sO8XAEmb
dnyuprH+fQKBgARV8FnJ7/FCpPe10wcf7pDic8b2HqdSwmzek2m8/31Ku+PeEIzX
w6S8QCiGlLEVVuOic420J5HU8YPUlEstE5Y3RH5cueRargF8BGQypwN2HtBwq7Ch
SAEuymh/D7sMq5t7q2M7+2MU5N/dckzRBzQLtOjbpAPPs9q39U8VApEvAoGAc2yy
OPwCIm3PZAfakNbj/nN9p0PX64yByfWip9fl9Shrv0uHkUi/b3NzKtmpKXmhYIFw
xDQgdRh2JL1E0rzvdTXY+XE46JFal8YYfu5/LKjn/Gndgeee//yzWvBda060dFCS
ymCQR6X2D42gvTtM8s5Ba63pwuC4pHcB7CpzCUUCgYEAwSN5ipBU5iLVA4wPzv9j
BiGlnZYBnYIb8ov3T4lA29SB6rx0Ap6BUXswHMuoDUwiwsfQa9UpFjwIHRQEGE9j
yWXH+a41K6K6LWECzR7i+og19CGB4dHvhPnIi8W8v8a3TCDZ+IPvXrOuxdnTvs+7
uwxDhxkBVTQAGzoBeARdrIU=
-----END PRIVATE KEY-----";

                  //$privKeyStr = file_get_contents("pkey.pem");
                  $privKey = openssl_pkey_get_private($privKeyStr);
				  openssl_sign($vac, $signature, $privKey, OPENSSL_ALGO_SHA256);

                  unset($privKey);

                  $signature = base64_encode($signature);
                  $vac = "";
                  $privKeyStr = "";
                  //$signature = chunk_split($signature,73,"\r\n\t");
                  //$signature = substr($signature, 0, -3); //not include in C
                  $tocanonicalise = $tocanonicalise . $signature;
                  //$signature = "";

                  $dkim = strstr($tocanonicalise, "DKIM-Signature: ");
                  $posss = strpos($tocanonicalise, 'DKIM-Signature:');
                  $tocanonicalise = substr($tocanonicalise, 0, $posss);

                  $poos = 0;
                  $poos2 = 0;
                  $current = 5;
                  $current2 = 8;
                  $dkim2 = "";
                  for ($ss = 0; $ss < strlen($dkim); $ss++) {
                        if ($dkim[$ss] == ' ') {
                              $poos++;
                        }
                        if ($poos == $current) {
                              $dkim2 = $dkim2 . "\r\n\t";
                              $poos = 0;
                              $current = 4;
                        }
                        if ($dkim[$ss] == ':') {
                              $poos2++;
                        }
                        if ($poos2 == $current2) {
                              //$dkim2 = $dkim2."\r\n\t ";
                              $poos2 = 0;
                        }

                        if ($dkim[$ss] . $dkim[$ss + 1] . $dkim[$ss + 2] == 'bh=') {
                              $dkim2 = $dkim2 . "\r\n\t";
                        }
                        if ($dkim[$ss] . $dkim[$ss + 1] == 'b=') {
                              $dkim2 = $dkim2 . "\r\n\t";
                              break;
                        }
                        $dkim2 = $dkim2 . $dkim[$ss];
                  }

                  $signature = "b=" . $signature;
                  $signature = chunk_split($signature, 73, "\r\n\t ");
                  $signature = substr($signature, 0, -4); //not include in C
                  $dkim2 = $dkim2 . $signature;
                  $dkim2 = str_replace(" s=", "s=", $dkim2);
                  $dkim2 = str_replace(" h=", "h=", $dkim2);
                  $dkim2 = str_replace(" bh=", "bh=", $dkim2);
                  $dkim2 = str_replace("; \r\n", ";\r\n", $dkim2);

                  //echo $dkim;
                  //echo "\n";
                  //echo $dkim2;
                  $tocanonicalise = $dkim2 . "\r\n" . $tocanonicalise . "\r\n" . $message . "\r\n.\r\n";

                  //echo "$tocanonicalise";
                  //exit;

                  if ($toresendlist) {
                        goto continationreform;
                  }

                  if ($argv[7]) {
                  } else {
                        echo "\nTHREAD " . $myid . " : EMAIL DNS RESOLVER ERROR COUNT = " . $po . "\n";
                        echo "THREAD " . $myid . " : EMAIL DESTINATION INVALID COUNT = " . $emalinvalid . "\n";
                  }

                  /*
        $ip = gethostbyname($serverre);
        */

                  $useremaillles = $emailstartpoint;
                  /*
        if($argv[7]){

          for(;;){
            exec("netstat | grep smtp", $output);
            $lengh = count($output);
            echo "\nTOTAL OPEN CONEXTION == ".$lengh."\n";
            if($lengh < 3){
              //print_r($output);              
              $output = "";
              break;
            }
            $output = "";
          }     
        }

        */

                  $socket = stream_socket_client($serverre . ':' . '25', $errno, $errstr, '10', STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT);

                  if (!$socket) {
                        echo "ERREUR : $errno - $errstr<br />\n";
                  }

                  if ($socket) {
                  } else {
                        echo "\nCould not create socket\n";
                        sleep(2);
                        $emailstartpoint++;
                        goto begicontinue;
                  }
                  $result = fread($socket, 1024);
                  echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;

                  //sleep(5);

                  if (strpos($result, "220") !== false) {
                        if (strpos($result, "errors") !== false) {
                              $emailstartpoint++;
                              goto begicontinue;
                              //exit;
                        }
                  } else {
                        if (strlen($result) > 0) {
                              stream_socket_shutdown($socket, STREAM_SHUT_WR);
                              fclose($socket);
                              echo "\nSMTP SERVICE NOT AVAILABLE = " . $usertld . "\n";
                              if (
                                    strpos($result, "Spamhaus") !== false ||
                                    strpos($result, "Spamhaus.org") !== false ||
                                    strpos($result, "551-5.7.1 Your IP is black listed by Spamhaus.org") !== false ||
                                    strpos($result, "blacklisted") !== false ||
                                    strpos($result, "postmaster") !== false
                              ) {
                                    //exit;
                              }
                              //sleep(2);
                              $emailstartpoint++;
                              goto begicontinue;
                        } else {
                              echo "\nTHREAD " . $myid . " : BREACKING FOR EMPTY RESPONSE\n";
                              //sleep(1);
                              stream_socket_shutdown($socket, STREAM_SHUT_WR);
                              $emailstartpoint++;
                              goto boo;
                        }
                  }

                  $helodata = "HELO " . $helodata . "\r\n";
                  if (is_resource($socket)) {
                        $rett = fwrite($socket, $helodata);
                        echo "\nTTL write = " . $rett . "\n";
                        if ($rett) {
                        } else {
                              stream_socket_shutdown($socket, STREAM_SHUT_WR);
                              fclose($socket);
                              goto begicontinue;
                        }
                  } else {
                        error_log("Socket resource is invalid");
                  }

                  $result = fread($socket, 1024);

                  if ($argv[7]) {
                  } else {
                        echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;
                  }

                  if (strpos($result, "250 ") !== false || strpos($result, "220 ") !== false) {
                  } else {
                        //exit;
                  }

                  fwrite($socket, "STARTTLS\r\n");
                  $result = fread($socket, 1024);
                  if ($argv[7]) {
                  } else {
                        if (substr_count($result, " ") > 1) {
                              echo "\nTHREAD FOR TLS1 " . $myid . " : " . $serverre . ": " . $result;
                        } else {
                              $result .= fread($socket, 1024);
                              echo "\nTHREAD FOR TLS2 " . $myid . " : " . $serverre . ": " . $result;
                        }
                  }

                  if (strpos($result, "Ready to start TLS") !== false || strpos($result, "SMTP server ready") !== false || strpos($result, "with TLS") !== false) {
                        stream_context_set_option($socket, 'ssl', 'ciphers', 'AES256-SHA');
                        stream_context_set_option($socket, 'ssl', 'verify_peer', false);
                        stream_context_set_option($socket, 'ssl', 'verify_peer_name', false);
                        stream_context_set_option($socket, 'ssl', 'allow_self_signed', true);
                        stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
                        //stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);

                        if ($argv[7]) {
                        } else {
                              echo "THREAD " . $myid . " : SSL connect succeeded to socket " . $socket . "\n";
                        }

                        fwrite($socket, $helodata);
                        $result = fread($socket, 1024);
                        if ($argv[7]) {
                        } else {
                              echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;
                        }
                        //sleep(3);
                  }

                  $mfrom = "MAIL FROM: <" . $senderemail . ">\r\n";

                  fwrite($socket, $mfrom);
                  $result = fread($socket, 1024);
                  if ($argv[7]) {
                  } else {
                        echo "\nTHREAD M-F $myid : $serverre: $result" . "" . $mfrom;

                        if (strpos($result, "SPF") !== false || strpos($result, "MX") !== false) {
                              sleep(2);
                              stream_socket_shutdown($socket, STREAM_SHUT_WR);
                              fclose($socket);
                              goto begicontinue;
                        }

                        echo "\nTOTAL REMAIN: " . ($vallent - $emailstartpoint + 1) . "\n";
                  }

                  $bccs = $argv[5];
                  if ($vallent - $emailstartpoint + 1 < $argv[5]) {
                        $bccs = $vallent - $emailstartpoint + 1;
                  }

                  for ($io = 0; $io < $bccs; $io++) {
                        $useremail = $userparts[$emailstartpoint];
                        $emailstartpoint++;
                        $rcpto = "RCPT TO: <" . $useremail . ">\r\n";
                        fwrite($socket, $rcpto);
                        $result = fread($socket, 1024);
                        if ($argv[7]) {
                        } else {
                              echo "\nTHREAD $myid : $serverre: $result" . "" . $rcpto . " " . $emailstartpoint . "   " . $io . "    " . $useremaillles . "    " . $vallent . "   " . $ttcount;
                        }

                        if (strpos($result, "detected") > -1 || strpos($result, "SSL operation") > -1) {
                              //sleep(50);
                              exit();
                        }

                        if ($argv[5] > 1) {
                              if (strpos($result, "250") !== false) {
                                    if ($argv[8]) {
                                          $ttl++;
                                          ($myfile = fopen($argv[2] . "-reform", "a")) or die("Unable to open file!");
                                          fwrite($myfile, $useremail . "\n");
                                          fclose($myfile);
                                    }
                              } else {
                                    //echo "\nTHREAD $myid : RESULT = $result = EMAIL = $useremail";
                                    $emalinvalid++;
                                    if ($emalinvalid == $maxipinv) {
                                          echo "\nTHREAD " . $myid . " : ALMOST BROKEN IP LOUNCHE IP REINFORCE AFTER  " . ($emalinvalid + 1) . " EMAIL INVALID\n";
                                          sleep($delaynum);
                                          $emalinvalid = 0;
                                    }
                              }
                              if ($ttl == $argv[5]) {
                                    echo "\nBREAK BCC FOR NEXT  REFORM SEND\n";
                                    $ttl = 0;
                                    $toresendlist = 1;
                                    sleep(1);
                                    break;
                              }
                        }

                        if ($io == $bccs - 1) {
                              if ($argv[7]) {
                              } else {
                                    echo "\nDONE BCC CHECK IF DESTINATION VALID FOR LAST BCC  $io  $bccs-1   $bccs\n";
                              }
                        }
                  }

                  if ($argv[5] == 1) {
                        if (strpos($result, "250") !== false) {
                        } else {
                              $emalinvalid++;
                              if ($emalinvalid > $maxipinv) {
                                    echo "\nTHREAD " . $myid . " : BROKEN IP DESTINATION INVALID: " . $emalinvalid . "\n";
                                    exit();
                              }
                              if (strpos($result, "459") !== false) {
                                    echo "\nBROKEN IP SKYNET\n";
                                    //exit;
                              }
                              stream_socket_shutdown($socket, STREAM_SHUT_WR);
                              fclose($socket);
                              goto boo;
                        }
                  } else {
                        $emalinvalid = 0;
                  }

                  if ($argv[7]) {
                        fwrite($socket, "RSET\r\n");
                        $result = fread($socket, 1024);
                        echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;
                  } else {
                        if ($argv[5] > 1 && $argv[8]) {
                              fwrite($socket, "RSET\r\n");
                              $result = fread($socket, 1024);
                              echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;
                        }
                        if ($argv[8]) {
                        } else {
                              fwrite($socket, "DATA\r\n");
                              $result = fread($socket, 1024);
                              echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;

                              //$tocanonicalise = $dkim."\r\n".$tocanonicalise."\r\n".$message."\r\n.\r\n";
                              fwrite($socket, $tocanonicalise);
                              $result = fread($socket, 1024);
                              echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;
                              if (strpos($result, "SPF") !== false) {
                                    socket_close($socket);
                                    exit();
                              }
                              if (strpos($result, "spam") !== false || strpos($result, "DMARC") !== false) {
                                    //stream_socket_shutdown($socket,STREAM_SHUT_WR);
                                    //fclose($socket);
                                    //$emailstartpoint = $emailstartpoint - $argv[5];
                                    //echo "\nTHREAD $myid : ".$emailstartpoint."    ".$useremaillles."    ".$vallent."   ".$ttcount;
                                    //sleep(2);
                                    //goto begicontinue;
                              } else {
                                    if ($argv[5] == 1) {
                                          $ttcount++;
                                    }
                              }
                        }
                  }

                  fwrite($socket, "QUIT\r\n");
                  $result = fread($socket, 1024);

                  if ($argv[7]) {
                        echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;

                        echo "\nDONE BRUTECHECK ++++++++++++++++++++++++\n";
                  } else {
                        echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;

                        echo "\nDONE \n";
                  }
                  stream_socket_shutdown($socket, STREAM_SHUT_WR);
                  fclose($socket);

                  if ($argv[7]) {
                        ($myfile = fopen("start.txt", "w")) or die("Unable to open file!");
                        $txt = "1";
                        fwrite($myfile, $txt);
                        fclose($myfile);
                  }
                  //socket_close($socket);

                  if ($toresendlist) {
                        echo "\n+++++++++++++++++++ TRY TO SEND NOW REFORM LIST ++++++++++++++++++++++++\n";

                        goto upreformdata;

                        continationreform:

                        $toresendlist = 0;

                        $socket = stream_socket_client($serverre . ':' . '25', $errno, $errstr, '10', STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT);
                        if ($socket) {
                        } else {
                              echo "\nCould not create socket retry REFORM LIST\n";
                              sleep(2);
                              goto continationreform;
                        }

                        $result = fread($socket, 1024);
                        echo "\nTHREAD REFORM LIST " . $myid . " : " . $serverre . ": " . $result;
                        if (strpos($result, "220") !== false) {
                              if (strpos($result, "errors") !== false || strpos($result, "421") !== false) {
                                    exit();
                              }
                        } else {
                              if (strlen($result) > 0) {
                                    fclose($socket);
                                    echo "\nSMTP SERVICE NOT AVAILABLE = " . $usertld . "\n";
                                    if (
                                          strpos($result, "Spamhaus") !== false ||
                                          strpos($result, "Spamhaus.org") !== false ||
                                          strpos($result, "551-5.7.1 Your IP is black listed by Spamhaus.org") !== false ||
                                          strpos($result, "blacklisted") !== false ||
                                          strpos($result, "postmaster") !== false
                                    ) {
                                          exit();
                                    }
                                    sleep(2);
                                    fclose($socket);
                              } else {
                                    echo "\nTHREAD " . $myid . " : BREACKING FOR EMPTY RESPONSE\n";
                                    sleep(1);
                                    fclose($socket);
                                    goto continationreform;
                              }
                        }

                        $helodata = "HELO " . $helodata . "\r\n";
                        fwrite($socket, $helodata);
                        $result = fread($socket, 1024);
                        echo "\nTHREAD REFORM2 " . $myid . " : " . $serverre . ": " . $result;

                        fwrite($socket, "STARTTLS\r\n");
                        $result = fread($socket, 1024);
                        //echo "\nTHREAD SECC ".$myid." : ".$serverre.": ".$result;

                        if (substr_count($result, " ") > 1) {
                              echo "\nTHREAD FOR TLS1 " . $myid . " : " . $serverre . ": " . $result;
                        } else {
                              $result .= fread($socket, 1024);
                              echo "\nTHREAD FOR TLS2 " . $myid . " : " . $serverre . ": " . $result;
                        }

                        if (strpos($result, "Ready to start TLS") !== false || strpos($result, "SMTP server ready") !== false || strpos($result, "with TLS") !== false) {
                              stream_context_set_option($socket, 'ssl', 'ciphers', 'AES256-SHA');
                              stream_context_set_option($socket, 'ssl', 'verify_peer', false);
                              stream_context_set_option($socket, 'ssl', 'verify_peer_name', false);
                              stream_context_set_option($socket, 'ssl', 'allow_self_signed', true);
                              stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);

                              echo "THREAD " . $myid . " : SSL connect succeeded to socket " . $socket . "\n";

                              fwrite($socket, $helodata);
                              $result = fread($socket, 1024);
                              echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;
                        }

                        $mfrom = "MAIL FROM: <" . $senderemail . ">\r\n";
                        fwrite($socket, $mfrom);
                        $result = fread($socket, 1024);
                        echo "\nTHREAD $myid : $serverre: $result" . "" . $mfrom;

                        if (strpos($result, "SPF") !== false || strpos($result, "MX") !== false) {
                              sleep(2);
                              stream_socket_shutdown($socket, STREAM_SHUT_WR);
                              fclose($socket);
                              goto begicontinue;
                        }

                        for ($io = 0; $io < $argv[5]; $io++) {
                              $usermails = $userpts[$io];
                              $rcpto = "RCPT TO: <" . $usermails . ">\r\n";
                              fwrite($socket, $rcpto);
                              $result = fread($socket, 1024);

                              echo "\nTHREAD $myid : $serverre: $result" . "" . $rcpto . " " . $io . " " . $ttcount . "\n";
                              if (strpos($result, "too") > -1 || strpos($result, "detected") > -1 || strpos($result, "SSL operation") > -1) {
                                    sleep(50);
                                    fclose($socket);
                              }
                        }

                        fwrite($socket, "DATA\r\n");
                        $result = fread($socket, 1024);
                        echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;

                        fwrite($socket, $tocanonicalise);
                        $result = fread($socket, 1024);
                        echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;
                        if (strpos($result, "SPF") !== false) {
                              fclose($socket);
                        }
                        if (strpos($result, "spam") !== false || strpos($result, "DMARC") !== false) {
                              stream_socket_shutdown($socket, STREAM_SHUT_WR);
                              fclose($socket);
                              sleep(2);
                              goto upreformdata;
                        } else {
                              $ttcount = $ttcount + $argv[5];
                        }
                        fwrite($socket, "QUIT\r\n");
                        $result = fread($socket, 1024);
                        echo "\nTHREAD " . $myid . " : " . $serverre . ": " . $result;

                        echo "\n++++++++++++ DONE WITH REFORM LIST ++++++++++++\n";

                        stream_socket_shutdown($socket, STREAM_SHUT_WR);
                        fclose($socket);
                        unlink($argv[2] . "-reform");
                  }

                  boo:

                  echo "\nHEADER SIZE " . strlen($tocanonicalise) . "\n\n";
                  $dkim = "";
                  $tocanonicalise = "";
                  $message = "";
                  $useremail = "";

                  if ($argv[7]) {
                        $emailstartpoint = "0";
                  }
            }
      }
}

$emaillist = "";
if (strlen($filesname) > 0) {
      $bfille = "";
}
printf("\n\nGOOD\n\n");

function RandomNumberGenerator($nMin, $nMax, $nNumOfNumsToGenerate)
{
      $nRandonNumber = 0;
      $tott = " ";
      $s = 1;
      $nNumOfNumsToGenerate++;
      $nMax++;
      for ($i = 0; $i <= $nNumOfNumsToGenerate; $i++) {
            $nRandonNumber = (rand() % ($nMax - $nMin)) + $nMin;
            $valent = substr_count($tott, ' ');
            $p = 1;
            for ($o = 1; $o < $valent; $o++) {
                  $tlsenable = explode(' ', $tott);
                  $tlsenable = $tlsenable[$o];
                  if ($tlsenable == $nRandonNumber) {
                        $i--;
                        $p = 0;
                        break;
                  } else {
                        $p = 1;
                  }
            }
            if ($p) {
                  $tott = $tott . $nRandonNumber;
                  $tott = $tott . " ";
                  $s++;
            }
            if ($s == $nNumOfNumsToGenerate) {
                  return $tott;
            }
      }
}

function encode($email)
{
      //printf("\n%s",email);
      $ret = "";
      for ($i = 0; $i < strlen($email); $i++) {
            //echo "\n$i  =  ".$email[$i]."\n";
            if ($email[$i] == 'a') {
                  $ret = $ret . "Zl";
            }
            if ($email[$i] == "b") {
                  $ret = $ret . "sl";
            }
            if ($email[$i] == "c") {
                  $ret = $ret . "nl";
            }
            if ($email[$i] == "d") {
                  $ret = $ret . "vs";
            }
            if ($email[$i] == "e") {
                  $ret = $ret . "Gd";
            }
            if ($email[$i] == "f") {
                  $ret = $ret . "Zc";
            }
            if ($email[$i] == "g") {
                  $ret = $ret . "yo";
            }
            if ($email[$i] == "h") {
                  $ret = $ret . "Dr";
            }
            if ($email[$i] == "i") {
                  $ret = $ret . "Wj";
            }
            if ($email[$i] == "j") {
                  $ret = $ret . "mD";
            }
            if ($email[$i] == "k") {
                  $ret = $ret . "Rc";
            }
            if ($email[$i] == "l") {
                  $ret = $ret . "Ph";
            }
            if ($email[$i] == "m") {
                  $ret = $ret . "Pn";
            }
            if ($email[$i] == "n") {
                  $ret = $ret . "Fv";
            }
            if ($email[$i] == "o") {
                  $ret = $ret . "Nw";
            }
            if ($email[$i] == "p") {
                  $ret = $ret . "wi";
            }
            if ($email[$i] == "q") {
                  $ret = $ret . "ol";
            }
            if ($email[$i] == "r") {
                  $ret = $ret . "lt";
            }
            if ($email[$i] == "s") {
                  $ret = $ret . "gt";
            }
            if ($email[$i] == "t") {
                  $ret = $ret . "ts";
            }
            if ($email[$i] == "u") {
                  $ret = $ret . "mz";
            }
            if ($email[$i] == "v") {
                  $ret = $ret . "Zv";
            }
            if ($email[$i] == "w") {
                  $ret = $ret . "NX";
            }
            if ($email[$i] == "x") {
                  $ret = $ret . "Wh";
            }
            if ($email[$i] == "y") {
                  $ret = $ret . "Ap";
            }
            if ($email[$i] == "z") {
                  $ret = $ret . "Pa";
            }
            if ($email[$i] == "A") {
                  $ret = $ret . "yn";
            }
            if ($email[$i] == "B") {
                  $ret = $ret . "jq";
            }
            if ($email[$i] == "C") {
                  $ret = $ret . "jx";
            }
            if ($email[$i] == "D") {
                  $ret = $ret . "ms";
            }
            if ($email[$i] == "E") {
                  $ret = $ret . "Gw";
            }
            if ($email[$i] == "F") {
                  $ret = $ret . "fq";
            }
            if ($email[$i] == "G") {
                  $ret = $ret . "yi";
            }
            if ($email[$i] == "H") {
                  $ret = $ret . "rt";
            }
            if ($email[$i] == "I") {
                  $ret = $ret . "Wz";
            }
            if ($email[$i] == "J") {
                  $ret = $ret . "Do";
            }
            if ($email[$i] == "K") {
                  $ret = $ret . "sk";
            }
            if ($email[$i] == "L") {
                  $ret = $ret . "ih";
            }
            if ($email[$i] == "M") {
                  $ret = $ret . "on";
            }
            if ($email[$i] == "N") {
                  $ret = $ret . "Fi";
            }
            if ($email[$i] == "O") {
                  $ret = $ret . "sf";
            }
            if ($email[$i] == "P") {
                  $ret = $ret . "wn";
            }
            if ($email[$i] == "Q") {
                  $ret = $ret . "lp";
            }
            if ($email[$i] == "R") {
                  $ret = $ret . "fp";
            }
            if ($email[$i] == "S") {
                  $ret = $ret . "gl";
            }
            if ($email[$i] == "T") {
                  $ret = $ret . "tq";
            }
            if ($email[$i] == "U") {
                  $ret = $ret . "zq";
            }
            if ($email[$i] == "V") {
                  $ret = $ret . "vD";
            }
            if ($email[$i] == "W") {
                  $ret = $ret . "Xi";
            }
            if ($email[$i] == "X") {
                  $ret = $ret . "Wx";
            }
            if ($email[$i] == "Y") {
                  $ret = $ret . "px";
            }
            if ($email[$i] == "Z") {
                  $ret = $ret . "pi";
            }
            if ($email[$i] == "1") {
                  $ret = $ret . "Qh";
            }
            if ($email[$i] == "2") {
                  $ret = $ret . "hx";
            }
            if ($email[$i] == "3") {
                  $ret = $ret . "xs";
            }
            if ($email[$i] == "4") {
                  $ret = $ret . "mq";
            }
            if ($email[$i] == "5") {
                  $ret = $ret . "gs";
            }
            if ($email[$i] == "6") {
                  $ret = $ret . "fe";
            }
            if ($email[$i] == "7") {
                  $ret = $ret . "Ss";
            }
            if ($email[$i] == "8") {
                  $ret = $ret . "xc";
            }
            if ($email[$i] == "9") {
                  $ret = $ret . "wv";
            }
            if ($email[$i] == "0") {
                  $ret = $ret . "zw";
            }
            if ($email[$i] == "=") {
                  $ret = $ret . "dQ";
            }
      }
      return $ret;
}

function substrringrespond($s, $c)
{
      $b = 0;
      $rl;
      $enf;
      $strr = "~";
      bgg:
      $rl = strpos($s, $c);
      for (;;) {
            if ($rl <= 0) {
                  break;
            }

            $b++;
            $s = substr($s, $rl + strlen($c), strlen($s));
            $enf = strpos($s, "\"");
            $sd = substr($s, 0, $enf) . "~";
            $strr = $strr . $sd;

            goto bgg;
      }
      $db = "~" . $b;
      $strr = $db . $strr;
      return $strr;
}
?>


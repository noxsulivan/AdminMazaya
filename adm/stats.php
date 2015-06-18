<?php

$timeIni = microtime(true);
date_default_timezone_set('America/Sao_Paulo');
$_tmp = explode("/",$_SERVER['REQUEST_URI']);

//echo $_SERVER['REQUEST_URI'];
//unset($_SESSION['objetos']);

//if(ereg('lotisa',$_SERVER['SCRIPT_URL'])){
//	include($_SERVER['DOCUMENT_ROOT']."/lotisa/ini.php");
//}else

if(!preg_match('admin',$_tmp[1])){
	$include = '';
	$includeIni = $_SERVER['DOCUMENT_ROOT']."/".$_tmp[1]."/ini.php";
}else{
	$include = '';
	$includeIni = $_SERVER['DOCUMENT_ROOT']."/ini.php";
}

	if(ereg('mesacor',$_SERVER['HTTP_HOST'])){
		$include = $_SERVER['DOCUMENT_ROOT'].'/2009/_admin/';
		$includeIni = $_SERVER['DOCUMENT_ROOT']."/2009/ini.php";
	}
	if(ereg('sdaasc',$_SERVER['HTTP_HOST'])){
		$include = '';
		$includeIni = $_SERVER['DOCUMENT_ROOT']."/ini2.php";
	}
	if(ereg('lotisa',$_SERVER['REQUEST_URI'])){
		//todos os arquivos da lotisa estão sendo incluidos a partir de seu diretório em separado
		//o index funciona por aqui, e se alterado precisa levar em considerações alterações relevantes para a Lotisa
		//todos os demais aquivos estão congelados na pasta
		$include = '/home/noxsulivan/lotisa.com.br/_admin/';
		$includeIni = "/home/noxsulivan/lotisa.com.br/ini.php";
	}
include($includeIni);



				$_POST['valor'] = "20,00";
				$_POST['vencimento'] = "10/10/2010";
				
				$_POST['data'] = date('d/m/Y H:i:s');
				$_POST['data_vencimento'] = $_POST['vencimento'];
				
				$Itau = new Itaucripto;
				 
				$chave = 'VIAGEM20COTA0709';
				
				$_POST['Pedido'] = '00000657';
				$_POST['cep'] = "88330540";
				if($_POST['cep']){
					
					$logradouro = new objetoDb('logradouros',$db->fetch("select idlogradouros from logradouros where cep = '".preg_replace('!\D!', '', $_POST['cep'])."'",'idlogradouros'));
				}
				$_POST['nome'] = "Sulivan Teixeira";
				$_POST['cpf'] = "92512763153";
				$_POST['endereco'] = "Rua 1528";
				$_POST['numero'] = "145";
				$_POST['complemento'] = "702";
				
				$dadosShopline = $Itau->geraDados
				(
					$codEmp = 'J0046371390001000000011068',
					$pedido = 657 ,
					$valor = $_POST['valor'],
					$observacao = 'Cota de viagem em favor de '.strtoupper($cliente->nome).' Nao receber apos '.$_POST['vencimento'],
					$chave = 'VIAGEM20COTA0709',
					$nomeSacado = $_POST['nome'],
					$codigoInscricao = '01',
					$numeroInscricao = $_POST['cpf'],
					$enderecoSacado = $_POST['endereco'].', '.$_POST['numero'].' '.$_POST['complemento'],
					$bairroSacado =  $logradouro->bairros->bairro,
					$cepSacado = preg_replace('!\D!', '', $_POST['cep']),
					$cidadeSacado = $logradouro->cidades->cidade,
					$estadoSacado = $logradouro->estados->nome,
					$dataVencimento = str_replace('/','',$_POST['vencimento']),
					$urlRetorna = '',//'https://viagememcotas.com.br',
					$obsAd1 = '',
					$obsAd2 = '',
					$obsAd3 = ''
				);
				
				//F183U175Z211H9S232B111L159X165T239C54G165J217R103X205T2H136P92R103I208T14Z7X146D45D100V241Z250T247Q211E208Y47B102J104T119B137Q102L194C187C127J185V117F29Q5F177X25O21Y158F8D255Q50N25X143P11K132A207S92G246A166M147X189E246L50Z142O166E125A145F167P210D12I163Z44Z183O76Q6E96L29E39D196Q216H159T140D159E102J35O11F100B85V5F62O249S135K50Z254S140Y215D158S33E37T161V163M21T104U200A110J245Z207M53N255C206D49V143W238G114A114F94V65F146H169Q216L216V216J13V242U221B168U130Y44T238Y229R81P244L154K244K101M212U18J88Y105H141M85B188D86I32I85D82O49D140I98V232U104U205Q131D37P106L138E160J240J204Y193I42B136N129U60M199Y29G122G241H184F206N25U34H37Q101D126Q211U100R172U207D64M118O184X212D26S13M232P0W192W119Z183U87F1A171I2Z31M40G181G4S239O86M106G19I49U19X124M41K168R198D181E174U98Q109T246S198T35L60E45J18I175B187I184D238H217I213L241G47U93S47N230N23G160Z41T97P219T166R148B168E143I93E241J210D196V139C22V128O177O102A106Y254X176C20G43A219J105O116M102P127J191E178D170W171K132C77Q47A112W242H3B222A101Q212G230K91T4B224N32P53Q21B136P56O168Z226R75U199Z231B220J86L24Q94S133Q201U89P40B15X57G10B219T63O14D175U229E13K48F200X76M144S59M144C173T105C52R112T15U181M191S106V13V115E180M248O203V191G59E172W212D167K45Y79X104Y181C15S130D130M24X112A195Y177P58N245B169J51Q214T114C63K23F98V176F134B106A208R198Q137V45Y209V182R39B202G64Q250Z41F145S36R239I222E167P188I42D148F129W163F124P205M36Y172R127X70E44N255C63G171N6U148X173I95S12S91A86U74Y107Q90T122D205I212L0L14N22B189U21Q139G116R190V8V83E59U180N249B90Y155B150D224F78O25X197C125X39Q144U180Y197K18T219O10E197W128X214Q37I78L2R216D133C24Y50U224X185U227Y137S64I27Z93Q91J249D78W213X90B31Y162V74R29T21T43C162M63I82H84J211G136X74S157R114P165V103T205N175P21R61I113O92J129Q139O147A30Z67R171W193W50T98V41S15L53O54L43N226A253T
				$Itau->decripto($dadosShopline, $chave);
				
				$DC = $dadosShopline;
				
					$ret['status'] = 'ok';
					$ret['mensagem'] = '<p>Para imprimir o boleto desative a função anti-popup do navegador</p>';
					$ret['mensagem'] .= '<form action="https://shopline.itau.com.br/shopline/shopline.asp" method="post" target="_blank">';
					$ret['mensagem'] .= '<input name="acao" id="acao" type="hidden" value="gerarBoleto" />';
					$ret['mensagem'] .= '<input type="hidden" id="DC" name="DC" value="'.$dadosShopline.'" />';
					$ret['mensagem'] .= '<button type="submit" class="submitButton">Clique aqui para imprimir o boleto</button>';
					$ret['mensagem'] .= '</form>';
					
					
					
					
					echo $ret['mensagem'];
				
	
	$logradouro = new objetoDb('logradouros',$db->fetch("select idlogradouros from logradouros where cep = '".preg_replace('!\D!', '', "88330540")."'",'idlogradouros'));
	pre($logradouro);

	//$DC = "L183S175R211R9N232H111D159M165U239D54E165T217R103H205Q2M136F92J103O208Y14A7I146V45H100S241N250L247U211G208A47Z102S108T114R134K102G194Z187N127T185T117Q28X1M177H25F21D158U8K255M50I25J143N11Q132E207U92I246S166F147D189Y246G50D142R166Z125V145B167F210U12O163Z44N183F94W29A104N6C52D196H182M252Q240Q240W111D62H23B23Y45P105T31E195T175S123K165X205J234J203S109L108P247M226A91O104A188F43L166B155S112N255F206A49Z143V238Q114W114Z94X64X131Y169N216R216C216H13J242N221E168T130W44W238F229L81J244F253A180J53L198L17K94E119Y149P89E188Y71L52V64U82L38T156S112J232H104J205M131O37T106Z138T160N240W204P193S42I136Y129Y60I199I29J122T241S184N206R25H34S40P101T126N211K100E172H207D64N118O184N212A26D13G232A0W200U127W180M84N1E174L6M31N90T220V107G239L50J15Y19T91C114O18M76Q193Y180R218X174B16F7M247O199F34P61V45F18M175P187C184Y238D217H213K241P47U93D47L230A23P160V41Y97J219X166M148V168N143L93M241L210N196R139X22B128X177N102X106C254Z176M20F43Y219Q105M116I102F127G191L178R170H171A132M77G47J112J242T3F222X101E212S230I91R4K224G32T53H21T136Q56K168S226D75P199Q231T220C86Z24Z94I133K201Q89P40L15C57V10V219M63O14A175J229S13T48S200K76E144Z59D144M173S105T52X112L15W181N191C106Q13P115B180P248X203M191F59M172X212I167I45S79U104W181T15E130P130N24X112Z195R177W58D245D169P51X214A114B63U23N98E176K134C106G208A198A137S45G209M182Q39O202V64J250J41R145D36N239H222Q167L188H42I148H129K163L124X205I36M172Z127C70Z44D255N63C171J6N148C173C95T12P91S86I74K107B90R122B205E212F0J14V22Q189Q21D139X116A190O8V83I59B180U249L90A155X150Y224D78H25M197F125J39G144V180B197O18F219D10F197H128I214K37Q78D2A216G133G24Y50H224V185U227Q137W64O27C93X91M249A78A213T90N31G162D74T29B21F43H162H63I82N84P211Q136Y74F157T114Z165M103A205X175U21V61S113L92S129G139N147P30T67O171P193N50B98W41Q15V53Y54V43C226F253D";
	$chave = "VIAGEM20COTA0709";
	$Itau = new Itaucripto;
	$Itau->decripto($DC, $chave);
	pre($Itau);
	
	
	die;
	
//pre($includeIni);
//pre(unserialize('a:5:{s:4:"FILE";a:6:{s:8:"FileName";s:9:"phpXO5C3c";s:12:"FileDateTime";i:1255063988;s:8:"FileSize";i:100835;s:8:"FileType";i:2;s:8:"MimeType";s:10:"image/jpeg";s:13:"SectionsFound";s:30:"ANY_TAG, IFD0, THUMBNAIL, EXIF";}s:8:"COMPUTED";a:9:{s:4:"html";s:24:"width="584" height="775"";s:6:"Height";i:775;s:5:"Width";i:584;s:7:"IsColor";i:1;s:17:"ByteOrderMotorola";i:1;s:8:"CCDWidth";s:3:"4mm";s:15:"ApertureFNumber";s:6:"f/18.0";s:18:"Thumbnail.FileType";i:2;s:18:"Thumbnail.MimeType";s:10:"image/jpeg";}s:4:"IFD0";a:10:{s:4:"Make";s:5:"Canon";s:5:"Model";s:21:"Canon EOS-1Ds Mark II";s:11:"Orientation";i:1;s:11:"XResolution";s:13:"1250000/10000";s:11:"YResolution";s:13:"1250000/10000";s:14:"ResolutionUnit";i:2;s:8:"Software";s:29:"Adobe Photoshop CS3 Macintosh";s:8:"DateTime";s:19:"2008:03:16 22:31:17";s:19:"UndefinedTag:0x882A";a:2:{i:0;i:-5;i:1;i:-5;}s:16:"Exif_IFD_Pointer";i:228;}s:9:"THUMBNAIL";a:6:{s:11:"Compression";i:6;s:11:"XResolution";s:4:"72/1";s:11:"YResolution";s:4:"72/1";s:14:"ResolutionUnit";i:2;s:21:"JPEGInterchangeFormat";i:730;s:27:"JPEGInterchangeFormatLength";i:5102;}s:4:"EXIF";a:24:{s:12:"ExposureTime";s:5:"1/125";s:7:"FNumber";s:4:"18/1";s:15:"ExposureProgram";i:1;s:15:"ISOSpeedRatings";i:400;s:11:"ExifVersion";s:4:"0221";s:16:"DateTimeOriginal";s:19:"2008:03:07 12:57:48";s:17:"DateTimeDigitized";s:19:"2008:03:07 12:57:48";s:17:"ShutterSpeedValue";s:15:"6965784/1000000";s:13:"ApertureValue";s:13:"833985/100000";s:17:"ExposureBiasValue";s:3:"0/1";s:16:"MaxApertureValue";s:3:"1/1";s:12:"MeteringMode";i:5;s:5:"Flash";i:16;s:11:"FocalLength";s:4:"50/1";s:10:"ColorSpace";i:65535;s:14:"ExifImageWidth";i:584;s:15:"ExifImageLength";i:775;s:21:"FocalPlaneXResolution";s:12:"5008000/1420";s:21:"FocalPlaneYResolution";s:11:"3334000/945";s:24:"FocalPlaneResolutionUnit";i:2;s:14:"CustomRendered";i:0;s:12:"ExposureMode";i:1;s:12:"WhiteBalance";i:0;s:16:"SceneCaptureType";i:0;}}'));die;
define ('COOKIE_NAME', diretorio('usuarioAdmin_'.$_SERVER['HTTP_HOST']."_".$_SERVER['SCRIPT_FILENAME']."_".date("Ymd")."_reset4"));

set_time_limit ( 0 );


define('DISPLAY_XPM4_ERRORS', true); // display XPM4 errors


$queryString = ereg('public_html',$_SERVER["QUERY_STRING"]) ? '' : $_SERVER["QUERY_STRING"];
$admin = new admin($queryString);


define('ga_email','noxsulivan@gmail.com');
define('ga_password','noxaetherne');
define('ga_profile_id','26224079');


$ga = new gapi(ga_email,ga_password);

$ga->requestAccountData();
pre($ga);

//$ga->requestReportData(ga_profile_id,array('date'),array('visits','newVisits','pageviews','bounces'),'date');
//pre($ga);

?>
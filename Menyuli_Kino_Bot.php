<?php
ob_start();
error_reporting(0);
date_Default_timezone_set('Asia/Tashkent');

/* Manba bilan oling va mualliflik huquqi
dasturchini mehnatini hurmat qiling!!!
Manba: @astiuz
Dasturchi: @Asilbek_Coder */

define('astiuz',"6949659981:AAEwv1BiHlfGO8icffTj1UVqpyw0_v6mDFg");
$admin = 5819317484;


function bot($method,$datas=[]){
	$url = "https://api.telegram.org/bot".astiuz."/".$method;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
	$res = curl_exec($ch);
	if(curl_error($ch)){
		var_dump(curl_error($ch));
	}else{
		return json_decode($res);
	}
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$cid = $message->chat->id;
$name = $message->chat->first_name;
$step = file_get_contents("step/$cid.step");
$mid = $message->message_id;
$text = $message->text;
$data = $update->callback_query->data;
$qid = $update->callback_query->id;
$id = $update->inline_query->id;
$query = $update->inline_query->query;
$query_id = $update->inline_query->from->id;
$cid2 = $update->callback_query->message->chat->id;
$mid2 = $update->callback_query->message->message_id;


mkdir("step");
mkdir("kinolar");

$panel = json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
    [['text'=>"🎥 Kino qoʻshish ➕"]],
[['text'=>"📊 Statistika"],['text'=>"◀️ Orqaga"]],
]
]);



$botPanel = json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"🎥 Tarjima Kinolar"]],
[['text'=>"Qo'llanma"],['text'=>"Support"]],
]
]);

/* Manba bilan oling va mualliflik huquqi
dasturchini mehnatini hurmat qiling!!!
Manba: @astiuz
Dasturchi: @Asilbek_Coder */


$back = json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"◀️ Orqaga"]],
]
]);


if($text == "/start"){
	bot('SendMessage',[
	'chat_id'=>$cid,
	'message_id'=>$mid,
	'text'=>"👋Salom $name @MenyuKinoBot ga xush kelibsiz Kerakli bo'limni tanlang👇",
	'parse_mode'=>'html',
	'reply_markup'=>$botPanel,
	]);
}

if($text == "Qo'llanma"){
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"Qo'llanma matni kiritilmagan",
'parse_mode'=>'html',
]);
}

if($text == "/panel" and $cid == $admin){
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"Qo'llanma matni kiritilmagan",
'parse_mode'=>'html',
	'reply_markup'=>$panel,
]);
}


/* Manba bilan oling va mualliflik huquqi
dasturchini mehnatini hurmat qiling!!!
Manba: @astiuz
Dasturchi: @Asilbek_Coder */


$stip = file_get_contents("step/$cid.step");
$kinobaza = file_get_contents("kinolar/royxat.txt");
$kinosoni = file_get_contents("kinolar/soni.txt");
$kinokey = file_get_contents("kinolar/key.txt");

if (preg_match("/$text/i",$kinobaza)){
$kinobazalink = explode("$text"."+","$kinobaza");
$kinobazalink2 = $kinobazalink[1];
	
	bot('SendVideo',[
'chat_id'=>$cid,
'replay_to_message_id'=>$mid,
'video'=>"$kinobazalink2",
'caption'=>"$text
@MenyuKinoBot • Biz bilan Kino topish oson! ",
'parse_mode'=>"html",
]);
}


if($text == "🎥 Kino qoʻshish ➕"){
    
file_put_contents("step/$cid.step","kinoqosh");

 bot('SendMessage',[
 'chat_id'=>$cid,
 'text'=>"Kino nomini kiriting",
 'parse_mode'=>'html'
 ]);
}

if($stip == "kinoqosh"){
    file_put_contents("kinolar/royxat.txt","$kinobaza\n$text");
    bot('SendMessage',[
 'chat_id'=>$cid,
 'text'=>"Bironta kanaldagi kino linkini kiriting
 
 Masalan: <code>https://t.me/Tarjima_Kinolar_Ozbek_Tilida1/17</code>",
 'parse_mode'=>'html'
 ]);
 file_put_contents("step/$cid.step","kinolink");
}


if($stip == "kinolink"){
    file_put_contents("kinolar/royxat.txt","$kinobaza"."+"."$text");
   
   
    bot('SendMessage',[
 'chat_id'=>$cid,
 'text'=>"Shu kino bot menyusiga qoʻshildi",
 'parse_mode'=>'html',
 ]);
 file_put_contents("step/$cid.step","kinoff");
}

/* Manba bilan oling va mualliflik huquqi
dasturchini mehnatini hurmat qiling!!!
Manba: @astiuz
Dasturchi: @Asilbek_Coder */

if($text == "🎥 Tarjima Kinolar"){

$ids = explode("\n",$kinobaza);

$soni = substr_count($kinobaza,"\n");

foreach($ids as $id){
    $kinomatn = explode("+",$id);
$id2 = $kinomatn[0];


$keyboards[]=["text"=>"$id2"];

}

$keyboards[]=["text"=>"◀️ Orqaga"];

$keyboard2=array_chunk($keyboards, 1);
$keyboard=json_encode([
'resize_keyboard'=>true,
'keyboard'=>$keyboard2,
]);


	bot('sendMessage',[
	'chat_id'=>$cid,
	'message_id'=>$mid,
	'text'=>"👇 Quyidagi kinolardan birini tanlang",
	'parse_mode'=>"html",
	'reply_markup'=>$keyboard,
	]);
	
 file_put_contents("kinolar/key.txt","$keyboard");
 
}






if($text == "◀️ Orqaga"){
	bot('SendMessage',[
	'chat_id'=>$cid,
	'text'=>"<b>🖥 Asosiy menyuga qaytdingiz.</b>",
	'parse_mode'=>'html',
	'reply_markup'=>$botPanel,
]);
}



if($text == "Support"){
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"Botda xato topsangiz adminga murojaat qiling
Administrator 👇",
'parse_mode'=>'html',
	'reply_markup'=>json_encode([
	'inline_keyboard'=>[
[['text'=>"📞 Admin",'url'=>"tg://user?id=$admin"]]
]
])
]);
}




if($text == "📊 Statistika" and $cid == $admin){

$baza = file_get_contents("azo.dat");
$obsh = substr_count($baza,"\n");
bot('SendMessage',[
'chat_id'=>$cid,
'text'=>"👥 <b>Foydalanuvchilar:</b> $obsh ta",
'parse_mode'=>'html',
]);
}


/* Manba bilan oling va mualliflik huquqi
dasturchini mehnatini hurmat qiling!!!
Manba: @astiuz
Dasturchi: @Asilbek_Coder */

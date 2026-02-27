<?php

#================================================

define('API_KEY', '8203200195:AAFL6hXkx-XWMia4KQg89neUjm1k3xsGvA4');

$idbot = 8203200195;
$userbot = 'animea_as_bot';
$sadiyuz = 8201674543;
$owners = array($bshaxriyor);
$user = "MarslikDev";

define('DB_HOST', 'localhost');
define('DB_USER', 'x_u_13443_fineapi');
define('DB_PASS', 'akobirshox');
define('DB_NAME', 'x_u_13443_fineapi');

$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($connect, 'utf8mb4');

function bot($method,$datas=[]){
	$url = "https://api.telegram.org/bot". API_KEY ."/". $method;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
	$res = curl_exec($ch);
	if(curl_error($ch)) var_dump(curl_error($ch));
	else return json_decode($res);
}

#================================================

function sendMessage($id, $text, $key = null){
return bot('sendMessage',[
'chat_id'=>$id,
'text'=>$text,
'parse_mode'=>'html',
'disable_web_page_preview'=>true,
'reply_markup'=>$key
]);
}

function editMessageText($cid, $mid, $text, $key = null){
return bot('editMessageText',[
'chat_id'=>$cid,
'message_id'=>$mid,
'text'=>$text,
'parse_mode'=>'html',
'disable_web_page_preview'=>true,
'reply_markup'=>$key
]);
}

function sendVideo($cid, $f_id, $text, $key = null){
return bot('sendVideo',[
'chat_id'=>$cid,
'video'=>$f_id,
'caption'=>$text,
'parse_mode'=>'html',
'reply_markup'=>$key
]);
}

function copyMessage($id, $from_chat_id, $message_id){
return bot('copyMessage',[
'chat_id'=>$id,
'from_chat_id'=>$from_chat_id,
'message_id'=>$message_id
]);
}

function forwardMessage($id, $cid, $mid){
return bot('forwardMessage',[
'from_chat_id'=>$id,
'chat_id'=>$cid,
'message_id'=>$mid
]);
}

function deleteMessage($cid,$mid){
return bot('deleteMessage',[
'chat_id'=>$cid,
'message_id'=>$mid
]);
}

function getChatMember($cid, $userid){
return bot('getChatMember',[
'chat_id'=>$cid,
'user_id'=>$userid
]);
}

function replyKeyboard($key){
return json_encode(['keyboard'=>$key, 'resize_keyboard'=>true]);
}

function getName($id){
$getname = bot('getchat',['chat_id'=>$id])->result->first_name;
if(!empty($getname)){
return $getname;
}else{
return bot('getchat',['chat_id'=>$id])->result->title;
}
}

function joinchat($id){
$array = array("inline_keyboard");
$kanallar=file_get_contents("admin/kanal.txt");
if($kanallar == null){
return true;
}else{
$ex = explode("\n",$kanallar);
for($i=0;$i<=count($ex) -1;$i++){
$first_line = $ex[$i];
$first_ex = explode("@",$first_line);
$url = $first_ex[1];
$ism=bot('getChat',['chat_id'=>"@".$url])->result->title;
$ret = bot("getChatMember",[
"chat_id"=>"@$url",
"user_id"=>$id,
]);
$stat = $ret->result->status;
if($url == null){
$stat = "member";
}
if((($stat=="creator" or $stat=="administrator" or $stat=="member"))){
$array['inline_keyboard']["$i"][0]['text'] = "✅ ". $ism;
$array['inline_keyboard']["$i"][0]['url'] = "https://t.me/Marslikdev";
}else{
$array['inline_keyboard']["$i"][0]['text'] = "❌ ". $ism;
$array['inline_keyboard']["$i"][0]['url'] = "https://t.me/$url";
$uns = true;
}}
$array['inline_keyboard']["$i"][0]['text'] = "🔄 Tekshirish";
$array['inline_keyboard']["$i"][0]['callback_data'] = "check";
if($uns == true){
sendMessage($id, "<b>⚠️ Botdan to'liq foydalanish uchun quyidagi kanallarimizga obuna bo'ling!</b>", json_encode($array));
return false;
}else{
return true;
}}}

#================================================

date_Default_timezone_set('Asia/Tashkent');
$soat = date('H:i');
$sana = date("d.m.Y");

#================================================

$update = json_decode(file_get_contents('php://input'));

$message = $update->message;
$callback = $update->callback_query;

if (isset($message)) {
$cid = $message->chat->id;
$Tc = $message->chat->type;

$text = $message->text;
$mid = $message->message_id;

$from_id = $message->from->id;
$name = $message->from->first_name;
$last = $message->from->last_name;

$photo = $message->photo[count($message->photo) - 1]->file_id;

$video = $message->video;
$file_id = $video->file_id;
$file_name = $video->file_name;
$file_size = $video->file_size;
$size = $file_size/1000;
$dtype = $video->mime_type;

$audio = $message->audio->file_id;
$voice = $message->voice->file_id;
$sticker = $message->sticker->file_id;
$video_note = $message->video_note->file_id;
$animation = $message->animation->file_id;

$caption = $message->caption;
}

if (isset($callback)) {
$data = $callback->data;
$qid = $callback->id;

$cid = $callback->message->chat->id;
$Tc = $callback->message->chat->type;
$mid = $callback->message->message_id;

$from_id = $callback->from->id;
$name = $callback->from->first_name;
$last = $callback->from->last_name;
}

#=================================================

$kino_id = file_get_contents("admin/kino.txt");
$kino = bot('getchat',['chat_id'=>$kino_id])->result->username;
$reklama = str_replace(["%kino%","%admin%"],[$kino,$user], file_get_contents("admin/rek.txt"));

#================================================

$admins = explode("\n", file_get_contents("admin/admins.txt"));
if (is_array($admins)) $admin = array_merge($owners, $admins);
else $admin = $owners;

#=================================================

$user = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `user_id` WHERE `id` = $cid"));
$user_id = $user['user_id'];
$step = $user['step'];
$ban = $user['ban'];
$lastmsg = $user['lastmsg'];

#=================================================

if ($ban == 1) exit();

if(isset($message)){
if(!$connect){
sendMessage($cid, "⚠️ <b>Ma'lumotlar olishda xatolik!</b>\n\n<i>Iltimos tezroq adminga xabar bering.</i>");
return false;
}
}

if($Tc == "private"){
$result = mysqli_query($connect,"SELECT * FROM `user_id` WHERE `id` = $cid");
$rew = mysqli_fetch_assoc($result);
if($rew){
}else{
mysqli_query($connect,"INSERT INTO `user_id`(`id`,`step`,`sana`,`ban`) VALUES ('$cid','0','$sana | $soat','0')");
}
}

#=================================================

$panel = replyKeyboard([
[['text'=>"👤 Userlar"],['text'=>"👨‍💼 Adminlar"]],
[['text'=>"💬 Kanallar"],['text'=>"🗂️ Ma'lumotlar"]],
[['text'=>"🚪 Paneldan chiqish"]]
]);

$cancel = replyKeyboard([
[['text'=>"◀️ Orqaga"]]
]);

$userlar_p = replyKeyboard([
[['text'=>"🔴 Blocklash"],['text'=>"🟢 Blockdan olish"]],
[['text'=>"✍️ Post xabar"],['text'=>"📬 Forward xabar"]],
[['text'=>"📈 Statistika"]],
[['text'=>"◀️ Orqaga"]]
]);

$kanallar_p = replyKeyboard([
[['text'=>"🔷 Kanal ulash"],['text'=>"🔶 Kanal uzish"]],
//[['text'=>"🟩 Majburish a'zolik"]],
[['text'=>"◀️ Orqaga"]]
]);

$malumotlar_p = replyKeyboard([
[['text'=>"🎬 Kino qo'shish"],['text'=>"🗑️ Kino o'chirish"]],
[['text'=>"💡 Kino kanal"],['text'=>"📈 Reklama"]],
[['text'=>"◀️ Orqaga"]]
]);

$removeKey = json_encode(['remove_keyboard'=>true]);

#=================================================

if($text == "/start" and joinchat($cid)==true){
$keyBot = json_encode(['inline_keyboard'=>[
[['text'=>"🔎 Kodlarni qidirish",'url'=>"https://t.me/nicecodersuz"]]
]]);
sendMessage($cid, "👋 <b>Assalomu alaykum</b> <a href='tg://user?id=$cid'>$name</a>  <b>botimizga xush kelibsiz.</b>\n\n<i>✍🏻 Kino kodini yuboring.</i>", $keyBot);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'start' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
exit();
}

else if ($data == "check"){
deleteMessage($cid, $mid);
$keyBot = json_encode(['inline_keyboard'=>[
[['text'=>"🔎 Kodlarni qidirish",'url'=>"https://t.me/$kino"]]
]]);
if (joinchat($cid)==true) {
sendMessage($cid, "👋 <b>Assalomu alaykum</b> <a href='tg://user?id=$cid'>$name</a>  <b>botimizga xush kelibsiz.</b>\n\n<i>✍🏻 Kino kodini yuboring.</i>", $keyBot);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'start' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
}
}

#=================================================

else if(($text == "/panel" or $text == "/a" or $text == "/admin" or $text == "/p" or $text == "◀️ Orqaga") and in_array($cid,$admin)){
sendMessage($cid, "<b>👨🏻‍💻 Boshqaruv paneliga xush kelibsiz.</b>\n\n<i>Nimani o'zgartiramiz?</i>", $panel);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'panel' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
exit();
}

else if ($text == "🚪 Paneldan chiqish" and in_array($cid,$admin)){
sendMessage($cid, "<b>🚪 Panelni tark etdingiz unga /panel yoki /admin xabarini yuborib kirishingiz mumkin.\n\nYangilash /start</b>", $removeKey);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'start' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
exit();
}

else if ($text == "🗂️ Ma'lumotlar" and in_array($cid,$admin)){
sendMessage($cid, "<b>🗂️ Ma'lumotlar bo'limi.\n🆔 Admin: $cid</b>", $malumotlar_p);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'manuals' WHERE `id` = $cid");
exit();
}

else if ($text == "🎬 Kino qo'shish" and in_array($cid,$admin)){
sendMessage($cid, "<b>🎬 Kino qo'shish uchun menga kino yuboring:</b>", $cancel);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'addMovie' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = 'movie-add' WHERE `id` = $cid");
exit();
}

else if(isset($video) and $step == "movie-add"){
if(in_array($cid,$admin)){
$result = mysqli_query($connect,"SELECT * FROM `data` WHERE `file_name` = '$file_name'");
$row = mysqli_fetch_assoc($result);
if(!$row){
$rand = rand(0,9999);
$file_name = str_replace("_", " ", $file_name);
mysqli_query($connect, "INSERT INTO `data`(`file_name`,`file_id`,`code`) VALUES ('$file_name','$file_id','$rand')");
$msg = sendMessage("@$kino", "$caption\n\n<b>Kino kodi:</b> <code>$rand</code>\n\n$reklama\n\n❗️ <b>Diqqat kinoni @$userbot orqali topishingiz mumkin!</b>")->result->message_id;
sendMessage($cid, "<b>✅ Bazaga muvaffaqiyatli joylandi!</b> \n\n<code>$rand</code>\n\n<a href='https://t.me/$kino/$msg'>👀 Ko'rish</a>");
exit();
}else{
sendMessage($cid, "$file_name <b>qabul qilinmadi!</b>\n\nQayta urinib ko'ring:");
exit();
}
}
}

else if ($text == "🗑️ Kino o'chirish" and in_array($cid,$admin)){
sendMessage($cid, "<b>🗑️ Kino o'chirish uchun menga kino kodini yuboring:</b>", $cancel);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'deleteMovie' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = 'movie-remove' WHERE `id` = $cid");
exit();
}

else if(($step == "movie-remove" and $text != "🗑️ Kino o'chirish") and in_array($cid,$admin)){
$res = mysqli_query($connect, "SELECT * FROM `data` WHERE `code` = '$text'");
$row = mysqli_fetch_assoc($res);
if($row){
mysqli_query($connect, "DELETE FROM `data` WHERE `code` = $text"); 
sendMessage($cid, "🗑️ $text <b>raqamli kino olib tashlandi!</b>");
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
exit();
}else{
sendMessage($cid, "📛 $text <b>mavjud emas!</b>\n\n🔄 Qayta urinib ko'ring:");
exit();
}
}

else if ($text == "💡 Kino kanal" and in_array($cid,$admin)){
sendMessage($cid, "<b>💡 Kino kanal havolasini yuboring!\n\nNa'muna: @Marslikdev</b>", $cancel);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'movie_chan' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = 'movie_chan' WHERE `id` = $cid");
exit();
}

else if (($step == "movie_chan" and $text != "💡 Kino kanal") and in_array($cid,$admin)) {
$nn_id = bot('getchat',['chat_id'=>$text])->result->id;
sendMessage($cid, "<b>✅ $text ga o'zgartirildi.</b>", $panel);
file_put_contents("admin/kino.txt", $nn_id);
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
}

else if ($text == "📈 Reklama" and in_array($cid,$admin)){
sendMessage($cid, "<b>📈 Reklamani yuboring!\n\nNa'muna:</b> <pre>@%kino% kanali uchun maxsus joylandi!</pre>", $cancel);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'ads_set' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = 'ads_set' WHERE `id` = $cid");
exit();
}

else if (($step == "ads_set" and $text != "📈 Reklama") and in_array($cid,$admin)) {
sendMessage($cid, "<b>✅ $text ga o'zgartirildi.</b>", $panel);
file_put_contents("admin/rek.txt", $text);
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
}

else if ($text == "💬 Kanallar" and in_array($cid,$admin)){
sendMessage($cid, "<b>🔰 Kanallar bo'limi:\n🆔 Admin: $cid</b>", $kanallar_p);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'channels' WHERE `id` = $cid");
exit();
}

else if ($text == "🔷 Kanal ulash" and in_array($cid,$admin)){
sendMessage($cid, "<b>Kanal ulash uchun kanal havolasini yuboring.\n\nNa'muna: @Marslikdev</b>", $cancel);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'channelsAdd' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = 'channel-add' WHERE `id` = $cid");
exit();
}

else if (($step == "channel-add" and $text != "🔷 Kanal ulash") and in_array($cid,$admin)){
sendMessage($cid, "<b>✅ Kanallar ulandi!</b>", $panel);
file_put_contents("admin/kanal.txt", "\n".$text, 8);
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
exit();
}

else if ($text == "🔶 Kanal uzish" and in_array($cid,$admin)){
sendMessage($cid, "<b>✅ Kanallar uzildi.</b>", $cancel);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'deleteChan' WHERE `id` = $cid");
unlink("admin/kanal.txt");
exit();
}

else if ($text == "🟩 Majburish a'zolik" and in_array($cid,$admin)){
bot('sendMessage',[
'chat_id'=>$cid,
'text'=>"<b>🟩 Majburish a'zolik kanallari:</b>\n\n". file_get_contents("admin/kanal.txt"),
'parse_mode'=>'html',
'reply_markup'=>$cancel
]);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'channels' WHERE `id` = $cid");
exit();
}

else if ($text == "👤 Userlar" and in_array($cid,$admin)){
sendMessage($cid, "<b>👥Userlar boshqaruvi bo'limi:\n🆔 Admin: $cid</b>", $userlar_p);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'users' WHERE `id` = $cid");
exit();
}

else if ($text == "🔴 Blocklash" and in_array($cid,$admin)){
sendMessage($cid, "<b>Foydalanuvchi ID raqamini kiriting:</b>\n\n<i>M-n: $cid</i>", $cancel);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'addblock' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = 'blocklash' WHERE `id` = $cid");
exit();
}

else if (($step == "blocklash" and $text != "🔔 Blocklash") and in_array($cid,$admin)){
sendMessage($cid, "<b>✅ $text blocklandi!</b>", $panel);
mysqli_query($connect, "UPDATE `user_id` SET `ban` = 1 WHERE `id` = $text");
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
exit();
}

else if ($text == "🟢 Blockdan olish" and in_array($cid,$admin)){
sendMessage($cid, "<b>Foydalanuvchi ID raqamini kiriting:</b>\n\n<i>M-n: $cid</i>", $cancel);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'deleteBlock' WHERE 	 = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = 'blockdanolish' WHERE `id` = $cid");
exit();
}

else if (($step == "blockdanolish" and $text != "🔕 Blockdan olish") and in_array($cid,$admin)){
sendMessage($cid, "<b>✅ $text blockdan olindi!</b>", $panel);
mysqli_query($connect, "UPDATE `user_id` SET `ban` = 0 WHERE `id` = $text");
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
exit();
}

else if($text == "✍️ Post xabar" and in_array($cid,$admin)){
sendMessage($cid, "<b>Xabaringizni yuboring:</b>");
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'post_msg' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = 'post_send' WHERE `id` = $cid");
exit();
}

else if (($step == "post_send" and $text != "✍️ Post xabar") and in_array($cid,$admin)){
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
$msg = sendMessage($cid, "✅ <b>Xabar yuborish boshlandi!</b>", $panel)->result->message_id;
$yuborildi = 0;
$yuborilmadi = 0;
$result = mysqli_query($connect, "SELECT * FROM `user_id`");
while($row = mysqli_fetch_assoc($result)){
$id = $row['id'];
$ok = copyMessage($id, $cid, $mid)->ok;
if ($ok == true) $yuborildi++;
else $yuborilmadi++;
editMessageText($cid, $msg, "✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga");
}
deleteMessage($cid, $msg);
sendMessage($cid, "💡 <b>Xabar yuborish tugatildi.\n\n</b>✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga\n\n<b>⏰ Soat: $soat | 📆 Sana: $sana</b>", $panel);
}

else if($text == "📬 Forward xabar" and in_array($cid,$admin)){
sendMessage($cid, "<b>Xabaringizni yuboring:</b>");
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'post_msg' WHERE `id` = $cid");
mysqli_query($connect, "UPDATE `user_id` SET `step` = 'forward_send' WHERE `id` = $cid");
exit();
}

else if (($step == "forward_send" and $text != "📬 Forward xabar") and in_array($cid,$admin)){
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
$msg = sendMessage($cid, "✅ <b>Xabar yuborish boshlandi!</b>", $panel)->result->message_id;
$result = mysqli_query($connect, "SELECT * FROM `user_id`");
$yuborildi = 0;
$yuborilmadi = 0;
while($row = mysqli_fetch_assoc($result)){
$id = $row['id'];
$ok = forwardMessage($cid, $id, $mid)->ok;
if ($ok == true) $yuborildi++;
else $yuborilmadi++;
editMessageText($cid, $msg, "✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga");
}
deleteMessage($cid, $msg);
sendMessage($cid, "💡 <b>Xabar yuborish tugatildi.\n\n</b>✅ <b>Yuborildi:</b> {$yuborildi}taga\n❌ <b>Yuborilmadi:</b> {$yuborilmadi}taga\n\n<b>⏰ Soat: $soat | 📆 Sana: $sana</b>", $panel);
}

else if($text == "📈 Statistika"){
$res = mysqli_query($connect, "SELECT * FROM `user_id`");
$us = mysqli_num_rows($res);
$res = mysqli_query($connect, "SELECT * FROM `data`");
$kin = mysqli_num_rows($res);
$ping = sys_getloadavg()[2];
sendMessage($cid, "💡 <b>O'rtacha yuklanish:</b> <code>$ping</code>\n\n• <b>Foydalanuvchilar:</b> $us ta\n• <b>Yuklangan kinolar:</b> $kin ta");
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'stat' WHERE `id` = $cid");
exit();
}

else if(($text == "👨‍💼 Adminlar" or $data == "admins") and in_array($cid,$admin)){
if(isset($data)) deleteMessage($cid, $mid);
$keyBot = json_encode(['inline_keyboard'=>[
[['text'=>"➕ Yangi admin qo'shish",'callback_data'=>"add-admin"]],
[['text'=>"📑 Ro'yxat",'callback_data'=>"list-admin"],['text'=>"🗑 O'chirish",'callback_data'=>"remove"]],
]]);
sendMessage($cid, "👇🏻 <b>Quyidagilardan birini tanlang:</b>", $keyBot);
mysqli_query($connect, "UPDATE `user_id` SET `lastmsg` = 'admins' WHERE `id` = $cid");
exit();
}

else if($data == "list-admin"){
$admins = file_get_contents("admin/admins.txt");
$keyBot = json_encode(['inline_keyboard'=>[
[['text'=>"◀️ Orqaga",'callback_data'=>"admins"]],
]]);
editMessageText($cid, $mid, "<b>👮 Adminlar ro'yxati:</b>\n\n$admins", $keyBot);
}

else if($data == "add-admin"){
deleteMessage($cid, $mid);
sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>", $cancel);
mysqli_query($connect, "UPDATE `user_id` SET `step` = 'add-admin' WHERE `id` = $cid");
}

else if($step == "add-admin" and $cid == $sadiyuz){
if(is_numeric($text)=="true"){
if($text != $sadiyuz){
file_put_contents("admin/admins.txt", "\n$text", 8);
sendMessage($sadiyuz, "✅ <b>$text endi bot admini.</b>", $panel);
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
exit();
}else{
sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
exit();
}
}else{
sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
exit();
}
}

else if($data == "remove"){
deleteMessage($cid, $mid);
sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>", $cancel);
mysqli_query($connect, "UPDATE `user_id` SET `step` = 'remove-admin' WHERE `id` = $cid");
exit();
}

else if($step == "remove-admin" and $cid == $sadiyuz){
if(is_numeric($text)=="true"){
if($text != $sadiyuz){
$files = file_get_contents("admin/admins.txt");
$file = str_replace("{$text}", '', $files);
file_put_contents("admin/admins.txt",$file);
sendMessage($sadiyuz, "✅ <b>$text endi botda admin emas.</b>", $panel);
mysqli_query($connect, "UPDATE `user_id` SET `step` = '0' WHERE `id` = $cid");
exit();
}else{
sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
exit();
}
}else{
sendMessage($cid, "<b>Kerakli iD raqamni kiriting:</b>");
exit();
}
}


else if((isset($text) and $lastmsg == "start") and $text != "/start"){
if(is_numeric($text) == true){
$res = mysqli_query($connect, "SELECT * FROM `data` WHERE `code` = '$text'");
$row = mysqli_fetch_assoc($res);
$fname = $row['file_name'];
$f_id = $row['file_id'];
if(!$row){
sendMessage($cid, "📛 $text <b>kodli kinolar mavjud emas!</b>");
exit();
}else{
sendVideo($cid, $f_id, "$fname\n\n$reklama");
exit();
}
}else{
sendMessage($cid, "<b>📛 Faqat raqamlardan foydalaning!</b>");
exit();
}
}

/*else {
sendMessage($cid, "<b>☹︎ Sizni tushuna olib bo'lmadi!\n\nBotni yangilang: /start</b>");
}*/

?>

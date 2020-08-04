<?php
namespace kickmut;
   
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\SimpleCommandMap;
    
class plugin extends PluginBase implements Listener{
public $tmute,$mute,$tban,$ban,$banip,$tbanip,$player;
public function onLoad(){
  $cmds= ["ban-ip", "ban", "banlist", "pardon-ip", "pardon", "kick"];
  foreach($cmds as $cmds){
   $cmd = $this->getServer()->getCommandMap()->getCommand($cmds);
   $this->getServer()->getCommandMap()->unregister($cmd);
  }
 }
 public function onEnable(){
  if(!is_dir($this->getDataFolder())){
   @mkdir($this->getDataFolder());
   }
   $this->getLogger()->info("Плагин запустился!");
   $this->getServer()->getPluginManager()->registerEvents($this,$this);
$this->mute=new Config($this->getDataFolder()."mute.yml", Config::YAML);
$this->tmute=new Config($this->getDataFolder()."tmute.yml", Config::YAML);
$this->ban=new Config($this->getDataFolder()."ban.yml", Config::YAML);
$this->tban=new Config($this->getDataFolder()."tban.yml", Config::YAML);
$this->banip=new Config($this->getDataFolder()."banip.yml", Config::YAML);
$this->tbanip=new Config($this->getDataFolder()."tbanip.yml", Config::YAML);
$this->player=new Config($this->getDataFolder()."player.yml", Config::YAML);
}
public function onCommand(CommandSender $sender,Command $cmd,string $label,array $args) : bool{
if(strtolower($cmd->getName()) =='banip'){
if(count($args)>=2){	
 $pl=$this->getServer()->getPlayer(strtolower($args[0]));
 if($pl!=null){
 $pn=$pl->getName();
 $ip=$pl->getAddress();
 if(!$pl->hasPermission("antibanip.use")){
 if(!$this->ban->exists(strtolower($pn)) and !$this->banip->exists($ip) and !$this->tbanip->exists($ip) and !$this->tban->exists(strtolower($pn))){
 $reason=implode(" ", $args);
 $this->banip->set($ip,$ip);
 $this->banip->set("sender_{$ip}",$sender->getName());
 $this->banip->set("reason_{$ip}",$reason);
 $this->getServer()->broadCastMessage("§l§cАдминистратор §a§n{$sender->getName()}§r§l§c забанил игрока §a§n{$pn}§r§b§l - {$reason}");
  foreach($this->getServer()->getOnlinePlayers() as $player){
if($player->getAddress() === $ip){
$player->close(" ","§l§cАдминистратор §a§n{$sender->getName()}§r§l§c забанил вас §a§b -  {$reason}");
   }
  }
 $this->banip->save();
 }else{
 $sender->sendMessage("Данный игрок уже забанен");
 }
 }else{
 $sender->sendMessage("Вы не можете забанить данного игрока");
 }
 }else{
 $sender->sendMessage("Игрок не в сети");
 }
 }else{
 $sender->sendMessage("Используйте /banip <игрок> <причина>");
 }
}
if(strtolower($cmd->getName())=='tbanip'){
 if(count($args)>=5){
  if(is_numeric($args[1]) and ($args[1]>=0 and $args[1]<=60) and is_numeric($args[2]) and ($args[2]>=0 and $args[2]<=24) and is_numeric($args[3]) and ($args[3]>=0 and $args[1]<=100)){
 $pl=$this->getServer()->getPlayer($args[0]);
   if($pl!=null){
 $pn=$pl->getName();
 $ip=$pl->geAddress();
 if(!$pl->hasPermission("antitbanip.use")){
 if(!$this->ban->exists(strtolower($pn)) and !$this->banip->exists($ip) and !$this->tbanip->exists($ip) and !$this->tban->exists(strtolower($pn))){
 $m=$args[1];
$h=$args[2];
$d=$args[3];
$t=time()+$m*60+$h*3600+$d*86400;
 unset($args[0]);
 unset($args[1]);
 unset($args[2]);
 unset($args[3]);
 $reason=implode(" ", $args);
 $this->tbanip->set("$ip",$ip);
 $this->tbanip->set("sender_{$ip}",$ip);
 $this->tbanip->set("reason_{$ip}",$reason);
 $this->tbanip->set("{$ip}_time",$t);
 $this->getServer()->broadCastMessage("§l§cАдминистратор §a§n{$sender->getName()}§r§l§c забанил игрока §n§a{$pn}§r§l§c на §n§a§l{$d} дней {$h} часов {$m} минут§r§l§b - {$reason}");
 foreach($this->getServer()->getOnlinePlayers() as $player){
if($player->getAddress() === $ip){
$player->close(" ","§l§cАдминистратор §n§a{$sender->getName()}§r§c§l забанил вас - §l§n§b{$reason}. §r§l§cДо конца бана §e{$d}§c дней §e{$h}§c часов §e{$m}§c минут §e{$s}§c секунд.");
   }
  }
 $this->tbanip->save();
 }else{
 $sender->sendMessage("Игрок уже забанен");
 }
 }else{
 $sender->sendMessage("Вы не можете забанить данного игрока");
 }
  }else{
 $sender->sendMessage("Игрок не в сети");
 }
 }else{
$sender->sendMessage("Неверено ввели время");
 }
 }else{
 $sender->sendMessage("Неверно ввели команду");
 }
}
if(strtolower($cmd->getName())=='unbanip'){
       if(count($args)>=2){
 $pn=$args[0];
 unset($args[0]);
 $reason=implode(" ",$args);
  if($this->player->exists(strtolower($pn)."_ip")){
  $ip=$this->player->get(strtolower($pn)."_ip");
          if($this->banip->exists($ip) and !$this->tbanip->exists($ip)){
     $this->bannedip->remove("sender_{$ip}");
     $this->banip->remove("reason_{$ip}");
     $this->banip->remove($ip);
     $this->getServer()->broadCastMessage("§l§cАдминистратор §n§a".$sender->getName()."§r§c§l разбанил игрока §a§l§n".$pn."§r§c§l - §b".$reason);
     $this->banip->save();
    }elseif(!$this->banip->exists($ip) and $this->tbanip->exists($ip)){
        $this->tbanip->remove("{$ip}_time");
        $this->tbanip->remove("sender_{$ip}");
        $this->tbanip->remove("reason_{$ip}");
        $this->tbanip->remove($ip);
     $this->tbanip->save();
           $this->getServer()->broadCastMessage("§l§cАдминистратор §n§a".$sender->getName()."§r§c§l разбанил игрока §a§l§n".$pn."§r§c§l - §b".$reason);
        }elseif($this->ban->exists(strtolower($pn)) or $this->tban->exists(strtolower($pn))){
        $sender->sendMessage("Данный игрок забанен,но не по айпи.Чтобы разбанить его используйте /unban <игрок> <причина>");
        }else{
        $sender->sendMessage("Данный игрок не забанен");
        }
        }else{
        $sender->sendMessage("Данный игрок не найден");
        }
    }else{
        $sender->sendMessage("Используйте /unbanip <игрок> <причина>");
        }
   }
if(strtolower($cmd->getName()) =='ban'){
 if(count($args)>=2){
 $pl=$this->getServer()->getPlayer($args[0]);
 if($pl!=null){
   if(!$pl->hasPermission("antiban.use")){
   $pn=$pl->getName();
   $ip=$pl->getAddress();
  if(!$this->ban->exists(strtolower($pn)) and !$this->tban->exists(strtolower($pn)) and !$this->banip->exists($ip) and !$this->tbanip->exists($ip)){
unset($args[0]);
$reason=implode(" ",$args);
$this->ban->set(strtolower($pn), $pn);
$this->ban->set("sender_".strtolower($pn), $sender->getName());
$this->ban->set("reason_".strtolower($pn), $reason);
$this->getServer()->broadCastMessage("§l§cАдминистратор §a§n".$sender->getName()."§r§l§c забанил игрока §l§a".$pn."§r§l§c по причине: §l§b".$reason);
$pl->close(" ","§l§cВас забанил администратор §a§n".$sender->getName()."§r§c§l по причине: §b".$reason);
$this->ban->save();
}else{
$sender->sendMessage("Игрок уже забанен");
}
}else{
$sender->sendMessage("У игрока есть иммунитет к банам");
}
}else{
$sender->sendMessage("Игрок не в сети");
}
}else{
$sender->sendMessage("Неправильно ввели команду");
}
}
if(strtolower($cmd->getName()) =='tban'){
if(count($args)>=5){
if(is_numeric($args[1]) and ($args[1]>=0 and $args[1]<=60) and is_numeric($args[2]) and ($args[2]>=0 and $args[2]<=24) and is_numeric($args[3]) and ($args[3]>=0 and $args[3]<=100)){
 $pl=$this->getServer()->getPlayer($args[0]);
 if($pl!=null){
 if(!$pl->hasPermission("antitban.use")){
 $pn=$pl->getName();
 $ip=$pl->getAddress();
  if(!$this->tban->exists(strtolower($pn)) and !$this->ban->exists(strtolower($pn)) and !$this->banip->exists($ip) and !$this->tbanip->exists($ip)){
$m=$args[1];
$h=$args[2];
$d=$args[3];
$t=time()+$m*60+$h*3600+$d*86400;
unset($args[0]);
unset($args[1]);
unset($args[2]);
unset($args[3]);
$reason=implode(" ",$args);
$this->tban->set(strtolower($pn), $pn);
$this->tban->set("sender_".strtolower($pn), $sender->getName());
$this->tban->set("reason_".strtolower($pn), $reason);
$this->tban->set(strtolower($pn)."_time",$t);
$this->getServer()->broadCastMessage("§l§cАдминистратор §n§a".$sender->getName()."§r§l§c забанил игрока §a§n".$pn."§r§l§c  на §a".$d."§c дней §a".$h."§c часов §a".$m."§c минут. §bПричине: ".$reason);
$pl->close(" ","§l§cВас забанил администратор §n§a".$sender->getName()."§r§l§c на §a".$d."§c дней §a".$h."§c часов §a".$m."§c минут. §bПричине: ".$reason);
$this->tban->save();
}else{
$sender->sendMessage("Игрок уже забанен");
}
}else{
$sender->sendMessage("У игрока есть иммунитет");
}
}else{
$sender->sendMessage("Игрок не в сети");
}
}else{
$sender->sendMessage("Неверно ввели время");
}
}else{
$sender->sendMessage("Неверно ввели команду");
}
}
if(strtolower($cmd->getName())=='unban'){
    if(count($args)>=2){
 $pn=$args[0];
 unset($args[0]);
 $reason=implode(" ",$args);
 if($this->player->exists(strtolower($pn)."_ip")){
 $ip=$this->player->get(strtolower($pn)."_ip");
          if($this->ban->exists(strtolower($pn)) and !$this->tban->exists(strtolower($pn))){
     $this->ban->remove("sender_".strtolower($pn));
     $this->ban->remove("reason_".strtolower($pn));
     $this->ban->remove(strtolower($pn));
     $this->getServer()->broadCastMessage("§c§lАдминистратор §a§n".$sender->getName()."§r§l§c разбанил игрока §a§n".$pn."§r§c§l. §bПричина:".$reason);
     $this->ban->save();
         }elseif(!$this->ban->exists(strtolower($pn)) and $this->tban->exists(strtolower($pn))){
        $this->tban->remove("sender_".strtolower($pn));
        $this->tban->remove("reason_".strtolower($pn));
        $this->tban->remove(strtolower($pn));
        $this->tban->remove(strtolower($pn)."_time");
           $this->getServer()->broadCastMessage("§c§lАдминистратор §a§n".$sender->getName()."§r§l§c разбанил игрока §a§n".$pn."§r§c§l. §bПричина:".$reason);
           $this->tban->save();
           }elseif($this->banip->exists($ip) or $this->tbanip->exists($ip)){
           $sender->sendMessage("Игрок уже забанен,но по айпи");
           }else{
           $sender->sendMessage("Игрок не забанен");
           }
           }else{
           $sender->sendMessage("Игрок не найден");
           }
        }else{
        $sender->sendMessage("Неверно ввели команду");
        }
    }
      if(strtolower($cmd->getName()) =='mute'){
 if(count($args)>=2){
 $pl=$this->getServer()->getPlayer($args[0]);
  if($pl!=null){
   if(!$pl->hasPermission("antimute.use")){
  $pn=$pl->getName();
  if(!$this->tmute->exists(strtolower($pn)) and !$this->mute->exists(strtolower($pn))){
unset($args[0]);
$this->mute->set(strtolower($pn), $pn);
$this->getServer()->broadCastMessage("§r§cАдминистратор §m§e".$sender->getName()." §r§cвыдал мут игроку §m§e".$pn." §r§cпо причине: §m§e".implode(" ",$args));
$pl->sendMessage("§r§cАдминистратор §m§e".$sender->getName()." §r§cвыдал вам мут по причине: §m§e".implode(" ",$args));
$this->mute->save();
}else{
$sender->sendMessage("У игрока уже есть мут");
}
}else{
$sender->sendMessage("У игрока есть иммунитет");
}
}else{
    $sender->sendMessage("Игрок не в сети");
    }
}else{
$sender->sendMessage("Неверно ввели команду");
}
}
if(strtolower($cmd->getName())=='tmute'){
       if(count($args)>=5){
if(is_numeric($args[1]) and ($args[1]>=0 and $args[1]<=60) and is_numeric($args[2]) and ($args[2]>=0 and $args[2]<=24) and is_numeric($args[3]) and ($args[3]>=0 and $args[3]<=100)){
 $pl=$this->getServer()->getPlayer($args[0]);
 if($pl!=null){
 if(!$pl->hasPermission("antitmute.use")){
 $pn=$pl->getName();
  if(!$this->tmute->exists(strtolower($pn)) and !$this->mute->exists(strtolower($pn))){
$m=$args[1];
$h=$args[2];
$d=$args[3];
$t=time()+$args[1]*60+$args[2]*3600+$args[3]*86400;
unset($args[0]);
unset($args[1]);
unset($args[2]);
unset($args[3]);
$this->tmute->set(strtolower($pn), $pn);
$this->tmute->set(strtolower($pn)."_time",$t);
$this->getServer()->broadCastMessage("§r§cАдминистратор §m§e".$sender->getName()." §r§cдал мут игроку §m§e".$pn." по причине: ".implode(" ",$args)." На ".$d." дней ".$h." часов ".$m." минут");
$pl->sendMessage("§r§cАдминистратор §m§e".$sender->getName()." §r§cвыдал вам мут на §m§e".$d." §r§cдней §m§e".$h." §r§cчасов §m§e".$m." §r§cминут по причине: §m§e".implode(" ",$args));
$this->tmute->save();
}else{
    $sender->sendMessage("У игрока уже есть мут");
}
}else{
$sender->sendMessage("У игрока есть иммунитет у игрока");
}
}else{
    $sender->sendMessage("Игрок не в сети");
}
}else{
$sender->sendMessage("Неверное время мута");
}
}else{
$sender->sendMessage("Неверно ввели команду");
}
}
if(strtolower($cmd->getName())=='unmute'){
    if(count($args)>=2){
     $arg=$args[0];
     unset($args[0]);
          if($this->tmute->exists(strtolower($arg)) and !$this->mute->exists(strtolower($arg))){
     $this->tmute->remove(strtolower($arg));
     $this->tmute->remove(strtolower($arg)."_time");
     $this->getServer()->broadCastMessage("§l§cАдминистратор §a§n".$sender->getName()."§r§l§c размутил игрока §a§n".$arg."§r§c§l по причине: §b".implode(" ",$args));
     $this->tmute->save();
        }elseif(!$this->tmute->exists(strtolower($arg)) and $this->mute->exists(strtolower($arg))){
     $this->mute->remove(strtolower($arg));
     $this->getServer()->broadCastMessage("§l§cАдминистратор §a§n".$sender->getName()."§r§l§c размутил игрока §a§n".$arg."§r§c§l по причине: §b".implode(" ",$args));
     $this->mute->save();
            }else{
        $sender->sendMessage("У игрока нету мута");
    }
        }else{
        $sender->sendMessage("Неверно ввели команду");
        }
}
return true;
}
public function onLogin(PlayerPreLoginEvent $e){
$p=$e->getPlayer();
$pn=strtolower($p->getName());
$ip=$p->getAddress();
if(!$this->player->exists($pn."_ip")){
$this->player->set($pn."_ip",$ip);
$this->player->save();
}
if($this->tban->exists($pn)){
$t=$this->tban->get($pn."_time");
if($t > time()){
$remainingTime = $t - time();
$day = floor($remainingTime / 86400);
$hourSeconds = $remainingTime % 86400;
$hour = floor($hourSeconds / 3600);
$minuteSec = $hourSeconds % 3600;
$minute = floor($minuteSec / 60);
$remainingSec = $minuteSec % 60;                $second=ceil($remainingSec);
$p->close(" ","§c§lВы были забанены администратором §a§n{$this->tban->get("sender_{$pn}")}§r§c§l. §bПричина:{$this->tban->get("reason_{$pn}")}. §cДо конца бана:§a{$day}§c дней §a{$hour}§c часов §a{$minute}§c минут");
$e->setCancelled();
}else{
    $this->tban->remove("sender_".$pn);
    $this->tban->remove("reason_".$pn);
    $this->tban->remove($pn);
    $this->tban->remove($pn."_time");
    $this->tban->save();
}
}
if($this->ban->exists($pn)){
$p->close(" ","§c§lВы были забанены администратором §a§n{$this->ban->get("sender_{$pn}")}§r§c§l. §bПричина:{$this->ban->get("reason_{$pn}")}");
$e->setCancelled(true);
}
if($this->tbanip->exists($ip)){
    $t=$this->tban->get("{$pn}_time");
if($t > time()){
$remainingTime = $t - time();
$day = floor($remainingTime / 86400);
$hourSeconds = $remainingTime % 86400;
$hour = floor($hourSeconds / 3600);
$minuteSec = $hourSeconds % 3600;
$minute = floor($minuteSec / 60);
$remainingSec = $minuteSec % 60;                $second=ceil($remainingSec);
$p->close(" ","§l§cВы были забанены администратором §a§n{$this->tbanip->get("sender_{$ip}")}§r§c§l , §bпричина:{$this->tbanip->get("reason_{$ip}")}§r§c§l. До конца бана:§a{$day}§c дней §a{$hour}§c часов §a{$minute}§c минут");
$e->setCancelled();
}else{
    $this->tbanip->remove("{$ip}_time");
    $this->tbanip->remove("sender_{$ip}");
    $this->tbanip->remove("reason_{$ip}");
    $this->tbanip->remove($ip);
    $this->tbanip->save();
}
}
if($this->banip->exists($ip)){
$p->close(" ","§l§cВы были забанены администратором §a§n{$this->banip->get("sender_{$ip}")}§r§c§l. §bПричина:{$this->banip->get("reason_{$ip}")}");
$e->setCancelled();
}
}
public function onChat(PlayerChatEvent $e){
    $p=$e->getPlayer();
    $pn=strtolower($p->getName());
if($this->tmute->exists($pn) and !$this->mute->exists($pn)){
    $t=$this->tmute->get($pn."_time");
if($t > time()){
$leftTime = $t - time();
$day = floor($leftTime / 86400);
$hourSeconds = $leftTime % 86400;
$hour = floor($hourSeconds / 3600);
$minuteSec = $hourSeconds % 3600;
$minute = floor($minuteSec / 60);
$leftSec = $minuteSec % 60;
$second=ceil($leftSec);
$p->sendMessage("§c§lВы в муте! До конца мута §a{$day}§c дней §a{$hour}§c часов §a{$minute}§c минут");
$e->setCancelled();
}else{
    $this->tmute->remove($pn);
    $this->tmute->remove($pn."_time");
    $this->tmute->save();
}
    }
    if($this->mute->exists($pn) and !$this->tmute->exists($pn)){
        $p->sendMessage("§c§lВы в муте!");
        $e->setCancelled();
        }
        }
}
?>
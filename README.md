# BanSystem
ENG:Ban system for MCPE server working on [PMMP](https://pmmp.readthedocs.io/en/rtfd/installation.html) 3.11.x+ (3.14.x supported) core. 
# Getting start
_We will work in ubuntu 18.04_
## 1. installing PocketMine-MP core
Open the terminal and write:
```
apt update
apt upgrade
```
Confirm installation by entering
> y
```
apt install screen
```
Confirm installation again
> y  
_______
We will use the repository `PMMPLinuxInstaller` `by w4x51m` for an easy installation  
- w4x51m's materials that we will use  
    - repository [PMMPLinuxInstaller](github.com/w4x51m/PMMPLinuxInstaller)  
Writing in console:  
```
git clone https://github.com/w4x51m/PMMPLinuxInstaller.git
```
After go to the install file directory  
```
cd PMMPLinuxInstaller
```  
Give rights install file and start install process  
```
chmod +x installer.sh
./install.sh
```
Wait...  
Write  
```
screen -S "server name without quotes"
screen -r "what you entered here"
./start.sh
```
Complete the setup wizard  
- screen mini tutorial  
    - So that exit server screen press __lctrl+a then lctrl+d__ the server will continue working
    - So that back to server screen , type __screen -r "what you entered here"__
> screen -S "server name without quotes"    
# 2.Installing ban system
go to __plugin__ directory and install __Dev Tools__
```
cd plugins
wget https://poggit.pmmp.io/r/74395/PocketMine-DevTools.phar
```
And install __ban system__
```
git clone https://github.com/kulisworm/BanSystem.git
```
Start/go to server screen
> screen -r "what you entered here"
And write
```
reload
```
After reloading , plugin installed , thanks!
donate <3: QIWI: +7(920)519-91 33
[DonationAlerts](https://www.donationalerts.com/r/iliashestakov315)

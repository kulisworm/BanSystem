# BanSystem
ENG:Ban system for MCPE server working on [PMMP](https://pmmp.readthedocs.io/en/rtfd/installation.html) 3.11.x+ core. 
# Getting start
_We will work in ubuntu 18.04_
## 1. installing PocketMine-MP core
Open the terminal and write:
```
apt update
apt upgrade
```
Confirm installation by entering
- y
```
apt install screen
```
Confirm installation be entering
- y
We will use the repository `PMMPLinuxInstaller` `by w4x51m` for an easy installation
- w4x51m's materials that we will use
    - repository `PMMPLinuxInstaller`(github.com/w4x51m/PMMPLinuxInstaller)
Writing in console:
`git clone https://github.com/w4x51m/PMMPLinuxInstaller.git`
After go to the install file directory
`cd PMMPLinuxInstaller`
Give rights install file and start install process
```
chmod +x installer.sh
./install.sh
```
Wait...
Write
```
screen -S "server name without quotes"
./start.sh
```
Complete the setup wizard
- screen mini tutorial
    - So that exit server screen press `lctrl+a then lctrl+d `the server will continue working
    - So that back to server screen , type `screen -r "what you entered here"`
> screen -S "server name without quotes"    

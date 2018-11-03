# Raspberry Pi Slideshow

Once I needed to do a slideshow on the Raspberry Pi with the following requirements

- free
- transitions (effects)
- some options (speed, delay, ...)

But couldn't find any good solution...

So I decided to use a website with php to display the slideshow.

There are 2 main drawbacks with this approach:

- some setup/installation is required (setting up stuff for the website)
  - however you can modify the setup and e.g. not use nginx and use only php
- because the browser is used to display the website there are some performance penalties
  - some transitions are not smooth on the Raspberry Pi (I used the 3rd Version)
    - *but* because this is only a Website it can be used with any device that can display a browser (and run php)



# The Idea

The idea is to use jQuery and the jQuery cycle plugin to display the slideshow in the browser.  
We use php to look for images in the `images` folder and reference the images in the generated website
or we use just image urls. 

Further we need to disable any screensaver else our slideshow will turn black after some time.


The last steps are for a *advanced* setup where we use `ssh` to start/stop the slideshow on the Raspberry Pi
and also turn on/off the connected Display.  


If you know how to do these steps you can just grab the code and deploy it.

# Setup/Installation

*This was tested on the Raspberry Pi 3 B with Raspian Stretch (9)*

*Also note that I could not test this steps because my Raspiberry now runs the slideshow 24/7*


*Make sure the Raspberry is connected to your network*

If you read *execute* then you need to open a terminal and run the described commands


### Install Raspian Stretch

There are many instructions how to install Raspian, I just want to mention that [https://www.balena.io/etcher/](https://www.balena.io/etcher/)
is an easy tool to flash Raspian to the SD card.

### Disable screensaver

From https://www.elektronik-kompendium.de/sites/raspberry-pi/2107011.htm

execute
```bash
nano .config/lxsession/LXDE-pi/autostart
```

insert the lines

```bash
@xset s noblank
@xset s off
@xset -dpms
```

reboot and execute

```bash
xset q
```

the output should be something like

```bash
Screen Saver:
prefer blanking:  no ...
...
DPMS is Disabled
```

### Install chromium (already present on Respian Stretch)

You can use another browser for this but then you need to adjust the scripts (described later) 

execute

```bash
sudo apt-get install chromium-browser
```

Maker sure you can run the browser by executing

```bash
chromium-browser
```

so we can later open the browser via scripts.


### Install nginx

From https://willy-tech.de/nginx-auf-raspberry-pi-installieren-einrichten/
and https://electrodrome.net/debian-9-stretch-php-7-0-und-nginx-auf-dem-raspberry-pi-installieren-und-einrichten/

```bash
sudo apt-get install nginx
```

start nginx

```bash
/etc/init.d/nginx start
```

The normal directory for the webserver is `/var/www/html` but we can use ony other directory if we want.

Execute

```bash
sudo nano /etc/nginx/sites-available/default
```

and find the `server {` part and make sure it looks like this (lines starting with # can be ignored)

```text
...

server {
        listen 80 default_server;
        listen [::]:80 default_server;

        # here you can specify the root path for the webserver (where we place the slideshow)
        root /var/www/html;

        # Add index.php to the list if you are using PHP
        index index.html index.htm index.nginx-debian.html index.php;

        server_name _;

        location / {
                # First attempt to serve request as file, then
                # as directory, then fall back to displaying a 404.
                try_files $uri $uri/ =404;
        }

        # pass PHP scripts to FastCGI server
        #
        location ~ \.php$ {
                include snippets/fastcgi-php.conf;

                # With php-fpm (or other unix sockets):
                # if you don't use php7 you need to change the path here!
                fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
                # With php-cgi (or other tcp sockets):
                # fastcgi_pass 127.0.0.1:9000;
        }

        # deny access to .htaccess files, if Apache's document root
        # concurs with nginx's one
        #
        location ~ /\.ht {
                deny all;
        }
}
...
```

### Install php 7

```bash
sudo apt-get install php7.0 php7.0-cli php7.0-cgi php7.0-common php7.0-curl php7.0-dev php7.0-fpm php7.0-gd php7.0-mcrypt php7.0-json php7.0-tidy php7.0-sqlite3 php7.0-opcache php7.0-intl php7.0-zip php7.0-xml php7.0-mbstring php-pear
```

then restart nginx (to load the new config)

```bash
/etc/init.d/nginx restart
```

### Copy the slideshow project

clone or copy the this repo in `/var/www/html` or where you specified your webserver root directory


### Specify the images

The 1st source for the images is in the file `index.php` in the variable `$imgUrls`.  
If you want to include images from the web you can add entries to the array e.g.

```php
$imgUrls = [
    'http://path-to-my-image.png',
    'http://path-to-my-2nd-image.png',
]
```

If there are no urls specified then only the 2nd (local) images are taken.


The 2nd source for the images is the local `images/` folder in the repo.  
All Images placed in this server (and not starting with a `.`) are included in the slideshow.
The image type should not matter as long as the browser can display this image type.



**Note** that the images are stretched to the full display size.  
To change this you can play around with the css in `css/fixes.css`

To change the color of the *background* behind the images open `index.php` and find the part `body {`
there you can change the line `background-color: rgb(83, 87, 96);` to any other color

### Test

Now open chromium and go to the url `http://localhost` and the slideshow should start

### Options

To see all possible transitions open the url `http://localhost/all.php`


To change the transition choose the ones you like from `http://localhost/all.php` and open the file `index.php`
and find the line `fx: 'uncover'`

Then replace the `uncover` with your transition names from `http://localhost/all.php` and separate them by comma

e.g. if I want scrollUp, scrollHorz and slideX the line should be

`fx: 'scrollUp,scrollHorz,slideX'`

The option `speed: 2000` specifies the speed of the transition

The option `timeout: 10000` specifies the delay before the next image is displayed

You can also set `random: 0` to `1` so that the next images is chosen randomly

For more options see [cycleOptions.md](./cycleOptions.md) or http://jquery.malsup.com/cycle/options.html


### Advanced setup

If you have no access to the raspberry once after the setup, it might be a good solution to setup remote access.

This is also a good solution if you want to start/stop the slideshow from another pc.

The other pc must have ssh installed!

Also make sure the Pi has a static ip (local ip).


#### Setup ssh

execute

```bash
sudo raspi-config
```

choose `5 Interfacing Options` then `SSH` and enable it.

To get the ip from the pi execute

```bash
ifconfig
```

If the Pi is connected via LAN look for `eth0:` and `inet 192.168.X.X` is the ip
If the Pi is connected via WLAN look for `wlan0:` and `inet 192.168.X.X` is the ip

Then switch to another pc and try to ssh (`ssh pi@192.168.X.X`) to the pi with the credentials

```text
User: pi
Password: raspberry
```

If this is not working open the file `/etc/ssh/sshd_config` and search for `PermitRootLogin` set it to `yes`

Then restart the ssh deamon (execute) 

```bash
/etc/init.d/ssh restart
```

#### Setup scripts

If ssh is working you can copy the scripts in this repo in `scripts/respberry/` to the desktop (`/home/pi/Desktop/`)


Before copying make sure the ip in the scripts at `scripts/client/` matches the ip of the Pi.
So it should be 

```bash
ssh pi@192.168.X.X 'bash /home/pi/Desktop/start_slides.sh'
```

in `start_slideshow.sh` and 

```bash
ssh pi@192.168.X.X 'bash /home/pi/Desktop/stop_slides.sh'
```

in `stop_slideshow.sh`.

Also copy the scripts in this repo in `scripts/client/` to any client that needs to start/stop the slideshow, the directory is not important 

For all scripts make sure they have the `x` flag set so you can execute them.

To check open the terminal and `cd` to the locations of the scritps and execute
``bash
ls -la
``

then in front of the scripts is should look like this

```bash
-rwxr-xr-x  2 pi pi 4096 Sep  7 18:53 start.sh

#not

-rw-r--r--  2 pi pi 4096 Sep  7 18:53 start.sh

#(note the 3 missing x)
``` 

to change this execute for every script where the `x` flag is missing

```bash
chmod +x start.sh
```

**Make sure** you cannected to the Pi at least once via ssh before because there might be a welcome message displayed from ssh or fingerprint stuff?

If you now run the script `start_slideshow.sh` then you need to login and the slideshow should start

To run the script you can open a terminal and execute

```bash
bash start_slideshow.sh
```

or you can setup double click on the file on the desktop,

see https://askubuntu.com/questions/138908/how-to-execute-a-script-just-by-double-clicking-like-exe-files-in-windows


#### Advanced^2 setup

If you don't want to type in the ssh password every time you can create a ssh key and use this for login

this needs to be executed on every pc that should have access to the Pi without password. 

Fist check if you already have a ssh-key, execute 

```bash
ls -al ~/.ssh
```

if this is empty you have no old keys.

If you have old keys make sure to choose a different name for the key pair (when executing `ssh-keygen`)

Then execute

```bash
ssh-keygen -t rsa -b 4096 -C "some comment from you e.g. for pi"
```

If you don't type in a passphrase then you don't need to enter anything when connecting to the Pi.


The key pair is now under `~/.ssh/id_rsa` (assuming this is your first key pair and you don't changed the location)  
(where `~/` is your home directory e.g. `/Users/pi`)

Then execute

```bash
ssh-copy-id -i ~/.ssh/id_rsa pi@192.168.X.X
```

Then check if ssh is working without a password, execute


```bash
ssh pi@192.168.X.X
```


If not, see https://www.ssh.com/ssh/copy-id



**It also might be a good idea to disable ssh root login via password**  
Then only pcs with a ssh key will be able to login into the Pi via ssh.

Open `/etc/ssh/sshd_config` on the Pi and change `PermitRootLogin` to `No`.



### Hints

One can enable (1) or disable (0) the Raspberry Pi HDMI port via

```bash
vcgencmd display_power 0
vcgencmd display_power 1
``` 

### Projects used

- jquery
- jquery cycle
  - options: see [cycleOptions.md](./cycleOptions.md) or http://jquery.malsup.com/cycle/options.html
- https://github.com/akv2/MaxImage ... some css


default images:

- https://pixabay.com/de/pier-anlegestelle-ozean-meer-569314/
- https://pixabay.com/de/winter-schnee-sonnenaufgang-2080070/
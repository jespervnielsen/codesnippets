//use your windows ubuntu, then go to correct drive/folder
cd /mnt/d/

//ftp download
wget -r --user="username" --password="password" ftp://example.com/ -m
//-m is for --mirror, to mirror the entire ftp location

//rsync - by ftp, or ssh. - requires rsync in install on remote server - usefull to only download files that not exists local
//-e "ssh for ssh. 
// @link https://linux.die.net/man/1/rsync
// --delete is for local delete, if there is files on local, that exists, but not on server.
rsync -r -a -v -e "ssh" --delete username@x.x.x.x:/var/www/domainpath ./dump
//ftp, and with a bandwithlimit on 1500kb, since a lot of hosts has trouble with connection, if too high
rsync -r -a -v --bwlimit=1500 --progress username@x.x.x.x:/var/www/domainpath ./dump

//ssh
scp -r username@x.x.x.x:/var/www/domainpath .
(. for current folder)

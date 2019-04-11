//use your windows ubuntu, then go to correct drive/folder
cd /mnt/d/

//ftp download
wget -r --user="username" --password="password" ftp://example.com/ -m
//-m is for --mirror, to mirror the entire ftp location

//rsync - by ftp, or ssh. - requires rsync in install on remote server - usefull to only download files that not exists local
rsync -r -a -v -e "ssh" --delete username@x.x.x.x:/var/www/domainpath /dump

//ssh
scp -r username@x.x.x.x:/var/www/domainpath .
(. for current folder)

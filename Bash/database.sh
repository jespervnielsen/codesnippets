
#Export db, when SSH is avaible
mysqldump -u USERNAME -pPASSWORD DATABASENAME | gzip > ./db-`date '+%Y-%m-%d-%H.%M.%S'`.sql.gz

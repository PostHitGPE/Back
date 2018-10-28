#!/bin/sh
now="$(date +'%d%m%Y%H%M_%S')"
filename="dbbackup$now".sql
backupfolder="/home/ubuntu/workspace/app/dbsave"
fullpathbackupfile="$backupfolder/$filename"
logfile="$backupfolder/"backuplog"$(date +'%Y_%m')".txt

echo "mysqldump started at $(date +'%d-%m-%Y %H:%M:%S')" >> "$logfile"

# cmd => dump la database dans $fullpathbackupfile soit "dbbackup + $(date +'%d%m%Y%H%M_%S').sql"
mysqldump --user=$C9_USER c9 > "$fullpathbackupfile"
echo "mysqldump finished at $(date +'%d-%m-%Y %H:%M:%S')" >> "$logfile"

# cmd => attribue les droits rwx proprio, rx group et rien others
chmod 755 "$fullpathbackupfile"
chmod 755 "$logfile"
chown ubuntu "$fullpathbackupfile"
chown ubuntu "$logfile"
echo "file permission changed" >> "$logfile"

# cmd trouve et delete tout les fichier commençant par dbbackup et se
# terminant par une date de modification de 8 jours antérieure au jour j. 
find "$backupfolder" -name dbbackup* -mtime +8 -exec /home/ubuntu/workspace/app/svn delete {} +
echo "old files deleted" >> "$logfile"
echo "operation finished at $(date +'%d-%m-%Y %H:%M:%S')" >> "$logfile"
echo "*" >> "$logfile"

# svn operations: commit des deletes, add du nouveau backup et commit du add...
svn commit "$backupfolder" -m "removed old backups"
svn add "$backupfolder"/*
svn commit "$backupfolder" -m "backupDBcommit$now"
exit 0


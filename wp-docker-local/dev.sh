#!/bin/sh

# while test $# -gt 0
# do
#     case "$1" in
#         -s|--simple)
# 			COMPOSER=false
# 			DBUPDATE=false
# 			;;
#         -nc|--no-composer)
# 			COMPOSER=false
# 			;;
#         -nd|--no-update-db)
# 			DBUPDATE=false
# 			;;
#         --*)
# 			echo "bad option $1"
# 			;;
#         *)
# 			APP=$1
# 			;;
#     esac
#     shift
# done

function runCommand() {
    for d in ./www/*; do (cd "$d" && $1); done
}

if [ "$1" = "project" ]; then
    if [ "$2" = "up" ]; then
        docker compose up -d
        runCommand "docker compose up -d"
    fi

    if [ "$2" = "down" ]; then
        runCommand "docker compose down"
        docker compose down
    fi
fi
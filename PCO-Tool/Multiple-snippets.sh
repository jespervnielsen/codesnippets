#Clear Cache all subdomains, on site
pco -e prod purge <app> --subdomain="*" --regex="*"

#Clear multiple individual subdomains. <app is the top domain eg: [example].com and not the app name, as you might expect
pco -e prod purge <app> --subdomain="aeroe" --regex="robots.txt" && \
pco -e prod purge <app> --subdomain="alleroed" --regex="robots.txt" && \
pco -e prod purge <app>--subdomain="assens" --regex="robots.txt" && \

#Check task wp_cron logs
pco logs <app> task wp_cron -e prod

#Check error log
pco logs <app> error -e prod

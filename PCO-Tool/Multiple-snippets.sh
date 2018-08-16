#Clear all subdomains, on site
pco -e prod purge <app> --subdomain="*" --regex="*"

Clear multiple individual subdomains. <app is the top domain eg: [example].com and not the app name, as you might expect
pco -e prod purge <app> --subdomain="aeroe" --regex="robots.txt" && \
pco -e prod purge <app> --subdomain="alleroed" --regex="robots.txt" && \
pco -e prod purge <app>--subdomain="assens" --regex="robots.txt" && \

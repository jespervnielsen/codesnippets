#Clear all subdomains, on site
pco -e prod purge <app> --subdomain="*" --regex="*"

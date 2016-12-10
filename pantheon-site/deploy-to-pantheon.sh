#!/bin/bash

# Variables
txtgrn=$(tput setaf 2) # Green
txtrst=$(tput sgr0) # Text reset.

COMMIT_MESSAGE="Deploy by $(git config --get user.name), $(git rev-parse --abbrev-ref HEAD) ($(git rev-parse --short HEAD))"
PANTHEON_GIT_URL="ssh://codeserver.dev.fb451424-0a65-4dc1-acfa-71476aeeb0bd@codeserver.dev.fb451424-0a65-4dc1-acfa-71476aeeb0bd.drush.in:2222/~/repository.git"

# If the Pantheon git directory does not exist.
if [ ! -d "rhm-site" ]
then
	# Clone the Pantheon repoa
	echo -e "\n${txtgrn}Cloning Pantheon repository ${txtrst}"
	git clone $PANTHEON_GIT_URL "rhm-site"
else
	echo -e "\n${txtgrn}Pull latest from Pantheon ${txtrst}"
	git -C "rhm-site" pull
fi

echo -e "\n${txtgrn}Applying new changes to Pantheon repo ${txtrst}"
cd ..
rsync -a --delete "build/" "pantheon-site/rhm-site/" --exclude .git --exclude .gitignore --exclude .env --exclude uploads --exclude wp-config.local.php --exclude *.sass-cache

# Move into the pantheon repo to apply changes.
cd pantheon-site/rhm-site
git add -A
git commit -m"$COMMIT_MESSAGE"

echo -e "\n${txtgrn}Pushing the master branch to Pantheon ${txtrst}"
git push --force

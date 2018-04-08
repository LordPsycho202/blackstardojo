#! /bin/bash

DEPLOY_DIR=""
COMMIT=""
BUILD=""

while [[ $# -gt 0 ]]
do
	key="$1"
	case $key in
		-c | --commit)
		COMMIT="$2"
		shift
		;;
		-b | --build)
		BUILD="$2"
		shift
		;;
		-d | --deploy)
		DEPLOY_DIR="$2"
		shift
		;;
		*)
		echo "Invalid Option $key"
		;;
	esac
shift
done

if [ -z "$COMMIT" ] || [ -z "$BUILD" ] || [ -z "$DEPLOY_DIR" ]
then
	echo "You must specify a --commit, --build, and --deploy"
	exit 1;
fi

echo "Tarring up old site for archiving"
tar -cvzf blackstar-backup-$COMMIT-$BUILD.tar.gz $DEPLOY_DIR

echo "Copying config file to new deployment"
/bin/cp -vrf $DEPLOY_DIR/config.php web/

echo "Copying new site files to deploy directory"
/bin/cp -vrf web/* $DEPLOY_DIR

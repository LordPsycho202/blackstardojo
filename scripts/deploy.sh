#! /bin/bash

CWD=$(pwd)
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

echo "Commit ID: $COMMIT"
echo "Build ID: $BUILD"
echo "Deploy Directory: $DEPLOY_DIR"
echo "Workspace: $CWD"
echo "Jenkins reported workspace: $WORKSPACE"

echo "Tarring up old site for archiving"
#sudo tar -cvzf $CWD/blackstar-backup-$COMMIT-$BUILD.tar.gz $DEPLOY_DIR

echo "Copying config file to new deployment"
#sudo /bin/cp -vrf $DEPLOY_DIR/config.php $CWD/web/

echo "Copying new site files to deploy directory"
#sudo /bin/cp -vrf $CWD/web/* $DEPLOY_DIR

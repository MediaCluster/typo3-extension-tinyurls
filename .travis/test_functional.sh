#!/usr/bin/env bash

set -ev

echo "Funning functional tests..."

phpenv config-rm xdebug.ini

if [ ${TYPO3_VERSION} = "typo3/minimal=dev-master" ]; then
    composer config minimum-stability dev && composer require ${TYPO3_VERSION} --prefer-stable --prefer-dist
else
    composer require ${TYPO3_VERSION}
fi

mkdir -p ".Build/Web/typo3temp/var/tests"

export typo3DatabaseName="typo3"
export typo3DatabaseHost="localhost"
export typo3DatabaseUsername="root"
export typo3DatabasePassword=""

find . -wholename '*Tests/Functional/*Test.php' ! -path "./.Build/*" | \
    parallel --gnu 'echo; echo "Running functional test suite {}"; .Build/bin/phpunit --colors -c .Build/vendor/nimut/testing-framework/res/Configuration/FunctionalTests.xml {}'

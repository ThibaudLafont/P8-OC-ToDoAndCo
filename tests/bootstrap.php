<?php
if (isset($_ENV['BOOTSTRAP_LOAD_DB_TEST'])) {
    // Clear cache
    passthru(
        'php "bin/console" cache:clear --env=test --no-warmup'
    );

    // Drop TestDb
    passthru(
        'php "bin/console" doctrine:database:drop --env=test --force'
    );

    // Re-create TestDb
    passthru(
        'php "bin/console" doctrine:database:create --env=test'
    );

    // Create schema
    passthru(
        'php "bin/console" doctrine:schema:create --env=test'
    );

    // Load Fixtures
    passthru(
        'php "bin/console" doctrine:fixtures:load --env=test -q'
    );
}

require __DIR__.'/../vendor/autoload.php';

import { test } from 'node:test';
import assert from 'node:assert/strict';
import { mkdtemp, mkdir, readFile, writeFile, rm } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import { spawnSync } from 'node:child_process';

for (const fail of [false, true]) {
    test(`deployment ${fail ? 'stays in maintenance on failure' : 'reopens only after successful preparation'}`, async () => {
        const root = await mkdtemp(join(tmpdir(), 'deploy-remote-test-'));
        try {
            await mkdir(join(root, 'scripts/fonts'), { recursive: true });
            await mkdir(join(root, 'bin'));
            await writeFile(join(root, 'scripts/fonts/DejaVuSans.ttf'), '');
            await writeFile(
                join(root, 'scripts/deploy-remote.sh'),
                await readFile(new URL('../../scripts/deploy-remote.sh', import.meta.url))
            );
            for (const command of ['php', 'composer', 'npm', 'python3', 'chown', 'chmod']) {
                await writeFile(
                    join(root, 'bin', command),
                    `#!/bin/bash\nprintf '%s\\n' "${command} $*" >> "$DEPLOY_TEST_LOG"\n${
                        command === 'composer' && fail ? 'exit 1' : 'exit 0'
                    }\n`,
                    { mode: 0o755 }
                );
            }
            const log = join(root, 'commands.log');
            const result = spawnSync('bash', [join(root, 'scripts/deploy-remote.sh')], {
                env: { ...process.env, PATH: `${root}/bin:${process.env.PATH}`, DEPLOY_TEST_LOG: log },
                encoding: 'utf8',
            });
            const commands = (await readFile(log, 'utf8')).trim().split('\n');
            assert.equal(commands[0], 'php artisan down --render=errors::503 --retry=60');
            assert.equal(result.status, fail ? 1 : 0, result.stderr);
            if (fail) {
                assert.equal(commands.includes('php artisan up'), false);
            } else {
                assert.equal(commands.at(-1), 'php artisan up');
                assert.ok(commands.indexOf('php artisan optimize') < commands.length - 1);
                assert.ok(commands.includes('chmod -R ug+rwx storage bootstrap/cache'));
            }
        } finally {
            await rm(root, { recursive: true, force: true });
        }
    });
}

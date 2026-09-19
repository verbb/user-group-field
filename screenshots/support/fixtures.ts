import { readFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

import type { ScreenshotSetupContext } from '@verbb/craft-screenshots/types';

type UserGroupFieldFixture = {
    entryEditRoute: string;
};

const supportDir = dirname(fileURLToPath(import.meta.url));
const seedScript = readFileSync(join(supportDir, 'seed', 'seed-user-group-fields.php'), 'utf8');

/** Seed real Craft user groups and all three User Group Field display modes. */
export async function seedUserGroupFieldFixture(context: ScreenshotSetupContext): Promise<UserGroupFieldFixture> {
    const output = await context.runCraftScript(seedScript, { label: 'seed-user-group-field' });
    const fixture = JSON.parse(output.trim()) as UserGroupFieldFixture;

    if (!fixture.entryEditRoute) {
        throw new Error(`Invalid User Group Field fixture payload: ${output}`);
    }

    return fixture;
}

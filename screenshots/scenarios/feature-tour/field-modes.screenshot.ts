import { defineScreenshotScenario } from '@verbb/craft-screenshots/api';

import { seedUserGroupFieldFixture } from '../../support/fixtures';

let entryEditRoute = '/admin/entries';

export default defineScreenshotScenario({
    id: 'user-group-field-feature-tour-field-modes',
    output: 'feature-tour/user-group-field.png',
    route: () => entryEditRoute,
    viewport: { width: 1120, height: 800, deviceScaleFactor: 2 },
    async setup(context) {
        const fixture = await seedUserGroupFieldFixture(context);
        entryEditRoute = fixture.entryEditRoute;
    },
    waitFor: [
        { type: 'selector', selector: '#fields-primaryAudience-field', state: 'visible', timeout: 30000 },
        { type: 'selector', selector: '#fields-visibleToGroups-field', state: 'visible', timeout: 30000 },
        { type: 'selector', selector: '#fields-approvalGroup-field', state: 'visible', timeout: 30000 },
        { type: 'text', text: 'Content editors' },
        { type: 'text', text: 'Marketing team' },
        { type: 'text', text: 'Members' },
    ],
    preSteps: [{ type: 'wait', waitFor: { type: 'timeout', ms: 250 } }],
    target: {
        type: 'anchoredClip',
        selector: '#fields-primaryAudience-field',
        x: 0,
        y: -16,
        width: 694,
        height: 351,
    },
    caption: 'Dropdown, checkbox, and radio User Group fields using the project’s real Craft user groups.',
    intent: 'Show every genuine field mode together without recreating Craft controls outside the plugin.',
});

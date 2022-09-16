// @ts-check
const {test, expect} = require('@playwright/test');

test.beforeEach(async ({page}) => {
  /**
   * navigate to the login screen
   */
  await page.goto('/redaxo/index.php');

  /**
   * check if the login input is present
   * add username
   */
  await page.locator('input[id=rex-id-login-user]').fill('nightwatch_username');

  /**
   * check if the password input is present
   * add password
   */
  await page.locator('input[id=rex-id-login-password]').fill('nightwatch_password');
  await page.locator('input[id=rex-id-login-password]').press('Enter');

  /**
   * check if the session cookie is available

   /**
   * check if we are logged in to the backend
   */
  await expect(page).toHaveURL('/redaxo/index.php?page=structure');
});

test('Test Activity Log functionality', async ({page}) => {
  /**
   * navigate to the addon page
   */
  await page.locator('#rex-navi-page-activity-log').click();

  /**
   * there should be no logs
   */
  await expect(page.locator('table.rex-activity-table tr.table-no-results')).toBeVisible();

  /**
   * navigate to the settings page
   */
  await page.locator('.rex-page-main-inner .nav-tabs > li:nth-child(2) > a:nth-child(1)').click();

  /**
   * toggle all checkboxes
   */
  await page.locator('button[name=config_toggle_true]').click();

  /**
   * create and delete a dummy template to generate some logs
   */
  await page.goto('/redaxo/index.php?page=templates');
  await page.locator('section.rex-page-section table thead th.rex-table-icon > a').click();
  await page.locator('input[id=rex-id-templatename]').fill('template_nightwatch');
  await page.locator('input[id=rex-id-templatename]').press('Enter');
  await expect(page).toHaveURL('/redaxo/index.php?page=templates&start=0');
  page.once('dialog', dialog => {
    console.log(`Dialog message: ${dialog.message()}`);
    dialog.accept().catch(() => {
    });
  });
  await page.locator('section.rex-page-section table tbody tr:last-child td:last-child > a').click();
  // page.on('dialog', dialog => dialog.accept());#
  await delay(500);

  /**
   * check if two logs available
   */
  await page.goto('/redaxo/index.php?page=activity_log/system.activity-log');
  await expect(page.locator('table.rex-activity-table tbody tr')).toHaveCount(2);

  /**
   * filter logs
   */
  await page.locator('select[id="filter_type"]').selectOption('delete');
  await page.locator('button[name=filter]').click();

  /**
   * check if one entry is available
   */
  await expect(page.locator('table.rex-activity-table tbody tr')).toHaveCount(1);

  /**
   * delete all logs
   */
  await page.locator('button[name=delete_all_logs]').click();
  await expect(page.locator('table.rex-activity-table tr.table-no-results')).toBeVisible();
});

function delay(timeout) {
  return new Promise((resolve) => {
    setTimeout(resolve, timeout);
  });
}

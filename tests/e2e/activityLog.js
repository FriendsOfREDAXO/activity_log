describe('Activity Log', () => {
  /**
   * login before the actual test
   */
  before(browser => {
    /**
     * navigate to the login screen
     */
    browser.navigateTo('redaxo');

    /**
     * check if the login input is present
     * add username
     */
    browser.assert.elementPresent('input[id=rex-id-login-user]');
    browser.sendKeys('input[id=rex-id-login-user]', 'admin');

    /**
     * check if the password input is present
     * add password
     */
    browser.assert.elementPresent('input[id=rex-id-login-password]');
    browser.sendKeys('input[id=rex-id-login-password]', ['adminpassword', browser.Keys.ENTER]);

    /**
     * check if the session cookie is available
     */
    browser.getCookie('PHPSESSID', function callback(result) {
      // add more assertions here to test the result
      this.assert.equal(result.name, 'PHPSESSID');
    });

    /**
     * check if we are logged in to the backend
     */
    browser.assert.urlContains('/redaxo/index.php?page=structure');
  });

  it('Test Activity Log functionality', function(browser) {
    /**
     * navigate to the addon page
     */
    browser.assert.elementPresent('#rex-navi-page-activity-log');
    browser.click('#rex-navi-page-activity-log');

    /**
     * there should be no logs
     */
    browser.assert.elementPresent('table.rex-activity-table tr.table-no-results');

    /**
     * navigate to the settings page
     */
    browser.assert.elementPresent('.rex-page-main-inner .nav-tabs > li:nth-child(2) > a:nth-child(1)');
    browser.click('.rex-page-main-inner .nav-tabs > li:nth-child(2) > a:nth-child(1)');

    /**
     * toggle all checkboxes
     */
    browser.assert.elementPresent('button[name=config_toggle_true]');
    browser.click('button[name=config_toggle_true]');

    /**
     * create and delete a dummy template to generate some logs
     */
    browser.navigateTo('/redaxo/index.php?page=templates');
    browser.assert.elementPresent('section.rex-page-section table thead th.rex-table-icon > a');
    browser.click('section.rex-page-section table thead th.rex-table-icon > a');
    browser.sendKeys('input[id=rex-id-templatename]', ['template_nightwatch', browser.Keys.ENTER]);
    browser.ensure.elementIsNotVisible('#rex-js-ajax-loader');
    browser.assert.elementPresent('section.rex-page-section table tbody tr:last-child td:last-child > a');
    browser.click('section.rex-page-section table tbody tr:last-child td:last-child > a');
    browser.acceptAlert();
    browser.ensure.elementIsNotVisible('#rex-js-ajax-loader');

    browser.pause(500);

    /**
     * check if two logs available
     */
    browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
    browser.waitForElementVisible('table.rex-activity-table');
    browser.assert.elementsCount('table.rex-activity-table tbody tr', 2);

    /**
     * filter logs
     */
    browser.click('select[id="filter_type"] option[value="delete"]');
    browser.click('button[name=filter]');

    /**
     * check if one entry is available
     */
    browser.assert.elementsCount('table.rex-activity-table tbody tr', 1);

    /**
     * delete all logs
     */
    browser.click('button[name=delete_all_logs]');
    browser.assert.elementPresent('table.rex-activity-table tr.table-no-results');
  });

  /**
   * close the browser
   */
  after(browser => {
    browser.end();
  });
});

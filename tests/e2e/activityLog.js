describe('Activity Log', () => {
  /**
   * login before the actual test
   */
  before(browser => {
    /**
     * navigate to the login screen
     */
    browser.navigateTo('/redaxo/index.php');

    /**
     * check if the login input is present
     * add username
     */
    browser.assert.elementPresent('input[id=rex-id-login-user]');
    browser.sendKeys('input[id=rex-id-login-user]', 'nightwatch_username');

    /**
     * check if the password input is present
     * add password
     */
    browser.assert.elementPresent('input[id=rex-id-login-password]');
    browser.sendKeys('input[id=rex-id-login-password]', ['nightwatch_password', browser.Keys.ENTER]);

    /**
     * check if the session cookie is available
     */
    browser.getCookie('PHPSESSID', function callback(result) {
      // add more assertions here to test the result
      this.assert.equal(result.name, 'PHPSESSID');
    });

    browser.pause(500);

    /**
     * check if we are logged in to the backend
     */
    browser.assert.urlContains('/redaxo/index.php?page=structure');
  });

  it('Test Article Logs', function(browser) {
    /**
     * navigate to the settings page, uncheck all
     */
    browser.navigateTo('/redaxo/index.php?page=activity_log/settings');
    browser.click('button[name=config_toggle_false]');

    /**
     * check article related checkboxes
     */
    browser.click('#rex_activity_log_article_added');
    browser.click('#rex_activity_log_article_updated');
    browser.click('#rex_activity_log_article_deleted');
    browser.click('#rex_activity_log_article_status');

    /**
     * save settings
     */
    browser.click('button[name=config-submit]');

    /**
     * asset if the checkboxes checked...
     */
    browser.expect.element('#rex_activity_log_article_added').to.be.selected;
    browser.expect.element('#rex_activity_log_article_updated').to.be.selected;
    browser.expect.element('#rex_activity_log_article_deleted').to.be.selected;
    browser.expect.element('#rex_activity_log_article_status').to.be.selected;

    /**
     * add a article
     */
    browser.navigateTo('/redaxo/index.php?page=structure&category_id=0&article_id=0&clang=1&function=add_art&artstart=0');
    browser.sendKeys('input[name=article-name]', ['nightwatch_test_article', browser.Keys.ENTER]);
    browser.waitForElementPresent('#rex-message-container .alert.alert-success');
    browser.pause(1000);

    /**
     * change added article
     */
    browser.click('section.rex-page-section:last-of-type table tbody tr:last-of-type td:nth-of-type(7) a');
    browser.sendKeys('input[name=article-name]', ['_change', browser.Keys.ENTER]);
    browser.waitForElementNotVisible('#rex-js-ajax-loader');
    browser.waitForElementPresent('#rex-message-container .alert.alert-success');
    browser.pause(1000);

    /**
     * change added article status
     */
    browser.click('section.rex-page-section:last-of-type table tbody tr:last-of-type td:nth-of-type(9) a');
    browser.waitForElementNotVisible('#rex-js-ajax-loader');
    browser.waitForElementPresent('#rex-message-container .alert.alert-success');
    browser.ensure.elementTextIs('#rex-message-container .alert.alert-success', 'Artikelstatus wurde aktualisiert.');
    browser.pause(1000);

    /**
     * delete added article
     */
    browser.click('section.rex-page-section:last-of-type table tbody tr:last-of-type td:nth-of-type(8) a');
    browser.pause(250);
    browser.acceptAlert();
    browser.pause(1000);

    /**
     * navigate to the log page
     */
    browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
    browser.waitForElementVisible('table.rex-activity-table');
    browser.assert.elementsCount('table.rex-activity-table tbody tr', 4);

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

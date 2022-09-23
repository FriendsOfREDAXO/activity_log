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

        /**
         * Add a start article if empty, because start articles cannot be deleted..
         */
        browser.getText('css selector', 'section.rex-page-section:last-of-type table tbody tr:last-of-type td:nth-of-type(3)', function (result) {
            if (result.value === '') {
                browser.navigateTo('/redaxo/index.php?page=structure&category_id=0&article_id=0&clang=1&function=add_art&artstart=0');
                browser.sendKeys('input[name=article-name]', ['nightwatch_test_start_article', browser.Keys.ENTER]);
                browser.waitForElementPresent('#rex-message-container .alert.alert-success');
            }
        });
    });

    beforeEach(browser => {
        /**
         * navigate to the settings page, uncheck all
         */
        browser.navigateTo('/redaxo/index.php?page=activity_log/settings');
        browser.click('button[name=config_toggle_false]');
    });

    it('Test Article Logs', function (browser) {
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
         * assert if the checkboxes checked...
         */
        browser.expect.element('#rex_activity_log_article_added').to.be.selected;
        browser.expect.element('#rex_activity_log_article_updated').to.be.selected;
        browser.expect.element('#rex_activity_log_article_deleted').to.be.selected;
        browser.expect.element('#rex_activity_log_article_status').to.be.selected;
        browser.pause(250);

        /**
         * add a article
         */
        browser.navigateTo('/redaxo/index.php?page=structure&category_id=0&article_id=0&clang=1&function=add_art&artstart=0');
        browser.sendKeys('input[name=article-name]', ['z_nightwatch_test_article', browser.Keys.ENTER]);
        browser.waitForElementPresent('#rex-message-container .alert.alert-success');
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.pause(250);

        /**
         * change added article
         */
        browser.click('section.rex-page-section:last-of-type table tbody tr:last-of-type td:nth-of-type(7) a');
        browser.sendKeys('input[name=article-name]', ['_change', browser.Keys.ENTER]);
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.waitForElementPresent('#rex-message-container .alert.alert-success');
        browser.pause(500);

        /**
         * change added article status
         */
        browser.click('section.rex-page-section:last-of-type table tbody tr:last-of-type td:nth-of-type(9) a');
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.waitForElementPresent('#rex-message-container .alert.alert-success');
        browser.ensure.elementTextIs('#rex-message-container .alert.alert-success', 'Artikelstatus wurde aktualisiert.');
        browser.pause(250);

        /**
         * delete added article
         */
        browser.click('section.rex-page-section:last-of-type table tbody tr:last-of-type td:nth-of-type(8) a');
        browser.pause(250);
        browser.acceptAlert();
        browser.pause(500);

        /**
         * navigate to the log page
         */
        browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
        browser.waitForElementVisible('table.rex-activity-table');
        browser.assert.elementsCount('table.rex-activity-table tbody tr', 4);
    });

    it('Test Category Logs', function (browser) {
        /**
         * check category related checkboxes
         */
        browser.click('#rex_activity_log_category_added');
        browser.click('#rex_activity_log_category_updated');
        browser.click('#rex_activity_log_category_deleted');
        browser.click('#rex_activity_log_category_status');
        browser.pause(250);

        /**
         * save settings
         */
        browser.click('button[name=config-submit]');

        /**
         * assert if the checkboxes checked...
         */
        browser.expect.element('#rex_activity_log_category_added').to.be.selected;
        browser.expect.element('#rex_activity_log_category_updated').to.be.selected;
        browser.expect.element('#rex_activity_log_category_deleted').to.be.selected;
        browser.expect.element('#rex_activity_log_category_status').to.be.selected;
        browser.pause(250);

        /**
         * add a category
         */
        browser.navigateTo('/redaxo/index.php?page=structure&category_id=0&article_id=0&clang=1&function=add_cat&catstart=0');
        browser.sendKeys('input[name=category-name]', ['z_nightwatch_test_category', browser.Keys.ENTER]);
        browser.waitForElementPresent('#rex-message-container .alert.alert-success');
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.pause(250);

        /**
         * change added category
         */
        browser.click('section.rex-page-section:first-of-type table tbody tr:last-of-type td:nth-of-type(5) a');
        browser.sendKeys('input[name=category-name]', ['_change', browser.Keys.ENTER]);
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.waitForElementPresent('#rex-message-container .alert.alert-success');
        browser.pause(500);

        /**
         * change added category status
         */
        browser.click('section.rex-page-section:first-of-type table tbody tr:last-of-type td:nth-of-type(7) a');
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.waitForElementPresent('#rex-message-container .alert.alert-success');
        browser.ensure.elementTextIs('#rex-message-container .alert.alert-success', 'Kategoriestatus wurde aktualisiert!');
        browser.pause(250);

        /**
         * delete added category
         */
        browser.click('section.rex-page-section:first-of-type table tbody tr:last-of-type td:nth-of-type(6) a');
        browser.pause(250);
        browser.acceptAlert();
        browser.pause(500);

        /**
         * navigate to the log page
         */
        browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
        browser.waitForElementVisible('table.rex-activity-table');
        browser.assert.elementsCount('table.rex-activity-table tbody tr', 4);
    });

    it('Test User Logs', function (browser) {
        /**
         * check user related checkboxes
         */
        browser.click('#rex_activity_log_user_added');
        browser.click('#rex_activity_log_user_updated');
        browser.click('#rex_activity_log_user_deleted');

        /**
         * save settings
         */
        browser.click('button[name=config-submit]');

        /**
         * assert if the checkboxes checked...
         */
        browser.expect.element('#rex_activity_log_user_added').to.be.selected;
        browser.expect.element('#rex_activity_log_user_updated').to.be.selected;
        browser.expect.element('#rex_activity_log_user_deleted').to.be.selected;
        browser.pause(250);

        /**
         * add a user
         */
        browser.navigateTo('/redaxo/index.php?page=users/users&FUNC_ADD=1');
        browser.sendKeys('input[name=userlogin]', ['z_nightwatch_test_user']);
        browser.sendKeys('input[name=userpsw]', ['nightwatch_test_user_pw', browser.Keys.ENTER]);
        browser.waitForElementPresent('.alert.alert-info');
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.pause(500);

        /**
         * check if is users page...
         */
        browser.assert.urlContains('/redaxo/index.php?page=users/users');

        /**
         * change added user
         */
        browser.click('section.rex-page-section:first-of-type table tbody tr:last-of-type td:nth-of-type(7) a');
        browser.waitForElementPresent('.panel.panel-edit');
        browser.sendKeys('input[name=username]', ['z_name', browser.Keys.ENTER]);
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.waitForElementPresent('.alert.alert-info');
        browser.pause(500);

        /**
         * delete added user
         */
        browser.click('section.rex-page-section:first-of-type table tbody tr:last-of-type td:nth-of-type(8) a');
        browser.pause(250);
        browser.acceptAlert();
        browser.pause(500);

        /**
         * navigate to the log page
         */
        browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
        browser.waitForElementVisible('table.rex-activity-table');
        browser.assert.elementsCount('table.rex-activity-table tbody tr', 3);
    });

    it('Test Template Logs', function (browser) {
        /**
         * check template related checkboxes
         */
        browser.click('#rex_activity_log_template_added');
        browser.click('#rex_activity_log_template_updated');
        browser.click('#rex_activity_log_template_deleted');

        /**
         * save settings
         */
        browser.click('button[name=config-submit]');

        /**
         * assert if the checkboxes checked...
         */
        browser.expect.element('#rex_activity_log_template_added').to.be.selected;
        browser.expect.element('#rex_activity_log_template_updated').to.be.selected;
        browser.expect.element('#rex_activity_log_template_deleted').to.be.selected;

        /**
         * add a template
         */
        browser.navigateTo('/redaxo/index.php?page=templates&start=0&function=add');
        browser.sendKeys('input[name=templatename]', ['z_nightwatch_test_template', browser.Keys.ENTER]);
        browser.waitForElementPresent('.alert.alert-success');
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.pause(250);

        /**
         * check if is templates page...
         */
        browser.assert.urlContains('/redaxo/index.php?page=templates&start=0');

        /**
         * change added template
         */
        browser.click('section.rex-page-section:first-of-type table tbody tr:last-of-type td:nth-of-type(6) a');
        browser.waitForElementPresent('.panel.panel-edit');
        browser.sendKeys('input[name=templatename]', ['_edit', browser.Keys.ENTER]);
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.waitForElementPresent('.alert.alert-success');
        browser.pause(250);

        /**
         * delete added template
         */
        browser.click('section.rex-page-section:first-of-type table tbody tr:last-of-type td:nth-of-type(7) a');
        browser.pause(250);
        browser.acceptAlert();
        browser.pause(500);

        /**
         * navigate to the log page
         */
        browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
        browser.waitForElementVisible('table.rex-activity-table');
        browser.assert.elementsCount('table.rex-activity-table tbody tr', 3);
    });

    it('Test Module Logs', function (browser) {
        /**
         * check module related checkboxes
         */
        browser.click('#rex_activity_log_module_added');
        browser.click('#rex_activity_log_module_updated');
        browser.click('#rex_activity_log_module_deleted');

        /**
         * save settings
         */
        browser.click('button[name=config-submit]');

        /**
         * assert if the checkboxes checked...
         */
        browser.expect.element('#rex_activity_log_module_added').to.be.selected;
        browser.expect.element('#rex_activity_log_module_updated').to.be.selected;
        browser.expect.element('#rex_activity_log_module_deleted').to.be.selected;
        browser.pause(250);

        /**
         * add a module
         */
        browser.navigateTo('/redaxo/index.php?page=modules/modules&start=0&function=add');
        browser.sendKeys('input[name=mname]', ['z_nightwatch_test_module', browser.Keys.ENTER]);
        browser.waitForElementPresent('.alert.alert-success');
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.pause(250);

        /**
         * check if is modules page...
         */
        browser.assert.urlContains('/redaxo/index.php?page=modules/modules&start=0');

        /**
         * change added module
         */
        browser.click('section.rex-page-section:first-of-type table tbody tr:last-of-type td:nth-of-type(5) a');
        browser.waitForElementPresent('.panel.panel-edit');
        browser.sendKeys('input[name=mname]', ['_edit', browser.Keys.ENTER]);
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.waitForElementPresent('.alert.alert-success');
        browser.pause(250);

        /**
         * delete added module
         */
        browser.click('section.rex-page-section:first-of-type table tbody tr:last-of-type td:nth-of-type(6) a');
        browser.pause(250);
        browser.acceptAlert();
        browser.pause(500);

        /**
         * navigate to the log page
         */
        browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
        browser.waitForElementVisible('table.rex-activity-table');
        browser.assert.elementsCount('table.rex-activity-table tbody tr', 3);
    });

    it('Test Meta Info Logs', function (browser) {
        /**
         * check meta info related checkboxes
         */
        browser.click('#rex_activity_log_meta_updated');

        /**
         * save settings
         */
        browser.click('button[name=config-submit]');

        /**
         * assert if the checkboxes checked...
         */
        browser.expect.element('#rex_activity_log_meta_updated').to.be.selected;
        browser.pause(250);

        /**
         * edit meta info
         */
        browser.navigateTo('/redaxo/index.php?page=structure');
        browser.click('section.rex-page-section:last-of-type table tbody tr:last-of-type td:nth-of-type(3) a');
        browser.waitForElementPresent('.rex-main-frame');
        browser.sendKeys('input[name=meta_article_name]', ['_edit', browser.Keys.ENTER]);
        browser.pause(250);

        /**
         * navigate to the log page
         */
        browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
        browser.waitForElementVisible('table.rex-activity-table');
        browser.assert.elementsCount('table.rex-activity-table tbody tr', 1);
    });

    it('Test Media Logs', function (browser) {
        /**
         * check media related checkboxes
         */
        browser.click('#rex_activity_log_media_added');
        browser.click('#rex_activity_log_media_updated');
        browser.click('#rex_activity_log_media_deleted');

        /**
         * save settings
         */
        browser.click('button[name=config-submit]');

        /**
         * assert if the checkboxes checked...
         */
        browser.expect.element('#rex_activity_log_media_added').to.be.selected;
        browser.expect.element('#rex_activity_log_media_updated').to.be.selected;
        browser.expect.element('#rex_activity_log_media_deleted').to.be.selected;
        browser.pause(250);

        /**
         * upload a file
         */
        browser.navigateTo('/redaxo/index.php?page=mediapool/upload');
        browser.uploadFile('#rex-mediapool-choose-file', require('path').resolve(__dirname + '/../../.tools/for-badge.png'));
        browser.click('button[name=save]');
        browser.pause(500);
        browser.assert.urlContains('/redaxo/index.php?page=mediapool/media');

        /**
         * edit media
         */
        browser.click('section.rex-page-section:last-of-type table tbody tr:last-of-type td:nth-of-type(5) a');
        browser.waitForElementPresent('.panel.panel-edit');
        browser.sendKeys('input[name=ftitle]', ['FOR', browser.Keys.ENTER]);
        browser.pause(500);

        /**
         * delete media
         */
        browser.click('button[name=btn_delete]');
        browser.pause(250);
        browser.acceptAlert();
        browser.pause(500);
        browser.assert.urlContains('/redaxo/index.php?page=mediapool/media');

        /**
         * navigate to the log page
         */
        browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
        browser.waitForElementVisible('table.rex-activity-table');
        browser.assert.elementsCount('table.rex-activity-table tbody tr', 3);
    });

    it('Test Language Logs', function (browser) {
        /**
         * check language related checkboxes
         */
        browser.click('#rex_activity_log_clang_added');
        browser.click('#rex_activity_log_clang_updated');
        browser.click('#rex_activity_log_clang_deleted');

        /**
         * save settings
         */
        browser.click('button[name=config-submit]');

        /**
         * assert if the checkboxes checked...
         */
        browser.expect.element('#rex_activity_log_clang_added').to.be.selected;
        browser.expect.element('#rex_activity_log_clang_updated').to.be.selected;
        browser.expect.element('#rex_activity_log_clang_deleted').to.be.selected;
        browser.pause(250);

        /**
         * add a language
         */
        browser.navigateTo('/redaxo/index.php?page=system/lang');
        browser.click('section.rex-page-section:last-of-type table thead tr:first-of-type th:nth-of-type(1) a');
        browser.waitForElementPresent('#rex-form-clang-code');
        browser.sendKeys('input[name=clang_code]', ['zz']);
        browser.sendKeys('input[name=clang_name]', ['zz_nightwatch_test_clang', browser.Keys.ENTER]);
        browser.waitForElementPresent('.alert.alert-success');
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.pause(250);

        /**
         * change added language
         */
        browser.click('section.rex-page-section:first-of-type table tbody tr:last-of-type td:nth-of-type(6) a');
        browser.waitForElementPresent('#rex-form-clang-code');
        browser.sendKeys('input[name=clang_code]', ['_edit', browser.Keys.ENTER]);
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.waitForElementPresent('.alert.alert-success');
        browser.pause(250);

        /**
         * delete added language
         */
        browser.click('section.rex-page-section:first-of-type table tbody tr:last-of-type td:nth-of-type(7) a');
        browser.pause(250);
        browser.acceptAlert();
        browser.pause(500);

        /**
         * navigate to the log page
         */
        browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
        browser.waitForElementVisible('table.rex-activity-table');
        browser.assert.elementsCount('table.rex-activity-table tbody tr', 3);
    });

    it('Test Slice Logs', function (browser) {
        /**
         * check slice related checkboxes
         */
        browser.click('#rex_activity_log_slice_added');
        browser.click('#rex_activity_log_slice_updated');
        browser.click('#rex_activity_log_slice_deleted');
        browser.click('#rex_activity_log_slice_moved');

        /**
         * save settings
         */
        browser.click('button[name=config-submit]');

        /**
         * assert if the checkboxes checked...
         */
        browser.expect.element('#rex_activity_log_slice_added').to.be.selected;
        browser.expect.element('#rex_activity_log_slice_updated').to.be.selected;
        browser.expect.element('#rex_activity_log_slice_deleted').to.be.selected;
        browser.expect.element('#rex_activity_log_slice_moved').to.be.selected;
        browser.pause(250);

        /**
         * add a module
         */
        browser.navigateTo('/redaxo/index.php?page=modules/modules&start=0&function=add');
        browser.sendKeys('input[name=mname]', ['z_nightwatch_test_module', browser.Keys.ENTER]);
        browser.waitForElementPresent('.alert.alert-success');
        browser.waitForElementNotVisible('#rex-js-ajax-loader');
        browser.pause(250);

        /**
         * check if is modules page...
         */
        browser.assert.urlContains('/redaxo/index.php?page=modules/modules&start=0');

        /**
         * add a slice
         */
        browser.navigateTo('/redaxo/index.php?page=structure');
        browser.click('section.rex-page-section:last-of-type table tbody tr:last-of-type td:nth-of-type(3) a');
        browser.waitForElementPresent('.rex-main-frame');
        browser.click('.rex-slices .dropdown-toggle');
        browser.waitForElementVisible('.rex-slices .dropdown-menu');
        browser.click('.rex-slices .dropdown-menu li:first-of-type a');
        browser.waitForElementVisible('.rex-slices .rex-slice-add')
        browser.pause(500);
        browser.click('.rex-slices .rex-slice-add button[name=btn_save]');
        browser.pause(500);

        /**
         * change added slice
         */
        browser.click('.rex-slice a.btn-edit');
        browser.pause(250);
        browser.waitForElementVisible('.rex-slices .rex-slice-edit');
        browser.click('.rex-slices .rex-slice-edit button[name=btn_save]');
        browser.pause(500);

        /**
         * move added slice
         */
        browser.click('.rex-slices li.rex-slice-output a.btn-move:first-of-type');
        browser.waitForElementPresent('.alert.alert-danger');
        browser.pause(500);

        /**
         * delete added slice
         */
        browser.click('.rex-slices li.rex-slice-output a.btn-delete');
        browser.pause(250);
        browser.acceptAlert();
        browser.pause(500);

        /**
         * navigate to the log page
         */
        browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
        browser.waitForElementVisible('table.rex-activity-table');
        browser.assert.elementsCount('table.rex-activity-table tbody tr', 4);
    });

    /**
     * delete all logs, uncheck all
     */
    afterEach(browser => {
        /**
         * check if is log page otherwise navigate to log page...
         */
        browser.getCurrentUrl(function (result) {
            if (!result.value.includes('page=activity_log/system.activity-log')) {
                browser.navigateTo('/redaxo/index.php?page=activity_log/system.activity-log');
            }
        });

        /**
         * delete all logs
         */
        browser.click('button[name=delete_all_logs]');
        browser.assert.elementPresent('table.rex-activity-table tr.table-no-results');

        /**
         * navigate to the settings page, uncheck all
         */
        browser.navigateTo('/redaxo/index.php?page=activity_log/settings');
        browser.click('button[name=config_toggle_false]');
    });

    /**
     * close the browser
     */
    after(browser => {
        browser.end();
    });
});

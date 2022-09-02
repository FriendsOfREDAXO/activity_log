<?php

/** @var rex_addon $this */

if (rex_post('config_toggle_true', 'bool') || rex_post('config_toggle_false', 'bool')) {
    $toggleTrue = rex_post('config_toggle_true', 'bool');
    $configEntries = rex_config::get('activity_log');

    foreach ($configEntries as $key => $value) {
        if ($key === 'rows_per_page') {
            continue;
        }

        $configEntries[$key] = $toggleTrue;
    }

    rex_config::set($this->getPackageId(), $configEntries);
}

if (rex_post('config-submit', 'bool')) {
    $this->setConfig(rex_post('config', [
        ['article_added', 'bool'],
        ['article_updated', 'bool'],
        ['article_status', 'bool'],
        ['article_deleted', 'bool'],
        ['category_added', 'bool'],
        ['category_updated', 'bool'],
        ['category_deleted', 'bool'],
        ['category_status', 'bool'],
        ['slice_added', 'bool'],
        ['slice_updated', 'bool'],
        ['slice_deleted', 'bool'],
        ['slice_moved', 'bool'],
        ['meta_updated', 'bool'],
        ['clang_added', 'bool'],
        ['clang_updated', 'bool'],
        ['clang_deleted', 'bool'],
        ['user_added', 'bool'],
        ['user_updated', 'bool'],
        ['user_deleted', 'bool'],
        ['media_added', 'bool'],
        ['media_updated', 'bool'],
        ['media_deleted', 'bool'],
        ['template_added', 'bool'],
        ['template_updated', 'bool'],
        ['template_deleted', 'bool'],
        ['module_added', 'bool'],
        ['module_updated', 'bool'],
        ['module_deleted', 'bool'],
        ['rows_per_page', 'int'],
    ]));

    echo rex_view::success($this->i18n('saved'));
}

$content = '<fieldset class="rex-activity-log">';

/**
 * articles
 */
$n = [];
$n['header'] = '<dl class="rex-form-group form-group"><dd><strong>Article</strong></dd></dl>';
$n['label'] = '<label for="rex_activity_log_article_added">Article added</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_article_added" name="config[article_added]" value="1" ' . ($this->getConfig('article_added') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_article_updated">Article updated</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_article_updated" name="config[article_updated]" value="1" ' . ($this->getConfig('article_updated') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_article_deleted">Article deleted</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_article_deleted" name="config[article_deleted]" value="1" ' . ($this->getConfig('article_deleted') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_article_status">Article status change</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_article_status" name="config[article_status]" value="1" ' . ($this->getConfig('article_status') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

/**
 * categories
 */
$n = [];
$n['header'] = '<dl class="rex-form-group form-group form-group-header"><dd><strong>Category</strong></dd></dl>';
$n['label'] = '<label for="rex_activity_log_category_added">Category added</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_category_added" name="config[category_added]" value="1" ' . ($this->getConfig('category_added') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_category_updated">Category updated</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_category_updated" name="config[category_updated]" value="1" ' . ($this->getConfig('category_updated') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_category_deleted">Category deleted</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_category_deleted" name="config[category_deleted]" value="1" ' . ($this->getConfig('category_deleted') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_category_status">Category status change</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_category_status" name="config[category_status]" value="1" ' . ($this->getConfig('category_status') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

/**
 * slices
 */
$n = [];
$n['header'] = '<dl class="rex-form-group form-group form-group-header"><dd><strong>Slice</strong></dd></dl>';
$n['label'] = '<label for="rex_activity_log_slice_added">Slice added</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_slice_added" name="config[slice_added]" value="1" ' . ($this->getConfig('slice_added') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_slice_updated">Slice updated</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_slice_updated" name="config[slice_updated]" value="1" ' . ($this->getConfig('slice_updated') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_slice_deleted">Slice deleted</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_slice_deleted" name="config[slice_deleted]" value="1" ' . ($this->getConfig('slice_deleted') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_slice_moved">Slice moved</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_slice_moved" name="config[slice_moved]" value="1" ' . ($this->getConfig('slice_moved') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

/**
 * media
 */
$n = [];
$n['header'] = '<dl class="rex-form-group form-group form-group-header"><dd><strong>Media</strong></dd></dl>';
$n['label'] = '<label for="rex_activity_log_media_added">Media added</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_media_added" name="config[media_added]" value="1" ' . ($this->getConfig('media_added') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_media_updated">Media updated</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_media_updated" name="config[media_updated]" value="1" ' . ($this->getConfig('media_updated') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_media_deleted">Media deleted</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_media_deleted" name="config[media_deleted]" value="1" ' . ($this->getConfig('media_deleted') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

/**
 * meta info
 */
$n = [];
$n['header'] = '<dl class="rex-form-group form-group form-group-header"><dd><strong>Meta Info</strong></dd></dl>';
$n['label'] = '<label for="rex_activity_log_meta_updated">Meta updated</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_meta_updated" name="config[meta_updated]" value="1" ' . ($this->getConfig('meta_updated') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

/**
 * user
 */
$n = [];
$n['header'] = '<dl class="rex-form-group form-group form-group-header"><dd><strong>User</strong></dd></dl>';
$n['label'] = '<label for="rex_activity_log_user_added">User added</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_user_added" name="config[user_added]" value="1" ' . ($this->getConfig('user_added') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_user_updated">User updated</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_user_updated" name="config[user_updated]" value="1" ' . ($this->getConfig('user_updated') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_user_deleted">User deleted</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_user_deleted" name="config[user_deleted]" value="1" ' . ($this->getConfig('user_deleted') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

/**
 * clang
 */
$n = [];
$n['header'] = '<dl class="rex-form-group form-group form-group-header"><dd><strong>Language</strong></dd></dl>';
$n['label'] = '<label for="rex_activity_log_clang_added">Clang added</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_clang_added" name="config[clang_added]" value="1" ' . ($this->getConfig('clang_added') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_clang_updated">Clang updated</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_clang_updated" name="config[clang_updated]" value="1" ' . ($this->getConfig('clang_updated') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_clang_deleted">Clang deleted</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_clang_deleted" name="config[clang_deleted]" value="1" ' . ($this->getConfig('clang_deleted') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

/**
 * template
 */
$n = [];
$n['header'] = '<dl class="rex-form-group form-group form-group-header"><dd><strong>Template</strong></dd></dl>';
$n['label'] = '<label for="rex_activity_log_template_added">Template added</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_template_added" name="config[template_added]" value="1" ' . ($this->getConfig('template_added') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_template_updated">Template updated</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_template_updated" name="config[template_updated]" value="1" ' . ($this->getConfig('template_updated') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_template_deleted">Template deleted</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_template_deleted" name="config[template_deleted]" value="1" ' . ($this->getConfig('template_deleted') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

/**
 * module
 */
$n = [];
$n['header'] = '<dl class="rex-form-group form-group form-group-header"><dd><strong>Module</strong></dd></dl>';
$n['label'] = '<label for="rex_activity_log_module_added">Module added</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_module_added" name="config[module_added]" value="1" ' . ($this->getConfig('module_added') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_module_updated">Module updated</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_module_updated" name="config[module_updated]" value="1" ' . ($this->getConfig('module_updated') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="rex_activity_log_module_deleted">Module deleted</label>';
$n['field'] = '<input type="checkbox" id="rex_activity_log_module_deleted" name="config[module_deleted]" value="1" ' . ($this->getConfig('module_deleted') ? ' checked="checked"' : '') . ' />';
$formElements[] = $n;


/**
 * render form
 */
$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/checkbox.php');

/**
 * misc
 */
$inputGroups = [];
$n = [];
$n['before'] = '<label class="form-label form-group-header" for="rex_activity_log_rows_per_page">Rows per page</label>';
$n['field'] = '<input class="form-control" type="number" value="' . $this->getConfig('rows_per_page') . '" id="rex_activity_log_rows_per_page" name="config[rows_per_page]"/>';
$inputGroups[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $inputGroups, false);
$content .= $fragment->parse('core/form/input_group.php');


$formElements = [];

$n = [];
$n['field'] = '<button class="btn btn-save" type="submit" name="config-submit" value="1" ' . rex::getAccesskey($this->i18n('save'), 'save') . '>' . $this->i18n('save') . '</button>';
$formElements[] = $n;

$n = [];
$n['field'] = '<button class="btn btn-default" name="config_toggle_true" value="1">' . $this->i18n('check_all') . '</button>';
$formElements[] = $n;

$n = [];
$n['field'] = '<button class="btn btn-default" name="config_toggle_false" value="1">' . $this->i18n('uncheck_all') . '</button>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('flush', true);
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', $this->i18n('settings'));
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$content = $fragment->parse('core/page/section.php');

echo '
    <form action="' . rex_url::currentBackendPage() . '" id="activity-log-settings" method="post">
        ' . $content . '
    </form>';

<?php

/** @var rex_addon $this */
if (rex_post('config-submit', 'boolean')) {
    $this->setConfig(rex_post('config', [
        ['slice_added', 'bool'],
        ['slice_updated', 'bool'],
        ['slice_deleted', 'bool'],
    ]));

    echo rex_view::success($this->i18n('saved'));
}

$content = '<fieldset class="rex-activity-log">';

$n = [];
$n['header'] = '<dl class="rex-form-group form-group"><dd><strong>Slice</strong></dd></dl>';
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

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/checkbox.php');

$formElements = [];

$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="config-submit" value="1" ' . rex::getAccesskey($this->i18n('save'), 'save') . '>' . $this->i18n('save') . '</button>';
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
    <form action="' . rex_url::currentBackendPage() . '" method="post">
        ' . $content . '
    </form>';

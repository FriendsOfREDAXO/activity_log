<?php
/** @var \rex_fragment $this */
$sql = \rex_sql::factory();
$causers = $sql->getArray('SELECT causer_id FROM ' . \rex::getTable('activity_log') . ' WHERE causer_id IS NOT NULL GROUP BY causer_id ORDER BY causer_id');
$sources = $sql->getArray('SELECT source FROM ' . \rex::getTable('activity_log') . ' WHERE source IS NOT NULL AND source != "" GROUP BY source ORDER BY source');

use FriendsOfRedaxo\ActivityLog\Activity;

$hasSources = count($sources) > 0;
$hasCausers = count($causers) > 0;

// column widths depending on visible filters
$colSearch  = ($hasSources && $hasCausers) ? 'col-sm-3' : ($hasSources || $hasCausers ? 'col-sm-4' : 'col-sm-6');
$colType    = 'col-sm-2';
$colSource  = $hasSources ? 'col-sm-2' : '';
$colUser    = $hasCausers ? 'col-sm-2' : '';
$colButtons = 'col-sm-3';
?>

<form action="<?= rex_url::currentBackendPage() ?>" method="get" style="margin-bottom: 10px;">
    <input type="hidden" name="page" value="<?= rex_get('page', 'string') ?>">
    <div class="row" style="margin-bottom: 10px;">

        <div class="<?= $colSearch ?> form-group">
            <label for="filter_search"><?= \rex_i18n::msg('activity_log_filter_search') ?></label>
            <input type="text"
                   class="form-control"
                   name="search"
                   id="filter_search"
                   placeholder="<?= \rex_i18n::msg('activity_log_filter_search_placeholder') ?>"
                   value="<?= htmlspecialchars($this->search ?? '') ?>">
        </div>

        <div class="<?= $colType ?> form-group">
            <label for="filter_type"><?= \rex_i18n::msg('activity_log_col_type') ?></label>
            <select class="form-control selectpicker" name="type" id="filter_type" title="-">
                <?php foreach ([Activity::TYPE_ADD, Activity::TYPE_UPDATE, Activity::TYPE_DELETE, Activity::TYPE_EDIT, Activity::TYPE_INFO, Activity::TYPE_WARNING, Activity::TYPE_ERROR, Activity::TYPE_CRITICAL, Activity::TYPE_NOTICE, Activity::TYPE_DEBUG] as $t) : ?>
                    <option <?= ($this->type ?? '') === $t ? 'selected' : '' ?> value="<?= $t ?>"><?= ucfirst($t) ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <?php if ($hasSources) : ?>
        <div class="<?= $colSource ?> form-group">
            <label for="filter_source"><?= \rex_i18n::msg('activity_log_col_source') ?></label>
            <select class="form-control selectpicker" name="source" id="filter_source" title="-">
                <?php foreach ($sources as $row) : ?>
                    <option <?= ($this->source ?? '') === $row['source'] ? 'selected' : '' ?> value="<?= htmlspecialchars($row['source']) ?>"><?= htmlspecialchars(ucfirst($row['source'])) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <?php endif ?>

        <?php if ($hasCausers) : ?>
        <div class="<?= $colUser ?> form-group">
            <label for="filter_user"><?= \rex_i18n::msg('activity_log_col_user') ?></label>
            <select class="form-control selectpicker" name="user" id="filter_user" data-live-search="true" title="-">
                <?php foreach ($causers as $causer) : ?>
                    <?php $u = \rex_user::get((int) $causer['causer_id']); ?>
                    <option <?= ($this->user ?? '') === $causer['causer_id'] ? 'selected' : '' ?> value="<?= $causer['causer_id'] ?>">
                        <?= null !== $u ? ('' !== $u->getName() ? $u->getName() : $u->getLogin()) : \rex_i18n::msg('activity_log_deleted_user') . ' [' . $causer['causer_id'] . ']' ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        <?php endif ?>

        <div class="<?= $colButtons ?> form-group">
            <label>&nbsp;</label>
            <div>
                <button type="submit" class="btn btn-default" name="filter" value="1"><?= \rex_i18n::msg('activity_log_filter_apply') ?></button>
                <button type="submit" class="btn btn-danger" name="clear_filter" value="1"><i class="rex-icon fa-times"></i></button>
            </div>
        </div>

    </div>
</form>
<hr style="margin-top: 0; margin-bottom: 0;">



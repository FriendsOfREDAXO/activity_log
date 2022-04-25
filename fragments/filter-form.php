<?php
$sql = \rex_sql::factory();
$causers = $sql->getArray('SELECT causer_id FROM ' . \rex::getTable('activity_log') . ' WHERE causer_id  IS NOT NULL GROUP BY causer_id ORDER BY causer_id');
?>

<form action="<?=rex_url::currentBackendPage()?>" class="form-inline" method="get">
    <input type="hidden" name="page" value="<?= rex_get('page') ?>">
    <div class="form-horizontal" style="text-align: right">
        <div class="form-group" style="text-align: left">
            <label class="control-label col-sm-3" for="filter_type">Type</label>
            <div class="col-sm-9">
                <select class="form-control" name="type" id="filter_type">
                    <option value="" <?=$this->type === '' ? 'selected' : ''?>>-</option>
                    <option <?=$this->type === rex_activity::TYPE_ADD ? 'selected' : ''?> value="<?=rex_activity::TYPE_ADD?>"><?=rex_activity::TYPE_ADD?></option>
                    <option <?=$this->type === rex_activity::TYPE_UPDATE ? 'selected' : ''?> value="<?=rex_activity::TYPE_UPDATE?>"><?=rex_activity::TYPE_UPDATE?></option>
                    <option <?=$this->type === rex_activity::TYPE_DELETE ? 'selected' : ''?> value="<?=rex_activity::TYPE_DELETE?>"><?=rex_activity::TYPE_DELETE?></option>
                    <option <?=$this->type === rex_activity::TYPE_EDIT ? 'selected' : ''?> value="<?=rex_activity::TYPE_EDIT?>"><?=rex_activity::TYPE_EDIT?></option>
                    <option <?=$this->type === rex_activity::TYPE_INFO ? 'selected' : ''?> value="<?=rex_activity::TYPE_INFO?>"><?=rex_activity::TYPE_INFO?></option>
                    <option <?=$this->type === rex_activity::TYPE_WARNING ? 'selected' : ''?> value="<?=rex_activity::TYPE_WARNING?>"><?=rex_activity::TYPE_WARNING?></option>
                    <option <?=$this->type === rex_activity::TYPE_ERROR ? 'selected' : ''?> value="<?=rex_activity::TYPE_ERROR?>"><?=rex_activity::TYPE_ERROR?></option>
                    <option <?=$this->type === rex_activity::TYPE_CRITICAL ? 'selected' : ''?> value="<?=rex_activity::TYPE_CRITICAL?>"><?=rex_activity::TYPE_CRITICAL?></option>
                    <option <?=$this->type === rex_activity::TYPE_NOTICE ? 'selected' : ''?> value="<?=rex_activity::TYPE_NOTICE?>"><?=rex_activity::TYPE_NOTICE?></option>
                    <option <?=$this->type === rex_activity::TYPE_DEBUG ? 'selected' : ''?> value="<?=rex_activity::TYPE_DEBUG?>"><?=rex_activity::TYPE_DEBUG?></option>
                </select>
            </div>
        </div>

        <?php if ($causers) : ?>
            <div class="form-group" style="text-align: left">
                <label class="control-label col-sm-3" for="filter_user">User</label>
                <div class="col-sm-9">
                    <select class="form-control" name="user" id="filter_user">
                        <option value="" <?=$this->user === '' ? 'selected' : ''?>>-</option>
                        <?php foreach ($causers as $causer) : ?>
                            <option <?=$this->user == $causer['causer_id'] ? 'selected' : ''?> value="<?=$causer['causer_id']?>"><?=\rex_user::get($causer['causer_id'])->getName()?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group" style="text-align: left">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-default" name="filter" value="1">Filter</button>
            </div>
        </div>
    </div>
</form>
<hr style="margin-top: 15px; margin-bottom: 0;">

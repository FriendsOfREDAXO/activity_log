<?php
/** @var \rex_fragment $this */
$sql = \rex_sql::factory();
$causers = $sql->getArray('SELECT causer_id FROM ' . \rex::getTable('activity_log') . ' WHERE causer_id  IS NOT NULL GROUP BY causer_id ORDER BY causer_id');
?>

<form action="<?=rex_url::currentBackendPage()?>" class="form-inline" method="get">
    <input type="hidden" name="page" value="<?= rex_get('page', 'string') ?>">
    <div class="form-horizontal" style="text-align: right">
        <div class="form-group" style="text-align: left">
            <label class="control-label col-sm-3" for="filter_type">Type</label>
            <div class="col-sm-9">
                <select class="form-control" name="type" id="filter_type">
                    <option value="" <?='' === $this->type ? 'selected' : ''?>>-</option>
                    <option <?=rex_activity::TYPE_ADD === $this->type ? 'selected' : ''?> value="<?=rex_activity::TYPE_ADD?>"><?=rex_activity::TYPE_ADD?></option>
                    <option <?=rex_activity::TYPE_UPDATE === $this->type ? 'selected' : ''?> value="<?=rex_activity::TYPE_UPDATE?>"><?=rex_activity::TYPE_UPDATE?></option>
                    <option <?=rex_activity::TYPE_DELETE === $this->type ? 'selected' : ''?> value="<?=rex_activity::TYPE_DELETE?>"><?=rex_activity::TYPE_DELETE?></option>
                    <option <?=rex_activity::TYPE_EDIT === $this->type ? 'selected' : ''?> value="<?=rex_activity::TYPE_EDIT?>"><?=rex_activity::TYPE_EDIT?></option>
                    <option <?=rex_activity::TYPE_INFO === $this->type ? 'selected' : ''?> value="<?=rex_activity::TYPE_INFO?>"><?=rex_activity::TYPE_INFO?></option>
                    <option <?=rex_activity::TYPE_WARNING === $this->type ? 'selected' : ''?> value="<?=rex_activity::TYPE_WARNING?>"><?=rex_activity::TYPE_WARNING?></option>
                    <option <?=rex_activity::TYPE_ERROR === $this->type ? 'selected' : ''?> value="<?=rex_activity::TYPE_ERROR?>"><?=rex_activity::TYPE_ERROR?></option>
                    <option <?=rex_activity::TYPE_CRITICAL === $this->type ? 'selected' : ''?> value="<?=rex_activity::TYPE_CRITICAL?>"><?=rex_activity::TYPE_CRITICAL?></option>
                    <option <?=rex_activity::TYPE_NOTICE === $this->type ? 'selected' : ''?> value="<?=rex_activity::TYPE_NOTICE?>"><?=rex_activity::TYPE_NOTICE?></option>
                    <option <?=rex_activity::TYPE_DEBUG === $this->type ? 'selected' : ''?> value="<?=rex_activity::TYPE_DEBUG?>"><?=rex_activity::TYPE_DEBUG?></option>
                </select>
            </div>
        </div>

        <?php if (0 !== count($causers)) : ?>
            <div class="form-group" style="text-align: left">
                <label class="control-label col-sm-3" for="filter_user">User</label>
                <div class="col-sm-9">
                    <select class="form-control" name="user" id="filter_user">
                        <option value="" <?='' === $this->user ? 'selected' : ''?>>-</option>
                        <?php foreach ($causers as $causer) : ?>
                            <?php if (null !== \rex_user::get((int) $causer['causer_id'])) : $user = \rex_user::get((int) $causer['causer_id']) ?>
                                <option <?=$this->user === $causer['causer_id'] ? 'selected' : ''?> value="<?=$causer['causer_id']?>"><?='' !== $user->getName() ? $user->getName() : $user->getLogin()?></option>
                            <?php else: ?>
                                <option <?=$this->user === $causer['causer_id'] ? 'selected' : ''?> value="<?=$causer['causer_id']?>"><?=\rex_i18n::msg('activity_log_deleted_user')?> [<?=$causer['causer_id']?>]</option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        <?php endif ?>

        <div class="form-group" style="text-align: left">
            <div class="col-sm-12">
                &nbsp;<button type="submit" class="btn btn-default" name="filter" value="1">Filter</button>
                &nbsp;<button type="submit" class="btn btn-danger" name="clear_filter" value="1">&#10005;</button>
            </div>
        </div>
    </div>
</form>
<hr style="margin-top: 15px; margin-bottom: 0;">

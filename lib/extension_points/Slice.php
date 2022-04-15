<?php

namespace RexActivity\EP;

class slice
{
    use ep_trait;

    private $user;
    private $addon;

    private $ADDED = 1;
    private $UPDATED = 2;
    private $DELETED = 3;

    public function __construct() {
        $this->addon = $this->addon();
        $this->user = $this->user();

        if($this->addon->getConfig('slice_added')) {
            $this->added();
        }

        if($this->addon->getConfig('slice_updated')) {
            $this->updated();
        }

        if($this->addon->getConfig('slice_deleted')) {
            $this->deleted();
        }
    }

    /**
     * a new slice has been added
     * @return void
     */
    private function added(): void {
        $context = $this;
        \rex_extension::register('SLICE_ADDED', static function (\rex_extension_point $ep) use ($context) {
            $params = $ep->getParams();
            \rex_activity::message($context->message($params, $context->ADDED))->causer($context->user)->log();
        });
    }

    /**
     * a slice has been updated
     * @return void
     */
    private function updated(): void {
        $context = $this;
        \rex_extension::register('SLICE_UPDATED', static function (\rex_extension_point $ep) use ($context) {
            $params = $ep->getParams();
            \rex_activity::message($context->message($params, $context->UPDATED))->causer($context->user)->log();
        });
    }

    /**
     * a new slice has been deleted
     * @return void
     */
    private function deleted(): void {
        $context = $this;
        \rex_extension::register('SLICE_DELETED', static function (\rex_extension_point $ep) use ($context) {
            $params = $ep->getParams();
            \rex_activity::message($context->message($params, $context->DELETED))->causer($context->user)->log();
        });
    }

    private function message(array $params, int $type): string {
        $module = \rex_sql::factory()->getArray('SELECT * from ' . \rex::getTable('module') . ' WHERE id = ' . $params['module_id']);

        $message = 'Slice ';
        $message .= '<a href="' . \rex_url::backendController([
                'page' => 'content/edit',
                'article_id' => $params['article_id'],
                'slice_id' => $params['slice_id'],
                'clang_id' => $params['clang_id'],
                'ctype' => $params['ctype'],
                'function' => 'edit'
            ]) . '">';
        $message .= $module[0]['name'];
        $message .= '</a>';

        switch ($type) {
            case $this->ADDED:
                $message .= ' ' . $this->addon->i18n('added');
                break;
            case $this->UPDATED:
                $message .= ' ' . $this->addon->i18n('updated');
                break;
            case $this->DELETED:
                $message .= ' ' . $this->addon->i18n('deleted');
                break;
        }

        /** @var \rex_article $article */
        $article = \rex_article::get($params['article_id']);

        $message .= ' - ';
        $message .= '<a href="' . \rex_url::backendController([
                'page' => 'content/edit',
                'article_id' => $article->getId(),
                'category_id' => $article->getCategoryId(),
                'clang_id' => $params['clang_id'],
                'mode' => 'edit'
            ]) . '">';
        $message .= $article->getName();
        $message .= '</a>';

        return $message;
    }
}


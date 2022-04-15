<?php

namespace RexActivity\EP;

class slice
{
    use ep_trait;

    private $user;
    private $addon;

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
            \rex_activity::message($context->message($params, \rex_activity::TYPE_ADD))->type(\rex_activity::TYPE_ADD)->causer($context->user)->log();
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
            \rex_activity::message($context->message($params, \rex_activity::TYPE_UPDATE))->type(\rex_activity::TYPE_UPDATE)->causer($context->user)->log();
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
            \rex_activity::message($context->message($params, \rex_activity::TYPE_DELETE))->type(\rex_activity::TYPE_DELETE)->causer($context->user)->log();
        });
    }

    private function message(array $params, string $type): string {
        $module = \rex_sql::factory()->getArray('SELECT * from ' . \rex::getTable('module') . ' WHERE id = ' . $params['module_id']);

        $message = '<strong>Slice:</strong> ';
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
        $message .= ' ' . $this->addon->i18n('type_'.$type);

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


<?php

namespace RexActivity\EP;

class article
{
    use ep_trait;

    private $user;
    private $addon;

    public function __construct() {
        $this->addon = $this->addon();
        $this->user = $this->user();

        if($this->addon->getConfig('article_added')) {
            $this->added();
        }

        if($this->addon->getConfig('article_updated')) {
            $this->updated();
        }

        if($this->addon->getConfig('article_deleted')) {
            $this->deleted();
        }

        $this->status();
        if($this->addon->getConfig('article_status')) {
            $this->status();
        }
    }

    /**
     * article has been added
     * @return void
     */
    private function added(): void {
        $context = $this;
        \rex_extension::register('ART_ADDED', static function (\rex_extension_point $ep) use ($context) {
            $params = $ep->getParams();
            \rex_activity::message($context->message($params, \rex_activity::TYPE_ADD))
                ->type(\rex_activity::TYPE_ADD)
                ->causer($context->user)
                ->log();
        });
    }

    /**
     * article has been updated
     * @return void
     */
    private function updated(): void {
        $context = $this;
        \rex_extension::register('ART_UPDATED', static function (\rex_extension_point $ep) use ($context) {
            $params = $ep->getParams();
            \rex_activity::message($context->message($params, \rex_activity::TYPE_UPDATE))
                ->type(\rex_activity::TYPE_UPDATE)
                ->causer($context->user)
                ->log();
        });
    }

    /**
     * article has been deleted
     * @return void
     */
    private function deleted(): void {
        $context = $this;
        \rex_extension::register('ART_DELETED', static function (\rex_extension_point $ep) use ($context) {
            $params = $ep->getParams();
            \rex_activity::message($context->message($params, \rex_activity::TYPE_DELETE))
                ->type(\rex_activity::TYPE_DELETE)
                ->causer($context->user)
                ->log();
        });
    }

    /**
     * article status has been changed
     * @return void
     */
    private function status(): void {
        $context = $this;
        \rex_extension::register('ART_STATUS', static function (\rex_extension_point $ep) use ($context) {
            $params = $ep->getParams();
            \rex_activity::message($context->message($params, \rex_activity::TYPE_UPDATE))
                ->type(\rex_activity::TYPE_UPDATE)
                ->causer($context->user)
                ->log();
        });
    }

    private function message(array $params, string $type): string {
        $article = \rex_article::get($params['id']);

        $message = '<strong>Article:</strong> ';

        if($article) {
            $message .= '<a href="' . \rex_url::backendController([
                    'page' => 'content/edit',
                    'article_id' => $article->getId(),
                    'category_id' =>  $article->getCategoryId(),
                    'clang_id' => isset($params['clang_id']) ?: $params['clang'],
                    'mode' => 'edit'
                ]) . '">';
            $message .= $article->getName();
            $message .= '</a>';
        }
        else {
            $message .= ' ['.$params['id'].'] ';
            $message .= $params['name'];
        }

        $message .= ' - ';
        $message .= $this->addon->i18n('type_'.$type);

        return $message;
    }
}


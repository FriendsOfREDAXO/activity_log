<?php

namespace RexActivity\EP;

class meta
{
    use ep_trait;

    private $user;
    private $addon;

    public function __construct() {
        $this->addon = $this->addon();
        $this->user = $this->user();

        if($this->addon->getConfig('meta_updated')) {
            $this->updated();
        }
    }

    /**
     * article meta has been updated
     * @return void
     */
    private function updated(): void {
        $context = $this;
        \rex_extension::register('ART_META_UPDATED', static function (\rex_extension_point $ep) use ($context) {
            $params = $ep->getParams();
            \rex_activity::message($context->message($params, \rex_activity::TYPE_UPDATE))
                ->type(\rex_activity::TYPE_UPDATE)
                ->causer($context->user)
                ->log();
        });
    }

    private function message(array $params, string $type): string {
        $message = '<strong>Meta Info:</strong> ';
        $message .= $this->addon->i18n('type_'.$type);

        /** @var \rex_article $article */
        $article = \rex_article::get($params['id']);

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


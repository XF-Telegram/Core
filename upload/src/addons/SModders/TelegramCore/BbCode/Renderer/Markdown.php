<?php


namespace SModders\TelegramCore\BbCode\Renderer;


/**
 * Markdown renderer from bb-code AST.
 * Supports Markdown v2.
 *
 * @see https://core.telegram.org/bots/api#markdown-style
 * @see https://core.telegram.org/bots/api#markdownv2-style
 */
class Markdown extends \XF\BbCode\Renderer\Html
{
    public function addDefaultTags()
    {
        $codeConfig = [
            'callback' => 'renderTagCode',
            'stopBreakConversion' => true,
            'trimAfter' => 2
        ];

        // We're define own tags set.
        $this->addTag('b', ['replace' => ['*', '*']]);
        $this->addTag('i', ['replace' => ['_', "_\r"]]); // \r should be ignored by Telegram
        $this->addTag('u', ['replace' => ['__', '__']]);
        $this->addTag('s', ['replace' => ['~', '~']]);

        $this->addTag('url', ['callback' => 'renderTagUrl']);
        $this->addTag('img', ['callback' => 'renderTagImage']);

        $this->addTag('code', $codeConfig);
        $this->addTag('icode', ['callback' => 'renderTagInlineCode']);

        $this->addTag('attach', ['callback' => 'renderTagAttach']);
        $this->addTag('user', ['callback' => 'renderTagUser']);

        // For compatibility purposes.
        $this->addTag('php', $codeConfig);
        $this->addTag('html', $codeConfig);
    }

    public function getDefaultOptions()
    {
        $options = parent::getDefaultOptions();
        $options['lightbox'] = false;
        $options['stopBreakConversion'] = 1;

        return $options;
    }

    public function filterFinalOutput($output)
    {
        return trim($output);
    }

    public function getCustomTagConfig(array $tag)
    {
        return [];
    }

    public function renderTagUrl(array $children, $option, array $tag, array $options)
    {
        if ($option !== null && !is_array($option))
        {
            $options['lightbox'] = false;

            $url = $option;
            $text = $this->renderSubTree($children, $options);

            if ($text === '')
            {
                $text = $url;
            }
        }
        else
        {
            $url = $this->renderSubTreePlain($children);
            $text = $this->prepareTextFromUrlExtended($url, $options);
        }

        $url = $this->getValidUrl($url);
        if (!$url)
        {
            return $text;
        }

        $url = $this->formatter->censorText($url);
        return $this->markdownUrl($url, $text);
    }
    public function renderTagImage(array $children, $option, array $tag, array $options)
    {
        $url = $this->renderSubTreePlain($children);

        $validUrl = $this->getValidUrl($url);
        if (!$validUrl)
        {
            return $this->filterString($url, $options);
        }

        $censored = $this->formatter->censorText($validUrl);
        if ($censored != $validUrl)
        {
            return $this->filterString($url, $options);
        }

        return $this->markdownUrl($validUrl, $validUrl);
    }
    public function renderTagInlineCode(array $children, $option, array $tag, array $options)
    {
        $content = $this->renderSubTree($children, $options);
        return $this->getRenderedCode($content, '', ['isMultiline' => false]);
    }
    public function renderTagAttach(array $children, $option, array $tag, array $options)
    {
        $id = intval($this->renderSubTreePlain($children));
        if (!$id)
        {
            return '';
        }

        $link = \XF::app()->router('public')->buildLink('full:attachments', ['attachment_id' => $id]);
        $phrase = \XF::phrase('view_attachment_x', ['name' => $id]);

        return $this->markdownUrl($link, $phrase);
    }
    public function renderTagUser(array $children, $option, array $tag, array $options)
    {
        $content = $this->renderSubTree($children, $options);
        if ($content === '')
        {
            return '';
        }

        $userId = intval($option);
        if ($userId <= 0)
        {
            return $content;
        }

        $link = \XF::app()->router('public')->buildLink('full:members', ['user_id' => $userId]);
        return $this->markdownUrl($link, $content);
    }

    protected function getRenderedCode($content, $language, array $config = [])
    {
        $isMultiline = isset($config['isMultiline']) ? $config['isMultiline'] : false;

        $open = $isMultiline ? "```\n" : '`';
        $end = $isMultiline ? "\n```" : '`';
        $content = str_replace(['`', '\\'], ['\`', '\\\\'], $content);

        return $open . $content . $end;
    }

    protected function markdownUrl($url, $text)
    {
        return sprintf('[%s](%s)', str_replace(['(', '\\'], ['\(', '\\\\'], $text), $url);
    }

    public function filterString($string, array $options)
    {
        $string = $this->formatter->censorText($string);

        // https://core.telegram.org/bots/api#markdownv2-style
        // See "notes".
        $deniedSymbols = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        $deniedEscapedSymbols = array_map(function ($el)
        {
            return '\\' . $el;
        }, $deniedSymbols);

        return str_replace($deniedSymbols, $deniedEscapedSymbols, $string);
    }
}
<?php

namespace Mynaparrot\Plugnmeet\Parameters;

/**
 *
 */
class CopyrightConfParameters
{
    /**
     * @var bool
     */
    protected $display = true;
    /**
     * @var string
     */
    protected $text = "Powered by <a href=\"https://www.plugnmeet.org\" target=\"_blank\">plugNmeet</a>";

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function isDisplay(): bool
    {
        return $this->display;
    }

    /**
     * @param bool $display
     * @return void
     */
    public function setDisplay(bool $display): void
    {
        $this->display = filter_var($display, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return void
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return array
     */
    public function buildBody(): array
    {
        return array(
            "display" => $this->isDisplay(),
            "text" => $this->getText()
        );
    }
}

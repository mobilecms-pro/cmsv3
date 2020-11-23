<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Page;

/**
 * Description of FormsBuilder
 *
 * @author Олег
 */
class FormsBuilder
{
    protected $page;
    protected $form;
    protected $options = [];
    protected $id = 0;
    public function __construct(
        Page $page, string $url, string $method = 'POST', bool $upload = false
    ) {
        $this->page = $page;
        $this->form = [
            'url' => $url,
            'method' => $method,
            'upload' => $upload,
            'elements' => []
        ];
    }
    public function text(
        string $name,
        string $placeholder = '',
        string $help = '',
        string $value = '',
        bool $disable = false
    ) {
        $this->withElement('text', $name, $placeholder, $help, $value, $disable);
        return $this;
    }
    public function email(
        string $name,
        string $placeholder = '',
        string $help = '',
        string $value = '',
        bool $disable = false
    ) {
        $this->withElement('email', $name, $placeholder, $help, $value, $disable);
        return $this;
    }
    public function password(
        string $name,
        string $placeholder = '',
        string $help = '',
        string $value = '',
        bool $disable = false
    ) {
        $this->withElement('password', $name, $placeholder, $help, $value, $disable);
        return $this;
    }
    public function submit(
        string $name,
        string $placeholder = '',
        string $help = '',
        string $value = '',
        bool $disable = false
    ) {
        $this->withElement('submit', $name, $placeholder, $help, $value, $disable);
        return $this;
    }
    public function textarea(
        string $name,
        string $placeholder = '',
        string $help = '',
        string $value = '',
        bool $disable = false
    ) {
        $this->withElement('textarea', $name, $placeholder, $help, $value, $disable);
        return $this;
    }
    public function checkbox(
        string $name,
        string $placeholder = '',
        string $help = '',
        string $value = '',
        bool $disable = false
    ) {
        $this->withElement('checkbox', $name, $placeholder, $help, $value, $disable);
        return $this;
    }
    public function captcha() {
        $this->withElement('captcha', 'captcha');
        return $this;
    }
    public function selectOption(string $name, string $value, $selected = false) {
        $this->options[] = [
            'name' => $name,
            'value' => $value,
            'selected' => $selected
        ];
        return $this;
    }
    public function select(
        string $name,
        array $options,
        string $help = '',
        bool $disable = false
    ) {
        $this->options = array_merge($options, $this->options);
        $this->form['elements'][] = [
            'type' => 'select',
            'name' => $name,
            'help' => $help,
            'disabled' => $disable,
            'options' => $this->options,
            'id' => $this->id++
        ];
        $this->options = [];
        return $this;
    }
    protected function withElement(
        string $type,
        string $name,
        string $placeholder = '',
        string $help = '',
        string $value = '',
        bool $disable = false
    ) {
        $this->form['elements'][] = [
            'type' => $type,
            'name' => $name,
            'placeholder' => $placeholder,
            'help' => $help,
            'value' => $value,
            'disabled' => $disable,
            'id' => $this->id++
        ];
    }
    public function view() {
        return $this->page->load('form', ['form' => $this->form]);
    }
}

<?php


namespace App\Service;

class FormValidator
{
    protected array $post;
    protected array $errors = [];
    public const NAME_LENGTH_MAX = 255;
    public const URL_LENGTH_MAX = 255;
    public const COLOR_LENGTH_MAX = 7;
    public const MIN_LENGTH = 2;

    public function __construct($post)
    {
        $this->post = $post;
    }


    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    public function getPost(): array
    {
        return $this->post;
    }

    public function setPost(array $post): void
    {
        $this->post = $post;
    }

    public function clean()
    {
        foreach ($this->post as $inputName => $formInput) {
            if (is_string($formInput)) {
                $this->post[$inputName] = trim($formInput);
            } else {
                $this->post[$inputName] = $formInput;
            }
        }
    }
    public function checkString($input, $inputName, $maxLength = self::NAME_LENGTH_MAX)
    {
        if (empty($input)) {
            $this->errors[$inputName]['length'] = "Ce champ ne peut être vide" ;
        } elseif (strlen($input) < self::MIN_LENGTH) {
            $this->errors[$inputName]['length'] = "Ce champ doit avoir au moins " . self::MIN_LENGTH . " caractères" ;
        } elseif (strlen($input) > $maxLength) {
            $this->errors[$inputName]['length'] = "Ce champ doit avoir au maximum " . $maxLength. " caractères" ;
        }
    }
    public function checkUrl($input, $inputName, $maxLength = self::URL_LENGTH_MAX)
    {
        $this->checkString($input, $inputName, $maxLength);
        if (!filter_var($input, FILTER_VALIDATE_URL)) {
            $this->errors[$inputName]['url'] = "Ceci n'est pas une URL valide";
        }
    }

    public function checkInt(string $inputName)
    {
        $this->post[$inputName] = (int)$this->post[$inputName];
    }
}

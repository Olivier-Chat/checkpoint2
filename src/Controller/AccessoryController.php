<?php
namespace App\Controller;

use App\Model\AccessoryManager;
use App\Service\FormValidator;

/**
 * Class AccessoryController
 *
 */
class AccessoryController extends AbstractController
{
    /**
     * Display accessory creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formValidator = new FormValidator($_POST);

            $formValidator->clean();
            $formValidator->checkString($formValidator->getPost()['name'], 'name');
            $formValidator->checkUrl($formValidator->getPost()['url'], 'url');
            $errors = $formValidator->getErrors();
            if (empty($errors)) {
                $this->accessoryManager->add($formValidator->getPost());
                header('Location:/accessory/list');
            }
        }
        return $this->twig->render('Accessory/add.html.twig', ['errors'=>$errors]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function list()
    {
        $accesories = $this->accessoryManager->selectAll();
        return $this->twig->render('Accessory/list.html.twig', ['accessories'=>$accesories]);
    }
}

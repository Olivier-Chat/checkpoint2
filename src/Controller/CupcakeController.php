<?php

namespace App\Controller;

use App\Model\CupcakeManager;
use App\Service\ColorGenerator;
use App\Service\FormValidator;

/**
 * Class CupcakeController
 *
 */
class CupcakeController extends AbstractController
{
    /**
     * Display cupcake creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $errors=[];
        $accessories = $this->accessoryManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formValidator = new FormValidator($_POST);
            $formValidator->clean();
            $formValidator->checkString($formValidator->getPost()['name'], 'name');
            $formValidator->checkString($formValidator->getPost()['color1'], 'color1', FormValidator::COLOR_LENGTH_MAX);
            $formValidator->checkString($formValidator->getPost()['color2'], 'color2', FormValidator::COLOR_LENGTH_MAX);
            $formValidator->checkString($formValidator->getPost()['color3'], 'color3', FormValidator::COLOR_LENGTH_MAX);
            $formValidator->checkInt('accessory');
            $errors = $formValidator->getErrors();
            if (empty($errors)) {
                $cupcakeManager = new CupcakeManager();
                $cupcakeManager->add($formValidator->getPost());
                header('Location:/cupcake/list');
            }
        }
        return $this->twig->render('Cupcake/add.html.twig', ['accessories' => $accessories,'errors' => $errors ]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function list()
    {
        $cupcakes = $this->cupcakeManager->selectAll('cupcake_id', 'DESC');
        $_SESSION['lastCupcake'] = $cupcakes[0];
        return $this->twig->render('Cupcake/list.html.twig', ['cupcakes'=>$cupcakes]);
    }
    public function show(int $cupcakeId)
    {
        $cupcakeProperties = $this->cupcakeManager->selectOneById($cupcakeId);
        $colors = [$cupcakeProperties['color1'], $cupcakeProperties['color2'], $cupcakeProperties['color3']];
        $colorGenerator = new ColorGenerator();
        $colorAverage = $colorGenerator->generateBackground($colors);
        $invertColor = $colorGenerator->invertColor($colorAverage);
        return $this->twig->render('Cupcake/show.html.twig', [
            'cupcake'=>$cupcakeProperties,
            'colorAverage'=>$colorAverage,
            'invertColor'=>$invertColor
            ]);
    }
}

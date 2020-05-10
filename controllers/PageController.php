<?php
/**
 * @link https://github.com/solutlux/yii2-simple-pages
 * @copyright Copyright (c) 2018 Solutlux LLC
 *
 * The full copyright and license information is stored the LICENSE
 * file distributed with this source code.
 */

namespace solutlux\pages\controllers;

use yii\web\Controller;

/**
 * Minimal controller functionality with 'page' action filling content from the view with the same name as url part
 * {@inheritdoc}
 */
class PageController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'page' => [
                'class' => 'solutlux\pages\web\ViewAction',
            ],
        ];
    }

}

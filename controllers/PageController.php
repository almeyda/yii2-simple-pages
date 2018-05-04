<?php

namespace almeyda\emcms\controllers;

use Yii;
use yii\web\Controller;

class PageController extends Controller {
    
    public function behaviors()
    {
        $behaviors = [];
        if ($this->module->cacheFiles) {
            $behaviors = [
                [
                    'class' => 'yii\filters\PageCache',
                    'duration' => 0,
                    'variations' => [
                        Yii::$app->language,
                    ],

                ],
            ];
        }
        return $behaviors;
    }
    
    public function actions() 
    {
        return [
            'page' => [
                'class' => 'almeyda\emcms\web\ViewAction',
            ],
        ];
    }

}

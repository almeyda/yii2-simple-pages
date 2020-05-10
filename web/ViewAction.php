<?php
/**
 * @link https://github.com/solutlux/yii2-simple-pages
 * @copyright Copyright (c) 2018 Solutlux LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

namespace solutlux\pages\web;

use yii\web\ViewAction as Action;
use yii\web\NotFoundHttpException;

/**
 * {@inheritdoc}
 */
class ViewAction extends Action
{
    /**
     * @var string the name of the GET parameter that contains the requested theme name
     */
    public $themeParam = 'theme';

    /**
     * @var string theme name used
     */
    public $themeName = '';
    
    /**
     * @var string view name used
     */
    public $viewName = '';
    
    /**
     * Resolves the view name currently being requested.
     * Parameter with the name 'themeParam' could be sent to form the url. This case its value added as a prefix to view name resolved
     *
     * @return string the resolved view name
     * @throws NotFoundHttpException if the specified view name is invalid
     */
    protected function resolveViewName()
    {
        $this->viewName = \Yii::$app->request->get($this->viewParam, $this->defaultView);
    
        $this->themeName = \Yii::$app->request->get($this->themeParam, '');
    
        if ($this->themeName) {
            $viewName = $this->themeName . '/' . $this->viewName;
        } else {
            $viewName = $this->viewName;
        }
    
        if (!is_string($viewName) || !preg_match('~^\w(?:(?!\/\.{0,2}\/)[\w\/\-\.])*$~', $viewName)) {
            if (YII_DEBUG) {
                throw new NotFoundHttpException("The requested view \"$viewName\" must start with a word character, must not contain /../ or /./, can contain only word characters, forward slashes, dots and dashes.");
            }

            throw new NotFoundHttpException(\Yii::t('yii', 'The requested route "{name}" was not found.', ['name' => $viewName]));
        }
    
        return empty($this->viewPrefix) ? $viewName : $this->viewPrefix . '/' . $viewName;
    }
}

Simple Pages Module
============

Simple Pages Module is a simple client-side CMS module for Yii2. 

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Place **composer.phar** file in the same directory with **composer.json** file and run

```
$ php composer.phar require solutlux/yii2-simple-pages "*"
```

or add

```
{
    ...
    "require": {
        ...
        "solutlux/yii2-simple-pages": "*"
        ...
    }
    ...
}
```

to the *"require"* section of your `composer.json` file and run

```
$ php composer.phar update
```

## Configuration 

Once the extension is installed, modify your application configuration to include:

```php
return [
    ...
    'modules' => [
        ...
        'pages' => [
           'class' => 'solutlux\pages\Module',
        ],
        ...
    ],
    ...	        
    'components' => [
        ...
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'rules' => [
                ...
                ...
                ...
                'pages' =>  [
                        'pattern' => 'blog/<view>',
                        'route' => 'pages/page/page',
                        'defaults' => ['view' => 'index'],
                        'suffix' => '.html'
                ],
                ...
            ]
        ...
        ],
        'view' => [
            ...
            'theme' => [
                'pathMap' => [
                    '@app/views/layouts' => '@app/views/themes/{your-theme}/layouts',
                    '@solutlux/pages/views/page/pages/blog' => '@app/views/themes/{your-theme}/pages',
                    ...
            ],
            ...
        ],
    ],
    ...             
]
];
```

## Usage example

We build a very simple blog with common layout, list of posts at the index.php page and articles (like post1.php, post2.php, ...)
We use example with 'blog' based on Yii2 path map feature. Please note that rule 'pages' should be added at the end of the rules stack. Otherwise all simple requests will be processed by the 'pages' module.


###Structure of the folders:

```
views/                          (folder) basic yii2 project views folder
    └─themes/                   (folder) folder with the list of themes
        └─{your-theme}/         (folder) your theme
            ├─layout/           (folder) layouts used for blog
            │   └main.php       (file) layout html/php
            └─pages/            (folder) views used for blog 
                ├─ index.php    (file) file with the list of posts
                ├─ post1.php    (file) file with some content of the 1st post 
                └─ post2.php    (file) file with some content of the 2nd post
```               

### File examples

**[main.php]**

layout used

```php
<?php
use yii\helpers\Html;
use yii\bootstrap\BootstrapAsset;
BootstrapAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

```
**[index.php]** file with the business logic, that shows the list of posts in a blog 
```php

<?php $this->title = 'Title of a blog page' ?>

<?php $this->registerMetaTag(['name' => 'description', 'content' => 'some useful content for SEO']); ?>

<?php

# next step we form the list of files in the current directory

$posts = [];
chdir(__DIR__);
array_multisort(array_map('filemtime', ($files = glob("*.*"))), SORT_DESC, $files); 


# next step we prepare an array to use it in special function **

foreach ($files as $fileName) {
    if (!in_array($fileName, ['.', '..', 'index.php', 'contact.php'])) {
        $fileContent = file_get_contents(__DIR__ . '/' . $fileName, true);

        foreach (['title', 'description'] as $id) {
            preg_match('#<(.*?)id=\"' . $id . '\"[^>]*>(.*?)</(.&?)>#i', $fileContent, $matches);
            $post['url'] = '/blog/' . substr($fileName, 0, -4) . '.html';
            $post[$id] = strip_tags(@$matches[2]);
        }
        if ($post['title'] || $post['description']) {
            $posts[] = $post;
        }
    }
}

# next step we form the list of files in the current directory

$provider = new \yii\data\ArrayDataProvider([
    'allModels' => $posts,
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'attributes' => ['title', 'description'],
    ],
]);

# next step we show the list of posts in the same html-blocks

foreach ($provider->getModels() as $post) : ?>
    <div class="row">
        <p><a HREF="<?= $post['url'] ?>" class="post-heading"><?= $post['title'] ?></a></p>
            <p><?= $post['description'] ?></p>
            <a HREF="<?= $post['url'] ?>" target="_self">Read More</a>
    </div>
<?php endforeach; ?>

# next step we show the pagination if nessesary

<div class="text-center">
    <?= \yii\widgets\LinkPager::widget(['pagination' => $provider->getPagination(),]);?>
</div>

```
**[post1.php]** Example file with some content for the 1st post 
```php

<?php $this->title = 'Title of the 1st blog post' ?>
<?php $this->registerMetaTag(['name' =>'description','content' =>'some useful content for SEO optimization' ]); ?>
<div class="row">
    <a HREF="yourhost/blog/" target="_self" class="btn btn-default">Back to blog</a>
</div>
<div class="row">
    <p>html-content of 1st blog page</p>
</div>

```
**[post2.php]** Example file with some content for the 2nd post 
```php

<?php $this->title = 'Title of the 2nd blog post' ?>
<?php $this->registerMetaTag(['name' =>'description','content' =>'some useful content for SEO optimization' ]); ?>
<div class="row">
    <a HREF="yourhost/blog/" target="_self" class="btn btn-default">Back to blog</a>
</div>
<div class="row">
    <p>html-content of 2nd blog page</p>
</div>
```

### URLs examples

* Your site index page: `yourhost`
* Blog page: `yourhost/blog`
* Blog page for 1st post: `yourhost/blog/post1.html`
* Blog page for 2st post: `yourhost/blog/post2.html`

### An example of creating a solitary new page

You could follow the next guide if you want to create page with address `yourhost/faq` with standard layout:
1. Ensure correct theme 'pathMap' used for component section of the config file:

```
'components' => [
    ...
    'view' => [
        ...
        'theme' => [
            'pathMap' => [
                '@solutlux/pages/views/page/pages' => '@app/views/themes/{your-theme}/common/pages',
                ...
        ],
        ...
    ],
    ...
]
```
2. create the new file `/views/themes/{your-theme}/common/pages/faq.php`;
3. open the address `yourhost/faq`;

### theme parameter

Theme parameter could be varied to enable different themes for the views

## License

Please take a look on the bundled [LICENSE.md](LICENSE.md) for details.
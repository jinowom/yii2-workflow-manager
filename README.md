jinowom-workflow
==================
jinowom-workflow Manager for Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jinowom/yii2-workflow-manager "*"
```

or add

```
"jinowom/yii2-workflow-manager": "*"
```

to the require section of your `composer.json` file.

## Configuration

```php
$config = [
    'components' => [
        'workflowSource' => [
            'class' => 'jinowom\workflow\manager\components\WorkflowDbSource',
        ],
    ],
    'modules' => [
        'workflow' => [
            'class' => 'jinowom\workflow\manager\Module',
        ],
    ],
];
```


## Usage

Simply visit `?r=workflow` within your application to start managing workflows.

Once you have defined a workflow, you can attach it to a model as follows:

```php
class Post extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => \raoul2000\workflow\base\SimpleWorkflowBehavior::className(),
                'defaultWorkflowId' => 'post',
                'propagateErrorsToModel' => true,
            ],
        ];
    }
}
```

Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \jinowom\workflow\manager\AutoloadExample::widget(); ?>```

## Links

- [Yii2 Extension](http://www.yiiframework.com/extension/yii2-workflow-manager)
- [Composer Package](https://packagist.org/packages/jinowom/yii2-workflow-manager)



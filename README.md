[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SAM-IT/yii2-jsonbehavior/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SAM-IT/yii2-jsonbehavior/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/SAM-IT/yii2-jsonbehavior/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/SAM-IT/yii2-jsonbehavior/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/SAM-IT/yii2-jsonbehavior/badges/build.png?b=master)](https://scrutinizer-ci.com/g/SAM-IT/yii2-jsonbehavior/build-status/master)
[![Total Downloads](https://poser.pugx.org/sam-it/yii2-jsonbehavior/downloads)](https://packagist.org/packages/sam-it/yii2-jsonbehavior)
[![Latest Stable Version](https://poser.pugx.org/sam-it/yii2-jsonbehavior/v/stable)](https://packagist.org/packages/sam-it/yii2-jsonbehavior)

# Yii2 JsonBehavior
Work with JSON fields in Yii2

This behavior adds advanced support for working with JSON data in Yii AR models.

# Use JSON fields like normal fields
Consider a model having a `data` attribute that is stored as JSON.
````
public function behaviors() {
    return [
        ['class' => JsonBehavior::class, 'jsonAttributes' => ['data']]
    ];
}

// Examples:
$model = new Model();
$model->a = "test"; // If attribute 'a' does not exist this is stored inside the data.

$model->a['b'] = 'c']; // Nested arrays are supported.

$model->data = ['x' => 'y']; // Assigning directly is supported.
````


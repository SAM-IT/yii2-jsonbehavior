# yii2-jsonbehavior
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


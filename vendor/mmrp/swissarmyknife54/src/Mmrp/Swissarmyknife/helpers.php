<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 20/01/17
 * Time: 13:58
 */

/**
 * Generating CRUD Routes
 * @param \Illuminate\Routing\Route $route
 * @param String $controller
 * @param String $prefix
 */
function createCrudRoute($route,$controller,$prefix)
{
    $route->get('/', $controller . '@index');
    $route->get('/search', $controller . '@search');
    $route->get('/async', $controller . '@async');
    $route->get('/new', $controller . '@insert');
    $route->get('/' . $prefix . '/{id}', $controller . '@get');
    $route->get('/' . $prefix . '/{id}/edit', $controller . '@edit');
    $route->get('/' . $prefix . '/{id}/delete', $controller . '@delete');
    $route->get('/' . $prefix . '/{id}/destroy', $controller . '@destroy');
    $route->get('/' . $prefix . '/{id}/restore', $controller . '@restore');
    $route->get('/' . $prefix . '/{path}/download', $controller . '@download');
    $route->get('/trash', $controller . '@trash');
    $route->post('/save/{id?}', $controller . '@save');
}


/**
 * Generating Batch Import Routes
 * @param \Illuminate\Routing\Route $route
 * @param String $controller
 */
function createBatchImportRoute($route, $controller)
{
    $route->group(['prefix' => 'batch'], function() use($controller, $route) {
        $route->get('/', $controller . '@imports');
        $route->get('upload/{file_id?}', $controller . '@formUpload');
        $route->post('upload', $controller . '@upload');
        $route->get('show/file/{file_id}', $controller . '@content');
        $route->get('matching/file/{file_id}', $controller . '@matching');
        $route->post('create/column/{file_id}', $controller . '@createColumn');
        $route->get('mapping/file/{file_id}', $controller . '@mapping');
        $route->post('import/{file_id}', $controller . '@batch');
        $route->get('imported/{file_id}', $controller . '@completed');
        $route->get('{id}/delete', $controller . '@deleteBatch');
    });
}

/**
 * Return value based on type of the field
 * @param \Illuminate\Database\Eloquent\Model $row
 * @param string $field
 * @param string $type
 * @return mixed
 */
function fieldType($row, $field, $type)
{
    switch ($type->type){
        case 'boolean':
            switch (getControllerAction()){
                case 'index':
                case 'get':
                    return '<i class="fa fa fw ' . (($row->$field) ? 'fa-check' : '') . '"></i>' ;
                case 'edit':
                case 'insert':
                    return $type->values;
            }
            break;

        case 'password':
            switch (getControllerAction()){
                case 'index':
                case 'get':
                    return NULL ;
                case 'edit':
                case 'insert':
                    return $type;
            }
            break;

        case 'hidden':
            switch (getControllerAction()){
                case 'index':
                case 'get':
                    return NULL ;
                case 'edit':
                case 'insert':
                    return $type;
            }
            break;

        case 'textarea':
            switch (getControllerAction()){
                case 'index':
                case 'get':
                    if($type->class == 'code'){
                        return '<code>' . $row->$field . '</code>';
                    } else {
                        return $row->$field;
                    }
                case 'edit':
                case 'insert':
                    return $type;
            }
            break;

        case 'select':
            switch (getControllerAction()){
                case 'index':
                case 'get':
                    return $row->$field;
                case 'edit':
                case 'insert':
                    return $type->values;
            }
            break;

        case 'profile_image':
            switch (getControllerAction()){
                case 'index':
                    return '<img class="img-responsive " src="' . (!is_null($row->$field) ? $row->$field : '/img/octopus/common/user.png') . '" width="40">';
                case 'get':
                    return '<img class="profile-user-img img-responsive" src="' . (!is_null($row->$field) ? $row->$field : '/img/octopus/common/user.png') . '">';
                case 'edit':
                case 'insert':
                    return '';
            }
            break;

        case 'relation':

            switch (getControllerAction()){
                case 'index':
                    if($type->relation_type == 'belongToMany'){
                        return $row->{$type->relation}->implode($type->attribute, ', ');
                    }
                case 'imports':
                case 'get':
                    if($type->relation_type == 'belongToMany'){
                        return $row->{$type->relation}->implode($type->attribute, ', ');
                    }

                    return  $row->{$type->relation}->{$type->attribute};
                case 'edit':
                case 'insert':

                    return $type;
            }
            break;

        case 'datetime':
            return \Carbon\Carbon::parse($row->$field)->format($type->format);
            break;

        case 'download':
            return $type;
            break;
    }

}

/**
 * Make Relation Field
 * @param $relation
 * @param $attribute
 * @param bool $action
 * @return stdClass
 */
function makeFieldTypeRelation($relation, $attribute, $action = FALSE, $type = 'hasMany', $tokenizer = FALSE)
{

    $return = new stdClass();
    $return->type = 'relation';
    $return->relation = $relation;
    $return->attribute = $attribute;
    $return->action = $action;
    $return->relation_type = $type;
    $return->tokenizer = $tokenizer;

    return $return;
}

/**
 * Make Password Field
 * @param bool $confirmed
 * @return stdClass
 */
function makeFieldPassword($confirmed = FALSE){
    $return = new stdClass();
    $return->type = 'password';
    $return->confirmed = $confirmed;

    return $return;
}

/**
 * Make DateTime Field
 * @param $format
 * @return stdClass
 */
function makeFieldDateTime($format)
{
    $return = new stdClass();
    $return->type = 'datetime';
    $return->format = $format;

    return $return;
}

/**
 * Make Field Boolean
 * @param $values
 * @return stdClass
 */
function makeFieldBoolean($values)
{
    $return = new stdClass();
    $return->type = 'boolean';
    $return->values = $values;

    return $return;
}

/**
 * Make Select Field
 * @param $values
 * @return stdClass
 */
function makeFieldSelect($values)
{
    $return = new stdClass();
    $return->type = 'select';
    $return->values = $values;

    return $return;
}

/**
 * Make Image Profile Field
 * @return stdClass
 */
function makeFieldProfileImage()
{
    $return = new stdClass();
    $return->type = 'profile_image';

    return $return;
}

/**
 * Make Hidden Field
 * @return stdClass
 */
function makeFieldHidden()
{
    $return = new stdClass();
    $return->type = 'hidden';

    return $return;
}

/**
 * Make Textarea Field
 * @param $class
 * @return stdClass
 */
function makeFieldTextArea($class)
{
    $return = new stdClass();
    $return->type = 'textarea';
    $return->class = $class;

    return $return;
}

/**
 * Make Download Field
 * @return stdClass
 */
function makeFieldDownload()
{
    $return = new stdClass();
    $return->type = 'download';

    return $return;
}

/**
 * Return date format based on location
 * @param $type
 * @param null $locale
 * @return string|null
 */
function dateFormat($type,$locale = NULL)
{
    if(is_null($locale)) {
        $locale = app()->getLocale();
    }

    $v = [
        'it.datetime' => 'd/m/Y H:i:s',
        'it.time' => 'H:i:s',
        'it.date' => 'd/m/Y',
        'iso.datetime' => 'Y-m-d H:i:s',
        'iso.time' => 'H:i:s',
        'iso.date' => 'Y-m-d'
    ];

    return (isset($v[$locale.'.'.$type]) ? $v[$locale.'.'.$type] : NULL);
}

/**
 * Return normalized value
 * @param string $value
 * @return string
 */
function normalize_name($value)
{
    return str_replace(' ', '_', preg_replace('/[^\d\sa-z]+/i', '', preg_replace("/&([a-z])[a-z]+;/i", "$1", strtolower(htmlentities(html_entity_decode($value))))));
}

function getControllerAction()
{
    $action = explode('@',app('request')->route()->getActionName())[1];

    return $action;
}
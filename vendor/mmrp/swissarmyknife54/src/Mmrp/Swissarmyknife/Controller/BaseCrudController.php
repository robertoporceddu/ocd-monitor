<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 11/04/17
 * Time: 14:54
 */

namespace Mmrp\Swissarmyknife\Controller;

use App\Http\Controllers\Controller;
use Mmrp\Swissarmyknife\Controller\BaseCrudTraits\AsyncTrait;
use Mmrp\Swissarmyknife\Controller\BaseCrudTraits\DeleteTrait;
use Mmrp\Swissarmyknife\Controller\BaseCrudTraits\DestroyTrait;
use Mmrp\Swissarmyknife\Controller\BaseCrudTraits\DownloadTrait;
use Mmrp\Swissarmyknife\Controller\BaseCrudTraits\EditTrait;
use Mmrp\Swissarmyknife\Controller\BaseCrudTraits\GetTrait;
use Mmrp\Swissarmyknife\Controller\BaseCrudTraits\IndexTrait;
use Mmrp\Swissarmyknife\Controller\BaseCrudTraits\RestoreTrait;
use Mmrp\Swissarmyknife\Controller\BaseCrudTraits\SearchTrait;
use Mmrp\Swissarmyknife\Controller\BaseCrudTraits\TrashTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class BaseCrudController
 * @package App\Http\Controllers
 */
class BaseCrudController extends Controller
{

    use IndexTrait, SearchTrait, AsyncTrait, GetTrait, EditTrait, DeleteTrait, DestroyTrait, RestoreTrait, TrashTrait, DownloadTrait;
    /**
     * Set if change default redirect action
     * @var null
     */
    protected $redirect_to = NULL;

    /**
     * Namaspace of child Controller (used to create the routes)
     * @example Management\UserController
     * @var string
     */
    protected $action = NULL;

    /**
     * Url parameters
     * @example [ 'key' => 'value' ]
     * @var array
     */
    protected $parameters = [];

    /**
     * String that contains the name of the resource used
     * @var string
     */
    protected $resource = NULL;

    /**
     * String that contains the path of views folder
     * @var string
     */
    protected $views_folder = NULL;

    /**
     * String that contains page title
     * @var string
     */
    protected $title = NULL;

    /**
     * String that contains page subtitle
     * @var string
     */
    protected $subtitle = NULL;

    /**
     * Array that contains list of model fields: use fillable attribute belonging to current model
     * @var array
     */
    protected $fields = NULL;

    /**
     * Array that contains fields types
     * @example [ 'password' => [ makeFieldPassword() ] ]
     * @link swissarmyknife helper guide
     * @var array
     */
    protected $fields_types = NULL;
    /**
     * Contains instance of EloquentORM which is used to interact with request table
     * @var Model
     */
    protected $model = NULL;

    /**
     * Array that contains request inputs
     * @var array
     */
    protected $input = NULL;

    /**
     * Array that contains laravel validation rules
     * @var array
     */
    protected $validation_rules = NULL;

    /**
     * Used to create breadcrumbs
     * @var array
     */
    protected $breadcrumbs = NULL;

    /**
     * Used to pass additional data to custom views
     * @var mixed
     */
    protected $additional_data = NULL;

    /**
     * Used to enable/disable fields translations
     * @var bool
     */
    protected $translate_fields = TRUE;

    /**
     * Used to enable/disable multiple_operations at index/trash view
     * @var bool
     */
    protected $multiple_operations = TRUE;

    /**
     * Array that contains a list of custom actions
     * @example [ 'link' => 'mylink', 'title' => 'MyCustomAction' ]
     * @var null
     */
    protected $custom_action = NULL;

    /**
     * BaseCrudController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->fields = (array_diff($this->model->getFillable(), $this->model->getHidden()));
        $this->views_folder = 'crud';
    }

    /**
     * Array that contains granted operations
     * @return array
     */
    protected function prepareAction()
    {
        return [
            'index' => $this->index,
            'get' => $this->get,
            'insert' => $this->insert,
            'edit' => $this->edit,
            'delete' => $this->delete,
            'destroy' => $this->destroy,
            'restore' => $this->restore,
            'trash' => $this->trash,
            'save' => $this->save,
            'download' => $this->download,
            'multiple_operations' => $this->multiple_operations
        ];
    }
}

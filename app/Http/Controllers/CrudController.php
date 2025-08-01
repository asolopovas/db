<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Validator;

abstract class CrudController extends Controller
{
    /**
     * Turns on and off the pagination.
     *
     * @var boolean
     */
    protected bool $paginated = true;

    /**
     * Turns on and off the pagination.
     */
    protected array $relations = [];

    /**
     * Name of the resource, in lowercase plural form.
     */
    protected string $resource;

    /**
     * Singular resource name
     */
    protected string $name;

    /**
     * Model instance.
     */
    protected string $model;

    /**
     * Set an instance of the model.
     */
    protected $instance;

    /**
     * Validator Instance
     */
    protected Validator $validator;

    /**
     * Custom Validation Messages
     *
     * @var array
     */
    protected array $messages;

    public function __construct()
    {
        $this->instance = new $this->model;

        $this->name = ucwords(str_replace('_', ' ', str_singular($this->resource)));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if ($this->model === 'App\Models\User') {
            $this->authorize('is_admin', User::class);
        }

        $query = $this->instance::with($this->relations);
        $sortableColumns = [
            'firstname' => 'customers.firstname',
            'lastname' => 'customers.lastname',
            'postcode' => 'customers.postcode',
        ];


        if ($orderBy = request()->get('orderBy')) {
            [$sortKey, $sortOrder] = explode(',', $orderBy);
            $sortColumn = $sortableColumns[$sortKey] ?? $sortKey;
            $query->orderBy($sortColumn, $sortOrder);
        }  else {
            $query->orderBy('id', 'desc');
        }

        $data = $this->paginated
            ? $query->paginate(request()->get('perPage', 20))
            : $query->get();

        $resourceName = ucfirst($this->resource);
        return $data->isNotEmpty()
            ? $data
            : response()->json(
                [
                    "type" => "No Results Found",
                    "message" => ["$resourceName is empty"],
                    "data" => []
                ],
                200
            );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function store()
    {

        $this->validate(request(), $this->storeValidationRules(), $this->messages);
        $data = request()->all();
        if (array_key_exists('password', $data)) {
            $data['password'] = bcrypt($data['password']);
        }

        return [
            "type"    => "success",
            "message" => $this->name . " Successfully Created",
            "item"    => $this->instance::create($data)->load($this->relations),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param Model $model
     *
     * @return Model
     *
     */
    public function show(Model $model)
    {
        return $model->load($this->relations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Model                    $model
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     *
     */
    public function update(Model $model)
    {
        $this->validate(request(), $this->updateValidationRules(), $this->messages);

        $data = request()->all();
        if (array_key_exists('password', $data)) {
            $data['password'] = bcrypt($data['password']);
        }

        $model->update($data);

        return [
            "type"    => "Success",
            "message" => $this->name . " Successfully updated.",
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Model $model
     *
     * @return array
     * @throws \Exception
     */
    public function destroy(Model $model)
    {
        $model->delete();

        return [
            "type"    => "Success",
            "message" => $this->name . " Successfully Deleted",
        ];
    }




    /**
     * Return validation rules for store method
     *
     * @return mixed
     */
    abstract protected function storeValidationRules();

    /**
     *  Returns validation rules for update method
     *
     * @return array
     */
    abstract protected function updateValidationRules();
}

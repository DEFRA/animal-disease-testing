<?php
namespace ahvla\controllers;

use Illuminate\Foundation\Application as App;
use Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use ahvla\form\CrudForm;
use ahvla\entity\crud\Crud;
use ahvla\entity\crud\CrudRepository;

class CrudController extends BaseController {

    /**
     * @var CrudRepository
     */
    private $crudRepository;

    public function __construct(
        App $app,
        crudForm $crudForm,
        Request $request,
        Input $input,
        CrudRepository $crudRepository)
    {
        parent::__construct($app);
        $this->pageTitle = 'Crud';
        $this->crudForm = $crudForm;
        $this->input = $input;
        $this->request = $request;
        $this->crudRepository = $crudRepository;
        $this->model = new Crud;

        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (!$currentUser->canManageLookupTables()) {
            throw new \Exception('You do not have permission to perform that action.');
        }

    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function indexAction()
	{
        $viewData = [
            'tables' => $this->model->getAdminLookupTables(),
            'pageTitle' => $this->pageTitle
        ];

        return $this->makeView('admin.crud.crud', $viewData);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function createAction($tableId) {

        $viewData = [
            'table' => $table = $this->model->getAdminLookupTable($tableId),
            'cols' => $this->model->getDatabaseSchema($table->table_name),
            'pageTitle' => $this->pageTitle
        ];

        return $this->makeView('admin.crud.create-crud', $viewData);

    }


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postCreateAction($tableId) {

        $input = $this->crudRepository->getRelevantInputData();
        $validator = $this->crudForm->getValidator($this->crudRepository, $this->model);

        if ($validator->fails()) {
            return \Redirect::route('crud.create-crud', $tableId)
                ->withErrors($validator)
                ->withInput();
        }
        $tableName = $this->model->getTableNameFromTableId($tableId);
        $fields = $this->model->getDatabaseSchemaNoId($tableName);

        // 10 rows can be inserted from form
        for ($rowNum = 1; $rowNum<=10; $rowNum++) {

            // initialise query
            $query = \DB::table($tableName);

            $values = [];

            // Iterate each database schema field. Don't rely on the fields from the form
            foreach ($fields as $field) {

                $value = array_key_exists( 'updated', $input[$field->Field][$rowNum] ) ?
                    $input[$field->Field][$rowNum]['updated'] :
                    '';

                if (strlen($value) > 0) {

                    // add Field:Value to query
                    $values[$field->Field] = $value;

                }

            }

            if (count($values)) {
                $query->insert($values);
            }

        }

        return $this->makeView('admin.crud.create-crud-success', []);

	}


    /**
     * @param $tableId
     * @return \Illuminate\View\View
     */
    public function editAction($tableId)
	{

        $table = $table = $this->model->getAdminLookupTable($tableId);
        $results = $this->model->getExistingRowsFromDatabase($table->table_name);

        $viewData = [

            'table' => $table,
            'cols' => $this->model->getDatabaseSchema($table->table_name),
            'data' => $results,
            'pageTitle' => $this->pageTitle,

            // pagination
            'previousPage' => $results->getCurrentPage()-1,
            'nextPage' => $results->getCurrentPage()+1,
            'currentPage' => $results->getCurrentPage(),
            'totalPages' => $results->getLastPage(),
            'filters' => '',

        ];

        return $this->makeView('admin.crud.edit-crud', $viewData);
	}


    /**
     * Update the specified resource in storage.
     *
     * @param $tableId
     * @return $this|\Illuminate\View\View
     */
    public function postEditAction($tableId)
	{
        $input = $this->crudRepository->getRelevantInputData();
        $validator = $this->crudForm->getValidator($this->crudRepository, $this->model);

        if ($validator->fails()) {
            return \Redirect::route('crud.edit-crud', $tableId)
                ->withErrors($validator)
                ->withInput();
        }

        foreach ($input as $field => $values) {

            if ( $field !== 'table' && $field !== 'tableId' ) {
                foreach ($values as $id => $value) {

                    if ($value['original'] != $value['updated']) {

                        \DB::table($input['table'])
                            ->where('id', $id)
                            ->update([
                                $field => $value['updated']
                            ]);

                    }

                }
            }
        }

        // Show success
        return $this->makeView('admin.crud.edit-crud-success', []);

	}

    /**
     * @param $tableId
     * @param $fieldId
     * @return \Illuminate\View\View
     */
    public function deleteAction($tableId, $fieldId)
    {
        $table = $this->model->getAdminLookupTable($tableId);

        $viewData = [
            'tableId' => $table->id,
            'tableName' => $table->table_name,
            'fieldId' => $fieldId,
            'data' => $this->crudRepository->presentTableRow(
                $this->model->getExistingRowFromDatabase($tableId, $fieldId)
            ),
            'pageTitle' => $this->pageTitle
        ];

        return $this->makeView('admin.crud.delete-crud', $viewData);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $tableId
     * @param $fieldId
     * @return \Illuminate\View\View
     */
    public function postDeleteAction($tableId, $fieldId)
	{
        $this->model->deleteRow(
            $this->model->getTableNameFromTableId($tableId),
            $fieldId
        );

        return $this->makeView('admin.crud.delete-crud-success', []);

	}

}

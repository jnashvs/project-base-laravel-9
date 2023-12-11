<?php

namespace App\Http\Controllers\Backoffice\Admin\FileManager;

use Exception;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FileTypes;
use App\Repositories\Repository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Backoffice\SaveFileTypeRequest;
use Log;

class FileTypesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $filetypes;

    private $SUCESS_CREATED = "Dados criado com sucesso";

    private $SUCESS_UPDATED = "Dados atualizado com sucesso";

    private $SUCESS_REMOVED = "Dados removido com sucesso";

    public function __construct(FileTypes $model)
    {
        $this->model = new Repository($model);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $fileTypes = $this->model->all();

        return view('backoffice.file-types.index', compact('fileTypes'));
    }

    public function create()
    {
        return view('backoffice.file-types.create');
    }

    public function store(SaveFileTypeRequest $request, FlasherInterface $flasher)
    {
        try {
            $data = $request->only('id', 'directory', 'title', 'extensions', 'max_file_size');

            $dir_path = public_path() . "/files/{$request->input('directory')}/";

            if ($data['id'] == NULL) {
                if ($data["extensions"])
                    $data["extensions"] = json_encode($data["extensions"]);

                FileTypes::create($data);

                if (!File::exists($dir_path)) {
                    File::makeDirectory($dir_path, 0777, true, true);
                    $flasher->addSuccess('File Type Created', 'File Types');
                }

            } else {
                $oldFileType = FileTypes::find($data["id"]);
                $old_dir_path = public_path() . "/files/{$oldFileType['directory']}/";
                if (rename($old_dir_path, $dir_path)) {
                    $oldFileType::update($data, $data['id']);
                    $flasher->addSuccess('File Type Updated', 'File Types');
                }
            }

            return response()->json(["success" => true]);
        } catch (Exception $th) {
            Log::info("File Types - " . $th->getMessage());
            return response()->json(["error" => $th->getMessage()]);
        }

    }

    public function edit(FileTypes $filetypes, $id = null)
    {
        if ($id) {
            $filetypes = FileTypes::find($id);
            $filetypes->extensions = json_decode($filetypes->extensions);
            Session::put('old_dir', $filetypes->directory); //otimizar essa linha
        }

        return view('backoffice.file-types.edit')->with('filetypes', $filetypes);
    }

    public function getAll()
    {
        $fileTypes = FileTypes::select('id', 'directory', 'title', 'extensions', 'max_file_size')->get();

        return response()->json($fileTypes);
    }

    public function getFileType($slug)
    {

        $res = $this->model->getModel()->where('directory', $slug)->first();

        return response()->json($res);
    }

    public function delete(Request $request)
    {
        $directory = $request->input('directory');
        $id = $request->input('id');

        try {
            $fileType = FileTypes::find($id);
            if ($fileType && $directory) {
                $fileType->delete($id);
                File::deleteDirectory(public_path() . "/files/{$directory}");
            }
        } catch (\Throwable $th) {
            Log::info("File Types - delete: " . $th->getMessage());
        }

        return redirect()->route('admin.file-types')->with('status', $this->SUCESS_REMOVED);
    }
}

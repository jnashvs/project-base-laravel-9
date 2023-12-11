<?php

namespace App\Http\Controllers\Backoffice\Admin\FileManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Files;
use App\Models\FileTypes;
use App\Repositories\Repository;
use App\Services\FilesManager;

class FilesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $filetypes;

    public function __construct(FileTypes $filetypes, Files $files)
    {
        // set the model
        $this->filetypes = new Repository($filetypes);
        $this->files = new Repository($files);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $result = FileTypes::select('id', 'directory')->get();

        return view('backoffice.files.add', ['filetypes' => $result]);
    }

    public function fileStore(Request $request)
    {
        return (new FilesManager())->uploadFiles($request);
    }

    public function allFiles(Request $request)
    {
        $searchValue = $request->input('search');
        $targetDirectory = $request->input('directory');

        $query = Files::where("file_type_id", $targetDirectory);

        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('path', 'like', '%' . $searchValue . '%');
            });
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function removeFile($id)
    {
        $res = Files::find($id);

        $res->delete($id);

        if (file_exists(public_path($res->path))) {

            unlink(public_path($res->path));
            $files = "Delete process success";
        } else {
            $files = "Delete process error";
        }

        return json_encode($files);
    }
}

<?php

namespace App\Http\Controllers\Backoffice\Admin\FileManager;

use App\Modules\Exceptions\ObjectNotFoundException;
use App\Repositories\FileType\FileTypeRepositoryInterface;
use Exception;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FileTypes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Backoffice\SaveFileTypeRequest;
use Log;

class FileTypesController extends Controller
{
    private FileTypeRepositoryInterface $fileTypeRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $filetypes;

    private $SUCESS_CREATED = "Dados criado com sucesso";

    private $SUCESS_UPDATED = "Dados atualizado com sucesso";

    private $SUCESS_REMOVED = "Dados removido com sucesso";

    public function __construct(FileTypeRepositoryInterface $fileTypeRepository)
    {
        $this->fileTypeRepository = $fileTypeRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $fileTypes = $this->fileTypeRepository->get();

        return view('backoffice.file-types.index', compact('fileTypes'));
    }

    public function show()
    {
        return view('backoffice.file-types.create');
    }

    public function create(SaveFileTypeRequest $request, FlasherInterface $flasher): FileTypes | bool
    {
        try {
            $data = $request->only('id', 'directory', 'title', 'extensions', 'max_file_size');

            $data["extensions"] = $this->extensionJsonEncode($data["extensions"]);

            return $this->fileTypeRepository->create($data["title"], $data["directory"], $data["extensions"], $data["max_file_size"]);

        } catch (Exception $th) {
            Log::info("File Types - " . $th->getMessage());
            return response()->json(["error" => $th->getMessage()]);
        }

    }

    public function update(SaveFileTypeRequest $request, int $id, FlasherInterface $flasher): FileTypes | bool
    {
        try {
            $data = $request->only('id', 'directory', 'title', 'extensions', 'max_file_size');

            $objFileType = $this->fileTypeRepository->getById($id);
            if (!$objFileType) {
                throw new ObjectNotFoundException('File Type can\'t be found');
            }

            $data["extensions"] = $this->extensionJsonEncode($data["extensions"]);

            return $this->fileTypeRepository->update($objFileType, $data["title"], $data["directory"], $data["extensions"], $data["max_file_size"]);

        } catch (Exception $th) {
            Log::info("File Types - " . $th->getMessage());
            return response()->json(["error" => $th->getMessage()]);
        }

    }

    public function extensionJsonEncode($extensions) {
        return $extensions ? json_encode($extensions) : null;
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
        return response()->json($slug);
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

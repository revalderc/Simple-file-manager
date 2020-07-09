<?php
namespace App\Http\Controllers;

use App\Modules\FilesModule;
use Illuminate\Http\Request;

class FilesController extends Controller
{
    protected $filesModule;

    public function __construct(FilesModule $files)
    {
        $this->filesModule = $files;
    }

    public function index(Request $request)
    {
        $user = $request->user();
//        $files = $user->files()->get();
        $files = $this->filesModule->getByUserId($user->id);

        return view('home', compact('files'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'title'         => 'required|min:3',
            'description'   => 'required',
            'tags'          => 'required',
            'file'          => 'required|file|mimes:jpeg,bmp,png,mp4,pdf,gif'
        ]);

        try {
            \DB::beginTransaction();

            $file      = $data['file'];
            $extension = $file->getClientOriginalExtension();
            $filename  = uniqid() .'.'. $extension;
            $file->storeAs('public', $filename);

            // create file
            $uploadedFile = $this->filesModule->create([
                'user_id'       => $user->id,
                'title'         => $data['title'],
                'description'   => $data['description'],
                'path'          => $filename,
                'type'          => $extension
            ]);

            // create tag
            $tags = explode(',', $data['tags']);
            foreach($tags as $tag) {
                $uploadedFile->tags()->create([
                    'name' => $tag
                ]);
            }

            \DB::commit();

            return back()->with('success', 'File uploaded successfully!');

        }catch (\Exception $e) {
            \DB::rollBack();
        }

        return back()->withErrors(['error' => 'Something went wrong.']);
    }
}

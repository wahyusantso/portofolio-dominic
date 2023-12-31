<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AboutModel;
use App\Models\PortofolioModel;
use App\Models\ServiceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.dashboard.index');
    }

    public function createPortofolio(Request $request)
    {
        $portofolio = $request->validate([
            'nama_project' => 'required',
            'kategori_project' => 'nullable',
            'image_project' => 'required',
            'url' => 'nullable|url:http,https',
            'video_path' => 'nullable'
        ]);

        if($request->video_path != null)
        {
            $portofolio['video_path'] = $request->file('video_path')->store('video_project', 'hosting');
        }

        if($request->image_project != null)
        {
            $portofolio['image_project'] = $request->file('image_project')->store('image_project', 'hosting');

        }

        PortofolioModel::create($portofolio);
        return back()->with('success', 'Portofolio Created');
    }

    public function createAbout(Request $request)
    {
        $about = $request->validate([
            'description' => 'required'
        ]);

        $db = AboutModel::count();

        if($db === 0)
        {
            AboutModel::create($about);

            return back()->with('success', 'About Created');
        } else {
            DB::table('about_models')
                    ->where('id', 1)
                    ->update(['description' => $request->description]);

            return back()->with('success', 'About Created');
        }

        return back()->with('error', 'An error occurred, please try again.');
    }

    public function createServices(Request $request)
    {
        $services = $request->validate([
            'layanan' => 'required',
            'logo' => 'required|image',
            'description' => 'required'
        ]);

        if($request->logo != null)
        {
            $services['logo'] = $request->file('logo')->store('image_project');

        }

        ServiceModel::create($services);

        return back()->with('success', 'Services Created');
    }
}
